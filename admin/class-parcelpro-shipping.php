<?php

/**
 * The shipping-settings-specific functionality of the plugin.
 *
 * @link       http://www.parcelpro.nl/
 * @since      1.0.0
 *
 * @package    Parcelpro
 * @subpackage Parcelpro/admin
 */

/**
 * The shipping-settings-specific functionality of the plugin.
 *
 * Defines the shipping method name, version, shipping prices
 * and creates/process the shipping settings.
 *
 * @package    Parcelpro
 * @subpackage Parcelpro/admin
 * @author     Ruben van den Ende <ict@parcelpro.nl>
 */
class ParcelPro_Shipping extends WC_Shipping_Method
{
    private $services;
    private $found_services;
    private $custom_services;

    /** @var ParcelPro_API $api */
    private $api;
    public $id;
    public $method_title;
    public $method_description;
    public $enabled;
    public $title;
    public $availability;
    public $countries;

    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     */
    public function __construct()
    {
        $this->register_hook_callbacks();
        $this->init();
    }

    /**
     * Register callbacks for actions and filters.
     *
     * @since    1.0.0
     */
    public function register_hook_callbacks()
    {
        add_action('woocommerce_update_options_shipping_parcelpro_shipping', array( $this, 'process_admin_options' ));
        add_action('woocommerce_load_shipping_methods', array( $this, 'load_shipping_methods' ));
    }

    /**
     * Initializes variables.
     *
     * @since    1.0.0
     */
    public function init()
    {
        $this->api = new ParcelPro_API();

        $this->id = 'parcelpro_shipping';
        $this->method_title = PARCELPRO_SHOPSUNITED;
        $this->method_description = 'Afhalen van een bestelling bij een door de klant gekozen afhaalpunt.';

        $this->services = include(plugin_dir_path(__FILE__) . 'data/parcelpro-shipping-settings-services.php');
        require_once(plugin_dir_path(dirname(__FILE__)) . 'includes/class-parcelpro-shipping-method.php');

        $this->enabled = $this->get_option('enabled', 'no');
        $this->title = $this->get_option('title', $this->method_title);
        $this->availability = $this->get_option('availability', 'all');
        $this->countries = $this->get_option('countries', array());
        $this->custom_services = $this->get_option('services', array());
        $this->init_form_fields();
        $this->init_settings();
    }

    /**
     * Initializes form fields.
     *
     * @since    1.0.0
     */
    public function init_form_fields()
    {
        $this->form_fields = include(plugin_dir_path(__FILE__) . 'data/parcelpro-shipping-settings-fields.php');
    }

    public function load_shipping_methods()
    {
        // We have to register a custom shipping method, to ensure the shipping method validation passes.
        // See error code "woocommerce_rest_invalid_shipping_option" in the WooCommerce function `OrderController::validate_selected_shipping_methods`.
        WC()->shipping()->register_shipping_method(new Parcelpro_Shipping_Method());
    }

    /**
     * Adds the section for services form fields.
     *
     * @since    1.0.0
     * @return string
     */
    public function generate_services_html()
    {
        ob_start();

        $this->custom_services = $this->get_option('services', array());
        $this->services = include(plugin_dir_path(__FILE__) . 'data/parcelpro-shipping-settings-services.php');

        include(plugin_dir_path(__FILE__) . 'partials/parcelpro-shipping-settings-tables.php');
        return ob_get_clean();
    }

