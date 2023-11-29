import { expect, test } from '@playwright/test';
import { createNewOrder, navigateToOrders } from './helpers/orders';

test('order row actions', async ({ page }) => {
  await page.goto('/wp-admin');
  await navigateToOrders(page);
  const orderNumber = await createNewOrder(page);

  await navigateToOrders(page);
  const orderRow = page.getByRole('row', { name: `#${orderNumber}` });

  // Register the order.
  await orderRow
    .getByRole('link', { name: 'Aanmelden bij Parcel Pro' })
    .click();

  // Check if the actions are available.
  await expect(
    orderRow.getByRole('link', { name: 'Print Parcel Pro verzendlabel' }),
  ).toBeVisible();
  await expect(
    orderRow.getByRole('link', { name: 'Volg Parcel Pro zending' }),
  ).toBeVisible();

  // Check if a tracking code is added to the order notes.
  await orderRow.click();
  await expect(page.getByText(/^3S/)).toBeVisible();
});

test('order meta box actions', async ({ page }) => {
  await page.goto('/wp-admin');
  await navigateToOrders(page);
  await createNewOrder(page);

  // Register the order.
  await page.getByRole('link', { name: 'Aanmelden bij Parcel Pro' }).click();

  // Check if the actions are available.
  await expect(
    page.getByRole('link', { name: 'Print verzendlabel' }),
  ).toBeVisible();
  await expect(page.getByRole('link', { name: 'Volg zending' })).toBeVisible();

  // Check if a tracking code is added to the order notes.
  await expect(page.getByText(/^3S/)).toBeVisible();
});

test('order bulk actions are available', async ({ page }) => {
  await page.goto('/wp-admin');
  await navigateToOrders(page);
  await createNewOrder(page);
  await navigateToOrders(page);

  const selectField = page.locator('#bulk-action-selector-top');
  await selectField.selectOption('Aanmelden bij Parcel Pro');
  await selectField.selectOption('Print Parcel Pro label');
});
