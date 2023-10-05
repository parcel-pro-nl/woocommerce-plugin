<?php

/**
 * Provide a public-facing view for the plugin
 *
 * This file is used to markup the public-facing aspects of the plugin.
 *
 * @link       http://www.parcelpro.nl/
 * @since      1.0.0
 *
 * @package    Parcelpro
 * @subpackage Parcelpro/public/partials
 */

?>
<input class="parcelpro-checkout-form" type="hidden" id="parcelpro_afhaalpunt" name="parcelpro_afhaalpunt" value=""/>
<input class="parcelpro-checkout-form" type="hidden" id="parcelpro_company" name="parcelpro_company" value=""/>
<input class="parcelpro-checkout-form" type="hidden" id="parcelpro_first_name" name="parcelpro_first_name" value=""/>
<input class="parcelpro-checkout-form" type="hidden" id="parcelpro_last_name" name="parcelpro_last_name" value=""/>
<input class="parcelpro-checkout-form" type="hidden" id="parcelpro_address_1" name="parcelpro_address_1" value=""/>
<input class="parcelpro-checkout-form" type="hidden" id="parcelpro_address_2" name="parcelpro_address_2" value=""/>
<input class="parcelpro-checkout-form" type="hidden" id="parcelpro_postcode" name="parcelpro_postcode" value=""/>
<input class="parcelpro-checkout-form" type="hidden" id="parcelpro_city" name="parcelpro_city" value=""/>
<input class="parcelpro-checkout-form" type="hidden" id="parcelpro_country" name="parcelpro_country" value=""/>
<script type="text/javascript">
    function set_url() {
        var url = "https://login.parcelpro.nl/plugin/afhaalpunt/parcelpro-kiezer.php";
        url += "?";
        url += "id=" + <?php echo $this->settings[ 'login_id' ] ?>;
        url += "&postcode=" + jQuery( "#billing_postcode" ).val();
        url += "&adres=" + jQuery( "#billing_address_1" ).val();
        url += "&origin=" + window.location.protocol + "//" + window.location.hostname;
        url += "&country=" + jQuery( "#billing_country" ).val()
        url += '&software=woocommerce';
        return url
    }
    jQuery(document).ready(function($) {
        $( document ).ready(function(){
            var targetElement = $('form[name*="checkout"]');
            $('.parcelpro-checkout-form').detach().appendTo(targetElement);
        });
    });
</script>
