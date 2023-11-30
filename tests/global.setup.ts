import { expect, test } from '@playwright/test';
import { STORAGE_STATE } from './playwright.config';

test('login', async ({ page }) => {
  // WordPress

  await page.goto('/wp-login.php');

  await page.getByLabel('Username or Email Address').fill('admin');
  await page.getByLabel('Password', { exact: true }).fill('parcelpro1');
  await page.getByLabel('Remember Me').click();
  await page.getByText('Log In').click();

  await expect(page).not.toHaveTitle(/Log In/);

  // Parcel Pro

  await page.goto('https://login.parcelpro.nl');

  await page.locator('#email').fill(process.env.USERNAME);
  await page.locator('#password').fill(process.env.PASSWORD);
  await page.getByText('Inloggen').click();

  await expect(page).not.toHaveTitle(/Inloggen/);

  // Store the browser context.
  await page.context().storageState({ path: STORAGE_STATE });
});
