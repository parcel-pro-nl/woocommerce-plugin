# Parcel Pro / Shops United WooCommerce plugin

Parcel Pro heeft een API koppeling ontwikkeld die gelinkt is aan de backoffice van WordPress/WooCommerce.
Hiermee kunt u heel gemakkelijk orders inladen in ons verzendsysteem.
Dit zorgt ervoor dat het verzendproces efficiënter wordt en het helpt u bij het verwerken van meerdere orders en zendingen.

De handleiding is te vinden op [https://support.parcelpro.nl/koppelingen/woocommerce-handleiding](https://support.parcelpro.nl/koppelingen/woocommerce-handleiding).
Bij vragen kunt u contact opnemen via [info@parcelpro.nl](mailto:info@parcelpro.nl) of 085 273 2785.

## Custom Hooks

Mocht u gebruik maken van een custom check out, dan zou het mogelijk kunnen zijn dat de developer hiervoor de standaard velden van Woocommerce aangepast heeft.

Om aan de gegevens die naar Parcel Pro verstuurd worden toch alle nieuwe gegevens toe te voegen, kan deze custom hook gebruikt worden: `parcelpro_format_order_data`.

Deze wordt door de plugin aangeroepen als:

```php
apply_filter('parcelpro_format_order_data',$data);
```

met alle verzendgegevens die verstuurd gaan worden naar Parcel Pro in de `$data`.

Zie de [handleiding](https://support.parcelpro.nl/koppelingen/woocommerce-handleiding) voor meer details.

## Changelog

Voor alle historischer veranderingen en updates, zie de [changelog](changelog.md).

## Development

Deze repository bevat de code voor de "Parcel Pro" _en_ "Shops United" plugins.
Om de code te bouwen, run:

```shell
composer build
# Of:
php build.php
```

### Publiceren

Om nieuwe versies van de plugins te publiceren maken we een nieuwe tag vanaf `main`.
Dit is eenvoudig te doen via de [releases pagina](https://github.com/parcel-pro-nl/woocommerce-plugin/releases) op GitHub.

De WordPress assets bevinden zich in de `.wordpress-parcel-pro` en `.wordpress-shops-united` mappen.
Deze worden automatisch gepubliceerd in de WordPress plugin registry vanaf de `main` branch. 