    /**
     * Validates submitted setting values before they get saved to the database. Invalid data will be overwritten with
     * defaults.
     *
     * @since    1.0.0*
     * @param array $input
     * @return array
     */
    public function validate_services_field($input)
    {
        $services = array();
        $posted_services = $_POST['parcelpro_shipping_settings'] ?? null;
        $posted_services_order = array_key_exists('service_order', $_POST) ? $_POST[ 'service_order' ] : array();
        $order_fix = count($posted_services_order) + 1;

        if (is_array($posted_services)) {
            foreach ($posted_services as $id => $settings) {
                foreach ($settings as $nr => $rule) {
                    $order = (in_array($id, $posted_services_order)) ? array_search($id, $posted_services_order) + 1 : $order_fix++;

                    $rule[ 'min-weight' ] = $this->validate_decimal_field(null, $rule[ 'min-weight' ]);
                    $rule[ 'type-id' ] = isset($rule[ 'type-id' ]) ? $this->validate_decimal_field(null, $rule[ 'type-id' ]) : null;
                    $rule[ 'max-weight' ] = $this->validate_decimal_field(null, $rule[ 'max-weight' ]);
                    $rule[ 'min-total' ] = $this->validate_decimal_field(null, $rule[ 'min-total' ]);
                    $rule[ 'max-total' ] = $this->validate_decimal_field(null, $rule[ 'max-total' ]);
                    $rule[ 'price' ] = $this->validate_price_field(null, $rule[ 'price' ]);
                    $rule[ 'servicepunt' ] = $rule['servicepunt'] ?? null;

                    $services[ $id ]['id'] = $id;
                    $services[ $id ]['order'] = $order;

                    if ($rule['method-title'] == '') {
                        foreach ($this->services as $carrier_name => $carrier) {
                            if (array_key_exists($id, $carrier)) {
                                $rule['method-title'] = $carrier_name . ' ' . $carrier[$id];
                            }
                        }
                    }

                    $services[ $id ][ $nr ] = array(
                        'country'      => $rule['country'] ?? 'NL',
                        'method-title' => $rule['method-title'] ?? null,
                        'type-id'      => $rule['type-id'] ?? 0,
                        'min-weight'   => $rule['min-weight'] ?? null,
                        'max-weight'   => $rule['max-weight'] ?? null,
                        'min-total'    => $rule['min-total'] ?? null,
                        'max-total'    => $rule['max-total'] ?? null,
                        'price'        => $rule['price'] ?? null,
                        'servicepunt'  => ( isset($rule['servicepunt']) ) ?  'on' :  null,
                        'order'        => $order ?? null
                    );
                }
            }

            return $services;
        }
    }

    /**
     * Executes the logic to add a service to the found services.
     *
     * @since    1.0.0
     * @param $service
     * @param $service_title
     * @param $price
     */
    private function prepare_service($service, $service_title, $price, $order, $type_id, $servicepunt): void
    {
        $this->found_services[ $service ][ 'id' ] = 'parcelpro_' . $service;
        $this->found_services[ $service ][ 'label' ] = $service_title;
        $this->found_services[ $service ][ 'cost' ] = ( $price ) ? $price : 0.00;
        $this->found_services[ $service ][ 'order' ] = ( $order ) ? $order : 0.00;
        ($type_id) ?  $this->found_services[ $service ][ 'type-id' ] = ( $type_id ) : null;
        ($servicepunt) ? $this->found_services[$service]['servicepunt'] = $servicepunt : null;
    }

