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
<script type="text/javascript">
    jQuery( function() {
        jQuery( "#accordion" ).accordion({
            collapsible: true,
            animate : false,
            heightStyle: "content",
            active: 0
        });
    } );
</script>
<tr valign="top" id="service_order">
    <th scope="row" class="titledesc">Volgorde voor het tonen van de verzendopties</th>
    <td class="forminp" colspan="12" style="padding-right: 46px">
        <p>Met onderstaande tabel is het mogelijk om de volgorde van de verschillende geactiveerde verzendmethodes in te stellen.</p>
        <table id="sortable" class="wc_gateways widefat" style="margin-top: 15px;">
            <thead>
            <tr>
                <th class="sort">Sort.</th>
                <th class="service">Verzendmethode</th>
                <th class="carrier">Vervoerder</th>
            </tr>
            </thead>
            <tbody class="ui-sortable">
            <?php
            if ($this->custom_services && !is_null($this->custom_services[ current(array_keys($this->custom_services)) ])) {
                $services = array_key_exists('order', $this->custom_services[ current(array_keys($this->custom_services)) ]) ? $this->service_sort($this->custom_services, 'order') : $this->custom_services;

                foreach ($services as $service_key => $service) {
                    foreach ($this->services as $carrier_name => $carrier) {
                        $carrier_parts = explode('_', $carrier_name);
                        if ($carrier_parts[0] === "Maatwerk") {
                            $carrier_name = 'dienst ' . $carrier_parts[1];
                        }

                        if (!array_key_exists('id', $service)) {
                            continue;
                        }
                        if (array_key_exists($service['id'], $carrier)) { ?>
                              <tr>
                                  <td style="background: whitesmoke; " width="1%;" class="sort ui-sortable-handle"><span style="width: 100%"><></span><input type="hidden" name="service_order[]" value="<?php echo esc_attr($service['id']) ?>"></td>
                                  <td class="service"><?php echo $carrier[$service['id']] ?></td>
                                  <td class="carrier"><?php echo $carrier_name ?></td>
                              </tr>
                        <?php }
                    }
                }
            }
            ?>
            </tbody>
        </table>
    </td>
