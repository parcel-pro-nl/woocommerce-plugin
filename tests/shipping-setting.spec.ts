import { expect, test } from '@playwright/test';
import { navigateWooCommerce } from './helpers/navigate';
import { randomString } from './helpers/random';
import { fillCheckoutForm } from './helpers/checkout';

test('created shipping setting shows in checkout', async ({ page }) => {
  await page.goto('/wp-admin');
  await navigateWooCommerce(page, 'Settings');

  await page.getByRole('link', { name: 'Shipping' }).click();
  await page.getByRole('link', { name: 'Parcel Pro' }).click();

  // Remove all existing shipping methods.
  const deleteRuleButtons = await page
    .locator('.parcelpro_rules')
    .first()
    .getByRole('button', { name: '×' })
    .all();
  for (let i = deleteRuleButtons.length - 1; i >= 0; i--) {
    await deleteRuleButtons[i].click();
  }

  // Click "Add Rule +"
  await page.locator('[name="postnl_afleveradres"]').click();

  // Get the new rule row and all input fields.
  const newRuleRow = page
    .locator('.parcelpro_rules tbody tr:last-child')
    .first();
  const [methodTitle, minWeight, maxWeight, minTotal, maxTotal, price] =
    await newRuleRow.locator('input').all();

  const shippingMethodName = `test-${randomString()}`;

  // Fill and save the shipping method fields.
  await methodTitle.fill(shippingMethodName);
  await minWeight.fill('0');
  await maxWeight.fill('9999');
  await minTotal.fill('0');
  await maxTotal.fill('9999');
  await price.fill('0');
  await page.getByRole('button', { name: 'Save changes' }).click();

  // Go to a product page and add it to the cart.
  await page.goto('/product/playwright-product');
  await page.getByRole('button', { name: 'Add to cart' }).click();

  // Fill the checkout form.
  await page.goto('/checkout');
  await fillCheckoutForm(page);

  // Select the shipping method and place the order.
  if (await page.locator('#shipping-option').isVisible()) {
    // Blocks checkout.
    await page
      .locator('#shipping-option')
      .getByText(shippingMethodName)
      .click();
  } else {
    // Standard checkout.
    await page.getByText(shippingMethodName).click();
  }
  await page.getByRole('button', { name: 'Place Order' }).click();

  // Check if the order details contain the right shipping method.
  await expect(
    page.getByText('Thank you. Your order has been received.'),
  ).toBeVisible();
  await expect(page.getByText(shippingMethodName)).toBeVisible();
});
