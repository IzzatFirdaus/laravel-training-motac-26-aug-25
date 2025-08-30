const { chromium } = require('playwright');
const fs = require('fs');
const path = require('path');

(async () => {
  const outDir = path.resolve(__dirname, '../storage/navbar-screenshots');
  if (!fs.existsSync(outDir)) fs.mkdirSync(outDir, { recursive: true });

  const browser = await chromium.launch({ headless: true });
  const page = await browser.newPage();
  const base = 'http://127.0.0.1:8000';

  // Default (logged out) home page
  await page.goto(base, { waitUntil: 'networkidle' });
  await page.screenshot({ path: path.join(outDir, 'home-default.png'), fullPage: false });

  // Try opening mobile nav toggle if present
  const navToggle = await page.$('#navToggle');
  if (navToggle) {
    await navToggle.click();
    await page.waitForTimeout(250);
    await page.screenshot({ path: path.join(outDir, 'home-mobile-open.png'), fullPage: false });
  }

  // Open inventories menu (if button exists)
  const invBtn = await page.$('#navInventories');
  if (invBtn) {
    await invBtn.click();
    await page.waitForTimeout(250);
    await page.screenshot({ path: path.join(outDir, 'home-inventories-open.png'), fullPage: false });
  }

  // Open notifications (if exists)
  const noteBtn = await page.$('#navNotificationsBtn');
  if (noteBtn) {
    await noteBtn.click();
    await page.waitForTimeout(250);
    await page.screenshot({ path: path.join(outDir, 'home-notifications-open.png'), fullPage: false });
  }

  // Toggle theme
  const themeBtn = await page.$('#theme-toggle');
  if (themeBtn) {
    await themeBtn.click();
    await page.waitForTimeout(200);
    await page.screenshot({ path: path.join(outDir, 'home-theme-toggled.png'), fullPage: false });
    // revert
    await themeBtn.click();
  }

  await browser.close();
  console.log('Screenshots saved to', outDir);
})();
