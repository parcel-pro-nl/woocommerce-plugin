import { test } from '@playwright/test';
import { navigateWooCommerce } from './helpers/navigate';
import { randomString } from './helpers/random';
import { clearAndCreateShippingMethod } from './helpers/settings';

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
});
