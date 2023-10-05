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
<a href="<?php echo $ship_link; ?>" class="button" alt="Aanmelden bij Parcel Pro" style="margin-bottom: 5px;">
    <img src="<?php echo dirname(plugin_dir_url(__FILE__)) . '/images/arrows.svg'; ?>" style="height: 12px; width: 12px; padding-right: 5px;" alt="Aanmelden bij Parcel Pro">
    Aanmelden bij Parcel Pro
</a>
<a href="<?php echo $package_link; ?>" class="button parcelpro-package" alt="Parcel Pro Aantal pakketten" style="margin-top: 5px;">
   <img src="<?php echo dirname(plugin_dir_url(__FILE__)) . '/images/box.svg'; ?>" style="height: 12px; width: 12px; padding-right: 5px;" alt="Parcel Pro Aantal pakketten">
   Parcel Pro verzendopties
</a>
