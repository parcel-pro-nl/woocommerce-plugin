(function ($) {
    'use strict';

    $(document).ready(function () {
        $("#order_review").change(function (event) {
            var target = $(event.target);
            if (target.hasClass('shipping_method')) {
                if ($("input[id*='pakjegemak']").is(":checked")) {
                    $('#modal').show();
                    $('#afhaalpunt_frame').attr('src', set_url() + '&carrier=PostNL');
                }
                else if ($("input[id*='dhl_parcelshop']").is(":checked")) {
                    $('#modal').show();
                    $('#afhaalpunt_frame').attr('src', set_url() + '&carrier=DHL');
                }
                else if ($("input[id*='homerr']").is(":checked")) {
                    $('#modal').show();
                    $('#afhaalpunt_frame').attr('src', set_url() + '&carrier=Homerr');
                }
                else if($("input").is(":checked")){
                    var checked_method = $("input:checked[class*=shipping_method]");
                    if(id_like_punten(checked_method.attr('id'))&& checked_method && checked_method.attr('id').includes("parcelpro")){
                        $('#modal').show();
                        $('#afhaalpunt_frame').attr('src', set_url() + '&typeId=' + encodeURI(checked_method.attr('id')));
                    }else{
                        $('#parcelpro_afhaalpunt').val('');
                    }
                }
                else {
                    $('#parcelpro_afhaalpunt').val('');
                }
            }
        });
        $(document).on("click", "input[class='shipping_method']", function () {
            popup_show();
        });
    });

    $(window).on('load', function () {
        popup_show();
    });

    function popup_show() {
        if ($("input[id*='pakjegemak']").is(":checked")) {
            jQuery('#modal').show();
            jQuery('#afhaalpunt_frame').attr('src', set_url() + '&carrier=PostNL');
            return;
        }

        if ($("input[id*='homerr']").is(":checked")) {
            jQuery('#modal').show();
            jQuery('#afhaalpunt_frame').attr('src', set_url() + '&carrier=Homerr');
            return;
        }

        if ($("input[id*='dhl_parcelshop']").is(":checked")) {
            $('#modal').show();
            $('#afhaalpunt_frame').attr('src', set_url() + '&carrier=DHL');
            return;
        }

        if ($("input[id*='intrapost_parcelshop']").is(":checked")) {
            $('#modal').show();
            $('#afhaalpunt_frame').attr('src', set_url() + '&carrier=Intrapost');
            return;
        }

        if ($("input[id*='viatim_parcelshop']").is(":checked")) {
            $('#modal').show();
            $('#afhaalpunt_frame').attr('src', set_url() + '&carrier=Viatim');
            return;
        }

        if ($("input[id*='dpd_337']").is(":checked")) {
            $('#modal').show();
            $('#afhaalpunt_frame').attr('src', set_url() + '&carrier=DPD');
            return;
        }

        if ($("input").is(":checked")) {
            var checked_method = $("input:checked[class*=shipping_method]");

            if(id_like_punten(checked_method.attr('id') ) && checked_method && checked_method.attr('id').includes("parcelpro")){
                $('#modal').show();
                $('#afhaalpunt_frame').attr('src', set_url() + '&typeId=' + encodeURI(checked_method.attr('id')));
            }
        }
    }

    function id_like_punten(id){
        if(!id) return;
        var process  = id.toLowerCase();
        // var types = ['parcelconnect','parcel connect','pakjegemak','afhaalpunt','afhaalpunten','afhalen','parcelshop','ophalen'];
        var return_value = false;
        if(typeof servicepuntmethodes !== 'undefined' && servicepuntmethodes){
            servicepuntmethodes.forEach(function(e){
                if(process.includes(e)){
                    return_value =  true;
                }
            });

        }else{
            console.log("Something went wrong while loading shipping rates with parcel pro pop up enabled!")
        }
        return return_value;
    }

    //DEPRECATED VERSION, USED TO CHECK LABEL BASED ON HARDCODED LABEL NAMES
    function label_like_punten(label){
        var process  = label.toLowerCase();
        var types = ['parcelconnect','parcel connect','pakjegemak','afhaalpunt','afhaalpunten','afhalen','parcelshop','ophalen'];
        var return_value = false;
        types.forEach(function(e){
            if(process.includes(e)){
                return_value =  true;
            }
        });

        return return_value;
    }

    function popup_submit(data) {
        var shippingmethod = $("ul[id='shipping_method']").find('input:checked')[0].id.split('_');

        if ($.inArray( data.Vervoerder.toLowerCase(), shippingmethod ) == -1 && ($.inArray( "maatwerk", shippingmethod ) == -1 )) {
            $('form[name="checkout"]').find('input[name*="parcelpro"]').val('');
            return;
        }

        $('#parcelpro_afhaalpunt').val(true);
        $('#parcelpro_company').val(data.Id);
        $('#parcelpro_first_name').val(data.LocationType);
        $('#parcelpro_last_name').val(data.Name);
        $('#parcelpro_address_1').val(data.Street);
        $('#parcelpro_address_2').val(data.Housenumber + data.HousenumberAdditional);
        $('#parcelpro_postcode').val(data.Postalcode);
        $('#parcelpro_city').val(data.City);
        $('#parcelpro_country').val(data.Countrycode ? data.Countrycode : 'NL');

        if(($.inArray( "maatwerk", shippingmethod ) != -1 )){
            var sm = $("ul[id='shipping_method']").find('input:checked')[0].id;
            var price = $('span', label);
            var label = $('label[for*="'+sm+'"]');
            var priceHtml = price.clone().prop('outerHTML');

            if(priceHtml === undefined) priceHtml = '';
            $('#parcelpro_afhaalpunt').val(data.Vervoerder.toLowerCase());
            $(label).html(data.LocationType + ": " + data.Name + " " + priceHtml);
            return;
        }

        if (data.LocationType.toLowerCase() == "dhl parcelshop") {
            var label = $('label[for*="parcelshop"]');
            console.log(label);
            var price = $('span', label);
            var priceHtml = price.clone().prop('outerHTML');

            if(priceHtml === undefined) priceHtml = '';

            $('#parcelpro_afhaalpunt').val('DHL');
            $(label).html(data.LocationType + ": " + data.Name + " " + priceHtml);
        }

        if (data.LocationType.toLowerCase() == "postnl pakketpunt") {
            var label = $('label[for*="pakjegemak"]');
            var price = $('span', label);
            var priceHtml = price.clone().prop('outerHTML');

            if(priceHtml === undefined) priceHtml = '';

            $('#parcelpro_afhaalpunt').val('PostNL');
            $(label).html(data.LocationType + ": " + data.Name + " " + priceHtml);
        }

        if ($.inArray(data.LocationType.toLowerCase(), ['homerr parcelshop', 'homerr servicepunt', 'homerr buurtpunt']) != -1) {
            //if (data.LocationType.toLowerCase() == "homerr parcelshop") {
            var label = $('label[for*="direct2shop"]');
            var price = $('span', label);
            var priceHtml = price.clone().prop('outerHTML');

            if(priceHtml === undefined) priceHtml = '';

            $('#parcelpro_afhaalpunt').val('Homerr');
            $(label).html(data.LocationType + ": " + data.Name + " " + priceHtml);
        }
    }

    function popup_close() {
        $('#modal').hide();
    }

    window.addEventListener("message", function (event) {
        if (event.origin === "https://login.parcelpro.nl") {
            var msg = event.data;
            if (msg == "closewindow") {
                popup_close();
            }
            else {
                popup_submit(msg);
                popup_close();
            }
        }
    }, false);

})(jQuery);
