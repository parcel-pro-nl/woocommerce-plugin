<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       http://www.parcelpro.nl/
 * @since      1.1.0
 *
 * @package    Parcelpro
 * @subpackage Parcelpro/admin/partials
 */

//  TODO: Functionaliteiten voor extra pakketten toevoegen aan /api/woocommerce/order-created.php
require_once(ABSPATH . 'wp-admin/admin.php');

wp_enqueue_style('colors');
wp_enqueue_style('media');
wp_enqueue_style($this->plugin_name, plugin_dir_url(__DIR__) . 'css/parcelpro-admin.css', array(), $this->version, 'all');
wp_enqueue_script($this->plugin_name, plugin_dir_url(__DIR__) . 'js/parcelpro-admin.js', array( 'jquery' ), $this->version, false);

do_action('admin_print_styles');
do_action('admin_print_scripts');

$package_apply_link = wp_nonce_url(admin_url('edit.php?&action=parcelpro-package-apply&order_id=' . $order_id), 'parcelpro-package-apply');

?>
<head>
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.0/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://code.jquery.com/ui/1.12.0/jquery-ui.js"></script>

    <script type="text/javascript">
        $( function() {
            $( "#spinner" ).spinner({
                spin: function( event, ui ) {
                    if ( ui.value > 10 ) {
                        $( this ).spinner( "value", 1 );
                        return false;
                    } else if ( ui.value < 1 ) {
                        $( this ).spinner( "value", 10 );
                        return false;
                    }
                }
            });
        } );
    </script>
</head>
<body class="package-selector">
    <div class="package-header">
        <h1 class="package-titel">Pakket Opties voor Order #<?php echo $order_id; ?></h1>
    </div>
    <div class="package-content">
        <p class="package-text"><b>Overschrijf de gekozen / standaard opties:</b></p>
        <p>
            <label class="package-label" for="spinner">Verzendmethode:&nbsp;</label>
            <select id="shipping_method">
                <?php foreach ($options as $key => $val) { ?>
                    <?php
                        $selected = false;
                        $disabled = false;

                    if ($key == "postnl_pakjegemak" || $key == "dhl_parcelshop") {
                        $disabled = true;
                    }
                    foreach ($val as $k => $v) {
                        if (is_array($v)) {
                            $title = $v['method-title'];
                            $id = $val['id'];
                            if (strpos(strtolower($key), "maatwerk") !== false) {
                                $id = $id . '_' . $v[ 'type-id' ];
                            }
                            if (strpos($shipping_method, 'maatwerk') !== false) {
                                if (strpos($shipping_method, $id) !== false) {
                                    $selected = true;
                                }
                            } else {
                                if ($id == substr($shipping_method, 10)) {
                                    $selected = true;
                                }
                            }
                            ?>

                            <?php
                        }
                    }
                    ?>
                    <option value="<?php echo $id ?>" <?php print($selected ?  "selected" : '') ?> <?php print($disabled ?  "disabled" : '') ?>><?php echo $title ?></option>
                <?php } ?>
            </select>
        </p>
        <p>
            <label class="package-label" for="spinner">Aantal pakketten:&nbsp;</label>
            <input id="spinner" name="value" value="<?php echo $package_count; ?>">
        </p>
    </div>
    <div class="package-actions">
        <button class="btn btn-cancel">Annuleren</button>
        <button class="btn btn-apply">Bevestigen</button>
        <input id="apply-url" style="display: none" value="<?php echo $package_apply_link ?>">
    </div>
</body>
