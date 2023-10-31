<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://www.parcelpro.nl/
 * @since      1.0.0
 *
 * @package    Parcelpro
 * @subpackage Parcelpro/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, the public-specific stylesheet and JavaScript,
 * adds the popup and input fields  to the checkout page and process the chosen pickup point.
 *
 * @package    Parcelpro
 * @subpackage Parcelpro/public
 * @author     Ruben van den Ende <ict@parcelpro.nl>
 */
class Parcelpro_Public
{
    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string $plugin_name The ID of this plugin.
     */
    private $plugin_name;
    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string $version The current version of this plugin.
     */
    private $version;

    private $settings;

    private $api;

    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     * @param      string $plugin_name The name of the plugin.
     * @param      string $version The version of this plugin.
     */
    public function __construct($plugin_name, $version)
    {
        $this->plugin_name = $plugin_name;
        $this->version = $version;
        $this->settings = get_option('woocommerce_parcelpro_shipping_settings');
        $this->api = new ParcelPro_API();
    }

    /**
     * Register the stylesheets for the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function enqueue_styles()
    {
        wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/parcelpro-public.css', array(), $this->version, 'all');
    }

    /**
     * Register the JavaScript for the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts()
    {
        wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/parcelpro-public.js', array( 'jquery' ), $this->version, false);
    }

    /**
     * Adds the Parcel Pro popup in the head of the checkout page.
     *
     * @since    1.0.0
     */
    public function add_popup()
    {
        include(plugin_dir_path(__FILE__) . 'partials/parcelpro-public-afhaalpunt-popup.php');
    }

    /**
     * Adds the Parcel Pro popup in the head of the checkout page.
     *
     * @since    1.0.0
     */
    public function add_input()
    {
        include(plugin_dir_path(__FILE__) . 'partials/parcelpro-public-afhaalpunt-input.php');
    }

    /**
     * Validates whether a choice of collection point is made.
     *
     * @since    1.0.0
     * @param $order_id
     */
    public function validate_shipping($order_id)
    {
        $afhaalpunt_methodes = array( 'parcelpro_postnl_pakjegemak', 'parcelpro_dhl_parcelshop', 'parcelpro_homerr_direct2shop', 'parcelpro_dpd_337');

        $rates = WC()->shipping()->get_shipping_methods();
        $parcelprorates = isset($rates['parcelpro_shipping']) ? $rates['parcelpro_shipping'] : null;
        if ($parcelprorates) {
            $parcelprorates = isset($parcelprorates->settings['services']) ? $parcelprorates->settings['services'] : null;
        }
        $order = wc_get_order($order_id);
        $shipping = $_POST['shipping_method'];

        $shipping = $shipping ? $shipping : ($order && method_exists($order, 'get_shipping_methods') ? ( !is_scalar($order->get_shipping_methods()) ? current($order->get_shipping_methods()) : null) : null);

        if (!is_scalar($shipping) && isset($shipping)) {
            $shipping = $shipping[0];
        }

        $maatwerk = false;
        if ($parcelprorates) {
            foreach ($parcelprorates as $rate) {
                foreach ($rate as $row) {
                    if (is_scalar($row)) {
                        continue;
                    }
                    if (isset($row['servicepunt']) && ($row['servicepunt'] != null || $row['servicepunt'] != 'off')) {
                        if (strpos($shipping, $rate['id']) > 0) {
                            $maatwerk = true;
                        }
                    }
                }
            }
        }
        $method_exploded = explode('_', $shipping);
        $method_assembled = count($method_exploded) > 2 ? $method_exploded[0] . '_' . $method_exploded[1] . '_' . $method_exploded[2] : $method_exploded[0];
        if ((in_array($method_assembled, $afhaalpunt_methodes) || $maatwerk) && !$_POST[ 'parcelpro_company' ]) {
            load_plugin_textdomain('woocommerce_parcelpro', false, dirname(plugin_basename(__FILE__)) . 'languages');
            wc_add_notice(__('Voor deze verzendwijze moet u een afhaalpunt selecteren. Kies een afhaalpunt of selecteer een andere verzendwijze.', 'woocommerce_parcelpro'), 'error');
        }
    }

