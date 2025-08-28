import { test, expect } from '@playwright/test';

test('users autocomplete shows suggestions and announces count', async ({ page }) => {
  // This test assumes the app is running on http://localhost:5173 or similar dev server
  await page.goto('http://localhost:8000/inventories/create');

  const input = page.locator('#name');
  await input.fill('a');

  // wait for suggestion list to appear
  const list = page.locator('#users-list');
  await expect(list).toBeVisible({ timeout: 2000 });

  // check that live region text is updated
  const live = page.locator('#users-list-live');
  await expect(live).not.toBeEmpty();

  // keyboard navigation
  await input.press('ArrowDown');
  await input.press('Enter');

  // after selection the user_id hidden input should exist or owner select updated
  const hidden = page.locator('input[name="user_id"]');
  await expect(hidden).toBeVisible();
});
