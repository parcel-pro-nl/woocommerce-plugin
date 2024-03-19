<?php

/**
 * Used to communicate with the API of Parcel Pro.
 *
 * @link       http://www.parcelpro.nl/
 * @since      1.0.0
 *
 * @package    Parcelpro
 * @subpackage Parcelpro/includes
 */

/**
 * Used to communicate with the API of Parcel Pro.
 *
 * Implements the methods to communicate with the API of Parcel Pro.
 * Makes it possible to use the functionality's of the Parcel Pro Api.
 *
 * @since      1.0.0
 * @package    Parcelpro
 * @subpackage Parcelpro/includes
 * @author     Ruben van den Ende <ict@parcelpro.nl>
 */
class ParcelPro_API
{
    protected $api_url     = 'https://login.parcelpro.nl';
    protected $webhook_url = 'https://login.parcelpro.nl/api/woocommerce/order-created.php';
    protected $login_id;
    protected $api_id;

    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     */
    public function __construct()
    {
        $this->init();
    }

    /**
     * Initializes variables.
     *
     * @since    1.0.0
     */
    public function init()
    {
        $settings = get_option('woocommerce_parcelpro_shipping_settings');
        $this->login_id = $settings[ 'login_id' ] ?? null;
        $this->api_id = $settings[ 'api_id' ] ?? null;
    }

    /**
     * Function to get the available shipping types from Parcel Pro.
     *
     * @since    1.0.0
     * @return mixed
     */
    public function get_type($forceUpdate = false)
    {
        if (!get_option('woocommerce_parcelpro_shipping_types_updated')) {
            update_option('woocommerce_parcelpro_shipping_types_updated', current_time('mysql'));
        }

        $lastUpdated = get_option('woocommerce_parcelpro_shipping_types_updated');

        if ((strtotime($lastUpdated) < strtotime('-1 days') ) || $forceUpdate) {
            $hash = $this->create_hash($this->login_id . $this->current_time());
            $headers = array(
              'GebruikerId' => $this->login_id,
              'Datum'       => $this->current_time(),
              'HmacSha256'  => $hash,
            );
            $curl = $this->setup_curl($this->api_url . '/api/type.php' . '?' . http_build_query($headers, '', '&'));

            $response = curl_exec($curl);
            $code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
            curl_close($curl);

            if ($code === 200) {
                update_option('woocommerce_parcelpro_shipping_types', $response);
                update_option('woocommerce_parcelpro_shipping_types_updated', current_time('mysql'));
            }
        } else {
            $response = get_option('woocommerce_parcelpro_shipping_types');
        }

        return $response;
    }

    /**
     * Function to hash the data to sha256.
     *
     * @since    1.0.0
     * @param $data
     * @return string
     */
    public function create_hash($data)
    {
        if ($this->api_id) {
            return hash_hmac('sha256', $data, $this->api_id);
        }
    }

    /**
     * Function to get the right format of date and time.
     *
     * @since    1.0.0
     * @return string
     */
    public function current_time()
    {
        return (new DateTime('now'))->format('Y-m-d H:i:s');
    }

    /**
     * Function to set the default curl options.
     *
     * @since    1.0.0
     * @param $url
     * @return resource
     */
    public function setup_curl($url)
    {
        $curl = curl_init();

        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

        return $curl;
    }

