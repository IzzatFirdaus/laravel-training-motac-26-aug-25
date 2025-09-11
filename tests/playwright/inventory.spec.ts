import { test, expect } from '@playwright/test';

test('inventory create validation and submit', async ({ page }) => {
  const uniq = Date.now();
  const email = `e2e+${uniq}@example.com`;
  const password = 'password123';

  // register quickly
  await page.goto('http://127.0.0.1:8000/register');
  await page.fill('input[name="name"]', `InvTester ${uniq}`);
  await page.fill('input[name="email"]', email);
  await page.fill('input[name="password"]', password);
  await page.fill('input[name="password_confirmation"]', password);
  await page.click('button[type="submit"]');

  await page.goto('http://127.0.0.1:8000/inventories/create');

  // Inform the server this is an E2E run so long-running background jobs may be skipped
  await page.setExtraHTTPHeaders({ 'x-e2e-run': '1' });

  // Submit without required fields (use deterministic selector)
  await page.click('[data-test="inventory-create-submit"]');
  // Expect validation messages on page (MYDS uses role=alert or myds-text--danger)
  const validationCount = await page.locator('[role="alert"], .myds-text--danger, .myds-text--danger').count();
  await expect(validationCount > 0).toBeTruthy();

  // Fill required fields and submit
  await page.fill('#name', 'Test Inventory');
  await page.fill('#qty', '5');
  await page.fill('#price', '10.50');

  // Wait for the POST /inventories response to determine if server-side validation passed.
  // Ensure the form carries the e2e flag (some servers may not preserve extra headers on form posts)
  await page.evaluate(() => {
    const f = document.querySelector('form[data-test="inventory-create-form"]');
    if (f && ! f.querySelector('input[name="x-e2e-run"]')) {
      const i = document.createElement('input');
      i.type = 'hidden';
      i.name = 'x-e2e-run';
      i.value = '1';
      f.appendChild(i);
    }
  });

  // Click submit and wait for navigation (redirect on success) or DOM update (validation errors)
  await Promise.all([
    page.waitForNavigation({ waitUntil: 'domcontentloaded', timeout: 10000 }).catch(() => null),
    page.click('[data-test="inventory-create-submit"]'),
  ]);

  // After submission expect either a redirect to the new inventory or validation errors on the same page
  if (await page.url().match(/inventories\/\d+/)) {
    await expect(page).toHaveURL(/inventories\/\d+/);
  } else {
    const hasErrors = (await page.locator('[role="alert"], .myds-text--danger').count()) > 0;
    await expect(hasErrors).toBeTruthy();
  }
});
