<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://www.parcelpro.nl/
 * @since      1.0.0
 *
 * @package    Parcelpro
 * @subpackage Parcelpro/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, the admin-specific stylesheet and JavaScript,
 * adds order actions to the order view of WooCommerce and process the added actions.
 *
 *
 * @package    Parcelpro
 * @subpackage Parcelpro/admin
 * @author     Ruben van den Ende <ict@parcelpro.nl>
 */
class Parcelpro_Admin
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
     * @param string $plugin_name The name of this plugin.
     * @param string $version The version of this plugin.
     * @since    1.0.0
     *
     */
    public function __construct($plugin_name, $version)
    {
        $this->plugin_name = $plugin_name;
        $this->version = $version;
        $this->settings = get_option('woocommerce_parcelpro_shipping_settings');
        $this->api = new ParcelPro_API();
    }


    /**
     * Register the stylesheets for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_styles()
    {
        wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/parcelpro-admin.css', array(), $this->version, 'all');
        wp_enqueue_style('wpb-jquery-ui-style', '//ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/themes/humanity/jquery-ui.css', false, null);
        wp_enqueue_style('thickbox');
    }

    /**
     * Register the JavaScript for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts()
    {
        wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/parcelpro-admin.js', array('jquery'), $this->version, false);
        wp_enqueue_script('jquery-ui-accordion');
        wp_enqueue_script('jquery-ui-spinner');
        wp_enqueue_script('thickbox');
    }

    /**
     * Adds the action buttons for the orders table
     *
     * @param $order
     * @since    1.0.0
     *
     */
    public function add_actions($order)
    {
        $label_link = wp_nonce_url(admin_url('edit.php?&action=parcelpro-label&order_id=' . $order->get_id()), 'parcelpro-label');
        $track_link = wp_nonce_url(admin_url('edit.php?&action=parcelpro-track&order_id=' . $order->get_id()), 'parcelpro-track');
        $ship_link = wp_nonce_url(admin_url('edit.php?&action=parcelpro-export&order_id=' . $order->get_id()), 'parcelpro-export');
        $package_link = wp_nonce_url(admin_url('edit.php?&action=parcelpro-package&order_id=' . $order->get_id()), 'parcelpro-package');

        if ($status = get_post_meta($order->get_id(), '_parcelpro_status', true)) {
            include(plugin_dir_path(__FILE__) . 'partials/parcelpro-admin-actions-after.php');
        } else {
            include(plugin_dir_path(__FILE__) . 'partials/parcelpro-admin-actions-before.php');
        }
    }

    /**
     * Adds the bulk action on the orders page
     *
     * @since    1.0.0
     */
    public function add_bulk_actions()
    {
        global $post_type;

        // Check for WooCommerce 7 and 8
        if ($post_type == 'shop_order' || get_current_screen()->id == 'woocommerce_page_wc-orders') {
            include(plugin_dir_path(__FILE__) . 'partials/parcelpro-admin-actions-bulk.php');
        }
    }

    /**
     * Add the meta box on the single order page
     *
     * @since    1.0.0
     */
    public function add_order_actions()
    {
        add_meta_box(
            'parcelpro',
            PARCELPRO_SHOPSUNITED,
            [$this, 'create_box_content'],
            ['shop_order', 'woocommerce_page_wc-orders'],
            'side'
        );
    }

    /**
     * Creates content for the order actions page
     *
     * @param $order WC_Order | WP_Post | null
     * @since    1.0.0
     */
    public function create_box_content($order)
    {
        global $post_id;

        $order_id = $post_id;
        if ($order instanceof WC_Order) {
            $order_id = $order->get_id();

            // In WooCommerce 8, orders are automatically saved as a draft.
            // So we need to check if this is an automatic draft or not.
            if ($order->get_status() === 'auto-draft') {
                $order_id = null;
            }
        }

        $label_link = wp_nonce_url(admin_url('edit.php?&action=parcelpro-label&order_id=' . $order_id), 'parcelpro-label');
        $track_link = wp_nonce_url(admin_url('edit.php?&action=parcelpro-track&order_id=' . $order_id), 'parcelpro-track');
        $ship_link = wp_nonce_url(admin_url('edit.php?&action=parcelpro-export&order_id=' . $order_id), 'parcelpro-export');
        $package_link = wp_nonce_url(admin_url('edit.php?&action=parcelpro-package&order_id=' . $order_id), 'parcelpro-package');

        if ($order_id) {
            $data_order = $this->format_order_data($order_id);
            if (is_array($data_order)) {
                if (array_key_exists('shipping_method', $data_order)) {
                    $options = get_option('woocommerce_parcelpro_shipping_settings');
                    $services = isset($options['services']) ? $options['services'] : [];
                    $shipping_method = $data_order['shipping_method'];
                    $exploded_method = explode('_', $shipping_method);
                    if (count($exploded_method) > 1) {
                        if ($exploded_method[1] === 'maatwerk') {
                            $service = $services[str_replace("parcelpro_", "", $shipping_method)] ?? null;
                            if (!$service) {
                                $maatwerkServices = $services[$exploded_method[1] . '_' . $exploded_method[2]];

                                if (isset($exploded_method[3])) {
                                    foreach ($maatwerkServices as $k => $v) {
                                        if (is_array($v)) {
                                            if ($v['type-id'] == $exploded_method[3]) {
                                                $service = $v;
                                            }
                                        }
                                    }
                                } else {
                                    $service = array_pop($maatwerkServices);
                                }
                            } else {
                                $service = array_pop($service);
                            }
                            if (isset($service['method-title'])) {
                                echo('<p>' . 'Gekozen dienst: ' . $service['method-title'] . '</p>');
                            } else {
                                echo('<p>' . 'Gekozen verzendmethode: ' . " 'niet aanmelden bij Parcel Pro!'" . '</p>');
                            }
                        } else {
                            $types = $this->api->types_key_values();
                            if (array_key_exists(3, $exploded_method) && array_key_exists($exploded_method[3], $types) && $exploded_method[1] === 'maatwerk') {
                                echo('<p>' . 'Gekozen dienst: ' . $types[($exploded_method[3])] . '</p>');
                            } elseif (array_key_exists(2, $exploded_method)) {
                                echo '<p>' . 'Gekozen dienst: ' . $exploded_method[2] . '</p>';
                            } else {
                                // As a last resort, simply show the full shipping method.
                                echo '<p>' . 'Gekozen dienst: ' . $shipping_method . '</p>';
                            }
                        }
                    }
                }
            }

            if ($status = get_post_meta($order_id, '_parcelpro_status', true)) {
                if ($shipmentId = get_post_meta($order_id, '_parcelpro_id', true)) {
                    $shipment = json_decode($this->api->shipments($shipmentId), true);

                    if (isset($shipment[0]['TrackingUrl'])) {
                        update_post_meta($order_id, '_parcelpro_track_url', $shipment[0]['TrackingUrl']);
                    }

                    include(plugin_dir_path(__FILE__) . 'partials/parcelpro-admin-order-actions-after.php');
                }
            } else {
                include(plugin_dir_path(__FILE__) . 'partials/parcelpro-admin-order-actions-before.php');
            }
        } else {
            echo('<p>' . 'Sla deze order eerst op! </p>');
        }
    }

    /**
     * Exports a given order to Parcel Pro after status completed
     *
     * @param $order_id
     * @since    1.0.0
     *
     */
    public function auto_export($order_id)
    {
        $status = get_post_meta($order_id, '_parcelpro_status', true);
        if (!$status || $status == '') {
            $allowed_export = $this->settings['auto_export'];
            $method = $this->settings['availability'];
            $countries = ($method == 'specific') ? ($this->settings['countries'] ?? []) : [];
            $country = wc_get_order($order_id)->get_shipping_country();
            $order_status = wc_get_order($order_id)->get_status();

            // Ensure the order status always starts with 'wc-', same as the options from `wc_get_order_statuses`.
            if (strpos($order_status, 'wc-') !== 0) {
                $order_status = 'wc-' . $order_status;
            }

            // Ensure the auto export state always starts with 'wc-', so states from other plugins match the naming convention.
            $auto_export_state = $this->settings['auto_export_on_state'];
            if (strpos($auto_export_state, 'wc-') !== 0) {
                $auto_export_state = 'wc-' . $auto_export_state;
            }

            if ($allowed_export == 'yes' && ($method == 'all' || in_array($country, $countries)) && ($auto_export_state == $order_status)) {
                $this->export_order($order_id);
            }
        }
    }

    /**
     * Process an action for the given target
     *
     * @return bool
     * @since    1.0.0
     */
    public function action_handler()
    {
        if (!isset($_REQUEST['action'])) {
            return false;
        }
        $action = $_REQUEST['action'];

        // If the action is not a parcelpro-* action, do nothing.
        if (!str_starts_with($action, 'parcelpro-')) {
            return false;
        }

        // Get the action used to calculate the nonce.
        // Note that for bulk actions the action type is different.
        $nonceAction = $action;
        if (str_starts_with($nonceAction, 'parcelpro-bulk-')) {
            $nonceAction = 'bulk-orders';

            // Check if WooCommerce stores orders as post or order objects.
            // If we have a post_type, that means it's a post, so the action type is different.
            if (isset($_REQUEST['post_type'])) {
                $nonceAction = 'bulk-posts';
            }
        }

        if (!isset($_REQUEST['_wpnonce'])) {
            wp_die(esc_html("No nonce set for action: $action ($nonceAction)"));
        }

        if (!wp_verify_nonce($_REQUEST['_wpnonce'], $nonceAction)) {
            wp_die(esc_html("Invalid nonce for action: $action ($nonceAction)"));
        }

        switch ($action) {
            case 'parcelpro-export':
                if (empty($_GET['order_id'])) {
                    wp_die('Er is geen order geselecteerd!');
                }
                if (get_post_meta($_GET['order_id'], '_parcelpro_status', true)) {
                    wp_die('Order is al aagemeld bij Parcel Pro!');
                }

                $order_id = $_GET['order_id'];
                $this->export_order($order_id);

                wp_redirect($_SERVER['HTTP_REFERER'], 301);
                exit;
            case 'parcelpro-bulk-export':
                // Check for WooCommerce 7 and 8
                if (empty($_GET['post']) && empty($_GET['id'])) {
                    wp_die('Er zijn geen order geselecteerd!');
                }

                $order_ids = empty($_GET['post']) ? $_GET['id'] : $_GET['post'];

                foreach ($order_ids as $order_id) {
                    if (!get_post_meta($order_id, '_parcelpro_status', true)) {
                        $this->export_order($order_id);
                    }
                }

                wp_redirect($_SERVER['HTTP_REFERER'], 301);
                exit;
            case 'parcelpro-label':
                if (empty($_GET['order_id'])) {
                    wp_die('Er is geen order geselecteerd!');
                }
                if (!get_post_meta($_GET['order_id'], '_parcelpro_status', true)) {
                    wp_die('Order is nog niet aagemeld bij Parcel Pro!');
                }

                $order_id = $_GET['order_id'];
                $url = $this->api->get_label(get_post_meta($order_id, '_parcelpro_id', true));

                wp_redirect($url);
                echo "<script>window.close();</script>";
                exit;
            case 'parcelpro-bulk-label':
                // Check for WooCommerce 7 and 8
                if (empty($_GET['post']) && empty($_GET['id'])) {
                    wp_die('Er zijn geen order geselecteerd!');
                }

                $order_ids = empty($_GET['post']) ? $_GET['id'] : $_GET['post'];
                $url = null;

                foreach ($order_ids as $order_id) {
                    if (get_post_meta($order_id, '_parcelpro_status', true)) {
                        if (!$url) {
                            $url = $this->api->get_label(get_post_meta($order_id, '_parcelpro_id', true)) . '&selected[]=' . get_post_meta($order_id, '_parcelpro_id', true);
                        } else {
                            $url .= '&selected[]=' . get_post_meta($order_id, '_parcelpro_id', true);
                        }
                    }
                }
                wp_redirect($url);
                exit;
            case 'parcelpro-track':
                if (empty($_GET['order_id'])) {
                    wp_die('Er is geen order geselecteerd!');
                }
                if (!get_post_meta($_GET['order_id'], '_parcelpro_status', true)) {
                    wp_die('Order is nog niet aagemeld bij Parcel Pro!');
                }

                $order_id = $_GET['order_id'];

                $single = array_values(array_filter(json_decode($this->api->shipments(), true), function ($shipment) use ($order_id) {
                    return $shipment['Id'] == get_post_meta($order_id, '_parcelpro_id', true) && $shipment['TrackingNummer'];
                }));

                if (isset($single[0]['TrackingUrl'])) {
                    if (get_post_meta($order_id, '_parcelpro_track_url', true) != $single[0]['TrackingUrl']) {
                        update_post_meta($order_id, '_parcelpro_track_url', $single[0]['TrackingUrl']);
                    }
                }

                $url = get_post_meta($order_id, '_parcelpro_track_url', true);

                wp_redirect($url);
                exit;
            case 'parcelpro-package':
                if (empty($_GET['order_id'])) {
                    wp_die('Er is geen order geselecteerd!');
                }
                if (get_post_meta($_GET['order_id'], '_parcelpro_status', true)) {
                    wp_die('Order is al aagemeld bij Parcel Pro!');
                }
                $order_id = $_GET['order_id'];

                $options = get_option('woocommerce_parcelpro_shipping_settings');
                $options = $options['services'];
                $order = wc_get_order($order_id);
                $shipping = current($order->get_shipping_methods());
                $shipping_method = $shipping['method_id'];

                $package_count = ($count = get_post_meta($order_id, '_parcelpro_package', true)) ? $count : 1;

                include(plugin_dir_path(__FILE__) . 'partials/parcelpro-admin-actions-package.php');
                exit;
            case 'parcelpro-package-apply':
                if (empty($_GET['order_id'])) {
                    wp_die('Er is geen order geselecteerd!');
                }
                if (get_post_meta($_GET['order_id'], '_parcelpro_status', true)) {
                    wp_die('Order is al aagemeld bij Parcel Pro!');
                }
                if (!(isset($_GET['package']) && isset($_GET['shipping_method']))) {
                    exit;
                }

                $order_id = $_GET['order_id'];
                $package_count = isset($_GET['package']) ? $_GET['package'] : null;
                $redirect = isset($_GET['redirect']) ? $_GET['redirect'] : null;
                $shipping_method = isset($_GET['shipping_method']) ? $_GET['shipping_method'] : null;

                $options = get_option('woocommerce_parcelpro_shipping_settings');
                $options = $options['services'];
                $type_id = null;

                foreach ($options as $key => $val) {
                    if (
                        $key == $shipping_method ||
                        (strpos($shipping_method, 'maatwerk') !== false && strpos($shipping_method, $key) !== false)
                    ) {
                        foreach ($val as $v) {
                            if (is_array($v)) {
                                $title = $v['method-title'];
                                if (key_exists('type-id', $v) && strpos($v['type-id'], $shipping_method) !== false) {
                                    $type_id = $v['type-id'];
                                }
                            }
                        }
                    }
                }

                $order = wc_get_order($order_id);
                $order_shipping_method = $order->get_shipping_methods();

                //van pakjegemak/service punt naar normaal.
                $shipping_method_nieuw_is_afhaalpunt = false;
                $shipping_method_origineel_is_afhaalpunt = false;
                $originele_method = !is_scalar($order_shipping_method) ? wc_get_order_item_meta(key($order_shipping_method), "method_id") : '';
                if ($originele_method) {
                    $originele_method_e = explode('_', $originele_method);
                    if (count($originele_method_e) > 3) {
                        $originele_method = $originele_method_e[0] . '_' . $originele_method_e[1] . '_' . $originele_method_e[2];
                    }
                    $afhaalpunt_methodes = array('parcelpro_postnl_pakjegemak', 'parcelpro_dhl_parcelshop', 'parcelpro_homerr_direct2shop_s', 'parcelpro_homerr_direct2shop_m', 'parcelpro_homerr_direct2shop_l', 'parcelpro_dpd_337', 'parcelpro_intrapost_parcelshop', 'parcelpro_viatim_parcelshop_normal', 'parcelpro_viatim_parcelshop_heavy');

                    foreach ($afhaalpunt_methodes as $string) {
                        if (strpos($string, $shipping_method) !== false) {
                            $shipping_method_nieuw_is_afhaalpunt = true;
                        }
                        if (strpos($string, $originele_method) !== false) {
                            $shipping_method_origineel_is_afhaalpunt = true;
                        }
                    }//ADRES AANPASSEN:
                    if (!$shipping_method_nieuw_is_afhaalpunt && $shipping_method_origineel_is_afhaalpunt) {
                        update_post_meta($order_id, '_shipping_address_1', $order->get_billing_address_1());
                        update_post_meta($order_id, '_shipping_address_2', $order->get_billing_address_2());
                        update_post_meta($order_id, '_shipping_company', $order->get_billing_company());
                        update_post_meta($order_id, '_shipping_first_name', $order->get_billing_first_name());
                        update_post_meta($order_id, '_shipping_last_name', $order->get_billing_last_name());
                        update_post_meta($order_id, '_shipping_city', $order->get_billing_city());
                        update_post_meta($order_id, '_shipping_postcode', $order->get_billing_postcode());
                        update_post_meta($order_id, '_shipping_country', $order->get_billing_country());
                        update_post_meta($order_id, '_shipping_email', $order->get_billing_email());
                        update_post_meta($order_id, '_shipping_phone', $order->get_billing_phone());
                    }
                }

                if (key($order_shipping_method) && $title) {  //ALS ER AL EEN SHIPPING METHOD IS, DEZE AANPASSEN, ANDERS TOEVOEGEN
                    wc_update_order_item(key($order_shipping_method), array("order_item_name" => $title, "order_item_type" => "shipping"));
                    wc_update_order_item_meta(key($order_shipping_method), "method_id", $type_id ? "parcelpro_" . $shipping_method . '_' . $type_id : "parcelpro_" . $shipping_method);
                } else {
                    $item_id = wc_add_order_item($order_id, array('order_item_name' => $title, 'order_item_type' => 'shipping'));
                    if ($item_id) {
                        wc_update_order_item_meta($item_id, "method_id", $type_id ? "parcelpro_" . $shipping_method . '_' . $type_id : "parcelpro_" . $shipping_method);
                    }
                }

                if (strpos($redirect, 'post.php') !== false) {
                    $redirect .= '&action=edit';
                }
                if ($package_count) {
                    update_post_meta($order_id, '_parcelpro_package', $package_count);
                }
                if ($redirect) {
                    wp_redirect($redirect);
                }
                exit;
        }
    }

    /**
     * Exports a given order to Parcel Pro
     *
     * @param $order_id
     * @since    1.0.0
     *
     */
    public function export_order($order_id)
    {
        $data = $this->format_order_data($order_id);

        $response = json_decode($this->api->post_zending($data), true);

        $order = wc_get_order($order_id);

        if (!isset($response['level']) && $response) {
            update_post_meta($order_id, '_parcelpro_status', 1);
            update_post_meta($order_id, '_parcelpro_id', $response['Id']);
            update_post_meta($order_id, '_parcelpro_label', $response['LabelUrl']);
            update_post_meta($order_id, '_parcelpro_track', $response['Barcode']);
            update_post_meta($order_id, '_parcelpro_track_url', $response['TrackingUrl']);
            update_post_meta($order_id, '_parcelpro_track_vervoerder', strtolower($response['Carrier']));
            $order->add_order_note($response['Barcode']);
        }

        //aanpassen van order status
        $allowed_export = $this->settings['export__update'];
        $set_to_status = $this->settings['export_update_to_state'];
        if ($allowed_export == 'yes' && $set_to_status) {
            $order->update_status($set_to_status);
        }
    }

    /**
     * Formats the order data of a specific order
     *
     * @param $order_id
     *
     * @return array
     * @since    1.0.0
     *
     */
    public function format_order_data($order_id)
    {
        if ($order_id <= 0) {
            return null;
        }
        $order = wc_get_order($order_id);
        $shipping = current($order->get_shipping_methods());

        $package_count = ($count = get_post_meta($order_id, '_parcelpro_package', true)) ? $count : 1;
        $shipping_method = $shipping ? $shipping['method_id'] : null;
        $orderitemsdata = $this->getOrderItems($order->get_items());

        //MAATWERK MET SERVICE PUNTEN
        $rates = WC()->shipping()->get_shipping_methods();
        $parcelprorates = isset($rates['parcelpro_shipping']) ? $rates['parcelpro_shipping'] : null;
        if ($parcelprorates) {
            $parcelprorates = isset($parcelprorates->settings['services']) ? $parcelprorates->settings['services'] : null;
        }
        $maatwerk = false;
        $title = null;
        if ($parcelprorates) {
            foreach ($parcelprorates as $rate) {
                foreach ($rate as $row) {
                    if (is_scalar($row)) {
                        continue;
                    }
                    if (strpos($shipping_method, $rate['id']) !== false) {
                        if (isset($row['servicepunt']) && ($row['servicepunt'] != null || $row['servicepunt'] != 'off')) {
                            $maatwerk = true;
                        }
                        $title = isset($row['method-title']) ? $row['method-title'] : $shipping_method;
                    }
                }
            }
        }

        $productShippingClasses = array_unique(array_map(function ($product) {
            return $product['product_shipping_class'];
        }, $orderitemsdata['products']));

        $user = new WP_User($order->get_customer_id());

        $data = array(
            'orderNR' => $order->get_order_number() ? $order->get_order_number() : null,
            'woo_parcel_pro_nr' => $this->version,
            'service_punt' => $maatwerk,
            'increment_id' => $order_id,
            'order' => $order,
            'items' => $orderitemsdata['products'],
            'unique_items' => count($orderitemsdata['products']),
            'unique_product_shipping_class' => count($productShippingClasses) === 1 ? reset($productShippingClasses) : false,
            'billing_address' => array(
                'firstname' => $order->get_billing_first_name(),
                'lastname' => $order->get_billing_last_name(),
                'company' => $order->get_billing_company(),
                'address_1' => $order->get_billing_address_1(),
                'address_2' => $order->get_billing_address_2(),
                'city' => $order->get_billing_city(),
                'state' => $order->get_billing_state(),
                'postcode' => $order->get_billing_postcode(),
                'country_id' => $order->get_billing_country(),
                'email' => $order->get_billing_email(),
                'telephone' => $order->get_billing_phone(),
            ),
            'shipping_address' => array(
                'firstname' => $order->get_shipping_first_name(),
                'lastname' => $order->get_shipping_last_name(),
                'company' => $order->get_shipping_company(),
                'address_1' => $order->get_shipping_address_1(),
                'address_2' => $order->get_shipping_address_2(),
                'city' => $order->get_shipping_city(),
                'state' => $order->get_shipping_state(),
                'postcode' => $order->get_shipping_postcode(),
                'country_id' => $order->get_shipping_country(),
                'email' => $order->get_billing_email(),
                'telephone' => $order->get_billing_phone(),
            ),
            'totalweight' => $orderitemsdata['total_weight'],
            'subtotal' => $order->get_subtotal(),
            'subtotal_incl_tax' => $order->get_subtotal() + $order->get_total_tax(),
            'grand_total' => $order->get_total(),
            'shipping_method' => $shipping_method,
            'shipping_title' => $title,
            'created_at' => $order->get_date_created(),
            'aantal_pakketten' => $package_count,
            'klant_email' => $user->user_email
        );

        $include_note = $this->settings['customer_note_inhoud'];
        if ($include_note == 'yes') {
            $data = array_merge($data, ['note' => $order->get_customer_note()]);
        }

        $data = apply_filters('parcelpro_format_order_data', $data, $order_id);

        return $data;
    }

    public function getOrderItems($param)
    {

        $products = array();
        $total_weight = 0;
        foreach ($param as $item_id => $item_obj) {
            if (isset($item_obj) && ($item_obj->get_variation_id() != null)) {
                $product = wc_get_product($item_obj->get_variation_id());
            } else {
                $product = wc_get_product($item_obj->get_product_id());
            }
            if (!$product) {
                continue;
            }
            // Get SKU
            $tmp_array = $item_obj->get_data();
            $tmp_array['sku'] = $product->get_sku();
            $tmp_array['unit_weight'] = wc_get_weight($product->get_weight(), 'kg', get_option('woocommerce_weight_unit'));
            $total_weight += (float)$tmp_array['quantity'] * (float)wc_get_weight($product->get_weight(), 'kg', get_option('woocommerce_weight_unit'));
            $tmp_array['unit_height'] = wc_get_dimension((float)$product->get_height(), 'cm', get_option('woocommerce_dimension_unit'));
            $tmp_array['unit_width'] = wc_get_dimension((float)$product->get_width(), 'cm', get_option('woocommerce_dimension_unit'));
            $tmp_array['unit_length'] = wc_get_dimension((float)$product->get_length(), 'cm', get_option('woocommerce_dimension_unit'));
            $tmp_array['product_shipping_class'] = $product->get_shipping_class();

            array_push($products, $tmp_array);
        }
        return ['products' => $products, 'total_weight' => $total_weight];
    }
}
