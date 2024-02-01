<?php

/**
 * Returns data of the available services to the shipping method
 *
 * This file is used to include in the Parcel Pro shipping method.
 *
 * @link       http://www.parcelpro.nl/
 * @since      1.0.0
 *
 * @package    Parcelpro
 * @subpackage Parcelpro/admin/data
 */

require_once(ABSPATH . 'wp-admin/includes/screen.php');

$PostNL_services = array();
$DHL_services = array();
$DPD_services = array();
$UPS_services = array();
$homerrServices = [];
$intrapostServices = [];
$viatimServices = [];
$cycloonServices = [];
$types = null;
if (is_admin()) {
    $current_screen = get_current_screen();
    $types = json_decode($this->api->get_type(($current_screen && strpos($current_screen->base, "settings") !== false ? true : false )), true);
    $services = array($types);

    if (is_array($types)) {
        foreach ($types as &$type) {
            if (is_array($type)) {
                if ($type['CarrierNaam'] == 'PostNL') {
                    $PostNL_services = array('PostNL' => array(
                        'postnl_afleveradres' => 'Afleveradres',
                        'postnl_pakjegemak' => 'PakjeGemak',
                        'postnl_nbb' => 'Alleen Huisadres',
                        'postnl_hvo' => 'Handtekening',
                        'postnl_hvoobb' => 'Handtekening, Ook bij Buren',
                        'postnl_or' => 'Onder Rembours',
                        'postnl_vb' => 'Verzekerd bedrag',
                        'postnl_brief' => 'Brievenbuspakje',
                        'postnl_buitenland' => 'Buitenland',
                    ));
                }
                if ($type['CarrierNaam'] == 'DHL') {
                    $DHL_services = array('DHL' => array(
                        'dhl_afleveradres' => 'Afleveradres',
                        'dhl_parcelshop' => 'Parcelshop',
                        'dhl_nbb' => 'Niet bij buren',
                        'dhl_hvo' => 'Handtekening',
                        'dhl_ez' => 'Extra zeker',
                        'dhl_eve' => 'Avondlevering',
                        'dhl_brief' => 'Brievenbuspakje',
                        'dhl_buitenland' => 'Buitenland',
                    ));
                }
                if ($type['CarrierNaam'] == 'DPD') {
                    $DPD_services = array('DPD' => array(
                        'dpd_101' => 'Afleveradres',
                        'dpd_136' => 'Klein pakket',
                        'dpd_109' => 'Onder rembours',
                        'dpd_161' => 'Onder rembours en verzekerd',
                        'dpd_350' => 'Voor 8:30 in huis',
                        'dpd_352' => 'Voor 8:30 in huis en onder rembours',
                        'dpd_179' => 'Voor 10:00 in huis',
                        'dpd_191' => 'Voor 10:00 in huis en onder rembours',
                        'dpd_225' => 'Voor 12:00 in huis',
                        'dpd_237' => 'Voor 12:00 in huis en onder rembours',
                        'dpd_228' => 'Voor 12:00 in huis op zaterdag',
                        'dpd_155' => 'Voor 18:00 in huis',
                        'dpd_337' => 'Normal Parcel B2C via Parcelshop',
                    ));
                }
                if ($type['CarrierNaam'] == 'UPS') {
                    $UPS_services = array('UPS' => array(
                        'ups_11' => 'Standard',
                        'ups_07' => 'Express',
                        'ups_54' => 'Express Plus',
                        'ups_65' => 'Express Saver',
                    ));
                }

                if ($type['CarrierNaam'] == 'Homerr') {
                    $homerrServices = ['Homerr' => [
                        'homerr_direct2shop_s' => 'Direct2Shop Small',
                        'homerr_direct2shop_m' => 'Direct2Shop Medium',
                        'homerr_direct2shop_l' => 'Direct2Shop Large'
                    ]];
                }

                if ($type['CarrierNaam'] == 'Intrapost') {
                    $intrapostServices = ['Intrapost' => [
                        'intrapost_standard' => 'StandardParcel',
                        'intrapost_insured' => 'InsuredParcel',
                        'intrapost_registered' => 'RegisteredParcel',
                        'intrapost_stated_address' => 'StandardParcelStatedAddress',
                        'intrapost_signature' => 'StandardParcelStatedAddressSignature',
                        'intrapost_mailbox' => 'MailboxParcel',
                        'intrapost_parcelshop' => 'ParcelViaPickupLocation'
                    ]];
                }

                if ($type['CarrierNaam'] == 'Viatim') {
                    $viatimServices = ['Viatim' => [
                        'viatim_mailbox' => 'Brievenbuspakket',
                        'viatim_normal' => 'Pakket 0-15',
                        'viatim_parcelshop_normal' => 'Pakket 0-15 Parcelshop',
                        'viatim_heavy' => 'Pakket 15-30',
                        'viatim_parcelshop_heavy' => 'Pakket 15-30 Parcelshop',
                    ]];
                }

                if ($type['CarrierNaam'] == 'Fietskoerier') {
                    $cycloonServices = [
                        'Fietskoerier' => [
                            'cycloon_pakket' => 'Pakket',
                            'cycloon_pakket_xl' => 'Pakket XL',
                            'cycloon_brievenbuspakket' => 'Brievenbuspakket'
                         ]
                    ];
                }
            }
        }
    }

    $Fadello_services = array('Fadello' => array(
        'fadello_dc' => 'Same Day'
    )
    );

    $Custom_services = array();
    $options = get_option('woocommerce_parcelpro_shipping_settings');
    if (isset($options['services']) && count($options['services']) > 0) {
        foreach ($options['services'] as $k => $v) {
            $parts = explode('_', $k);
            if ($parts[0] === "maatwerk") {
                if (!array_key_exists(ucfirst($k), $Custom_services)) {
                    $Custom_services[ucfirst($k)] = array();
                }
                $Custom_services[ucfirst($k)] = array_merge($Custom_services[ucfirst($k)], array($k => $v['id']));
            }
        }
    }
    $services = array_merge($PostNL_services, $DHL_services, $DPD_services, $UPS_services, $homerrServices, $intrapostServices, $viatimServices, $cycloonServices, $Fadello_services, $Custom_services);
} else {
    $options = get_option('woocommerce_parcelpro_shipping_settings');

    $services = array();
    if (isset($options['services']) && count($options['services']) > 0) {
        foreach ($options['services'] as $k => $v) {
            $parts = explode('_', $k);

            $type_id = '';
            if (!array_key_exists(ucfirst($parts[0]), $services)) {
                $services[ucfirst($parts[0])] = array();
            }
            $services[ucfirst($parts[0])] = array_merge($services[ucfirst($parts[0])], array($k => $v['id'] . $type_id));
        }
    }
}

return $services;
