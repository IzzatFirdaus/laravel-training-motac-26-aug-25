import { test, expect } from '@playwright/test';

test('users autocomplete shows suggestions and announces count', async ({ page }) => {
  // Register a temp user so we can access the authenticated page
  const uniq = Date.now();
  const name = `E2E Tester ${uniq}`;
  const email = `e2e+${uniq}@example.com`;
  const password = 'password123';

  // Go to register and create account
  await page.goto('http://127.0.0.1:8000/register');
  await page.fill('input[name="name"]', name);
  await page.fill('input[name="email"]', email);
  await page.fill('input[name="password"]', password);
  await page.fill('input[name="password_confirmation"]', password);
  await page.click('button[type="submit"]');

  // After registration the app should redirect to home; navigate to create inventory
  await page.goto('http://127.0.0.1:8000/inventories/create');

  const input = page.locator('#name');
  await expect(input).toBeVisible({ timeout: 5000 });

  await input.fill('a');

  // wait for suggestion list to appear
  const list = page.locator('#users-list');
  await expect(list).toBeVisible({ timeout: 5000 });

  // check that live region text is updated
  const live = page.locator('#users-list-live');
  await expect(live).not.toBeEmpty();

  // keyboard navigation
  await input.press('ArrowDown');
  await input.press('Enter');

  // after selection the user_id hidden input should exist or owner select updated
  const hidden = page.locator('input[name="user_id"]');
  // Hidden inputs are not visible; ensure a value has been set after selection
  const val = await hidden.getAttribute('value');
  expect(val).toBeTruthy();
});