</tr>
<tr valign="top" id="service_options">
    <td class="titledesc" colspan="12" style="padding-left:0;">
        <div class="noStyleAccordion" id="accordion">
        <?php
        $countries_obj = new WC_Countries();
        $countries = $countries_obj->__get('countries');
        $verzend_methodes =  $this->api->types_key_values();
        foreach ($this->services as $carrier_name => $carrier) {
            $carrier_parts = explode('_', $carrier_name);

            if ($carrier_parts[0] === "Maatwerk") {
                $carrier_name = 'dienst ' . $carrier_parts[1];
            }
            ?>
            <h3>Verzendmethodes van <?php echo $carrier_name; ?></h3>
            <div>
                <p>Met onderstaande tabellen is het mogelijk om de verschillende verzendmethodes van <?php echo $carrier_name; ?> in te stellen.</p>
            <?php
            foreach ($carrier as $key => $value) {
                    $id = $key;
                    $id_matches = explode('_', $key);

                    $type = $value;
                    $carrier = $carrier_name;
                ?>
                <h4><?php if ($carrier_parts[0] !== "Maatwerk") {
                    echo $carrier_name . ' ' . $type;
                    } else {
                        echo $carrier_name;
                    } ?>:</h4>
                <table class="parcelpro_rules widefat">
                    <thead>
                    <tr>
                        <th class="remove">&nbsp;</th>
                        <?php if ($id_matches[1] == 'buitenland' || $id_matches[0] == 'maatwerk') {  ?>
                            <th class="country">Country <span class="woocommerce-help-tip parcelpro_tip" data-tip="Land waar order naar wordt verzonden."></span></th>
                        <?php } ?>
                        <?php if ($id_matches[0] == 'maatwerk') { ?>
                            <th>Verzendmethode<span class="woocommerce-help-tip parcelpro_tip" data-tip="De verzendmethode voor het Parcel Pro systeem. Zorg ervoor dat deze per dienst het zelfde is. "></span></th>
                        <?php } ?>
                        <th>Method Title <span class="woocommerce-help-tip parcelpro_tip" data-tip="Titel van de verzendmethode voor de klanten in de checkout."></span></th>
                        <th>Min Weight <span class="woocommerce-help-tip parcelpro_tip" data-tip="Minimum gewicht van een order."></span></th>
                        <th>Max Weight <span class="woocommerce-help-tip parcelpro_tip" data-tip="Maximum gewicht van een order."></span></th>
                        <th>Min Total <span class="woocommerce-help-tip parcelpro_tip" data-tip="Minimum prijs van een order."></span></th>
                        <th>Max Total <span class="woocommerce-help-tip parcelpro_tip" data-tip="Maximum prijs van een order."></span></th>
                        <th>Price <span class="woocommerce-help-tip parcelpro_tip" data-tip="Prijs voor de verzendmethode aan de hand van ingevoerde variabele."></span></th>
                        <?php if ($id_matches[0] == 'maatwerk') { ?>
                            <th>Servicepunt <span class="woocommerce-help-tip parcelpro_tip" data-tip="Als een van deze rijen als servicepunten is aangevinkt, wordt de pop up geopend voor deze verzendmethode. WERKT NIET VOOR ALLE VERVOERDERS!"></span></th>
                        <?php } ?>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $rules = array_key_exists(( $id ), $this->custom_services) ? $this->custom_services[ $id ] : null;
                    if (is_array($rules)) {
                        foreach ($rules as $rule_nr => $rule) {
                            if (in_array($rule_nr, array( 'id', 'order' ))) {
                                continue;
                            }
                            ?>
                            <tr id="parcelpro_rule">
                                <td><button class="btn btn-danger delete" type="button">×</button></td>
                                <?php if ($id_matches[1] == 'buitenland' || $id_matches[0] == 'maatwerk') {
                                    ?><td style="width=100%">
                                    <?php
                                        woocommerce_form_field('parcelpro_shipping_settings[' . $id . '][' . $rule_nr . '][country]', array(
                                            'type'    => 'select',
                                            'default' => $rule[ 'country' ],
                                            'class'   => array('landdrop'),
                                            'options' => $countries,
                                        ));
                                    ?>
                                    </td>
                                <?php } ?>
                                <?php if ($id_matches[0] == 'maatwerk') {
                                    ?><td style="width=100%">
                                    <?php
                                    woocommerce_form_field('parcelpro_shipping_settings[' . $id . '][' . $rule_nr . '][type-id]', array(
                                        'type'    => 'select',
                                        'default' => $rule[ 'type-id' ],
                                        'options' => $verzend_methodes,
                                    ));
                                    ?>
                                    </td>
                                <?php } ?>
                                <td><input type="text" name="parcelpro_shipping_settings[<?php echo $id; ?>][<?php echo $rule_nr ?>][method-title]" value="<?php ( array_key_exists(('method-title'), $rule) ) ? ( $rule[ 'method-title' ] ) ? print( $rule[ 'method-title' ] ) : print( $carrier_name . ' ' . $type ) : print( $carrier_name . ' ' . $type ); ?>"/></td>
                                <td><input type="text" name="parcelpro_shipping_settings[<?php echo $id; ?>][<?php echo $rule_nr ?>][min-weight]" value="<?php ( $rule[ 'min-weight' ] ) ? print( $rule[ 'min-weight' ] ) : print( 0 ); ?>"/></td>
                                <td><input type="text" name="parcelpro_shipping_settings[<?php echo $id; ?>][<?php echo $rule_nr ?>][max-weight]" value="<?php ( $rule[ 'max-weight' ] ) ? print( $rule[ 'max-weight' ] ) : print( 0 ); ?>"/></td>
                                <td><input type="text" name="parcelpro_shipping_settings[<?php echo $id; ?>][<?php echo $rule_nr ?>][min-total]" value="<?php ( $rule[ 'min-total' ] ) ? print( $rule[ 'min-total' ] ) : print( 0 ); ?>"/></td>
                                <td><input type="text" name="parcelpro_shipping_settings[<?php echo $id; ?>][<?php echo $rule_nr ?>][max-total]" value="<?php ( $rule[ 'max-total' ] ) ? print( $rule[ 'max-total' ] ) : print( 0 ); ?>"/></td>
                                <td><input type="text" name="parcelpro_shipping_settings[<?php echo $id; ?>][<?php echo $rule_nr ?>][price]" value="<?php ( $rule[ 'price' ] ) ? print( $rule[ 'price' ] ) : print( 0 ); ?>"/></td>
                                <?php if ($id_matches[0] == 'maatwerk') {
                                    ?>
                                    <td ><input type="checkbox" name="parcelpro_shipping_settings[<?php echo $id; ?>][<?php echo $rule_nr ?>][servicepunt]" <?php ( $rule[ 'servicepunt' ]  ) ? print('checked') : null; ?>/></td>

                                <?php } ?>
                            </tr>
                                <?php
                        }
                    }
                    ?>
                    </tbody>
                    <tfoot>
                    <tr>
                        <th colspan="9"><input type="button" class="button addrule" name="<?php echo $id; ?>" value="+ Add Rule" style="margin-right: 10px"/></th>
                    </tr>
                    </tfoot>
                </table>
            <?php } ?>

            </div>
        <?php } ?>
    </div>
    <input id="addcarrier" type="button" class="button addcarrier" name="nieuwe_dienst" value="+ Nieuwe Dienst" style="margin-right: 10px; margin-top: 30px"/>
    </td>

</tr>
<tr id="template_rule" style="display: none">
    <td><button class="btn btn-danger delete" type="button">×</button></td>
    <td>
        <?php
        woocommerce_form_field('', array(
            'id'      => 'parcelpro_select',
            'class'   => array('landdrop'),
            'type'    => 'select',
            'default' => 'GB',
            'options' => $countries,
        ));
        ?>
    </td>
</tr>
<tr id="template_rule_maatwerk" style="display: none">
    <td><button class="btn btn-danger delete" type="button">×</button></td>
    <td>
        <?php
        woocommerce_form_field('', array(
            'id'      => 'parcelpro_select',
            'class'   => array('landdrop'),
            'type'    => 'select',
            'default' => 'GB',
            'options' => $countries,
        ));
        ?>
    </td>
    <td>
        <?php
        woocommerce_form_field('', array(
            'type'    => 'select',
            'class'   => array('verzenddrop'),
            'default' => 0,
            'options' => $this->api->types_key_values(),
        ));
        ?>
    </td>
</tr>
