=== Parcel Pro ===
Tags: Shipping, Verzending, Pakketten, PostNL, DHL, DPD, UPS, Multi Carrier, Shops United Parcel Pro, Parcelpro
Requires at least: 3.0.1
Tested up to: 6.3.2
Requires PHP: 5.2.4
Stable tag: 1.6.3
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

== Description ==
Parcel Pro heeft een API koppeling ontwikkeld die gelinkt is aan de backoffice van WordPress/WooCommerce. Hiermee kunt u heel gemakkelijk orders inladen in ons verzendsysteem. Dit zorgt ervoor dat het verzendproces efficiënter wordt en het helpt u bij het verwerken van meerdere orders en zendingen.

De handleiding is te vinden op [https://support.parcelpro.nl/koppelingen/woocommerce-handleiding](https://support.parcelpro.nl/koppelingen/woocommerce-handleiding)
Bij vragen kunt u contact opnemen via [info@parcelpro.nl](mailto:info@parcelpro.nl) of 085 273 2785.

== Custom Hooks ==
Mocht u gebruik maken van een custom check out, dan zou het mogelijk kunnen zijn dat de developer hiervoor de standaard velden van Woocommerce aangepast heeft.

Om aan de gegevens die naar Parcel Pro verstuurd worden toch alle nieuwe gegevens toe te voegen, kan gebruikt worden van de volgende custom hook:
'parcelpro_format_order_data'

Deze wordt door de plugin aangeroepen als apply_filter('parcelpro_format_order_data',$data), met alle verzendgegevens die verstuurd gaan worden naar Parcel Pro in de $data.

Zie de handleiding voor meer details.

== Changelog ==
= 1.6.3 - 2023-10-20 =
* Fix bulk acties voor orders

= 1.6.2 - 2023-10-20 =
* Fix automatisch aanmelden

= 1.6.1 - 2023-09-05 =
* Fix pakketpunten Homerr

= 1.6 - 2023-08-30 =
* PHP 8.x ondersteuning

= 1.5.53 - 2023-06-07 =
* Cycloon toegevoegd als vervoerder

= 1.5.48 - 2023-03-30 =
* Fix: Gekozen eigen (maatwerk) dienst bij verzendmethoden ook toestaan en correct afhandelen.

= 1.5.47 - 2023-03-29 =
* Knop 'Volg zending' pas tonen nadat zending is afgedrukt
* Viatim en Intrapost toegevoegd aan back-end, zodat deze vervoerders ook geconfigureerd kunnen worden zonder dat de vervoerder 'Maatwerk' heet

= 1.5.46 - 2023-03-17 =
* Fix: Na het toevoegen van een nieuwe dienst de nieuwe diensten vernieuwen.

= 1.5.45 - 2023-02-27 =
* Fix: Product shipping class

= 1.5.42 - 2023-02-07 =
* Fix: Shipping class

= 1.5.41 - 2022-12-28 =
* Fix: Undefined list parcelpro-public.js

= 1.5.40 - 2022-12-23 =
* Fix: DPD parcelshops

= 1.5.38 - 2022-11-25 =
* Fix: Call to undefined function get_current_screen()

= 1.5.37 - 2022-11-24 =
* Fix: Om het gebruik van de type.php te limiteren

= 1.5.36 - 2021-10-05 =
* Fix: Uitzonderlijke foutmelding opgelost bij het laden van een order met producten zonder lengte, breedte of hoogte ingevuld.

= 1.5.35 - 2021-09-27 =
* Fix: Checkout verbetering om te verzekeren dat de afhaalpunt pop up en parameters altijd in de checkout form geplaatst worden.
* Fix: Fix voor unidentified index notice bij het overnemen van trackinggegevens.

= 1.5.34 - 2021-09-23 =
* Fix: Landcode van pakketpunt overnemen

## 1.5.33 - 2021-06-09 =
* Fix: Communiceert nu verzendregel titel via de achtergrond.
* Fix: Fix voor layout problemen met bepaalde themes.
* Fix: Notice melding als er geen mail id ingesteld is
* Fix: Melding als er geen landing ingesteld zijn op woocommerce omgeving.
* Fix: Naam verandering voor Api Key en Gebruikers ID om verwarring te voorkomen.
* Fix: Automatisch aanmeldstatus aangepast bij standaard instellingen.
* Fix: Overschrijvende status bij aanmelding aangepast bij standaard instellingen.

= 1.5.32 - 2021-04-19 =
* Nieuw:  Melding 'geen afhaalpunten geselecteerd' nu geregristreerd als vertaalbaar.
* Fix: Meerdere deprecated jQuery functies geüpdatet.

=  1.5.31 - 2021-01-26 =
* Nieuw: Optie toegevoegd om klant opmerkingen bij de order aan te melden.

=  1.5.30 - 2020-01-06 =
* Fix: Homerr verbeterde punten check.

= 1.5.29 - 2020-12-17 =
* Fix: Bug bij het veranderen van verzendmethode met pakketpunt in de checkout opgelost.

= 1.5.28 - 2020-12-15 =
* Fix: Er wordt nu strenger gecontroleerd op Homerr punten en of deze ingevuld zijn in de checkout.

= 1.5.27 - 2020-11-17 =
* Fix: Bug in checkout opgelost.

= 1.5.26 - 2020-11-16 =
* Fix : Homerr verzendmethodes worden nu beter verwerkt.

= 1.5.25 - 2020-11-12 =
* Nieuw: Bij het aanpassen van een verzendmethode wordt de afhaalpunt nu verwijderd inplaats van gebruikt als afleveradres.

= 1.5.24 - 2020-11-11 =
* Fix: Foutmeldingen null defined variables opgelost.

= 1.5.23 - 2020-10-30 =
* Fix: Extra diensten met buitenlandse afhaalpunten werken nu beter. Alleen voor DHL ondersteund

= 1.5.22 - 2020-10-20 =
* Fix: Gewichten en dimensies worden nu correct doorgezet naar Parcel Pro systeem op basis van gekozen eenheid.

= 1.5.21 - 2020-10-13 =
* Nieuw: Vervoerder gebruikt om barcode te genereren wordt nu opgeslagen in een meta veld ([#orderid]_parcelpro_track_vervoerder) voor koppelingsmogelijkheden.
* Nieuw: Optie toegevoegd voor extra diensten om expliciet aan te kunnen geven of afhaalpunten gekozen moeten worden of niet.

= 1.5.20 - 2020-09-15 =
* Nieuw: Pop-upscherm wordt getoond voor pakketpunten van Homerr.

= 1.5.19 - 2020-09-02 =
* Nieuw: Barcode wordt nu toegevoegd aan de order notes zodra zending aangemeld wordt.
* Nieuw: Gewichten worden nu doorgestuurd met de zendingsgegevens.
* Fix: Sorteer tabel is nu iets gebruiksvriendelijker gemaakt.

= 1.5.18 - 2020-06-20 =
* Nieuw: Optie om het berekenen met coupons aan/uit te zetten.
* Fix: Probleem opgelost dat ervoor zorgde dat bij het printen in bulk de meest recente label overgeslagen werd.

= 1.5.17 - 2020-05-27 =
* Fix:  Een warning die gegeven werd door parcelpro-admin.js opgelost.

= 1.5.16 - 2020-05-25 =
* Fix:  Opslaan van gekozen pakketpunt

= 1.5.15 - 2020-05-06 =
* Nieuw:  Optie om order status aan te passen na aanmelden toegevoegd.

= 1.5.14 - 2020-03-31 =
* Fix: Pop up kan nu geopend worden.

= 1.5.13 - 2020-03-31 =
* Fix: Problemen met deployment opgelost.

= 1.5.12 - 2020-03-24 =
* Fix:  Opgelost dat de parcel pro locatie kiezer werd geopend op andere shipment methodes.
* Verbeterde documentatie voor custom hooks.

= 1.5.11 - 2020-03-04 =
* Fix: Update voor nieuwe versie van Woocommerce

= 1.5.10 - 2020-02-28 =
* Fix: Update voor nieuwe versie van Woocommerce

= 1.5.9 - 2020-02-24 =
* Fix:  Update voor nieuwe versie van Woocommerce

= 1.5.8 - 2020-02-24 =
* Fix:  Update voor nieuwe versie van Woocommerce

= 1.5.7 - 2020-02-24 =
* Fix:  Update voor nieuwe versie van Woocommerce

= 1.5.6 - 2020-02-19 =
* Fix:  Update voor nieuwe versie van Woocommerce

= 1.5.5 - 2020-02-19 =
* Fix:  Variatie ID s die verkeerd werden opgehaald via oude methode nu verhopen.

= 1.5.4 - 2020-02-19 =
* Fix: Handmatige orders kunnen nu correct aangemaakt worden met een verzendmethode.

= 1.5.3 - 2020-02-12 =
* Fix: Adres gegevens worden nu correct verstuurd.

= 1.5.2 - 2020-02-11 =
* Fix: Aanmaken van orders via de backend werkt nu zonder problemen.

= 1.5.1 - 2020-02-11 =
* Fix: Verbeterde failsaves om technische moeizaamheden te voorkomen op de achtergrond.

= 1.5.0 - 2020-01-31 =
* Fix: Er kan weer direct worden afgedrukt als de label opgevraagd wordt.
* Nieuw: Maatwerk verzendopties kunnen nu worden toevoegd.

= 1.4.3 - 2019-06-19 =
* Fix: Files

= 1.4.2 - 2019-06-19 =
* Fix: Gekozen parcelshop

= 1.4.1 - 2019-01-03 =
* Fix: PHP7 compatible
* Fix: Script includes

= 1.4.0 - 2018-08-15 =
* Nieuw: Verzendopties achteraf via de backend wijzigen.
* Fix: E-mail variabelen

= 1.3.0 - 2018-06-15 =
* Nieuw: Module in reposityro Wordpress
* Fix: Tonen van de verzendmethoden bij het afrekenen

= 1.2.8 - 2018-04-04 =
* Nieuw: PostNL Handtekening voor ontvangst ook bij buren.
* Fix: Type aanroep via API
