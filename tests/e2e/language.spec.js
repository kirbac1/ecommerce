import { test, expect } from '@playwright/test';

/**
 * Language switching.
 *
 * The storefront used to render Finnish whatever was selected, because
 * lang/en/header.php was a verbatim copy of the Finnish file. Locale is also
 * resolved centrally now (App\Http\Middleware\SetLocale) rather than by each
 * controller action, which is what made a chosen language survive the request.
 */
test.describe('storefront', () => {
    test('the header offers a language switcher', async ({ page }) => {
        await page.goto('/', { waitUntil: 'domcontentloaded' });

        const items = page.locator('.language-switcher__item');
        expect(await items.count()).toBeGreaterThanOrEqual(2);
        await expect(page.locator('.language-switcher__item.is-active')).toHaveCount(1);
    });

    test('switching to English actually renders English', async ({ page }) => {
        await page.goto('/locale/en', { waitUntil: 'domcontentloaded' });
        await page.goto('/', { waitUntil: 'domcontentloaded' });

        await expect(page.locator('#search input, input[type="text"]').first())
            .toHaveAttribute('placeholder', /Search products/i);
        await expect(page.locator('.language-switcher__item.is-active')).toHaveText('EN');
    });

    test('switching to Finnish actually renders Finnish', async ({ page }) => {
        await page.goto('/locale/fi', { waitUntil: 'domcontentloaded' });
        await page.goto('/', { waitUntil: 'domcontentloaded' });

        await expect(page.locator('#search input, input[type="text"]').first())
            .toHaveAttribute('placeholder', /Etsi tuote/i);
        await expect(page.locator('.language-switcher__item.is-active')).toHaveText('FI');
    });

    test('the choice survives navigation', async ({ page }) => {
        await page.goto('/locale/en', { waitUntil: 'domcontentloaded' });

        for (const path of ['/', '/gallery', '/promotions', '/brands']) {
            await page.goto(path, { waitUntil: 'domcontentloaded' });
            await expect(page.locator('.language-switcher__item.is-active'), path).toHaveText('EN');
        }
    });

    test('an unsupported language is a 404', async ({ page }) => {
        const response = await page.goto('/locale/zz', { waitUntil: 'domcontentloaded' });

        expect(response.status()).toBe(404);
    });
});

test.describe('admin', () => {
    test.beforeEach(async ({ page }) => {
        await page.goto('/admin/login', { waitUntil: 'domcontentloaded' });
        await page.fill('input[type="text"]', 'admin@example.com');
        await page.fill('input[type="password"]', 'test');
        await page.click('a[href="#"]');
        await page.waitForURL('**/admin/dashboard', { timeout: 15000 });
    });

    test('the header offers a language switcher', async ({ page }) => {
        await expect(page.locator('#header .language-switcher__item').first()).toBeVisible();
    });

    test('switching changes the admin language', async ({ page }) => {
        await page.goto('/locale/en', { waitUntil: 'domcontentloaded' });
        await page.goto('/admin/dashboard', { waitUntil: 'domcontentloaded' });
        await expect(page.locator('body')).toContainText('Manage your invoices');

        await page.goto('/locale/fi', { waitUntil: 'domcontentloaded' });
        await page.goto('/admin/dashboard', { waitUntil: 'domcontentloaded' });
        await expect(page.locator('body')).toContainText('Laskujen hallinta');
    });

    // The demo logins are read-only, but choosing a language is session state,
    // not a database write, so it must still work for them.
    test('a read-only demo account can still switch language', async ({ page }) => {
        const response = await page.goto('/locale/en', { waitUntil: 'domcontentloaded' });

        expect(response.status()).toBeLessThan(400);
        await page.goto('/admin/dashboard', { waitUntil: 'domcontentloaded' });
        await expect(page.locator('#header .language-switcher__item.is-active')).toHaveText('EN');
    });
});

test.describe('storefront product grid', () => {
    // These were hardcoded English in the templates, so they never translated
    // however the language was set.
    const labels = {
        en: { cart: 'Add to Cart', heading: 'Popular Products', qty: 'QTY' },
        fi: { cart: 'Lisää ostoskoriin', heading: 'Suositut tuotteet', qty: 'KPL' },
    };

    for (const [locale, expected] of Object.entries(labels)) {
        test(`the grid labels are translated in ${locale}`, async ({ page }) => {
            await page.goto(`/locale/${locale}`, { waitUntil: 'domcontentloaded' });
            await page.goto('/', { waitUntil: 'domcontentloaded' });
            await page.locator('.main-products .product-grid-item').first().waitFor();

            // innerText reflects CSS text-transform, and the theme uppercases
            // these buttons, so compare case-insensitively.
            const body = (await page.locator('body').innerText()).toLowerCase();
            expect(body, 'add-to-cart label').toContain(expected.cart.toLowerCase());
            expect(body, 'section heading').toContain(expected.heading.toLowerCase());
            expect(body, 'quantity label').toContain(expected.qty.toLowerCase());
        });
    }

    test('the Finnish grid shows no leftover English labels', async ({ page }) => {
        await page.goto('/locale/fi', { waitUntil: 'domcontentloaded' });
        await page.goto('/', { waitUntil: 'domcontentloaded' });
        await page.locator('.main-products .product-grid-item').first().waitFor();

        const grid = (await page.locator('.main-products').innerText()).toLowerCase();
        expect(grid).not.toContain('add to cart');
        expect(grid).not.toContain('popular products');
    });
});

test.describe('cart and checkout translations', () => {
    // The cart table headings and buttons were hardcoded English, so the page
    // stayed English even with the site set to Finnish.
    // The line-item table is v-show'd, so on an empty cart it is present but
    // hidden -- innerText skips it, textContent does not. Using textContent
    // checks the template's strings whatever the cart contains.
    test('the cart page is translated into Finnish', async ({ page }) => {
        await page.goto('/locale/fi', { waitUntil: 'domcontentloaded' });
        await page.goto('/cart', { waitUntil: 'domcontentloaded' });

        const text = (await page.locator('body').textContent()).toLowerCase();
        for (const word of ['ostoskori', 'tuote', 'määrä', 'yksikköhinta', 'jatka ostoksia']) {
            expect(text, word).toContain(word);
        }
    });

    test('the Finnish cart shows no leftover English headings', async ({ page }) => {
        await page.goto('/locale/fi', { waitUntil: 'domcontentloaded' });
        await page.goto('/cart', { waitUntil: 'domcontentloaded' });

        const text = (await page.locator('body').textContent()).toLowerCase();
        for (const word of ['product name', 'unit price', 'continue shopping', 'shopping cart']) {
            expect(text, word).not.toContain(word);
        }
    });

    test('the cart page is translated into English', async ({ page }) => {
        await page.goto('/locale/en', { waitUntil: 'domcontentloaded' });
        await page.goto('/cart', { waitUntil: 'domcontentloaded' });

        const text = (await page.locator('body').textContent()).toLowerCase();
        expect(text).toContain('shopping cart');
        expect(text).toContain('continue shopping');
    });
});
