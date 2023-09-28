(function ($) {
    'use strict';

    $(document).ready(function () {
        $(".addrule").click(function(){
            add_row($(this))
        });

        // TODO: Functionaliteiten voor extra pakketten toevoegen aan /api/woocommerce/order-created.php
        $('.parcelpro-package').on('click', function (event) {
            event.preventDefault();

            var url = $(this).attr('href');
            $("body").css({overflow: 'hidden'});

            var H = ($(window).height() - 120 > 225) ? 225 : $(window).height() - 120;
            tb_show('Selecteer het aantal pakketen', url + '&TB_iframe=true&width=400&height=' + H);
        });

        $(window).on('tb_unload', function () {
            $("body").css({overflow: 'inherit'});
            window.location.reload()
        });

        $('.btn-apply').on('click', function () {
            if ($('#spinner').val() >= 1 && $('#spinner').val() <= 10) {
                parent.location.href = $('#apply-url').val() + '&package=' + $('#spinner').val() + '&shipping_method='+$('#shipping_method').val()+'&redirect=' + parent.location.href.split('&')[0 ];
            }
            else {
                alert('Aantal moet tussen de 1 en 10 zijn.');
            }
        });

        $(".addcarrier").click(function () {
            var item_div = $('#accordion');
            var huidige_diensten = item_div.children('h3').length + 1;
            var huidige_count = item_div.children().length + 1;
            //console.log(huidige_count);
            $('#accordion').append('<h3 class="ui-accordion-header ui-state-default ui-accordion-icons ui-corner-all" role="tab" id="ui-id-' + huidige_count +'" aria-controls="ui-id-' + (huidige_count+1) +'" aria-selected="false" aria-expanded="false" tabindex="0">' +
                '<span class="ui-accordion-header-icon ui-icon ui-icon-triangle-1-s"></span>Verzendmethode dienst ' + huidige_diensten +'</h3>');
            $('#accordion').append('<div class="ui-accordion-content ui-helper-reset ui-widget-content ui-corner-bottom ui-accordion-content-active" id="ui-id-' + (huidige_count+1) +'" aria-labelledby="ui-id-' + (huidige_count) +'" role="tabpanel" aria-hidden="true" style="display: block;">\n' +
                '                <p>Met onderstaande tabel is het mogelijk om een specifieke nieuwe dienst in te stellen</p>\n' +
                '                            <h4>Nieuwe Dienst:</h4>\n' +
                '                <table class="parcelpro_rules widefat">\n' +
                '                    <thead>\n' +
                '                    <tr>\n' +
                '                        <th class="remove">&nbsp;</th>\n' +
                '<th class="country">Country <span class="woocommerce-help-tip parcelpro_tip" data-tip="Land waar order naar wordt verzonden."></span></th>\n' +
                '<th>Verzendmethode<span class="woocommerce-help-tip parcelpro_tip" data-tip="De verzendmethode voor het Parcel Pro systeem. Zorg ervoor dat deze per dienst het zelfde is. "></span></th>\n' +
                '                                                <th>Method Title <span class="woocommerce-help-tip parcelpro_tip" data-tip="Titel van de verzendmethode voor de klanten in de checkout."></span></th>\n' +
                '                                                <th>Min Weight <span class="woocommerce-help-tip parcelpro_tip" data-tip="Minimum gewicht van een order."></span></th>\n' +
                '                        <th>Max Weight <span class="woocommerce-help-tip parcelpro_tip" data-tip="Maximum gewicht van een order."></span></th>\n' +
                '                        <th>Min Total <span class="woocommerce-help-tip parcelpro_tip" data-tip="Minimum prijs van een order."></span></th>\n' +
                '                        <th>Max Total <span class="woocommerce-help-tip parcelpro_tip" data-tip="Maximum prijs van een order."></span></th>\n' +
                '                        <th>Price <span class="woocommerce-help-tip parcelpro_tip" data-tip="Prijs voor de verzendmethode aan de hand van ingevoerde variabele."></span></th>\n' +
                '                        <th>Servicepunt <span class="woocommerce-help-tip parcelpro_tip" data-tip="Open servicepunt pop-up voor deze verzendmethode?"></span></th>\n' +
                '                    </tr>\n' +
                '                    </thead>\n' +
                '                    <tbody>\n' +
                '                                        </tbody>\n' +
                '                    <tfoot>\n' +
                '                    <tr>\n' +
                '                        <th colspan="9"><input type="button" class="button addrule" name="maatwerk_' + huidige_diensten +'" value="+ Add Rule" style="margin-right: 10px"></th>\n' +
                '                    </tr>\n' +
                '                    </tfoot>\n' +
                '                </table>\n' +
                '                \n' +
                '            </div>');

            $(".addrule").click(function(){
                add_row($(this))
            });
        });

        $(".delete").click(function () {
            $(this).closest('tr').remove();
        });

    });




    function add_row(e){
        var row;
        var rule_nr = Math.round(Math.random() * (99999999 - 0) + 0);
        var name = e.attr('name');

        if(name.substring(0,8)  === 'maatwerk'){
            row = $('#template_rule_maatwerk').clone();
            row.find(".verzenddrop").find('select').prop("name", ("parcelpro_shipping_settings[" + name + "][" + rule_nr + "][type-id]"));
            row.find(".verzenddrop").find('select').prop("id", ("parcelpro_shipping_settings[" + name + "][" + rule_nr + "][type-id]"));

        }else{
            row = $('#template_rule').clone()
        }

        row.show();
        row.prop("id", "parcelpro_rule");

        if (name == 'postnl_buitenland' || name == 'dhl_buitenland' || name.substring(0,8)  === 'maatwerk') {
            row.find(".landdrop").find('select').prop("name", ("parcelpro_shipping_settings[" + name + "][" + rule_nr + "][country]"));

            row.find(".landdrop").find('select').prop("id", ("parcelpro_shipping_settings[" + name + "][" + rule_nr + "][country]"));
        }
        else {
            row.children("td").eq(1).remove();
        }

        row.append('<td><input type="text" name="parcelpro_shipping_settings[' + name + '][' + rule_nr + '][method-title]" value=""/></td>');

        row.append('<td><input type="text" name="parcelpro_shipping_settings[' + name + '][' + rule_nr + '][min-weight]" value=""/></td>');
        row.append('<td><input type="text" name="parcelpro_shipping_settings[' + name + '][' + rule_nr + '][max-weight]" value=""/></td>');
        row.append('<td><input type="text" name="parcelpro_shipping_settings[' + name + '][' + rule_nr + '][min-total]" value=""/></td>');
        row.append('<td><input type="text" name="parcelpro_shipping_settings[' + name + '][' + rule_nr + '][max-total]" value=""/></td>');
        row.append('<td><input type="text" name="parcelpro_shipping_settings[' + name + '][' + rule_nr + '][price]" value=""/></td>');

        if(name.substring(0,8) =="maatwerk"){
            row.append('<td><input type="checkbox" name="parcelpro_shipping_settings[' + name + '][' + rule_nr + '][servicepunt]"/></td>');

        }

        e.closest('table').find('tbody:last').append(row);
    }
})(jQuery);
