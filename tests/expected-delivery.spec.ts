import { test } from '@playwright/test';
import { navigateWooCommerce } from './helpers/navigate';
import { randomString } from './helpers/random';
import { clearAndCreateShippingMethod } from './helpers/settings';
import { fillCheckoutForm } from './helpers/checkout';

test('expected delivery shows in checkout', async ({ page }) => {
  await page.goto('/wp-admin');
  await navigateWooCommerce(page, 'Settings');

  await page.getByRole('link', { name: 'Shipping' }).click();
  await page.getByRole('link', { name: 'Parcel Pro' }).click();

  // Enable the expected delivery date for PostNL.
  const expectedDeliveryCheckbox = page.getByLabel(
    'Toon verwachte levertijd voor PostNL',
  );
  if (!(await expectedDeliveryCheckbox.isChecked())) {
    await expectedDeliveryCheckbox.click();
  }

  // Clear all existing shipping methods and create a new one.
  const shippingMethodName = `test-${randomString()}`;
  await clearAndCreateShippingMethod(page, shippingMethodName);

  // Go to a product page and add it to the cart.
  await page.goto('/product/playwright-product');
  await page.getByRole('button', { name: 'Add to cart', exact: true }).click();

  // Fill the checkout form.
  await page.goto('/checkout');
  await fillCheckoutForm(page);

  // Check if the text contains a date.
  // E.g.: test-iennluploq (22 March)
  const textMatcher = new RegExp(`${shippingMethodName} \\(\\d+ [a-zA-Z]+\\)`);
  if (await page.locator('#shipping-option').isVisible()) {
    // Blocks checkout.
    await page.locator('#shipping-option').getByText(textMatcher).click();
  } else {
    // Standard checkout.

    // It can happen that this checkout doesn't properly update the shipping methods,
    // so we want to trigger another postcode change here, to force it to load the data.
    await page.getByRole('textbox', { name: 'Postcode / ZIP' }).press(' ');

    await page.getByText(textMatcher).click();
  }
});
