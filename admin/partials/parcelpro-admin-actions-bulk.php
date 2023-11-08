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
    (function ( $ ) {
        'use strict';

        $( document ).ready( function () {
            $( '<option>' ).val( 'parcelpro-bulk-export' ).text( 'Aanmelden bij <?= PARCELPRO_SHOPSUNITED ?>' ).appendTo( "select[name='action']" );
            $( '<option>' ).val( 'parcelpro-bulk-export' ).text( 'Aanmelden bij <?= PARCELPRO_SHOPSUNITED ?>' ).appendTo( "select[name='action2']" );

            $( '<option>' ).val( 'parcelpro-bulk-label' ).text( 'Print <?= PARCELPRO_SHOPSUNITED ?> label' ).appendTo( "select[name='action']" );
            $( '<option>' ).val( 'parcelpro-bulk-label' ).text( 'Print <?= PARCELPRO_SHOPSUNITED ?> label' ).appendTo( "select[name='action2']" );
        } );

    })( jQuery );
</script>
