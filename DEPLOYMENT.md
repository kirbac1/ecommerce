# Deploying to invoicing.kirbac.fi

[`.github/workflows/deploy.yml`](.github/workflows/deploy.yml) runs the test
suite, builds `vendor/` on PHP 7.4, rsyncs a timestamped release to the Plesk
server, runs migrations, and flips a symlink — on every push to `main`.

It follows the same shape as the paljonkose deploy (releases directory, atomic
symlink swap, prune to the last 5, health check), adapted for PHP/Laravel
instead of Node/Passenger.

> **Before the first deploy will work**, the server side has to exist: a Plesk
> subdomain, PHP 7.4, a database, and four GitHub secrets. Those steps are
> below and have to be done by hand — they need panel access.

---

## What the pipeline does

```
push to main
  └─ test.yml        PHP 7.4 + MySQL, migrate --seed, Vitest (23), Playwright (54)
  └─ deploy          composer install --no-dev   (vendor built here, not on the server)
                     rsync  → invoicing/releases/<stamp>-<sha>/
                     symlink  storage/ and public/catalog/ → shared/
                     artisan clear-compiled / config:clear / view:clear / route:clear
                     artisan migrate --force
                     mv -T    httpdocs/current → the new release   (atomic)
                     prune    keep the newest 5 releases
  └─ verify          GET https://invoicing.kirbac.fi/healthz must return 200
```

A failing test blocks the deploy. A deploy that ships but does not answer
`/healthz` with a 200 fails the run rather than reporting success.

### Layout on the server

Everything lives under `$HOME`, so no step needs sudo:

```
invoicing/
  releases/20260904-041530-c8e757f/   one full copy of the app
  releases/…                          the previous four
  shared/.env                         credentials — survives every deploy
  shared/storage/                     logs, sessions, cache
  shared/catalog/                     product images uploaded through the admin
httpdocs/
  current -> ~/invoicing/releases/20260904-041530-c8e757f
```

`storage/` and `public/catalog/` are symlinks into `shared/` on purpose. Product
images uploaded through the admin land in `public/catalog`; if that lived inside
a release, every deploy would silently delete them and the prune step would make
it permanent.

**The subdomain's document root must be `httpdocs/current/public`** — Laravel
serves from `public/`. Pointing it at the app root instead publishes `.env`,
`vendor/`, and the whole source tree.

---

## Server setup (once, by hand)

### 1. Create the subdomain in Plesk

`invoicing.kirbac.fi` already resolves — it points at the server through
Cloudflare — but there is no subscription behind it yet, so it currently lands
on the Plesk login page.

In Plesk: **Websites & Domains → Add Subdomain**

| Field | Value |
|---|---|
| Subdomain | `invoicing` of `kirbac.fi` |
| Document root | `httpdocs/current/public` |

Plesk will warn that the document root does not exist yet. That is expected —
the first deploy creates it.

### 2. Set PHP 7.4 for the subdomain

**Websites & Domains → PHP Settings → PHP version: 7.4**

This is not optional and not a preference: Laravel 5.2 uses constructs removed
in PHP 8, so the app cannot run on 8.x. If 7.4 is not in the version list, it
has to be installed (**Tools & Settings → Updates → Add Components → PHP 7.4**)
before any of this works.

The deploy calls PHP directly at `/opt/plesk/php/7.4/bin/php`. If your server
puts it somewhere else, set the `PHP_BIN` repository variable (see below). The
deploy checks for it up front and stops with the list of installed versions
rather than half-deploying.

### 3. Create the database

**Databases → Add Database**, then a user for it. Note the three values; they
go into GitHub secrets in step 5.

The deploy runs `artisan migrate --force`, which builds the schema on the first
run. It deliberately does **not** seed: the demo catalogue is for local
development, not a live site. To load it once, by hand, after the first deploy:

```bash
cd ~/httpdocs/current && /opt/plesk/php/7.4/bin/php artisan db:seed --force
```

Remember the seeded logins all use the password `test`. Change or delete them
before the site is reachable by anyone else.

### 4. Authorise the deploy key

The workflow authenticates with its own SSH key. Reuse the paljonkose deploy key
or generate a dedicated one — a separate key per repo is better, since revoking
one then does not break the other:

