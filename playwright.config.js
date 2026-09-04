import { defineConfig, devices } from '@playwright/test';

const BASE_URL = process.env.BASE_URL || 'http://127.0.0.1:8000';

export default defineConfig({
    testDir: './tests/e2e',
    // PHP's built-in server dedicates a worker to each open browser connection,
    // so more than one browser at a time starves it and page loads hang.
    workers: 1,
    fullyParallel: false,
    reporter: process.env.CI ? 'line' : [['list']],
    timeout: 30000,
    expect: { timeout: 10000 },

    use: {
        baseURL: BASE_URL,
        // These pages pull ~45 assets from a single-process dev server.
        navigationTimeout: 30000,
        trace: 'retain-on-failure',
        screenshot: 'only-on-failure',
    },

    projects: [
        { name: 'chromium', use: { ...devices['Desktop Chrome'] } },
    ],

    // Start the app automatically unless one is already listening.
    webServer: {
        command: './run-local.sh',
        url: BASE_URL,
        reuseExistingServer: true,
        timeout: 60000,
    },
});
