## Invoicing & webshop platform

A Laravel 13 + Vue 1.x invoicing and e-commerce platform, originally built in
2016 on Laravel 5.2 and since ported to PHP 8.
It has an admin back office (products, orders, invoices, proformas, returns, customers,
tickets), a cashier register, a customer-facing storefront, and a JSON API under `/api/v3`.

The front end is plain Vue 1.0.17 loaded from a `<script>` tag with pre-built CSS/JS in
`public/assets`. **There is no front-end build step** — no npm install, no gulp, no elixir.

---

## Fresh install

These steps are written for macOS with Homebrew. Adapt the package manager for other
systems; nothing below is macOS-specific apart from the install commands.

### 1. Requirements

| Component | Version | Why |
|---|---|---|
| PHP | **8.3+** | Laravel 13's floor |
| MySQL or MariaDB | 5.7+ | Migrations use MySQL-flavoured schema |
| Composer | 2.x | Installs the PHP dependencies |

### 2. Install PHP and MySQL

```bash
brew install php@8.3 mysql
```

`php@8.3` is keg-only, meaning it is deliberately **not** put on your `PATH`. Its binary
lives at `/opt/homebrew/opt/php@8.3/bin/php` (Apple Silicon) or
`/usr/local/opt/php@8.3/bin/php` (Intel). Use that full path, or add it to your `PATH`
for the session:

```bash
export PATH="/opt/homebrew/opt/php@8.3/bin:$PATH"
```

### 3. Start MySQL and create the database

```bash
brew services start mysql
```

Then create the schema and a user for it. Change the password to something of your own:

```bash
mysql -u root -e "
CREATE DATABASE IF NOT EXISTS carta CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER IF NOT EXISTS 'carta'@'127.0.0.1' IDENTIFIED BY 'change-me';
GRANT ALL PRIVILEGES ON carta.* TO 'carta'@'127.0.0.1';
FLUSH PRIVILEGES;"
```

### 4. Configure the environment

```bash
cp .env.example .env
```

