import { defineConfig } from 'vitest/config';

export default defineConfig({
    test: {
        // Only the JS unit tests. tests/ also holds the PHPUnit suite and the
        // Playwright specs, which have their own runners.
        include: ['tests/js/**/*.test.js'],
        environment: 'node',
    },
});
