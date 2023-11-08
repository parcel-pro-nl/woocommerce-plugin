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
<a href="<?php echo $label_link; ?>" class="button" alt="Print <?= PARCELPRO_SHOPSUNITED ?> verzendlabel" style="margin-bottom: 5px;">
    <img src="<?php echo dirname(plugin_dir_url(__FILE__)) . '/images/symbol.svg'; ?>" style="height: 12px; width: 12px; padding-right: 5px;" alt="Print <?= PARCELPRO_SHOPSUNITED ?> verzendlabel">
    Print verzendlabel
</a>
<?php if (isset($shipment[0]['TrackingNummer']) && isset($track_link) && $track_link) { ?>
<a href="<?php echo $track_link; ?>" class="button" alt="Volg <?= PARCELPRO_SHOPSUNITED ?> zending" target="_blank" style="margin-top: 5px;">
    <img src="<?php echo dirname(plugin_dir_url(__FILE__)) . '/images/transport.svg'; ?>" style="height: 12px; width: 12px; padding-right: 5px;" alt="Volg <?= PARCELPRO_SHOPSUNITED ?> zending">
    Volg zending
</a>
<?php } ?>
