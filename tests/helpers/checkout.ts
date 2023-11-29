import { Page } from '@playwright/test';

/**
 * Fill the checkout form.
 * This function should start and ends on the checkout page.
 */
export async function fillCheckoutForm(page: Page) {
  // Check if the address is pre-filled. If so, click edit.
  const editButton = page.getByLabel('Edit address');
  if (await editButton.isVisible()) {
    await editButton.click();
  }

  // Fill all checkout details.
  await page.getByRole('textbox', { name: 'First name' }).fill('Playwright');
  await page.getByRole('textbox', { name: 'Last name' }).fill('Tester');
  await page
    .getByRole('textbox', { name: 'Email address' })
    .fill('test@example.com');

  // These fields have different names in different WC versions.
  if (await page.getByRole('textbox', { name: 'Street address' }).isVisible()) {
    // Standard checkout.
    await page
      .getByRole('textbox', { name: 'Street address' })
      .fill('Hofhoek 7');
    await page.getByRole('textbox', { name: 'Postcode / ZIP' }).fill('3176 PD');
    await page.getByRole('textbox', { name: 'Town / City' }).fill('Poortugaal');
    await page.getByRole('textbox', { name: 'Phone' }).fill('0612345678');
  } else {
    // Blocks checkout.
    await page
      .getByRole('textbox', { name: 'Address', exact: true })
      .fill('Hofhoek 7');
    await page.getByRole('textbox', { name: 'Postal code' }).fill('3176 PD');
    await page.getByRole('textbox', { name: 'City' }).fill('Poortugaal');
    await page
      .getByRole('textbox', { name: 'Phone (optional)' })
      .fill('0612345678');
  }
}
