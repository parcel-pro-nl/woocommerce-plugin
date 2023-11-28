import { test } from '@playwright/test';
import { navigateWooCommerce } from './helpers/navigate';
import { createNewProduct, navigateToProducts } from './helpers/products';

test('configure plugin', async ({ page }) => {
  await page.goto('/wp-admin');
  await navigateWooCommerce(page, 'Settings');

  await page.getByRole('link', { name: 'Shipping' }).click();
  await page.getByRole('link', { name: 'Parcel Pro' }).click();

  // Ensure the shipping method is enabled.
  const enableCheckbox = page.getByLabel('Parcel Pro Shipping inschakelen');
  if (!(await enableCheckbox.isChecked())) {
    await enableCheckbox.click();
  }

  // Fill the user id and api key fields.
  await page
    .getByLabel('Gebruikers Id', { exact: true })
    .fill(process.env.USER_ID);
  await page.getByLabel('API Key', { exact: true }).fill(process.env.API_KEY);

  await page.getByRole('button', { name: 'Save changes' }).click();
});

test('create product', async ({ page }) => {
  await page.goto('/wp-admin');
  await navigateToProducts(page);

  // Check if the test product already exists.
  if (
    (await page
      .getByRole('link', { name: 'Playwright Product', exact: true })
      .count()) > 0
  ) {
    return;
  }

  await createNewProduct(page, 'Playwright Product');
});
