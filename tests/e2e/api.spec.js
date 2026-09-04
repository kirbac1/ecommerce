import { test, expect } from '@playwright/test';

/**
 * The /api/v3 JSON API. These also guard the PHP-version compatibility work:
 * on stock PHP 7.4 every one of these returned a 500, because Eloquent's own
 * Builder::applyScopes() calls count() on a null and Laravel 5.2 escalates the
 * resulting warning into an exception.
 */

const collections = [
    'products',
    'customers',
    'categories',
    'manufacturers',
    'orders',
    'invoices',
    'users',
    'warehouses',
    'discounts',
    'measureunits',
    'customergroups',
];

test.describe('collection endpoints', () => {
    for (const collection of collections) {
        test(`GET /api/v3/${collection} returns JSON`, async ({ request }) => {
            const response = await request.get(`/api/v3/${collection}`);

            expect(response.status()).toBe(200);
            expect(response.headers()['content-type']).toContain('json');
            expect(await response.json()).toBeTruthy();
        });
    }
});

test.describe('products', () => {
    test('returns seeded products with the fields the storefront needs', async ({ request }) => {
        const response = await request.get('/api/v3/products');
        const body = await response.json();

        expect(body.result.length).toBeGreaterThan(0);

        const product = body.result[0];
        expect(product).toHaveProperty('id');
        expect(product).toHaveProperty('name');
        expect(product).toHaveProperty('priceEach');
        expect(product).toHaveProperty('taxPercent');
        expect(product).toHaveProperty('image');
    });

    test('every seeded product has a catalog image that is actually served', async ({ request }) => {
        const body = await (await request.get('/api/v3/products')).json();

        for (const product of body.result) {
            expect(product.image, `${product.name} has no image`).toBeTruthy();

            const image = await request.get(`/catalog/${product.image}`);
            expect(image.status(), `/catalog/${product.image}`).toBe(200);
        }
    });
});

test.describe('search', () => {
    test('finds products by name', async ({ request }) => {
        const body = await (await request.get('/api/v3/search/products/Bulgur')).json();

        expect(body.count).toBeGreaterThan(0);
        expect(body.result[0].name).toContain('Bulgur');
    });

    // Regression: scopeLike() discarded the query it was handed, and the OR
    // clauses were left ungrouped, so searching by brand always returned zero.
    test('finds products by their manufacturer name', async ({ request }) => {
        const manufacturers = await (await request.get('/api/v3/manufacturers')).json();
        const brand = manufacturers[0].name;

        const body = await (await request.get(`/api/v3/search/products/${encodeURIComponent(brand)}`)).json();

        expect(body.count).toBeGreaterThan(0);
        for (const product of body.result) {
            expect(product.manufacturer_id).toBe(manufacturers[0].id);
        }
    });

    test('returns an empty result rather than an error for no matches', async ({ request }) => {
        const response = await request.get('/api/v3/search/products/zzzznotathing');

        expect(response.status()).toBe(200);
        expect((await response.json()).count).toBe(0);
    });
});

test.describe('categories', () => {
    test('returns a nested tree', async ({ request }) => {
        const body = await (await request.get('/api/v3/categories')).json();
        const roots = Object.values(body);

        expect(roots.length).toBeGreaterThan(0);
        expect(roots[0]).toHaveProperty('children');
        expect(Array.isArray(roots[0].children)).toBe(true);
    });
});

test.describe('discounts', () => {
    test('embeds the discounted product', async ({ request }) => {
        const body = await (await request.get('/api/v3/discounts')).json();

        expect(body.length).toBeGreaterThan(0);
        expect(body[0]).toHaveProperty('product');
        expect(body[0].product).toHaveProperty('priceEach');
    });
});

test.describe('PDF export', () => {
    // Regression: this shelled out to a PhantomJS binary that ships as a 64-bit
    // Linux ELF, so it failed with "cannot execute binary file" anywhere else.
    // It then hung for 60s trying to fetch the template's assets over HTTP.
    test('an invoice renders to a real PDF', async ({ request }) => {
        const response = await request.get('/api/v3/invoices/1/generatePDF', { timeout: 30000 });

        expect(response.status()).toBe(200);
        expect(response.headers()['content-type']).toContain('application/pdf');

        const body = await response.body();
        expect(body.length).toBeGreaterThan(1000);
        expect(body.slice(0, 4).toString()).toBe('%PDF');
    });

    test('a return renders to a real PDF', async ({ request }) => {
        const response = await request.get('/api/v3/returns/1/generatePDF', { timeout: 30000 });

        expect(response.status()).toBe(200);
        expect((await response.body()).slice(0, 4).toString()).toBe('%PDF');
    });
});
