<?php

/**
 * Provide a public-facing view for the plugin
 *
 * This file is used to markup the public-facing aspects of the plugin.
 *
 * @link       http://www.parcelpro.nl/
 * @since      1.1.0
 *
 * @package    Parcelpro
 * @subpackage Parcelpro/public/partials
 */

?>
<h2>Tracking Data</h2>
<table class="shop_table">
    <tbody>
    <?php if ($tracking) { ?>
        <tr>
            <th scope="row">Tracking code:</th>
            <td><?php echo $tracking; ?></td>
        </tr>
    <?php } ?>
    <?php if ($tracking_url) { ?>
        <tr>
            <th scope="row">Tracking URL:</th>
            <td>
                <a href="<?php echo $tracking_url; ?>"><?php echo ( $name = explode(":", $order->get_shipping_method())[ 0 ] ) ? $name : 'Track'; ?></a>
            </td>
        </tr>
    <?php } ?>
    </tbody>
</table>
