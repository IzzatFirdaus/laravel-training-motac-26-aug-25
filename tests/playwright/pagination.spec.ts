import { test, expect } from '@playwright/test';

test('users index shows pagination controls or user table', async ({ page }) => {
  await page.goto('http://127.0.0.1:8000/users');
  const nav = page.locator('nav[aria-label="Navigasi halaman"]');
  const table = page.locator('table');
  // Either pagination nav or a table of users should be present
  const hasNav = await nav.count() > 0;
  const hasTable = await table.count() > 0;
  expect(hasNav || hasTable).toBeTruthy();
});
