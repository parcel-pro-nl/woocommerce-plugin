import { expect, Page } from '@playwright/test';

export async function navigateToProducts(page: Page) {
  await page
    .locator('#menu-posts-product')
    .getByRole('link', { name: 'Products', exact: true })
    .click();
  await expect(page).toHaveTitle(/Products/);
}

/**
 * Create a new product.
 * This function should start on the products page,
 * and ends on the created product (edit) page.
 */
export async function createNewProduct(page: Page, name: string) {
  // Navigate to the new product page.
  await page.locator('#wpbody').getByRole('link', { name: 'Add New' }).click();

  // Fill the title.
  await page.getByLabel('Product name').fill(name);

  // Fill the general info.
  await page.getByLabel('Regular price ($)').fill('0');

  // Fill the shipping info.
  await page.getByRole('link', { name: 'Shipping' }).click();
  await page.getByLabel('Weight (kg)').fill('1');
  await page.getByPlaceholder('Length').fill('10');
  await page.getByPlaceholder('Width').fill('20');
  await page.getByPlaceholder('Height').fill('30');

  // Publish the product.
  await page.getByRole('button', { name: 'Publish', exact: true }).click();
}
