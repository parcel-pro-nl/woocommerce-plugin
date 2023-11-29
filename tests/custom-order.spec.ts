import { expect, test } from '@playwright/test';
import { createNewOrder, navigateToOrders } from './helpers/orders';

test('create and register custom order', async ({ page }) => {
  await page.goto('/wp-admin');
  await navigateToOrders(page);

  const orderNumber = await createNewOrder(page);
  await navigateToOrders(page);
  const orderRow = page.getByRole('row', { name: `#${orderNumber}` });

  // TODO: The button may not be visible, if the actions column is not visible.
  // Register the order.
  await orderRow
    .getByRole('link', { name: 'Aanmelden bij Parcel Pro' })
    .click();

  // Check if the tracking action is available.
  await expect(
    orderRow.getByRole('link', { name: 'Volg Parcel Pro zending' }),
  ).toBeVisible();

  // Check if a tracking code is added to the order notes.
  await orderRow.click();
  await expect(page.getByText(/^3S/)).toBeVisible();
});
