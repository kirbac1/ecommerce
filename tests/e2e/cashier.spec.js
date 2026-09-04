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
