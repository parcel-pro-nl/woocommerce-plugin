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
<a href="<?php echo $ship_link; ?>" class="button tips parcelpro parcelpro-export" alt="Aanmelden bij <?= PARCELPRO_SHOPSUNITED ?>" data-tip="Aanmelden bij <?= PARCELPRO_SHOPSUNITED ?>">
    <img src="<?php echo dirname(plugin_dir_url(__FILE__)) . '/images/arrows.svg'; ?>" style="height: 16px; width: 12px;" alt="Aanmelden bij <?= PARCELPRO_SHOPSUNITED ?>">
</a>

<!-- <a href="<?php //echo $package_link; ?>" class="button tips parcelpro-package" alt="Parcel Pro Aantal pakketten" data-tip="Parcel Pro Aantal pakketten">
   <img src="<?php //echo dirname( plugin_dir_url( __FILE__ ) ) . '/images/box.svg'; ?>" style="height: 16px; width: 12px;" alt="Parcel Pro Aantal pakketten">
</a> -->
