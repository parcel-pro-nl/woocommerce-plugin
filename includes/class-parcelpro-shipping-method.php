<?php

/**
 * A custom (mock) shipping method, which is only used to ensure shipping method validation succeeds.
 * See: `ParcelPro_Shipping::load_shipping_methods`.
 */
class Parcelpro_Shipping_Method extends WC_Shipping_Method
{
    public function __construct()
    {
        parent::__construct();
        // Using 'parcelpro_' as the id here ensures any shipping method coming from this plugin passes validation,
        // as they all have a 'parcelpro_' prefix.
        $this->id = 'parcelpro_';
        $this->supports = [];
    }
}
