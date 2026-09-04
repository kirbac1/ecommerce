# Deploying to ecommerce.kirbac.fi

[`.github/workflows/deploy.yml`](.github/workflows/deploy.yml) runs the test
suite, builds `vendor/` on PHP 7.4, rsyncs a timestamped release to the Plesk
server, runs migrations, and flips a symlink — on every push to `main`.

It follows the same shape as the paljonkose deploy (releases directory, atomic
symlink swap, prune to the last 5, health check), adapted for PHP/Laravel
instead of Node/Passenger.

> **Not deployable as things stand.** The server offers only PHP 8.2/8.3, and
> Laravel 5.2 cannot run on PHP 8 — see
> [PHP 8 is not an option](#php-8-is-not-an-option). The pipeline itself is
> finished and green; it is waiting on a runtime.
>
> The rest of the server side also has to exist: the subdomain (done), a
> database, an authorised deploy key, and the GitHub secrets. Those need panel
> or password access.

---

## What the pipeline does

```
push to main
  └─ test.yml        PHP 7.4 + MySQL, migrate --seed, Vitest (23), Playwright (54)
  └─ deploy          composer install --no-dev   (vendor built here, not on the server)
                     rsync  → ecommerce/releases/<stamp>-<sha>/
                     symlink  storage/ and public/catalog/ → shared/
                     artisan clear-compiled / config:clear / view:clear / route:clear
                     artisan migrate --force
                     mv -T    httpdocs/current → the new release   (atomic)
                     prune    keep the newest 5 releases
  └─ verify          GET https://ecommerce.kirbac.fi/healthz must return 200
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
  current -> ~/ecommerce/releases/20260904-041530-c8e757f
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

`ecommerce.kirbac.fi` already exists and resolves through Cloudflare. It
currently serves a default Laravel welcome page on PHP 8.3 — that is a
different, newer app, not this one.

In Plesk: **Websites & Domains → Add Subdomain**

| Field | Value |
|---|---|
| Subdomain | `ecommerce` of `kirbac.fi` |
| Document root | `httpdocs/current/public` |

Plesk will warn that the document root does not exist yet. That is expected —
the first deploy creates it.

### 2. PHP 7.x — currently the blocker

> **The server only offers PHP 8.2/8.3, and this app cannot run on either.**
> Nothing below works until that is resolved. See
> [PHP 8 is not an option](#php-8-is-not-an-option).

**Websites & Domains → PHP Settings → PHP version: 7.4**

If 7.4 is not in the list it has to be installed first
(**Tools & Settings → Updates → Add/Remove Components → PHP 7.4**). Plesk has
been dropping end-of-life PHP packages, so it may no longer be offered.

The deploy calls PHP directly at `/opt/plesk/php/7.4/bin/php`. If your server
puts it somewhere else, set the `PHP_BIN` repository variable (see below). The
deploy checks for it up front and stops with the list of installed versions
rather than half-deploying.

#### PHP 8 is not an option

This is not a version preference that can be waived:

- Laravel 5.2 and its Symfony 3.0 components call `each()`, **removed** in PHP
  8.0. The framework fatals on boot.
- **44 of the 51 locked packages** declare a PHP constraint that predates 8.x.
  Several — `baum/baum` (the category tree), `maatwebsite/excel` 2.1 (product
  import/export), `arcanedev/settings` — have no PHP 8 release at all.

So there are three real paths, in ascending order of effort:

| Option | Effort | Trade-off |
|---|---|---|
| Install PHP 7.4 alongside 8.x in Plesk | Small | 7.4 is end-of-life and gets no security fixes |
| Run the app in a PHP 7.4 Docker container behind Plesk | Medium | Extra moving part, but the app stays untouched and 7.4 stays contained |
| Upgrade the app to a PHP 8 Laravel | Large | The only path with a supported runtime, but it means replacing the abandoned packages above and stepping through several Laravel majors |

Running an end-of-life PHP on a public site is a real risk, so option 3 is the
only one that ends somewhere sustainable — it is just much more work than the
other two.

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

A dedicated key has been generated at `~/.ssh/ecommerce_deploy` — separate from
the paljonkose one, so revoking either does not break the other. Its private
half is already in the `DEPLOY_SSH_KEY` secret.

The public half still has to be added to the server. It needs the account
password, so run this yourself. Export the address and username first, from the
same values that are in the GitHub secrets:

```bash
export DEPLOY_HOST=... DEPLOY_USER=...
ssh-copy-id -i ~/.ssh/ecommerce_deploy.pub "$DEPLOY_USER@$DEPLOY_HOST"
```

Then confirm it worked — this must print `CONNECTED` without prompting:

```bash
ssh -i ~/.ssh/ecommerce_deploy -o BatchMode=yes "$DEPLOY_USER@$DEPLOY_HOST" 'echo CONNECTED'
```

> The private key must never be committed. It lives in `~/.ssh/`, outside the
> working tree, which is why `.gitignore` does not need to mention it.

**`DEPLOY_HOST` must be the server's IP, not `ecommerce.kirbac.fi`.** The
hostname resolves to Cloudflare, which proxies HTTP only — port 22 there is
closed. The IP is the same machine the paljonkose deploy already uses, and its
host key matches the one already in `~/.ssh/known_hosts`.

### 5. Add the GitHub secrets

**Settings → Secrets and variables → Actions** on `kirbac1/ecommerce`.

The host and username are deliberately not written down in this public repo.

Already set:

| Secret | Status |
|---|---|
| `DEPLOY_HOST` | ✅ set — the server IP |
| `DEPLOY_USER` | ✅ set |
| `DEPLOY_SSH_KEY` | ✅ set — private half of `~/.ssh/ecommerce_deploy` |
| `DEPLOY_KNOWN_HOSTS` | ✅ set — pinned from `ssh-keyscan` |
| `APP_KEY` | ✅ set — freshly generated, 32 chars |

Still needed, because they depend on the database from step 3:

| Secret | What it is |
|---|---|
| `DB_DATABASE` | database name |
| `DB_USERNAME` | database user |
| `DB_PASSWORD` | that user's password |

```bash
gh secret set DB_DATABASE -R kirbac1/ecommerce
gh secret set DB_USERNAME -R kirbac1/ecommerce
gh secret set DB_PASSWORD -R kirbac1/ecommerce
```

`DEPLOY_KNOWN_HOSTS` pins the server's host key. Without it the deploy falls
back to trust-on-first-use and logs a warning — it works, but a DNS or routing
surprise could then redirect the deploy somewhere else.

`APP_KEY` is a different value from local development, and must not change
afterwards: changing it invalidates every session and every encrypted cookie.

### Optional repository variables

Only needed if the server does not match the defaults. Same screen, the
**Variables** tab:

| Variable | Default | When to set it |
|---|---|---|
| `SITE_URL` | `https://ecommerce.kirbac.fi` | different hostname |
| `DEPLOY_PATH` | `ecommerce` | releases live somewhere other than `~/invoicing` |
| `DOCROOT` | `httpdocs` | the subdomain's root is not `~/httpdocs` |
| `PHP_BIN` | `/opt/plesk/php/7.4/bin/php` | PHP 7.4 is installed elsewhere |

`DOCROOT` is the one to check first if the first deploy succeeds but the site
404s. A Plesk subdomain often gets its own directory
(`~/ecommerce.kirbac.fi/httpdocs`) rather than the subscription's `~/httpdocs`.

---

## Running a deploy

Push to `main`, or **Actions → Deploy to production → Run workflow**.

### Rolling back

A release swap is one symlink, so a rollback is one command:

```bash
ls ~/ecommerce/releases/                        # newest last
ln -sfn ~/ecommerce/releases/<previous> ~/httpdocs/current.tmp
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
| 500, `/healthz` returns 503 | database credentials in `~/ecommerce/shared/.env` |
| `.env` or source visible in a browser | document root points at the app root, not `public/` — fix immediately |
| Product images vanish after a deploy | `public/catalog` is not symlinked to `shared/catalog` |

The app log is at `~/ecommerce/shared/storage/logs/laravel.log`, shared across
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
