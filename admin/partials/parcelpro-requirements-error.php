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
<div class="notice notice-error">
    <p><?php echo PARCELPRO_NAME; ?> error: Your environment doesn't meet all of the system requirements listed
        below.</p>
    <ul class="ul-disc">
        <li>
            <strong>PHP <?php echo PARCELPRO_REQUIRED_PHP_VERSION; ?>+</strong>
            <em>(You're running version <?php echo esc_html(PHP_VERSION); ?>)</em>
        </li>
        <li>
            <strong>WordPress <?php echo PARCELPRO_REQUIRED_WP_VERSION; ?>+</strong>
            <em>(You're running version <?php echo esc_html($wp_version); ?>)</em>
        </li>
        <li>
            <strong><?php echo PARCELPRO_NAME; ?> requires the plugin <a
                        href="https://wordpress.org/plugins/woocommerce/">WooCommerce</a> to be active!</strong>
        </li>
        <li>
            <strong>WooCommerce <?php echo PARCELPRO_REQUIRED_WOOCOMMERCE_VERSION; ?>+</strong>
            <?php if (defined('WOOCOMMERCE_VERSION')) { ?>
            <em>(You're running version <?php echo esc_html(WOOCOMMERCE_VERSION); ?>)</em>
            <?php } ?>
        </li>
    </ul>
    <p>If you need to upgrade your version of PHP you can ask your hosting company for assistance, and if you need help
        upgrading WordPress you can refer to <a href="https://wordpress.org/documentation/article/updating-wordpress/">the WordPress update guide</a>.</p>
</div>