Edit `.env` so the database block matches what you created above:

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=carta
DB_USERNAME=carta
DB_PASSWORD=change-me
```

Keep `CACHE_DRIVER=file`. The original `.env.development` used a Redis server that no
longer exists; unless you run Redis locally, `file` is what you want.

Generate an application key:

```bash
php artisan key:generate
```

### 5. Install PHP dependencies

```bash
curl -sS -o composer.phar https://getcomposer.org/composer-2.phar
php composer.phar install --no-scripts
```

`--no-scripts` skips the post-install `artisan optimize` / ide-helper hooks, which are not
needed and are slow.

### 6. Create the tables and demo data

```bash
php artisan migrate --seed
```

This builds the schema and seeds a working demo shop: users, 180 categories, 6
manufacturers, 16 products (with generated placeholder images), customers, support
tickets, and **30 days of orders, invoices and returns** so the admin dashboard has real
numbers to chart.

To wipe and rebuild at any point:

```bash
php artisan migrate:refresh --seed
```

### 7. Make the storage directories writable

```bash
chmod -R 777 storage bootstrap/cache
```

### 8. Run it

```bash
./run-local.sh
```

That is a thin wrapper around `artisan serve` that uses the right PHP binary. Or run it
directly:

```bash
PHP_CLI_SERVER_WORKERS=32 php artisan serve --host=127.0.0.1 --port=8000
```

`PHP_CLI_SERVER_WORKERS` matters: PHP's built-in server is single-threaded by default, and
it ties up one process per open browser connection. These pages pull ~45 assets, so
without plenty of headroom the page load intermittently stalls.

Then open:

- Storefront — <http://127.0.0.1:8000/>
- Admin — <http://127.0.0.1:8000/admin>
- Cashier — <http://127.0.0.1:8000/cashier>

### Demo logins

All seeded accounts use the password `test`.

| Email | Role |
|---|---|
| `admin@example.com` | Super admin (English) |
| `manager@example.com` | Super admin (Finnish) |
| `cashier@example.com` | Cashier |

These are safe to publish: they are flagged `demo` in the database, which makes
every one of their sessions **read-only** — see [Demo mode](#demo-mode). Clear
the flag on an account that needs to write.

### Demo mode

`App\Http\Middleware\PreventDemoWrites` refuses anything that would change the
database, so the credentials above can be shared without the site being
vandalised. Reads, navigation and the whole admin UI work normally; writes come
back as a 403.

It switches on in two ways:

- **`DEMO_MODE=true`** in `.env` makes the entire site read-only for everyone.
- **a `demo` flag on a user or customer row** applies the same to just that
  login, leaving the rest of the site writable.

Blocking on the HTTP verb alone would not be enough: signing in is a POST (so
the auth routes are allowed through), and several routes mutate on a GET
(`convertToInvoice` and friends), so those are named explicitly.

### Branding

The wordmark is `public/templates/assets/images/logo.png` (the previous one is kept
alongside it as `logo-halime-original.png`). The store name, address, VAT id, IBAN and
contact details come from `database/seeds/SettingsSeeder.php` and are editable in the
admin under **Settings**.

### Running under Apache/nginx instead

Point the document root at the `public/` directory, not the repository root. The
`.htaccess` in `public/` handles the rewrite.

---

## Tests

Two suites, both driven from `package.json`. Install the tooling once with
`npm install` (and `npx playwright install chromium` for the browser).

```bash
npm test          # Vitest unit tests
npm run test:e2e  # Playwright end-to-end tests
npm run test:all  # both
```

**Vitest** (`tests/js/`) covers `public/assets/js/pricing.js`, the shared price
arithmetic. It is small but load-bearing: the API serialises decimals as strings,
so every one of these values has to be coerced before use.

**Playwright** (`tests/e2e/`) drives a real browser against a running app:

| Spec | Covers |
|---|---|
| `api.spec.js` | every `/api/v3` collection, search by name and by brand, the category tree, PDF export |
| `storefront.spec.js` | product grid, prices, images, gallery, offers, brands filter, translation keys |
| `admin.spec.js` | login, dashboard charts and totals, every admin page, settings, the read-only demo lockdown |
| `cashier.spec.js` | the register, its document pages, and the return print preview |

The Playwright config starts the app itself (`webServer` → `./run-local.sh`) and
reuses one that is already running. It needs the seeded demo data, so run
`php artisan migrate --seed` first. It runs with a single worker on purpose:
PHP's built-in server dedicates a process to each open browser connection, and
more than one browser at a time starves it.

Many of the tests are written as regressions against specific bugs found while
getting this running again; those cases carry a comment saying what broke.

There is also the original PHPUnit suite in `tests/`, runnable with
`vendor/bin/phpunit`.

---

## Deployment

Pushes to `main` deploy to <https://ecommerce.kirbac.fi> via GitHub Actions:
the test suite runs, `vendor/` is built on PHP 7.4, a timestamped release is
rsynced to the Plesk server, migrations run, and a symlink is flipped
atomically. `/healthz` must return 200 or the run fails.

Server setup, the required secrets, rollback and troubleshooting are in
[DEPLOYMENT.md](DEPLOYMENT.md).

---

## The PHP 8 port

This started as Laravel 5.2 on PHP 5.5/7.0 and was ported to Laravel 13 on PHP
8.3, because the target server offers only PHP 8.x. Laravel 5.2 cannot run
there at all: it and its Symfony 3.0 components call `each()`, removed in PHP
8.0, and 44 of the 51 locked packages predated 8.x.

What that involved, in case something looks unfamiliar:

- **Abandoned packages replaced.** `baum/baum` (category tree) →
  `kalnoy/nestedset`, keeping the existing `lft`/`rgt` columns via the model's
  name getters. `arcanedev/settings` → `App\Support\Settings`, a small
  key/value class over the same table. `maatwebsite/excel` 2.1 → native
  `fputcsv`/`fgetcsv`, since it was only ever producing a flat CSV.
  `danielboendergaard/phantom-pdf` → mPDF (its PhantomJS binary was Linux-only
  and the project was abandoned in 2018).
- **Real schema bugs surfaced.** Laravel 5.2 ran MySQL with `strict => false`,
  which hid two genuine faults: `categories.slug` was NOT NULL and never
  populated (stored as `''`), and five tables declared `deleted_at` NOT NULL,
  which is incoherent for a soft-delete column. Strict mode is on now and both
  are fixed properly rather than by loosening the database.
- **The error_reporting shim is gone.** Laravel 5.2's Eloquent called
  `count()` on a null, so the app needed PHP 5.6-era error levels to boot at
  all. That code no longer exists; warnings are switched on, and the handful of
  places relying on the old leniency — reading `Auth::user()->isCustomer` as a
  guest, for one — were fixed. mPDF is the one exception: it is not
  warning-clean, so warnings from inside its own files are suppressed for the
  duration of a render and nothing else.
- **Structural moves** that come with modern Laravel: routes to `routes/web.php`
  with controllers referenced by class, `database/seeds` → `database/seeders`
  under `Database\Seeders`, `$factory->define()` → factory classes,
  `resources/lang` → `lang/`, and the HTTP/Console kernels replaced by
  `bootstrap/app.php`.

The Vue 1.x front end and every Blade template were carried over unchanged apart
from those fixes.

## Usage

After the database population, you can point your browser to http://localhost to get the index page, or one of the following to retrieve a RESTful resource of the models inside the database:

- http://localhost/api/v3/orders // to get all the orders
- http://localhost/api/v3/orders/{order_id} // to get a particular order
- http://localhost/api/v3/proformas // to get all the proformas
- http://localhost/api/v3/proformas/{proforma_id} // to get a particular proforma
- http://localhost/api/v3/payments // to get all the payments handled by the application
- http://localhost/api/v3/payments/{payment_id} // to get a particular payment
- http://localhost/api/v3/returns // get all the returns
- http://localhost/api/v3/returns/{return_id} // get a particular return
- http://localhost/api/v3/customers // to get all the customers
- http://localhost/api/v3/customers/{customer_id} // get a particular customer
- http://localhost/api/v3/categories // get all the categories
- http://localhost/api/v3/categories/{category_id} // get a particular category
- http://localhost/api/v3/discounts // get all the discounts
- http://localhost/api/v3/discounts/{discount_id} // get a particular discount
- http://localhost/api/v3/manufactirers // get all the manufacturers
- http://localhost/api/v3/manufacturers/{manufacturer_id} // get a particular manufacturer
- http://localhost/api/v3/tickets // get all the tickets
- http://localhost/api/v3/tickets/{ticket_code} // get a particular ticket
- http://localhost/api/v3/users // get all the users
- http://localhost/api/v3/users/{user_id} get a particular user
- http://localhost/api/v3/warehouses // get all the warehouses
- http://localhost/api/v3/warehouses // get a particular warehouse

#### Update an entity
- (PUT) http://localhost/api/v3/products/{product_id} // update a product with new values
- (POST) http://localhost/api/v3/products // create a new product
- (DELETE) http://localhost/api/v3/products/{product_id} // delete a product

The content of PUT and POST requests are the values you need to create (or update), sent in JSON.
The attributes you need to get are the same you get when retrieving them from the database, except the id that is autogenerated.

##### Example
```
POST to http://localhost/api/v3/products

