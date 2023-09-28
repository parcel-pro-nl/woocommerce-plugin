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
    'enabled'      => array(
        'title'   => 'Inschakelen',
        'type'    => 'checkbox',
        'label'   => 'Parcel Pro Shipping inschakelen',
        'default' => 'no',
    ),
    'title'        => array(
        'title'   => __( 'Method Title', 'woocommerce' ),
        'type'    => 'text',
        'default' => 'Parcel Pro',
    ),
    'availability' => array(
        'title'       => __( 'Method availability', 'woocommerce' ),
        'type'        => 'select',
        'default'     => 'all',
        'class'       => 'availability wc-enhanced-select',
        'options'     => array(
            'all'      => __( 'All allowed countries', 'woocommerce' ),
            'specific' => __( 'Specific Countries', 'woocommerce' ),
        ),
        'description' => 'De landen waarop de verzendmethodes en het automatisch aanmelden van toepassing zijn.',
        'desc_tip'    => TRUE
    ),
    'countries'    => array(
        'title'             => __( 'Specific Countries', 'woocommerce' ),
        'type'              => 'multiselect',
        'class'             => 'wc-enhanced-select',
        'css'               => 'width: 450px;',
        'default'           => '',
        'options'           =>  WC()->countries ? WC()->countries->get_shipping_countries() : [],
        'custom_attributes' => array(
            'data-placeholder' => __( 'Select some countries', 'woocommerce' ),
        ),
    ),
    'login_id'     => array(
        'title'        => 'Gebruikers Id',
        'type'         => 'text',
        'label'        => 'Voer hier uw ontvangen Gebruikers Id in.',
        'autocomplete' => 'off',
        'description'  => 'Het Parcel Pro gebruikers id is te vinden op de accountpagina van het verzendsysteem.',
        'desc_tip'     => TRUE
    ),
    'api_id'       => array(
        'title'        => 'API Key',
        'type'         => 'text',
        'label'        => 'Voer hier uw ontvangen API Key in.',
        'autocomplete' => 'off',
        'description'  => 'De Parcel Pro api key is te vinden op de instellingenpagina van het verzendsysteem.',
        'desc_tip'     => TRUE
    ),
    'auto_export'  => array(
        'title'       => 'Automatisch aanmelden',
        'type'        => 'checkbox',
        'label'       => 'Automatisch aanmelden bij Parcel Pro inschakelen',
        'default'     => 'no',
        'description' => 'Zending worden automatisch aangemeld na het bereiken van de status voltooid.',
        'desc_tip'    => TRUE
    ),
    'auto_export_on_state' => array(
        'title'       => 'Automatisch aanmelden status',
        'type'        => 'select',
        'default'     => 'wc-processing',
        'class'       => 'availability wc-enhanced-select',
        'options'     => wc_get_order_statuses(),
        'description' => 'Indien de optie \'Automatisch aanmelden\' aangevinkt is zal bij het bereiken van deze status, de order worden doorgezet.',
        'desc_tip'    => TRUE
    ),
    'order_tracking'  => array(
        'title'       => 'Voeg tracking toe aan order',
        'type'        => 'checkbox',
        'label'       => 'Voeg tracking gegevens toe aan het order overzicht van de klant',
        'default'     => 'no',
        'description' => 'Aan het order overzicht van de klant wordt een extra tabel toegevoegd met tracking gegevens.',
        'desc_tip'    => TRUE
    ),
    'export__update'  => array(
        'title'       => 'Status aanpassen',
        'type'        => 'checkbox',
        'label'       => 'Status aanpassen wanneer order wordt aangemeld bij Parcel Pro',
        'default'     => 'no',
        'description' => 'Status aanpassen wanneer order wordt aangemeld bij Parcel Pro.',
        'desc_tip'    => TRUE
    ),
    'export_update_to_state' => array(
        'title'       => 'Na aanmelden naar status',
        'type'        => 'select',
        'default'     => 'wc-completed',
        'class'       => 'availability wc-enhanced-select',
        'options'     => wc_get_order_statuses(),
        'description' => 'Indien de optie \'Aanmelden status aanpassen\' aangevinkt is zal bij het expliciet en automatisch voormelden naar Parcel Pro zal de order in Woocommerce automatisch op deze order status gezet worden.',
        'desc_tip'    => TRUE
    ),
    'coupon_calculate'  => array(
        'title'       => 'Korting mee rekenen',
        'type'        => 'checkbox',
        'label'       => 'Kortingen van coupons in sub totaal mee rekenen',
        'default'     => 'yes',
        'description' => 'Bij het berekenen van welke verzendmethodes zichtbaar worden, wordt eerst de korting van coupons over het subtotaal behandeld als deze optie aan staan.',
        'desc_tip'    => TRUE
    ),
    'customer_note_inhoud'  => array(
        'title'       => 'Klant opmerking bijvoegen',
        'type'        => 'checkbox',
        'label'       => 'Klant opmerking bijvoegen bij aanmelding van order',
        'default'     => 'no',
        'description' => 'Customer note (billing address) invullen bij het veld "inhoud" in het verzendsysteen.',
        'desc_tip'    => TRUE
    ),
    'services'     => array(
        'type' => 'services',
    ),
);