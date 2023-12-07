<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       http://www.parcelpro.nl/
 * @since      1.0.0
 *
 * @package    Parcelpro
 * @subpackage Parcelpro/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.2.0
 * @package    Parcelpro
 * @subpackage Parcelpro/includes
 * @author     Ruben van den Ende <ict@parcelpro.nl>
 */
class Parcelpro
{
    /**
     * The loader that's responsible for maintaining and registering all hooks that power
     * the plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      Parcelpro_Loader $loader Maintains and registers all hooks for the plugin.
     */
    protected $loader;
    /**
     * The unique identifier of this plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      string $plugin_name The string used to uniquely identify this plugin.
     */
    protected $plugin_name;
    /**
     * The current version of the plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      string $version The current version of the plugin.
     */
    protected $version = PARCELPRO_VERSION;

    /**
     * Define the core functionality of the plugin.
     *
     * Set the plugin name and the plugin version that can be used throughout the plugin.
     * Load the dependencies, define the locale, and set the hooks for the admin area and
     * the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function __construct()
    {
        $this->plugin_name = 'parcelpro';

        $this->load_dependencies();
        $this->define_shipping_method();
        $this->define_admin_hooks();
        $this->define_public_hooks();
    }

    /**
     * Load the required dependencies for this plugin.
     *
     * Include the following files that make up the plugin:
     *
     * - Parcelpro_Loader. Orchestrates the hooks of the plugin.
     * - Parcelpro_i18n. Defines internationalization functionality.
     * - Parcelpro_API. Communicates with Parcel Pro.
     * - Parcelpro_Admin. Defines all hooks for the admin area.
     * - Parcelpro_Public. Defines all hooks for the public side of the site.
     *
     * Create an instance of the loader which will be used to register the hooks
     * with WordPress.
     *
     * @since    1.0.0
     * @access   private
     */
    private function load_dependencies()
    {

        /**
         * The class responsible for orchestrating the actions and filters of the
         * core plugin.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-parcelpro-loader.php';

        /**
         * The class responsible for the communication with Parcel Pro
         * of the plugin.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-parcelpro-api.php';

        /**
         * The class responsible for defining all actions that occur in the admin area.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'admin/class-parcelpro-admin.php';

        /**
         * The class responsible for defining all actions that occur in the public-facing
         * side of the site.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'public/class-parcelpro-public.php';

        $this->loader = new Parcelpro_Loader();
    }

    /**
     * Define the shipping method for WooCommerce.
     *
     * Uses the Parcelpro_shipping class in order to add the shipping methodes to WooCommerce.
     * with WordPress.
     *
     * @since    1.0.0
     * @access   private
     */
    private function define_shipping_method()
    {
        $this->loader->add_action('woocommerce_shipping_init', $this, 'shipping_init');
        $this->loader->add_filter('woocommerce_shipping_methods', $this, 'add_shipping_method');
    }

    /**
     * Register all of the hooks related to the admin area functionality.
     * of the plugin.
     *
     * @since    1.0.0
     * @access   private
     */
    private function define_admin_hooks()
    {
        $plugin_admin = new Parcelpro_Admin($this->get_plugin_name(), $this->get_version());

        $this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_styles');
        $this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts');

        $this->loader->add_action('woocommerce_admin_order_actions_end', $plugin_admin, 'add_actions', 20);
        $this->loader->add_action('woocommerce_order_status_changed', $plugin_admin, 'auto_export');
        $this->loader->add_action('admin_footer', $plugin_admin, 'add_bulk_actions');
        $this->loader->add_action('load-edit.php', $plugin_admin, 'action_handler');

        $this->loader->add_action('add_meta_boxes_shop_order', $plugin_admin, 'add_order_actions');
        $this->loader->add_action('add_meta_boxes', $plugin_admin, 'add_order_actions');

        $this->loader->add_filter('handle_bulk_actions-woocommerce_page_wc-orders', $plugin_admin, 'action_handler');
    }

    /**
     * Register all of the hooks related to the public-facing functionality.
     * of the plugin.
     *
     * @since    1.0.0
     * @access   private
     */
    private function define_public_hooks()
    {
        $plugin_public = new Parcelpro_Public($this->get_plugin_name(), $this->get_version());

        $this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_styles');
        $this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_scripts');

        $this->loader->add_action('woocommerce_before_checkout_form', $plugin_public, 'add_popup');
        $this->loader->add_action('woocommerce_after_checkout_form', $plugin_public, 'add_input');
        $this->loader->add_action('woocommerce_before_cart_contents', $plugin_public, 'add_popup');
        $this->loader->add_action('woocommerce_after_cart_contents', $plugin_public, 'add_input');

        $this->loader->add_action('woocommerce_checkout_process', $plugin_public, 'validate_shipping', 10, 1);
        $this->loader->add_action('woocommerce_checkout_update_order_meta', $plugin_public, 'set_checkout_meta', 10, 2);
        $this->loader->add_action('woocommerce_order_details_after_order_table', $plugin_public, 'add_order_tracking', 10, 1);

        $this->loader->add_action('woocommerce_email_after_order_table', $plugin_public, 'add_tracking_to_email_template', 20, 4);
    }

    /**
     * Initializes shipping method.
     *
     * @since    1.0.0
     */
    public function shipping_init()
    {
        require_once plugin_dir_path(dirname(__FILE__)) . 'admin/class-parcelpro-shipping.php';
    }

    /**
     * Adds shipping method to WooCommerce.
     *
     * @since    1.0.0
     * @param $methods
     * @return array
     */
    public function add_shipping_method($methods)
    {
        $methods[] = 'ParcelPro_Shipping';

        return $methods;
    }

    /**
     * Run the loader to execute all of the hooks with WordPress.
     *
     * @since    1.0.0
     */
    public function run()
    {
        $this->loader->run();
    }

    /**
     * The name of the plugin used to uniquely identify it within the context of
     * WordPress and to define internationalization functionality.
     *
     * @since     1.0.0
     * @return    string    The name of the plugin.
     */
    public function get_plugin_name()
    {
        return $this->plugin_name;
    }

    /**
     * The reference to the class that orchestrates the hooks with the plugin.
     *
     * @since     1.0.0
     * @return    Parcelpro_Loader    Orchestrates the hooks of the plugin.
     */
    public function get_loader()
    {
        return $this->loader;
    }

    /**
     * Retrieve the version number of the plugin.
     *
     * @since     1.0.0
     * @return    string    The version number of the plugin.
     */
    public function get_version()
    {
        return $this->version;
    }
}