{
  "image": "bulgur-coarse-1kg.png",
  "category_id": "1",
  "name": "Product name",
  "visible": "1",
  "sku": "9791402652904",
  "barcode": "8519658460415",
  "qtyPerPack": "10",
  "basePrice": "31.00",
  "taxPercent": "30.00",
  "measureunit": "kg",
  "manufacturer_id": 2
}
```

#### Search features

The search features are, obviously, not REST enabled and will retrieve the records for the search <string> sent.
The result is still sent as JSON.

- http://localhost/api/v3/search/categories/{string} // search all the categories by {string}
- http://localhost/api/v3/search/customers/{string} // search all the customers by {string}
- http://localhost/api/v3/search/customergroups/{string} // search all the customer groups by {string}
- http://localhost/api/v3/search/discounts/{string} // search all the discounts by {string}
- http://localhost/api/v3/search/invoices/{string} // search all the invoices by {string}
- http://localhost/api/v3/search/manufacturers/{string} // search all the manufacturers by {string}
- http://localhost/api/v3/search/orders/{string} // search all the orders by {string}
- http://localhost/api/v3/search/payments/{string} // search all the payments by {string}
- http://localhost/api/v3/search/products/{string} // search all the products by {string}
- http://localhost/api/v3/search/proformas/{string} // search all the proformas by {string}
- http://localhost/api/v3/search/returns/{string} // search all the returns by {string}
- http://localhost/api/v3/search/tickets/{string} // search all the tickets by {string}
- http://localhost/api/v3/search/users/{string} // search all the users by {string}
- http://localhost/api/v3/search/warehouses/{string} // search all the warehouses by {string}
- http://localhost/api/v3/search // get all the searchable items

### License

This platform is CLOSED SOURCE, and NO FORKING, COPYING, STEALING is allowed, and will be punished at the maximum extents permitted by the law.
