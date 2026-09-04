import { test, expect } from '@playwright/test';

/**
 * Storefront pages. These assume the demo data from `php artisan migrate --seed`
 * is loaded: 16 products, 5 discounts, all with generated catalog images.
 */

/** Wait until every <img> on the page has either loaded or failed. */
async function settleImages(page) {
    await page.waitForFunction(
        () => Array.from(document.images).every((img) => img.complete),
        null,
        { timeout: 15000 }
    ).catch(() => {});
}

/** Every <img> that finished loading with no intrinsic size is broken. */
async function brokenImages(page) {
    return page.evaluate(() =>
        Array.from(document.images)
            .filter((img) => img.complete && img.naturalWidth === 0)
            // An empty src is a Vue placeholder that is hidden until used.
            .filter((img) => img.getAttribute('src'))
            .map((img) => img.getAttribute('src'))
    );
}

test.describe('home page', () => {
    test('renders the product grid from the API', async ({ page }) => {
        await page.goto('/', { waitUntil: 'domcontentloaded' });

        const items = page.locator('.product-grid-item');
        await expect(items.first()).toBeVisible();
        expect(await items.count()).toBeGreaterThan(0);

        await expect(page.locator('.product-grid-item').first()).toContainText(/\S/);
    });

    test('shows tax-inclusive prices, not raw decimal strings', async ({ page }) => {
        await page.goto('/', { waitUntil: 'domcontentloaded' });
        await page.locator('.main-products .product-grid-item .price').first().waitFor();

        const price = await page.locator('.main-products .product-grid-item .price').first().innerText();

        // Formatted as €3.36 -- not "2.9500", and not the 295.41 that the
        // string-concatenation bug used to produce.
        expect(price).toMatch(/€\d+\.\d{2}/);
        expect(price).not.toMatch(/\d\.\d{4}/);
    });

    test('product count reflects what is on screen', async ({ page }) => {
        await page.goto('/', { waitUntil: 'domcontentloaded' });
        await page.locator('.product-grid-item').first().waitFor();

        // Poll: the grid is rendered by Vue after the products request, so a
        // single read can catch it mid-render.
        await expect
            .poll(async () => {
                const shown = await page.locator('.results').first().innerText();
                const count = await page.locator('.main-products .product-grid-item').count();
                return shown.includes(String(count));
            }, { timeout: 10000 })
            .toBe(true);
    });

    test('loads every image it references', async ({ page }) => {
        await page.goto('/', { waitUntil: 'domcontentloaded' });
        await page.locator('.product-grid-item').first().waitFor();
        await settleImages(page);

        expect(await brokenImages(page)).toEqual([]);
    });

    test('resolves its translation keys', async ({ page }) => {
        await page.goto('/', { waitUntil: 'domcontentloaded' });
        await page.locator('.product-grid-item').first().waitFor();

        // An untranslated key renders literally, e.g. "messages.SLOGAN1".
        const body = await page.locator('body').innerText();
        expect(body).not.toMatch(/\bmessages\.[A-Za-z_]/);
        expect(body).not.toMatch(/\binfo\.[a-z-]+/);
    });
});

