<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link            https://parcelpro.nl/
 * @since           1.0.0
 * @package         Parcelpro
 *
 * @wordpress-plugin
 * Plugin Name:     WooCommerce Parcel Pro
 * Plugin URI:      https://www.parcelpro.nl/koppelingen/woocommerce/
 * Description:     Geef klanten de mogelijkheid om hun pakket af te halen bij een afhaalpunt in de buurt. Daarnaast exporteert de plug-in uw zendingen direct in het verzendsysteem van Parcel Pro.
 * Version:         1.6.9
 * Author:          Parcel Pro
 * Author URI:      https://parcelpro.nl/
 * License:         GPL-3.0+
 * License URI:     http://www.gnu.org/licenses/gpl-3.0.txt
 * Text Domain:     woocommerce_parcelpro
 * Domain Path:     /languages
 */

if (!defined('WPINC')) {
    die('Access denied.');
}

define('PARCELPRO_NAME', 'WooCommerce Parcel Pro');
define('PARCELPRO_VERSION', '1.6.9');
define('PARCELPRO_REQUIRED_PHP_VERSION', '5.3');
define('PARCELPRO_REQUIRED_WP_VERSION', '3.1');
define('PARCELPRO_REQUIRED_WOOCOMMERCE_VERSION', '7.0');

define('PARCELPRO_SHOPSUNITED', 'Parcel Pro');

/**
 * Checks if the system requirements are met.
 *
 * @since    1.0.0
 * @return bool True if system requirements are met, false if not
 */

function parcelpro_requirements_met()
{
    global $wp_version;

    if (version_compare(PHP_VERSION, PARCELPRO_REQUIRED_PHP_VERSION, '<')) {
        return false;
    }
    if (version_compare($wp_version, PARCELPRO_REQUIRED_WP_VERSION, '<')) {
        return false;
    }

    // The following checks have proven to be fragile in other WordPress systems, so are commented out for now.
    /*
    if (!in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')))) {
        return false;
    }
    if (defined('WOOCOMMERCE_VERSION') && version_compare(WOOCOMMERCE_VERSION, PARCELPRO_REQUIRED_WOOCOMMERCE_VERSION, '<')) {
        return false;
    }
    */

    return true;
}

/**
 * Prints an error that the system requirements weren't met.
 * @since    1.0.0
 */
function parcelpro_requirements_error()
{
    include(plugin_dir_path(__FILE__) . 'admin/partials/parcelpro-requirements-error.php');
}

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path(__FILE__) . 'includes/class-parcelpro.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_parcelpro()
{
    if (parcelpro_requirements_met()) {
        if (class_exists('ParcelPro')) {
            $plugin = new Parcelpro();
            $plugin->run();
        }
    } else {
        add_action('admin_notices', 'parcelpro_requirements_error');
    }
}

run_parcelpro();
