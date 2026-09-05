import { test, expect } from '@playwright/test';

/** Seeded cashier — see database/seeders/UserSeeder.php. */
const CASHIER = { email: 'cashier@example.com', password: 'test' };

async function login(page) {
    await page.goto('/cashier/login', { waitUntil: 'domcontentloaded' });
    await page.fill('input[type="text"], input[name="email"]', CASHIER.email);
    await page.fill('input[type="password"]', CASHIER.password);
    await page.click('a[href="#"], button[type="submit"], input[type="submit"]');
    await page.waitForTimeout(1500);
}

test.describe('cashier register', () => {
    test('anonymous visitors are sent to the login form', async ({ page }) => {
        await page.goto('/cashier', { waitUntil: 'domcontentloaded' });

        expect(page.url()).toContain('/cashier/login');
    });

    test('the login form renders', async ({ page }) => {
        const response = await page.goto('/cashier/login', { waitUntil: 'domcontentloaded' });

        expect(response.status()).toBe(200);
        await expect(page.locator('input[type="password"]')).toBeVisible();
    });
});

test.describe('cashier pages', () => {
    test.beforeEach(async ({ page }) => {
        await login(page);
    });

    for (const [name, path] of Object.entries({
        register: '/cashier',
        index: '/cashier/index',
        proforma: '/cashier/proforma',
        invoice: '/cashier/invoice',
        receipt: '/cashier/receipt',
        return: '/cashier/return',
        shipment: '/cashier/shipment',
    })) {
        test(`${name} renders without a server error`, async ({ page }) => {
            const response = await page.goto(path, { waitUntil: 'domcontentloaded' });

            expect(response.status()).toBe(200);
            await expect(page.locator('body')).not.toContainText('Whoops');
        });
    }

    // Regression: this rendered the view without the $return and $total it
    // requires. Undefined-variable notices were suppressed on the old stack, so
    // it produced a page of blank fields; with warnings on it was a 500.
    test('the return print preview renders', async ({ page }) => {
        const response = await page.goto('/cashier/returnPreview', { waitUntil: 'domcontentloaded' });

        expect(response.status()).toBe(200);
    });

    test('the return print preview accepts an id', async ({ page }) => {
        const response = await page.goto('/cashier/returnPreview?id=1', { waitUntil: 'domcontentloaded' });

        expect(response.status()).toBe(200);
        await expect(page.locator('body')).toContainText('PALAUTUS');
    });

    test('an unknown return id is a 404, not a 500', async ({ page }) => {
        const response = await page.goto('/cashier/returnPreview?id=99999', { waitUntil: 'domcontentloaded' });

        expect(response.status()).toBe(404);
    });
});

test.describe('cashier markup', () => {
    test.beforeEach(async ({ page }) => {
        await login(page);
    });

    // Regression: the views emitted a stray <head>, a block of scripts after
    // @stop, and an IE9 conditional comment -- all outside any @section, so
    // Blade wrote them before the layout's <!doctype html>. The browser then
    // discarded the doctype and rendered the whole cashier in quirks mode,
    // which is what broke the layout.
    test('renders in standards mode, not quirks mode', async ({ page }) => {
        await page.goto('/cashier', { waitUntil: 'domcontentloaded' });

        expect(await page.evaluate(() => document.compatMode)).toBe('CSS1Compat');
        expect(await page.evaluate(() => !!document.doctype)).toBe(true);
    });

    test('the login page renders in standards mode too', async ({ page }) => {
        await page.goto('/cashier/login', { waitUntil: 'domcontentloaded' });

        expect(await page.evaluate(() => document.compatMode)).toBe('CSS1Compat');
    });

    // The stray <head> duplicated every stylesheet the layout already loads.
    test('each stylesheet is loaded once', async ({ page }) => {
        await page.goto('/cashier', { waitUntil: 'domcontentloaded' });

        const hrefs = await page.evaluate(() =>
            Array.from(document.querySelectorAll('link[rel=stylesheet]'))
                .map((l) => l.getAttribute('href'))
                .filter((h) => h && !h.includes('_debugbar'))
        );

        expect(hrefs.length).toBe(new Set(hrefs).size);
    });

    test('loads every image it references', async ({ page }) => {
        await page.goto('/cashier/login', { waitUntil: 'domcontentloaded' });
        await page.waitForFunction(
            () => Array.from(document.images).every((img) => img.complete),
            null,
            { timeout: 15000 }
        ).catch(() => {});

        const broken = await page.evaluate(() =>
            Array.from(document.images)
                .filter((img) => img.complete && img.naturalWidth === 0)
                .map((img) => img.getAttribute('src'))
                .filter(Boolean)
        );

        expect(broken).toEqual([]);
    });

    // The shop name was hardcoded as the previous owner's; it now follows the
    // store_name setting.
    test('the header shows the configured store name', async ({ page }) => {
        await page.goto('/cashier', { waitUntil: 'domcontentloaded' });

        const brand = await page.locator('.brand h4 a').first().innerText();
        expect(brand.trim()).toBe('Ugur Bakkal');
    });
});

test.describe('cashier sign-out', () => {
    // Regression: logout rendered a view that does not exist (500) and never
    // called Auth::logout(), so the session survived and there was no way to
    // switch account.
    test('every register page carries a logout control', async ({ page }) => {
        await login(page);

        for (const path of ['/cashier', '/cashier/invoice', '/cashier/receipt',
                            '/cashier/proforma', '/cashier/return', '/cashier/shipment']) {
            await page.goto(path, { waitUntil: 'domcontentloaded' });
            await expect(page.locator('a.cashier-logout'), path).toHaveCount(1);
        }
    });

    test('logging out ends the session', async ({ page }) => {
        await login(page);
        await page.goto('/cashier/logout', { waitUntil: 'domcontentloaded' });

        expect(page.url()).toContain('/cashier/login');

        const response = await page.goto('/cashier', { waitUntil: 'domcontentloaded' });
        expect(response.url()).toContain('/cashier/login');
    });

    test('after logging out, an admin can sign in in the same browser', async ({ page }) => {
        await login(page);
        await page.goto('/cashier/logout', { waitUntil: 'domcontentloaded' });

        await page.goto('/admin/login', { waitUntil: 'domcontentloaded' });
        await page.fill('input[name="email"], input[type="text"]', 'admin@example.com');
        await page.fill('input[type="password"]', 'test');
        await page.click('a[href="#"], button[type="submit"], input[type="submit"]');
        await page.waitForURL('**/admin/dashboard', { timeout: 15000 });

        expect(page.url()).toContain('/admin/dashboard');
    });
});
