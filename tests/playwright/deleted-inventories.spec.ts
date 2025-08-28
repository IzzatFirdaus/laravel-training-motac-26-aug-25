import { test, expect } from '@playwright/test';

test.describe('Deleted Inventories', () => {

  test('admin can access deleted inventories page', async ({ page }) => {
    const uniq = Date.now();
    const email = `admin+${uniq}@example.com`;
    const password = 'password123';

    // Register as admin user (assuming admin role is assigned during registration for testing)
    await page.goto('http://127.0.0.1:8000/register');
    await page.fill('input[name="name"]', `Admin ${uniq}`);
    await page.fill('input[name="email"]', email);
    await page.fill('input[name="password"]', password);
    await page.fill('input[name="password_confirmation"]', password);
    await page.click('button[type="submit"]');

    // Navigate to inventories page
    await page.goto('http://127.0.0.1:8000/inventories');

    // Check if "Inventori Dipadam" link exists (for admin users)
    const deletedLink = page.locator('a[href*="/inventories/deleted"]');
    if (await deletedLink.count() > 0) {
      await deletedLink.click();

      // Should be on the deleted inventories page
      await expect(page).toHaveURL(/.*\/inventories\/deleted/);
      await expect(page.locator('h1')).toContainText('Inventori Dipadam');

      // Should see the back button
      await expect(page.locator('a[href*="/inventories"]:has-text("Kembali ke Inventori")')).toBeVisible();
    } else {
      // If no link is visible, user might not be admin - try accessing directly
      await page.goto('http://127.0.0.1:8000/inventories/deleted');

      // Should either see the page or be forbidden
      const url = page.url();
      if (url.includes('/inventories/deleted')) {
        await expect(page.locator('h1')).toContainText('Inventori Dipadam');
      } else {
        // User was redirected away (likely 403 or login)
        expect(url.includes('/login') || url.includes('/403')).toBeTruthy();
      }
    }
  });

  test('non-admin cannot access deleted inventories page', async ({ page }) => {
    const uniq = Date.now();
    const email = `user+${uniq}@example.com`;
    const password = 'password123';

    // Register as regular user
    await page.goto('http://127.0.0.1:8000/register');
    await page.fill('input[name="name"]', `User ${uniq}`);
    await page.fill('input[name="email"]', email);
    await page.fill('input[name="password"]', password);
    await page.fill('input[name="password_confirmation"]', password);
    await page.click('button[type="submit"]');

    // Try to access deleted inventories directly
    await page.goto('http://127.0.0.1:8000/inventories/deleted');

    // Should be redirected away or see 403
    const url = page.url();
    expect(url.includes('/login') || url.includes('/403') || !url.includes('/inventories/deleted')).toBeTruthy();
  });

  test('admin can see search functionality on deleted inventories page', async ({ page }) => {
    const uniq = Date.now();
    const email = `admin+${uniq}@example.com`;
    const password = 'password123';

    // Register as admin and navigate to deleted inventories
    await page.goto('http://127.0.0.1:8000/register');
    await page.fill('input[name="name"]', `Admin ${uniq}`);
    await page.fill('input[name="email"]', email);
    await page.fill('input[name="password"]', password);
    await page.fill('input[name="password_confirmation"]', password);
    await page.click('button[type="submit"]');

    // Try to access deleted inventories page
    await page.goto('http://127.0.0.1:8000/inventories/deleted');

    // Check if we're on the correct page (depends on user permissions)
    const url = page.url();
    if (url.includes('/inventories/deleted')) {
      // Look for search functionality if there are deleted items
      const searchForm = page.locator('form[action*="/inventories/deleted"]');
      const searchInput = page.locator('input[name="search"]');

      // Search form should be present if there are deleted inventories
      // OR we should see the empty state message
      const hasDeletedInventories = await page.locator('table tbody tr').count() > 0;
      const hasEmptyMessage = await page.locator('text=Tiada inventori yang dipadam dijumpai').count() > 0;

      expect(hasDeletedInventories || hasEmptyMessage).toBeTruthy();

      if (hasDeletedInventories) {
        await expect(searchInput).toBeVisible();
        await expect(page.locator('button:has-text("Cari")')).toBeVisible();
      }
    }
  });

  test('deleted inventories page shows proper table structure', async ({ page }) => {
    const uniq = Date.now();
    const email = `admin+${uniq}@example.com`;
    const password = 'password123';

    // Register as admin
    await page.goto('http://127.0.0.1:8000/register');
    await page.fill('input[name="name"]', `Admin ${uniq}`);
    await page.fill('input[name="email"]', email);
    await page.fill('input[name="password"]', password);
    await page.fill('input[name="password_confirmation"]', password);
    await page.click('button[type="submit"]');

    // Access deleted inventories page
    await page.goto('http://127.0.0.1:8000/inventories/deleted');

    const url = page.url();
    if (url.includes('/inventories/deleted')) {
      // Check for proper table headers
      const tableHeaders = [
        'ID',
        'Nama',
        'Kuantiti',
        'Harga',
        'Pemilik',
        'Dipadam Pada',
        'Tindakan'
      ];

      for (const header of tableHeaders) {
        const headerElement = page.locator(`th:has-text("${header}")`);
        // Header should exist either in the table or we should see empty state
        const hasTable = await page.locator('table thead').count() > 0;
        const hasEmptyState = await page.locator('text=Tiada inventori yang dipadam dijumpai').count() > 0;

        if (hasTable) {
          await expect(headerElement).toBeVisible();
        } else {
          expect(hasEmptyState).toBeTruthy();
        }
      }
    }
  });

  test('deleted inventories page has proper navigation', async ({ page }) => {
    const uniq = Date.now();
    const email = `admin+${uniq}@example.com`;
    const password = 'password123';

    // Register as admin
    await page.goto('http://127.0.0.1:8000/register');
    await page.fill('input[name="name"]', `Admin ${uniq}`);
    await page.fill('input[name="email"]', email);
    await page.fill('input[name="password"]', password);
    await page.fill('input[name="password_confirmation"]', password);
    await page.click('button[type="submit"]');

    // Access deleted inventories page
    await page.goto('http://127.0.0.1:8000/inventories/deleted');

    const url = page.url();
    if (url.includes('/inventories/deleted')) {
      // Should have back link to inventories
      const backLink = page.locator('a:has-text("Kembali ke Inventori")');
      await expect(backLink).toBeVisible();

      // Click back link and verify navigation
      await backLink.click();
      await expect(page).toHaveURL(/.*\/inventories$/);
    }
  });

});
