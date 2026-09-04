import { test, expect } from '@playwright/test';

/** Seeded demo admin -- see database/seeds/UserSeeder.php. */
const ADMIN = { email: 'admin@example.com', password: 'test' };

async function login(page, user = ADMIN) {
    await page.goto('/admin/login', { waitUntil: 'domcontentloaded' });
    await page.fill('input[type="text"]', user.email);
    await page.fill('input[type="password"]', user.password);
    await page.click('a[href="#"]');
    await page.waitForURL('**/admin/dashboard', { timeout: 15000 });
}

test.describe('authentication', () => {
    test('admin pages redirect anonymous visitors to the login form', async ({ page }) => {
        await page.goto('/admin/products', { waitUntil: 'domcontentloaded' });

        expect(page.url()).toContain('/admin/login');
    });

    test('a seeded admin can sign in', async ({ page }) => {
        await login(page);

        expect(page.url()).toContain('/admin/dashboard');
    });
});

test.describe('dashboard', () => {
    test.beforeEach(async ({ page }) => {
        await login(page);
    });

    test('shows revenue from the seeded invoices, not zero', async ({ page }) => {
        const body = await page.locator('.dash-widget-item').first().innerText();
        const amounts = body.match(/€\s*([\d'.,]+)/g) || [];

        expect(amounts.length).toBeGreaterThan(0);

        const gross = parseFloat(amounts[0].replace(/[^\d.]/g, ''));
        expect(gross).toBeGreaterThan(0);
    });

    // Regression: sparklineLine() was defined inside charts.js's ready()
    // closure, so the dashboard's call to it threw ReferenceError.
    test('the sales sparkline helper is available to the page', async ({ page }) => {
        expect(await page.evaluate(() => typeof window.sparklineLine)).toBe('function');
    });

    test('the monthly sales sparkline draws a canvas', async ({ page }) => {
        const canvas = page.locator('.dash-widget-monthly-sales canvas');

        await expect(canvas.first()).toBeAttached();
        expect(await canvas.first().evaluate((c) => c.width)).toBeGreaterThan(0);
    });

    // Regression: this chart used to plot Math.random() demo data.
    test('the main chart plots real sales rather than random data', async ({ page }) => {
        const data = await page.evaluate(() => window.salesChartData);

        expect(data).toBeTruthy();
        expect(Array.isArray(data.invoices)).toBe(true);
        expect(data.invoices.length).toBe(31);
        expect(data.revenue.length).toBe(31);

        // The seeded month contains sales, so the series cannot be all zeroes.
        expect(data.revenue.reduce((a, b) => a + b, 0)).toBeGreaterThan(0);
    });

    test('the flot chart renders onto a canvas', async ({ page }) => {
        const canvas = page.locator('#curved-line-chart canvas.flot-base');

        await expect(canvas).toBeAttached();
        expect(await canvas.evaluate((c) => c.width)).toBeGreaterThan(0);
    });

    test('the percentage pies show whole numbers', async ({ page }) => {
        const pies = page.locator('.easy-pie .percent');
        expect(await pies.count()).toBeGreaterThan(0);

        for (let i = 0; i < await pies.count(); i++) {
            const value = (await pies.nth(i).innerText()).trim();

            // Not a long unrounded float like "64.9122807017544".
            expect(value).toMatch(/^\d+$/);
            expect(Number(value)).toBeLessThanOrEqual(100);
        }
    });

    test('best sellers name a real product with a working image', async ({ page }) => {
        const widget = page.locator('#best-selling');
        await expect(widget).toBeVisible();

        const image = widget.locator('img').first();
        await expect(image).toHaveAttribute('src', /^\/catalog\//);

        expect(
            await image.evaluate((img) => img.complete && img.naturalWidth > 0)
        ).toBe(true);
    });
});

test.describe('admin pages load', () => {
    test.beforeEach(async ({ page }) => {
        await login(page);
    });

    for (const [name, path] of Object.entries({
        products: '/admin/products',
        customers: '/admin/customers',
        categories: '/admin/categories',
        orders: '/admin/orders',
        invoices: '/admin/invoices',
        returns: '/admin/returns',
        users: '/admin/users',
        settings: '/admin/settings',
    })) {
        test(`${name} renders without a server error`, async ({ page }) => {
            const response = await page.goto(path, { waitUntil: 'domcontentloaded' });

            expect(response.status()).toBe(200);
            await expect(page.locator('body')).not.toContainText('Whoops, looks like something went wrong');
        });
    }

    test('the category tree is populated', async ({ page }) => {
        await page.goto('/admin/categories', { waitUntil: 'domcontentloaded' });

        const content = await page.locator('body').innerText();
        expect(content.length).toBeGreaterThan(100);
    });

    // Regression: SettingsSeeder's class name did not match its filename, it was
    // never called by DatabaseSeeder, and it never called Setting::save() -- so
    // the settings page came up empty on a fresh install.
    test('the settings page is populated with the seeded store details', async ({ page }) => {
        await page.goto('/admin/settings', { waitUntil: 'domcontentloaded' });

        const storeName = page.locator('input[name="store_name"], #store_name').first();

        if (await storeName.count()) {
            await expect(storeName).toHaveValue(/\S/);
        } else {
            await expect(page.locator('body')).toContainText('Ugur Bakkal');
        }
    });

    test('admin pages resolve their translation keys', async ({ page }) => {
        await page.goto('/admin/dashboard', { waitUntil: 'domcontentloaded' });

        const body = await page.locator('body').innerText();
        expect(body).not.toMatch(/\bmessages\.[A-Za-z_]/);
    });
});

test.describe('read-only demo accounts', () => {
    // The seeded logins are published in the readme, so they must not be able
    // to change anything. See App\Http\Middleware\PreventDemoWrites.
    test.beforeEach(async ({ page }) => {
        await login(page);
    });

    test('reading still works', async ({ page }) => {
        const response = await page.goto('/admin/products', { waitUntil: 'domcontentloaded' });

        expect(response.status()).toBe(200);
    });

    test('creating is refused', async ({ page }) => {
        const result = await page.evaluate(async () => {
            const token = document.querySelector("meta[name='_token']")?.getAttribute('content');
            const res = await fetch('/api/v3/manufacturers', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': token,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ name: 'Should Not Exist' }),
            });
            return { status: res.status, body: await res.text() };
        });

        expect(result.status).toBe(403);
        expect(result.body).toContain('demo_read_only');
    });

    test('deleting is refused', async ({ page }) => {
        const status = await page.evaluate(async () => {
            const token = document.querySelector("meta[name='_token']")?.getAttribute('content');
            const res = await fetch('/api/v3/products/1', {
                method: 'DELETE',
                headers: { 'X-CSRF-TOKEN': token, 'Accept': 'application/json' },
            });
            return res.status;
        });

        expect(status).toBe(403);
    });

    // Several routes mutate on a GET, so blocking by HTTP verb alone is not
    // enough and these have to be named explicitly in the middleware.
    test('routes that mutate on a GET are refused too', async ({ page }) => {
        const status = await page.evaluate(async () => {
            const res = await fetch('/api/v3/orders/1/convertToInvoice', {
                headers: { 'Accept': 'application/json' },
            });
            return res.status;
        });

        expect(status).toBe(403);
    });

    test('the product it tried to delete is still there', async ({ request }) => {
        const response = await request.get('/api/v3/products/1');

        expect(response.status()).toBe(200);
    });
});
