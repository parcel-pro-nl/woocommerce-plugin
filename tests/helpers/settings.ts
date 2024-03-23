import { Page } from '@playwright/test';

/**
 * Clear all existing shipping methods, create a new one, and save the changes.
 * This function should start and ends on the Parcel Pro settings page
 */
export async function clearAndCreateShippingMethod(page: Page, name: string) {
  await clearShippingMethods(page);
  await createShippingMethod(page, name);
  await page.getByRole('button', { name: 'Save changes' }).click();
}

/**
 * Remove all existing shipping methods.
 * This function should start and ends on the Parcel Pro settings page.
 */
export async function clearShippingMethods(page: Page) {
  const deleteRuleButtons = await page
    .locator('.parcelpro_rules')
    .first()
    .getByRole('button', { name: '×' })
    .all();

  for (let i = deleteRuleButtons.length - 1; i >= 0; i--) {
    await deleteRuleButtons[i].click();
  }
}

/**
 * Create a new shipping method.
 * This function should start and ends on the Parcel Pro settings page.
 */
export async function createShippingMethod(page: Page, name: string) {
  // Click "Add Rule +"
  await page.locator('[name="postnl_afleveradres"]').click();

  // Get the new rule row and all input fields.
  const newRuleRow = page
    .locator('.parcelpro_rules tbody tr:last-child')
    .first();
  const [methodTitle, minWeight, maxWeight, minTotal, maxTotal, price] =
    await newRuleRow.locator('input').all();

  // Fill and save the shipping method fields.
  await methodTitle.fill(name);
  await minWeight.fill('0');
  await maxWeight.fill('9999');
  await minTotal.fill('0');
  await maxTotal.fill('9999');
  await price.fill('0');
}
