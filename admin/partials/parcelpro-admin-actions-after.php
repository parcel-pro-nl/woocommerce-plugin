<?php
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       http://www.parcelpro.nl/
 * @since      1.0.0
 *
 * @package    Parcelpro
 * @subpackage Parcelpro/admin/partials
 */
?>
<a href="<?php echo $label_link; ?>" class="button tips parcelpro-label" alt="Print Parcel Pro verzendlabel" data-tip="Print Parcel Pro verzendlabel">
    <img src="<?php echo dirname( plugin_dir_url( __FILE__ ) ) . '/images/symbol.svg'; ?>" style="height: 16px; width: 12px;" alt="Print Parcel Pro verzendlabel">
</a>
<a href="<?php echo $track_link; ?>" class="button tips parcelpro-track" alt="Volg Parcel Pro zending" data-tip="Volg Parcel Pro zending" target="_blank">
    <img src="<?php echo dirname( plugin_dir_url( __FILE__ ) ) . '/images/transport.svg'; ?>" style="height: 16px; width: 12px;" alt="Volg Parcel Pro zending">
</a>
