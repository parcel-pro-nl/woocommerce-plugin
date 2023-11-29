import { expect, Page } from '@playwright/test';

/**
 * Navigate to a WooCommerce page by clicking a link in the menu.
 */
export async function navigateWooCommerce(page: Page, name: string) {
  await page.getByRole('link', { name: 'WooCommerce' }).click();
  await expect(page).toHaveTitle(/WooCommerce/);
  await page
    .locator('#toplevel_page_woocommerce')
    .getByRole('link', { name })
    .click();
}