    /**
     * Saves the data of the chosen pickup point to the database.
     *
     * @since    1.0.0
     * @param $order_id
     */
    public function set_checkout_meta($order_id)
    {
        if (!empty($_POST[ 'parcelpro_afhaalpunt' ])) {
            // WooCommerce 7
            update_post_meta($order_id, '_parcelpro_afhaalpunt', 'yes');
            update_post_meta($order_id, '_shipping_company', $_POST[ 'parcelpro_company' ]);
            update_post_meta($order_id, '_shipping_first_name', $_POST[ 'parcelpro_first_name' ]);
            update_post_meta($order_id, '_shipping_last_name', $_POST[ 'parcelpro_last_name' ]);
            update_post_meta($order_id, '_shipping_address_1', $_POST[ 'parcelpro_address_1' ]);
            update_post_meta($order_id, '_shipping_address_2', $_POST[ 'parcelpro_address_2' ]);
            update_post_meta($order_id, '_shipping_postcode', $_POST[ 'parcelpro_postcode' ]);
            update_post_meta($order_id, '_shipping_city', $_POST[ 'parcelpro_city' ]);
            update_post_meta($order_id, '_shipping_country', $_POST[ 'parcelpro_country' ]);

            // WooCommerce 8
            $order = wc_get_order($order_id);
            $order->set_meta_data([ 'parcelpro_afhaalpunt' => 'yes' ]);
            $order->set_shipping_company($_POST[ 'parcelpro_company' ]);
            $order->set_shipping_first_name($_POST[ 'parcelpro_first_name' ]);
            $order->set_shipping_last_name($_POST[ 'parcelpro_last_name' ]);
            $order->set_shipping_address_1($_POST[ 'parcelpro_address_1' ]);
            $order->set_shipping_address_2($_POST[ 'parcelpro_address_2' ]);
            $order->set_shipping_postcode($_POST[ 'parcelpro_postcode' ]);
            $order->set_shipping_city($_POST[ 'parcelpro_city' ]);
            $order->set_shipping_country($_POST[ 'parcelpro_country' ]);
            $order->save();

            $data = $_POST;
            $data[ 'order_id' ] = $order_id;

            $this->api->post_afhaalpunt_keuze($data);
        }
    }


    /**
     * Saves the data of the chosen pickup point to the database.
     *
     * @since    1.1.0
     * @param $order
     */
    public function add_order_tracking($order)
    {
        $tracking = get_post_meta($order->get_id(), '_parcelpro_track', true);
        $tracking_url = get_post_meta($order->get_id(), '_parcelpro_track_url', true);
        $allowed = ( isset($this->settings[ 'enabled' ]) ) ? $this->settings[ 'enabled' ] : null;
        $allowed_track = ( isset($this->settings[ 'order_tracking' ]) ) ? $this->settings[ 'order_tracking' ] : null;

        if (( $tracking || $tracking_url ) && $allowed == 'yes' && $allowed_track == 'yes') {
            include(plugin_dir_path(__FILE__) . 'partials/parcelpro-public-order-tracking.php');
        }
    }

    /**
     * Add tracking data to e-mail template
     *
     * @since    1.3.1
     * @param $order
     */
    public function add_tracking_to_email_template($order, $sent_to_admin, $plain_text, $email)
    {
        if ($email && !is_scalar($email) && $email->id == 'customer_completed_order') {
            $tracking = get_post_meta($order->get_id(), '_parcelpro_track', true);
            $tracking_url = get_post_meta($order->get_id(), '_parcelpro_track_url', true);
            $allowed = ( isset($this->settings[ 'enabled' ]) ) ? $this->settings[ 'enabled' ] : null;
            $allowed_track = ( isset($this->settings[ 'order_tracking' ]) ) ? $this->settings[ 'order_tracking' ] : null;

            if (( $tracking || $tracking_url ) && $allowed == 'yes' && $allowed_track == 'yes') {
                echo '<h2 class="email-upsell-title">Tracking</h2><p>Volg uw bestelling via <a href="' . $tracking_url . '">deze</a> link of kopieer onderstaande url: ' . $tracking_url . '</p>';
            }
        }
    }
}
