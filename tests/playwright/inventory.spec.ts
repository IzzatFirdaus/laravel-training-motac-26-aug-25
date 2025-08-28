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

  // Submit without required fields (scope to the inventory form submit button)
  await page.click('form[action$="/inventories"] button[type="submit"]');
  // Expect validation messages on page
  await expect((await page.locator('.text-danger').count()) > 0).toBeTruthy();

  // Fill required fields and submit
  await page.fill('#name', 'Test Inventory');
  await page.fill('#qty', '5');
  await page.fill('#price', '10.50');
  await page.click('form[action$="/inventories"] button[type="submit"]');

  // After successful submit should redirect to inventories.show or index
  await expect(page).toHaveURL(/inventories\/\d+/);
});
