import { test } from '@playwright/test';
import { createNewOrder, navigateToOrders } from './helpers/orders';

test('bulk actions are available', async ({ page }) => {
  await page.goto('/wp-admin');
  await navigateToOrders(page);

  // Ensure we have at least one order, otherwise the actions are not visible.
  await createNewOrder(page);
  await navigateToOrders(page);

  const selectField = page.locator('#bulk-action-selector-top');

  await selectField.selectOption('Aanmelden bij Parcel Pro');
  await selectField.selectOption('Print Parcel Pro label');
});
