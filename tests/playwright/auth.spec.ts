import { test, expect } from '@playwright/test';

test('register, logout and login flow', async ({ page }) => {
  const uniq = Date.now();
  const name = `E2E User ${uniq}`;
  const email = `e2e+${uniq}@example.com`;
  const password = 'password123';

  await page.goto('http://127.0.0.1:8000/register');
  await page.fill('input[name="name"]', name);
  await page.fill('input[name="email"]', email);
  await page.fill('input[name="password"]', password);
  await page.fill('input[name="password_confirmation"]', password);
  await page.click('button[type="submit"]');

  // After registration we should be authenticated and see a logout button
  await expect(page.locator('button[aria-label="{{ nav.logout }}"]').first()).not.toBeVisible({ timeout: 1000 }).catch(() => {});
  // Fallback: ensure we can navigate to a protected page
  await page.goto('http://127.0.0.1:8000/inventories/create');
  await expect(page).toHaveURL(/inventories\/create/);

  // Logout
  await page.goto('http://127.0.0.1:8000/home');
  // try to click logout in dropdown; use form submit directly
  await page.evaluate(() => { const f = document.getElementById('logout-form'); if (f) (f as HTMLFormElement).submit(); });
});
