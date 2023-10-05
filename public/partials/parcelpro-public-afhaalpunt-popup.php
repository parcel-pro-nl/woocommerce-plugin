<?php

/**
 * Provide a public-facing view for the plugin
 *
 * This file is used to markup the public-facing aspects of the plugin.
 *
 * @link       http://www.parcelpro.nl/
 * @since      1.0.0
 *
 * @package    Parcelpro
 * @subpackage Parcelpro/public/partials
 */

$rates = WC()->shipping()->get_shipping_methods();
$parcelprorates = isset($rates['parcelpro_shipping']) ? $rates['parcelpro_shipping'] : null;
if ($parcelprorates) {
    $parcelprorates = isset($parcelprorates->settings['services']) ? $parcelprorates->settings['services'] : null;
}

$servicepuntenrates = [];
if ($parcelprorates) {
    foreach ($parcelprorates as $rate) {
        foreach ($rate as $row) {
            if (is_scalar($row)) {
                continue;
            }
            if (isset($row['servicepunt']) && ($row['servicepunt'] != null || $row['servicepunt'] != 'off') && !in_array($rate['id'], $servicepuntenrates)) {
                array_push($servicepuntenrates, $rate['id']);
            }
        }
    }
}
echo("<script> var servicepuntmethodes = " . json_encode($servicepuntenrates) . "; console.log(servicepuntmethodes)</script>");

?>
<div class="global-modal" id="modal">
    <div class="overlay" id="global_overlay"></div>
    <div class="global-modal_contents modal-transition">
        <iframe class="global-frame intrinsic-ignore" frameborder="0" scrolling="no" id="afhaalpunt_frame" src=""></iframe>
    </div>
</div>