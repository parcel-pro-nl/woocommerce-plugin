import { expect, test } from '@playwright/test';
import { navigateWooCommerce } from './helpers/navigate';
import { randomString } from './helpers/random';
import { fillCheckoutForm } from './helpers/checkout';
import { clearAndCreateShippingMethod } from './helpers/settings';

test('created shipping setting shows in checkout', async ({ page }) => {
  await page.goto('/wp-admin');
  await navigateWooCommerce(page, 'Settings');

  await page.getByRole('link', { name: 'Shipping' }).click();
  await page.getByRole('link', { name: 'Parcel Pro' }).click();

  // Clear all existing shipping methods and create a new one.
  const shippingMethodName = `test-${randomString()}`;
  await clearAndCreateShippingMethod(page, shippingMethodName);

  // Go to a product page and add it to the cart.
  await page.goto('/product/playwright-product');
  await page.getByRole('button', { name: 'Add to cart', exact: true }).click();

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