test.describe('product detail', () => {
    test('shows the product and its catalog image', async ({ page }) => {
        await page.goto('/product?id=1', { waitUntil: 'domcontentloaded' });

        const image = page.locator('#image');
        await expect(image).toBeVisible();

        // The image must come from /catalog/, not a dead 2016 CDN.
        await expect(image).toHaveAttribute('src', /^\/catalog\//);
        await settleImages(page);
        expect(await brokenImages(page)).toEqual([]);
    });
});

test.describe('gallery', () => {
    test('is a gallery, not a copy of the About Us page', async ({ page }) => {
        await page.goto('/gallery', { waitUntil: 'domcontentloaded' });

        await expect(page.locator('h1.heading-title')).not.toHaveText(/About Us/i);

        const items = page.locator('.product-grid-item');
        await expect(items.first()).toBeVisible();
        expect(await items.count()).toBeGreaterThan(0);
    });

    test('has no leftover lorem ipsum placeholders', async ({ page }) => {
        await page.goto('/gallery', { waitUntil: 'domcontentloaded' });

        const content = await page.locator('#content').innerText();
        expect(content).not.toMatch(/Lorem ipsum/i);
        expect(content).not.toMatch(/^Hello$/m);
    });
});

test.describe('offers', () => {
    test('lists discounted products with both prices', async ({ page }) => {
        await page.goto('/promotions', { waitUntil: 'domcontentloaded' });

        await expect(page.locator('h1.heading-title')).not.toHaveText(/About Us/i);

        const items = page.locator('.product-grid-item');
        await expect(items.first()).toBeVisible();

        const first = items.first();
        await expect(first.locator('.price-new')).toContainText(/€\d+\.\d{2}/);
        await expect(first.locator('.price-old')).toContainText(/€\d+\.\d{2}/);
    });

    test('shows the offer name and percentage', async ({ page }) => {
        await page.goto('/promotions', { waitUntil: 'domcontentloaded' });
        await page.locator('.offer-label').first().waitFor();

        await expect(page.locator('.offer-label').first()).toContainText(/-\d+%/);
    });

    test('the discounted price is below the original', async ({ page }) => {
        await page.goto('/promotions', { waitUntil: 'domcontentloaded' });
        await page.locator('.price-new').first().waitFor();

        const toNumber = (text) => parseFloat(text.replace(/[^\d.]/g, ''));
        const newPrice = toNumber(await page.locator('.price-new').first().innerText());
        const oldPrice = toNumber(await page.locator('.price-old').first().innerText());

        expect(newPrice).toBeGreaterThan(0);
        expect(newPrice).toBeLessThan(oldPrice);
    });
});

test.describe('brands', () => {
    test('lists products on first load, before any filter is ticked', async ({ page }) => {
        await page.goto('/brands', { waitUntil: 'domcontentloaded' });

        const items = page.locator('.product-grid-item');
        await expect(items.first()).toBeVisible();
        expect(await items.count()).toBeGreaterThan(0);
    });

    test('offers a brand filter populated from the API', async ({ page }) => {
        await page.goto('/brands', { waitUntil: 'domcontentloaded' });
        await page.locator('.sf-name').first().waitFor();

        expect(await page.locator('.sf-name').count()).toBeGreaterThan(0);
    });

    test('filtering by a brand shows only that brand', async ({ page, request }) => {
        await page.goto('/brands', { waitUntil: 'domcontentloaded' });

        // Scope to the main grid: the footer carousel also renders
        // .product-grid-item, which made a plain count meaningless.
        const grid = page.locator('.main-products .product-grid-item');
        await grid.first().waitFor();

        const brand = (await page.locator('.sf-name').first().innerText()).trim();

        await page.locator('.sf-category input[type="checkbox"]').first().check();
        const response = await page.waitForResponse((r) => r.url().includes('/api/v3/search/products/'));
        const expected = (await response.json()).count;

        // The grid should settle on exactly what the API said for that brand,
        // rather than merely being no larger than before -- the old assertion
        // raced the initial render and could pass or fail on timing alone.
        await expect
            .poll(async () => grid.count(), { timeout: 10000 })
            .toBe(expected);

        expect(expected, `no products for brand "${brand}"`).toBeGreaterThan(0);
    });
});

test.describe('cart', () => {
    // Regression: the cart control was a Bootstrap dropdown toggle wrapping a
    // bare anchor. The toggle swallowed the click, so the cart was unreachable
    // from the header.
    test('the header cart icon opens the cart', async ({ page }) => {
        await page.goto('/', { waitUntil: 'domcontentloaded' });

        await page.locator('#cart a[href="/cart"]').first().click();
        await page.waitForURL('**/cart', { timeout: 10000 });

        expect(new URL(page.url()).pathname).toBe('/cart');
    });

    test('the cart page itself renders', async ({ page }) => {
        const response = await page.goto('/cart', { waitUntil: 'domcontentloaded' });

        expect(response.status()).toBe(200);
    });
});