    /**
     * Calculates the shipping cost and adds prepares the services.
     *
     * @since    1.0.0
     * @param $package
     */
    public function calculate_shipping($package = array()): void
    {
        $this->found_services = array();
        $allowed = $this->get_option('availability', '');
        $allowed_countries = $this->get_option('countries', array());
        $country = $package['destination']['country'];
        $weight = 0;
        $price = 0;
        $order = 0;
        if (in_array($country, $allowed_countries) != 0 || $allowed == 'all') {
            foreach ($package[ 'contents' ] as $item_id => $values) {
                if ((!isset($this->settings['coupon_calculate'])) || $this->settings['coupon_calculate'] == 'yes') {
                    $price += $values[ 'line_total' ] + $values[ 'line_tax' ];
                } else {
                    $price += $values[ 'line_subtotal' ] + $values[ 'line_subtotal_tax' ];
                }
                if (!is_numeric($values[ 'data' ]->get_weight())) {
                    $weight += 0 * $values[ 'quantity' ];
                } else {
                    $weight += $values[ 'data' ]->get_weight() * $values[ 'quantity' ];
                }
            }

            foreach ($this->services as $carrier_name => $carrier) {
                $lowercaseCarrier  = strtolower($carrier_name);
                $shipping_time = new DateTimeImmutable();
                $rawCutoffTime = $this->get_option($lowercaseCarrier . '_last_shipping_time');
                $isBeforeCutoffTime = $this->isBeforeLastShippingTime($rawCutoffTime);

                if (!$isBeforeCutoffTime) {
                    $shipping_time = $shipping_time->add(new DateInterval('P1D'));
                }
                // Fetch expected delivery day
                $is_enabled = $this->get_option(
                    $lowercaseCarrier . '_show_delivery_date'
                );
                $formattedDeliveryDate = '';
                if ($is_enabled === 'yes') {
                    $delivery_expected = $this->api->getDeliveryDate(
                        $carrier_name,
                        $shipping_time,
                        $package['destination']['postcode']
                    );

                    if ($delivery_expected) {
                        $formattedDeliveryDate = ' (' . $this->formatDeliveryDate($delivery_expected) . ')';
                    }
                }

                foreach ($carrier as $key => $value) {
                    if (array_key_exists($key, $this->custom_services) && is_array($this->custom_services[ $key ])) {
                        foreach ($this->custom_services[ $key ] as $rule_nr => $rule) {
                            if (in_array($rule_nr, array( 'id', 'order' ))) {
                                continue;
                            }
                            if ($country == $rule[ 'country' ] && $weight <= $rule[ 'max-weight' ] && $weight >= $rule[ 'min-weight' ] && $price <= $rule[ 'max-total' ] && $price >= $rule[ 'min-total' ]) {
                                if (!array_key_exists('order', $this->custom_services[ $key ])) {
                                    $this->custom_services[ $key ][ 'order' ] = $order++;
                                }
                                $maatwerk_type = '';
                                if (array_key_exists('type-id', $rule) && $rule['type-id'] !== '') {
                                    $maatwerk_type = '_' . $rule[ 'type-id' ] ;
                                }

                                $this->prepare_service(
                                    $key . $maatwerk_type,
                                    $rule[ 'method-title' ] . $formattedDeliveryDate,
                                    $rule[ 'price' ],
                                    $this->custom_services[ $key ][ 'order' ],
                                    array_key_exists('type-id', $rule) ? $rule[ 'type-id' ] : null,
                                    isset($rule[ 'servicepunt' ])  ? $rule[ 'servicepunt' ] : null
                                );
                            }
                        }
                    }
                }
            }
        }

        $services = $this->service_sort($this->found_services, 'order');

        foreach ($services as &$service) {
            $this->id = $service['id'];
            $this->add_rate($service);
        }
    }

    private function isBeforeLastShippingTime($rawLastTime): bool
    {
        if (!$rawLastTime) {
            return true;
        }

        try {
            $parsed = new DateTime($rawLastTime);
        } catch (\Exception $e) {
            $logger = wc_get_logger();
            if ($logger) {
                $logger->error(sprintf(
                    'Failed to parse last shipping time (%s): %s',
                    $rawLastTime,
                    $e->getMessage()
                ));
            }
            return true;
        }

        $now = new DateTime();
        return $now < $parsed;
    }

    /**
     * Sorts the found services on the given preferences.
     *
     * @param $records
     * @param $field
     * @param bool $reverse
     *
     * @return array
     *@since    1.2.1
     */
    public function service_sort($records, $field, $reverse = false)
    {
        $hash = array();

        foreach ($records as $record) {
            $hash[$record[$field]] = $record;
        }

        ($reverse) ? krsort($hash) : ksort($hash);

        $records = array();

        foreach ($hash as $record) {
            $records [] = $record;
        }

        return $records;
    }

    private function formatDeliveryDate(\DateTimeInterface $date): string
    {
        $locale = get_locale();
        return \IntlDateFormatter::formatObject($date, 'd MMMM', $locale);
    }
}