```bash
ssh-keygen -t ed25519 -f ~/.ssh/invoicing_deploy -N "" -C "github-actions-invoicing"
```

> The private key must never be committed. Generate it outside the working tree.

Add the public half to the server:

```bash
ssh-copy-id -i ~/.ssh/invoicing_deploy.pub "$DEPLOY_USER@$DEPLOY_HOST"
```

### 5. Add the GitHub secrets

**Settings → Secrets and variables → Actions** on `kirbac1/invoicingSystem`.

The host and username are deliberately not written down in this public repo.

| Secret | What it is |
|---|---|
| `DEPLOY_HOST` | server address |
| `DEPLOY_USER` | SSH username |
| `DEPLOY_SSH_KEY` | contents of `~/.ssh/invoicing_deploy` (the private half) |
| `DEPLOY_KNOWN_HOSTS` | output of `ssh-keyscan -H "$DEPLOY_HOST"` |
| `APP_KEY` | `php artisan key:generate --show` — 32 chars, used for session and cookie encryption |
| `DB_DATABASE` | from step 3 |
| `DB_USERNAME` | from step 3 |
| `DB_PASSWORD` | from step 3 |

`DEPLOY_KNOWN_HOSTS` pins the server's host key. Without it the deploy falls
back to trust-on-first-use and logs a warning — it works, but a DNS or routing
surprise could then redirect the deploy somewhere else.

`APP_KEY` must be a *different* value from the one in local development, and
must not change afterwards: changing it invalidates every session and every
encrypted cookie.

### Optional repository variables

Only needed if the server does not match the defaults. Same screen, the
**Variables** tab:

| Variable | Default | When to set it |
|---|---|---|
| `SITE_URL` | `https://invoicing.kirbac.fi` | different hostname |
| `DEPLOY_PATH` | `invoicing` | releases live somewhere other than `~/invoicing` |
| `DOCROOT` | `httpdocs` | the subdomain's root is not `~/httpdocs` |
| `PHP_BIN` | `/opt/plesk/php/7.4/bin/php` | PHP 7.4 is installed elsewhere |

`DOCROOT` is the one to check first if the first deploy succeeds but the site
404s. A Plesk subdomain often gets its own directory
(`~/invoicing.kirbac.fi/httpdocs`) rather than the subscription's `~/httpdocs`.

---

## Running a deploy

Push to `main`, or **Actions → Deploy to production → Run workflow**.

### Rolling back

A release swap is one symlink, so a rollback is one command:

```bash
ls ~/invoicing/releases/                        # newest last
ln -sfn ~/invoicing/releases/<previous> ~/httpdocs/current.tmp
mv -Tf ~/httpdocs/current.tmp ~/httpdocs/current
```

This does **not** roll back a migration. If the bad release migrated the
database, undo that separately before relinking.

### When something fails

| Symptom | Where to look |
|---|---|
| `PHP 7.4 not found at …` | step 2 — the job prints the versions Plesk has |
| 403 on every page | directory modes; the deploy sets 755/644, but check the docroot |
| 404 on every page | `DOCROOT` variable, or the Plesk document root is not `…/current/public` |
| 500, `/healthz` returns 503 | database credentials in `~/invoicing/shared/.env` |
| `.env` or source visible in a browser | document root points at the app root, not `public/` — fix immediately |
| Product images vanish after a deploy | `public/catalog` is not symlinked to `shared/catalog` |

The app log is at `~/invoicing/shared/storage/logs/laravel.log`, shared across
releases, so it survives a rollback.

`/healthz` returns 200 only when the app boots *and* reaches its database, so it
is a real check rather than a "the web server is up" check. It is also useful
for uptime monitoring.

---

## Production notes

`APP_DEBUG` is `false` in the generated `.env`. Laravel 5.2's debug screen prints
environment variables, including database credentials, so leaving it on in
production leaks them on the first error.

The seeded demo accounts (`admin@example.com`, `manager@example.com`,
`cashier@example.com`, all with the password `test`) exist for local
development. If you seed a live database, change them first.

`.env` is written on the first deploy and never touched again — edit it on the
server when something needs to change, and it will be carried into every later
release.