    /**
     * Function to register a order at Parcel Pro as shipping.
     *
     * @since    1.0.0
     * @param $parameters
     * @return mixed
     */
    public function post_zending($parameters)
    {
        $json = json_encode($parameters);

        $hash = $this->create_hash($json);
        $curl = $this->setup_curl($this->webhook_url);
        $referer = get_site_url();

        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            sprintf('X-Wc-Webhook-Id: %s', $this->login_id),
            sprintf('X-Wc-Webhook-Signature: %s', $hash),
            sprintf('X-Wc-Webhook-Referer: %s', $referer),
        ));

        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $json);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

        $response = curl_exec($curl);
        curl_close($curl);

        return $response;
    }

    /**
     * Function to get a list of shipments
     *
     * @return mixed
     * @since    1.5.47
     */
    public function shipments($id = null)
    {
        $hash = $this->create_hash($this->login_id . $this->current_time());
        $headers = array(
            'GebruikerId' => $this->login_id,
            'Datum'       => $this->current_time(),
            'HmacSha256'  => $hash,
            'ZendingId' => $id
        );
        $curl = $this->setup_curl($this->api_url . '/api/zendingen.php' . '?' . http_build_query($headers, '', '&'));
        $response = curl_exec($curl);
        curl_close($curl);

        return $response;
    }

    /**
     * Function to get a label of a specific order.
     *
     * @since    1.0.0
     * @param $zending_id
     * @return mixed
     */
    public function get_label($zending_id)
    {
        return $this->get_url_label($zending_id);
    }

    public function get_url_label($zending_id)
    {
        $hash = $this->create_hash($this->login_id . $zending_id);
        $headers = array(
            'GebruikerId' => $this->login_id,
            'ZendingId'   => $zending_id,
            'HmacSha256'  => $hash,
        );
        $url =  $this->api_url . '/api/label.php' . '?' . http_build_query($headers, '', '&') ;
        return $url;
    }

    /**
     * Function to send the choice of pickup point to Parcel Pro.
     *
     * @since    1.0.0
     * @param $data
     * @return mixed
     */
    public function post_afhaalpunt_keuze($data)
    {
        $hash = $this->create_hash($this->login_id . $this->current_time());
        $curl = $this->setup_curl($this->api_url . '/api/afhaalpunt_keuze.php');

        $parameters = array(
            "GebruikerId" => $this->login_id,
            "Datum"       => $this->current_time(),
            "HmacSha256"  => $hash,
            "Postcode"    => $data[ 'billing_postcode' ],
            "Nummer"      => $this->get_housenumber($data[ 'billing_address_1' ]),
            "Ipadres"     => $this->get_user_ip(),
            "Ordernummer" => $data[ 'order_id' ],
            "Carrier"     => $data[ 'parcelpro_afhaalpunt' ],
            "LocationId"  => $data[ 'parcelpro_company' ],
            'software'    => 'woocommerce'
        );

        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $parameters);

        $response = curl_exec($curl);
        curl_close($curl);

        return $response;
    }

    /**
     * Function to get the house number and address of a sting.
     *
     * @since    1.0.0
     * @param $adres
     * @return mixed
     */
    public function get_housenumber($adres)
    {
        preg_match('/^(\d*[\wäöüß\d \'\-\.]+)[,\s]+(\d+)\s*([\wäöüß\d\-\/]*)$/i', $adres, $match);

        return $match[ 2 ];
    }

    /**
     * Retrieves and returns users ip adres.
     *
     * @since    1.0.0
     * @return mixed
     */
    public function get_user_ip()
    {
        if (!empty($_SERVER[ 'HTTP_CLIENT_IP' ])) {
            $ip = $_SERVER[ 'HTTP_CLIENT_IP' ];
        } elseif (!empty($_SERVER[ 'HTTP_X_FORWARDED_FOR' ])) {
            $ip = $_SERVER[ 'HTTP_X_FORWARDED_FOR' ];
        } else {
            $ip = $_SERVER[ 'REMOTE_ADDR' ];
        }

        return apply_filters('wpb_get_ip', $ip);
    }

    /**
     * Loads a list of available types that a user can add to their woocommerce environment. Based on their active contracts.
     * through the api types.
     *
     * @since    1.5.0
     * @param void
     * @return array jsonarray
     */
    public function types_key_values()
    {
        $result_records = [];
        $types = json_decode($this->get_type(), true);
        $result_records['0'] = 'Niet Aanmelden Bij Parcel Pro';
        if (isset($types['level']) && strtolower($types['level']) == 'error') {
            return [];
        }

        foreach ($types as $type) {
            if (is_scalar($type)) {
                continue;
            }
            if (array_key_exists('Id', $type) && array_key_exists('Type', $type)) {
                $result_records[$type['Id']] = $type['Type'];
            }
        }


        return(($result_records));
    }

    /**
     * @param string $carrier The carrier name.
     * @param DateTimeInterface $dateTime The date on which the package will be handed over to the carrier.
     * @param $postcode string The postal code of the package destination.
     *
     * @return DateTimeImmutable|false
     */
    public function getDeliveryDate(string $carrier, \DateTimeInterface $dateTime, string $postcode)
    {
        if (!$postcode) {
            return false;
        }
        $postcode = str_replace(' ', '', $postcode);

        $date = $dateTime->format('Y-m-d');

        $query = http_build_query([
            'Startdatum' => $date,
            'Postcode' => $postcode,
            'GebruikerId' => $this->login_id,
            'Map' => true,
        ]);

        $curlHandle = curl_init();
        curl_setopt_array($curlHandle, [
            CURLOPT_URL => $this->api_url . '/api/v3/timeframes.php?' . $query,
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
                'Digest: ' . hash_hmac(
                    "sha256",
                    sprintf('GebruikerId=%sPostcode=%sStartdatum=%s', $this->login_id, $postcode, $date),
                    $this->api_id
                ),
            ],
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HEADER => false,
            CURLOPT_RETURNTRANSFER => true,
        ]);

        $responseBody = curl_exec($curlHandle);
        $responseCode = curl_getinfo($curlHandle, CURLINFO_RESPONSE_CODE);

        curl_close($curlHandle);

        if ($responseCode !== 200) {
            $logger = wc_get_logger();
            if ($logger) {
                $logger->error(sprintf(
                    'Failed to get expected delivery date, response code %s, body:\n%s',
                    $responseCode,
                    $responseBody
                ));
            }
            return false;
        }

        $responseJson = json_decode($responseBody, true);
        $rawDate = false;

        // Lookup the expected date in a case-insensitive way, since a carrier may be "Postnl" or PostNL".
        foreach ($responseJson as $api_carrier => $carrier_times) {
            if (strtolower($api_carrier) === strtolower($carrier)) {
                $rawDate = $carrier_times['Date'];
            }
        }

        if (!$rawDate) {
            $logger = wc_get_logger();
            if ($logger) {
                $logger->error(sprintf(
                    'Failed to get expected delivery date, body:\n%s',
                    $responseBody
                ));
            }
            return false;
        }

        return \DateTimeImmutable::createFromFormat('Y-m-d', $rawDate);
    }
}
