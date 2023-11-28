import { Page } from '@playwright/test';
import { navigateWooCommerce } from './navigate';

export async function navigateToOrders(page: Page) {
  await navigateWooCommerce(page, 'Orders');
}

/**
 * Create a new order and return the order number.
 * This function should start on the orders page,
 * and ends on the created order (edit) page.
 */
export async function createNewOrder(page: Page) {
  // Navigate to the new order page.
  await page.getByText('Add order').click();

  // Create a new order.
  await page.getByRole('button', { name: 'Create' }).click();
  const orderTitle = await page.getByText(/Order #\d+ details/).textContent();

  return orderTitle.trim().split(' ')[1].substring(1);
}
