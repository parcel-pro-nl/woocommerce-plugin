<?php

/**
 * Returns data of all the settings fields to the shipping method
 *
 * This file is used to include in the Parcel Pro shipping method.
 *
 * @link       http://www.parcelpro.nl/
 * @since      1.0.0
 *
 * @package    Parcelpro
 * @subpackage Parcelpro/admin/data
 */

return array(
    // This title is necessary to ensure the `<table>` start tag is rendered.
    array(
        'type' => 'title',
    ),
    'enabled'      => array(
        'title'   => 'Inschakelen',
        'type'    => 'checkbox',
        'label'   => PARCELPRO_SHOPSUNITED . ' Shipping inschakelen',
        'default' => 'no',
    ),
    'title'        => array(
        'title'   => __('Method Title', 'woocommerce'),
        'type'    => 'text',
        'default' => PARCELPRO_SHOPSUNITED,
    ),
    'availability' => array(
        'title'       => __('Method availability', 'woocommerce'),
        'type'        => 'select',
        'default'     => 'all',
        'class'       => 'availability wc-enhanced-select',
        'options'     => array(
            'all'      => __('All allowed countries', 'woocommerce'),
            'specific' => __('Specific Countries', 'woocommerce'),
        ),
        'description' => 'De landen waarop de verzendmethodes en het automatisch aanmelden van toepassing zijn.',
        'desc_tip'    => true
    ),
    'countries'    => array(
        'title'             => __('Specific Countries', 'woocommerce'),
        'type'              => 'multiselect',
        'class'             => 'wc-enhanced-select',
        'css'               => 'width: 450px;',
        'default'           => '',
        'options'           =>  WC()->countries ? WC()->countries->get_shipping_countries() : [],
        'custom_attributes' => array(
            'data-placeholder' => __('Select some countries', 'woocommerce'),
        ),
    ),
    'login_id'     => array(
        'title'        => 'Gebruikers Id',
        'type'         => 'text',
        'label'        => 'Voer hier uw ontvangen Gebruikers Id in.',
        'autocomplete' => 'off',
        'description'  => 'Het ' . PARCELPRO_SHOPSUNITED . ' gebruikers id is te vinden op de accountpagina van het verzendsysteem.',
        'desc_tip'     => true
    ),
    'api_id'       => array(
        'title'        => 'API Key',
        'type'         => 'text',
        'label'        => 'Voer hier uw ontvangen API Key in.',
        'autocomplete' => 'off',
        'description'  => 'De ' . PARCELPRO_SHOPSUNITED . ' api key is te vinden op de instellingenpagina van het verzendsysteem.',
        'desc_tip'     => true
    ),
    'auto_export'  => array(
        'title'       => 'Automatisch aanmelden',
        'type'        => 'checkbox',
        'label'       => 'Automatisch aanmelden bij ' . PARCELPRO_SHOPSUNITED . ' inschakelen',
        'default'     => 'no',
        'description' => 'Zending worden automatisch aangemeld na het bereiken van de status voltooid.',
        'desc_tip'    => true
    ),
    'auto_export_on_state' => array(
        'title'       => 'Automatisch aanmelden status',
        'type'        => 'select',
        'default'     => 'wc-processing',
        'class'       => 'availability wc-enhanced-select',
        'options'     => wc_get_order_statuses(),
        'description' => 'Indien de optie \'Automatisch aanmelden\' aangevinkt is zal bij het bereiken van deze status, de order worden doorgezet.',
        'desc_tip'    => true
    ),
    'order_tracking'  => array(
        'title'       => 'Voeg tracking toe aan order',
        'type'        => 'checkbox',
        'label'       => 'Voeg tracking gegevens toe aan het order overzicht van de klant',
        'default'     => 'no',
        'description' => 'Aan het order overzicht van de klant wordt een extra tabel toegevoegd met tracking gegevens.',
        'desc_tip'    => true
    ),
    'export__update'  => array(
        'title'       => 'Status aanpassen',
        'type'        => 'checkbox',
        'label'       => 'Status aanpassen wanneer order wordt aangemeld bij ' . PARCELPRO_SHOPSUNITED,
        'default'     => 'no',
        'description' => 'Status aanpassen wanneer order wordt aangemeld bij ' . PARCELPRO_SHOPSUNITED,
        'desc_tip'    => true
    ),
    'export_update_to_state' => array(
        'title'       => 'Na aanmelden naar status',
        'type'        => 'select',
        'default'     => 'wc-completed',
        'class'       => 'availability wc-enhanced-select',
        'options'     => wc_get_order_statuses(),
        'description' => 'Indien de optie \'Aanmelden status aanpassen\' aangevinkt is zal bij het expliciet en automatisch voormelden naar ' . PARCELPRO_SHOPSUNITED . ' zal de order in Woocommerce automatisch op deze order status gezet worden.',
        'desc_tip'    => true
    ),
    'coupon_calculate'  => array(
        'title'       => 'Korting mee rekenen',
        'type'        => 'checkbox',
        'label'       => 'Kortingen van coupons in sub totaal mee rekenen',
        'default'     => 'yes',
        'description' => 'Bij het berekenen van welke verzendmethodes zichtbaar worden, wordt eerst de korting van coupons over het subtotaal behandeld als deze optie aan staan.',
        'desc_tip'    => true
    ),
    'customer_note_inhoud'  => array(
        'title'       => 'Klant opmerking bijvoegen',
        'type'        => 'checkbox',
        'label'       => 'Klant opmerking bijvoegen bij aanmelding van order',
        'default'     => 'no',
        'description' => 'Customer note (billing address) invullen bij het veld "inhoud" in het verzendsysteen.',
        'desc_tip'    => true
    ),
    'postnl_show_delivery_date' => array(
        'type'     => 'checkbox',
        'title'    => 'Verwachte levertijd voor PostNL',
        'label'    => 'Toon verwachte levertijd voor PostNL',
        'desc_tip' => true,
        'default'  => 'no',
        'description' => 'Toon bij de checkout de geschatte tijd waarop het pakket geleverd zal worden',
    ),
    'postnl_last_shipping_time' => array(
        'type'     => 'text',
        'title'    => 'Uiterlijke besteltijd voor verzending met PostNL.',
        'default'  => '17:00 Europe/Amsterdam',
        'placeholder' => '17:00 Europe/Amsterdam',
        'desc_tip' => true,
        'description' => 'Stel hier de tijd in waarop de bestelling geplaatst moet zijn om nog dezelfde dag verzonden te worden. Bijvoorbeeld: \'17:00 Europe/Amsterdam\'.',
    ),
    'dhl_show_delivery_date' => array(
        'type'     => 'checkbox',
        'title'    => 'Verwachte levertijd voor DHL',
        'label'    => 'Toon verwachte levertijd voor DHL',
        'desc_tip' => true,
        'default'  => 'no',
        'description' => 'Toon bij de checkout de geschatte tijd waarop het pakket geleverd zal worden',
    ),
    'dhl_last_shipping_time' => array(
        'type'     => 'text',
        'title'    => 'Uiterlijke besteltijd voor verzending met DHL.',
        'default'  => '17:00 Europe/Amsterdam',
        'placeholder' => '17:00 Europe/Amsterdam',
        'desc_tip' => true,
        'description' => 'Stel hier de tijd in waarop de bestelling geplaatst moet zijn om nog dezelfde dag verzonden te worden. Bijvoorbeeld: \'17:00 Europe/Amsterdam\'.',
    ),
    'services'     => array(
        'type' => 'services',
    ),
    // This title is necessary to ensure the save button is rendered here.
    array(
        'type' => 'title',
    ),
);
