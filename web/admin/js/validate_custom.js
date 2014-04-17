// validador de NIF
jQuery.validator.addMethod( "nifES", function ( value, element ) {
    "use strict";

    value = value.toUpperCase();

    // Basic format test
    if ( !value.match('((^[A-Z]{1}[0-9]{7}[A-Z0-9]{1}$|^[T]{1}[A-Z0-9]{8}$)|^[0-9]{8}[A-Z]{1}$)') ) {
        return false;
    }

    // Test NIF
    if ( /^[0-9]{8}[A-Z]{1}$/.test( value ) ) {
        return ( "TRWAGMYFPDXBNJZSQVHLCKE".charAt( value.substring( 8, 0 ) % 23 ) === value.charAt( 8 ) );
    }
    // Test specials NIF (starts with K, L or M)
    if ( /^[KLM]{1}/.test( value ) ) {
        return ( value[ 8 ] === String.fromCharCode( 64 ) );
    }

    // Test NIE
    //T
    if ( /^[T]{1}/.test( value ) ) {
        return ( value[ 8 ] === /^[T]{1}[A-Z0-9]{8}$/.test( value ) );
    }

    //XYZ
    if ( /^[XYZ]{1}/.test( value ) ) {
        return (
            value[ 8 ] === "TRWAGMYFPDXBNJZSQVHLCKE".charAt(
                value.replace( 'X', '0' )
                    .replace( 'Y', '1' )
                    .replace( 'Z', '2' )
                    .substring( 0, 8 ) % 23
            )
            );
    }

    return false;

}, "Please specify a valid NIE number." );

$(document).on('ready', function(){
    // Función que valida el formulario.
    // Nota: las reglas de validación está en las mismas etiquetas
    $("#sabadellForm").validate({
        rules: {
            nienif: {
                required: true,
                nifES: true
            },
            address: {
                minlength: 4
            },
            phone:{
                minlength: 9
            }

        },
        submitHandler: function(form) {
            // valido que tenga una oficina correcta
            var office_code = $("#coffice").val();
            if ( office_code == "" ) {
                $("#caddress").focus().select();
                $("#error-office").dialog({
                    modal: true,
                    draggable: false
                });
                return false;
            }
            // valido dni, nif o cif
            var card_id = $("").val();
            var options = {
                clearForm: true,
                type: 'post',
                error: function() {
                    $("#error-general").dialog({
                        modal: true,
                        draggable: false
                    });
                },
                success: function(data) {
                    if ( data == "OK" ) {
                        window.location.href = website+url_gracias;
                    } else {
                        $("#error-general").dialog({
                            modal: true,
                            draggable: false
                        });
                    }
                }
            };
            $('#sabadellForm').ajaxSubmit(options);
            return false;
        },
        errorClass: 'input-error',
        errorElement: 'span'
    });

    // Programa el autocompletado del campo de búsqueda de oficina por ciudad o cp.
    $("#caddress").autocomplete({
        minLength: 4,
        source: function( request, response ) {
            var term = request.term;"/"
            if ( term in cache ) {
                response( cache[ term ] );
                return;
            }

            $.getJSON( website+"/offices", request, function( data, status, xhr ) {
                cache[ term ] = data;
                response( data );
            });
        },
        select: function( event, ui ) {
            cache[ui.item.label] = [ui.item];
            $("#coffice").val( ui.item.id );
        }
    });

    // Cuando el usuario cambia la dirección a mano reinicio el campo coffice
    $("#caddress").keyup(function() {
        $("#coffice").val("");
    });
});