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
<a href="<?php echo $label_link; ?>" class="button tips parcelpro parcelpro-label" alt="Print <?= PARCELPRO_SHOPSUNITED ?> verzendlabel" data-tip="Print <?= PARCELPRO_SHOPSUNITED ?> verzendlabel">
    <img src="<?php echo dirname(plugin_dir_url(__FILE__)) . '/images/symbol.svg'; ?>" style="height: 16px; width: 12px;" alt="Print <?= PARCELPRO_SHOPSUNITED ?> verzendlabel">
</a>
<a href="<?php echo $track_link; ?>" class="button tips parcelpro parcelpro-track" alt="Volg <?= PARCELPRO_SHOPSUNITED ?> zending" data-tip="Volg <?= PARCELPRO_SHOPSUNITED ?> zending" target="_blank">
    <img src="<?php echo dirname(plugin_dir_url(__FILE__)) . '/images/transport.svg'; ?>" style="height: 16px; width: 12px;" alt="Volg <?= PARCELPRO_SHOPSUNITED ?> zending">
</a>
