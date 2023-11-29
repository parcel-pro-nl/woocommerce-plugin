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

Voor alle historische veranderingen en updates, zie de [changelog](changelog.md).
