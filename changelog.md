# Changelog

## 1.9.0 - 2024-10-23
* Fix XSS vulnerability in nonce error message

## 1.8.4 - 2024-06-13
* Fix potential "undefined array key 2" error in order box

## 1.8.3 - 2024-05-29
* Fix "this order requires a shipping option" error in checkout for WooCommerce 8.8.0

## 1.8.2 - 2024-04-23
* Fix in_array() error for countries

## 1.8.1 - 2024-03-20
* Fix actions icon alignment in orders table

## 1.8.0 - 2024-03-20
* Set up Shops United branded plugin build

## 1.7.3 - 2024-03-19
* Improve error handling in the getDeliveryDate function

## 1.7.2 - 2024-03-04
* Fixed a bug that would incorrectly display a wrong expected datetime

## 1.7.1 - 2024-02-06
* Change support for Wordpress up to 6.4.3

## 1.7.0 - 2024-02-01
* Added optional delivery time expectations for DHL and PostNL

## 1.6.21 - 2024-01-09
* Fix custom shipping types

## 1.6.20 - 2024-01-09
* Quick fix on countries select dropdown

## 1.6.18 - 2023-12-19
* Fix shipping settings page layout for WooCommerce 8.4.0

## 1.6.17 - 2023-12-07
* Improve parcelshop popup hooks resilience with other checkouts

## 1.6.16 - 2023-11-22
* Fix bulk order actions for WC 7 and legacy order data storage

## 1.6.15 - 2023-11-22
* Improve nonce-related error messages

## 1.6.14 - 2023-11-22
* Fix to avoid unnecessary type calls

## 1.6.13 - 2023-11-22
* Fix nonce validation for non-parcelpro actions in filter

## 1.6.12 - 2023-11-21
* Add nonce validation to action handler for improved security

## 1.6.11 - 2023-11-15
* Fix order auto export with order statuses from other plugins

## 1.6.10 - 2023-11-14
* Fix parcelshop selector popup on cart page

## 1.6.9 - 2023-11-02
* Fix actions on edit order screen in WooCommerce 8

## 1.6.8 - 2023-10-31
* Remove fragile requirements checks

## 1.6.7 - 2023-10-31
* Fix Parcelshops in WooCommerce 8

## 1.6.6 - 2023-10-31
* Fix requirements check

## 1.6.5 - 2023-10-26
* Added a small filter to parcelshops

## 1.6.4 - 2023-10-25
* Fix PHP warning met maatwerk verzendmethodes

## 1.6.3 - 2023-10-20
* Fix bulk acties voor orders in WooCommerce 8

## 1.6.2 - 2023-10-20
* Fix automatisch aanmelden

## 1.6.1 - 2023-09-05
* Fix pakketpunten Homerr

## 1.6 - 2023-08-30
* PHP 8.x ondersteuning

## 1.5.54 - 2023-06-28
* Kleine fix voor zending specifiek ophalen

## 1.5.53 - 2023-06-07
* Cycloon toegevoegd als vervoerder

## 1.5.52 - 2023-04-26
* Kleine fix Intrapost track&trace

## 1.5.51 - 2023-04-24
* Kleine fix in checkoutpagina

## 1.5.50 - 2023-04-24
* E-mailadres klantaccount toegevoegd aan request naast factuurgegevens

## 1.5.49 - 2023-04-11
* Fix: Gekozen eigen (maatwerk) dienst bij verzendmethoden goed verwerken zodat zending ook met de juiste dienst aangemaakt wordt

## 1.5.48 - 2023-03-30
* Fix: Gekozen eigen (maatwerk) dienst bij verzendmethoden ook toestaan en correct afhandelen.

## 1.5.47 - 2023-03-29
* Knop 'Volg zending' pas tonen nadat zending is afgedrukt
* Viatim en Intrapost toegevoegd aan back-end, zodat deze vervoerders ook geconfigureerd kunnen worden zonder dat de vervoerder 'Maatwerk' heet

## 1.5.46 - 2023-03-17
* Fix: Na het toevoegen van een nieuwe dienst de nieuwe diensten vernieuwen.

## 1.5.45 - 2023-02-27
* Fix: Product shipping class

## 1.5.42 - 2023-02-07
* Fix: Shiping class

## 1.5.41 - 2022-12-28
* Fix: Undefined list parcelpro-public.js

## 1.5.40 - 2022-12-23
* Fix: Voor buitenlandse DPD-zendingen ook pakketpunten tonen

## 1.5.38 - 2022-11-25
* Fix: Call to undefined function get_current_screen()

## 1.5.37 - 2022-11-24
* Fix: Om het gebruik van de type.php te limiteren

## 1.5.36
* Fix: Uitzonderlijke foutmelding opgelost bij het laden van een order met producten zonder lengte, breedte of hoogte ingevuld.

## 1.5.35
* Fix: Checkout verbetering om te verzekeren dat de afhaalpunt pop up en parameters altijd in de checkout form geplaatst worden.
* Fix: Fix voor unidentified index notice bij het overnemen van trackinggegevens.

## 1.5.34
* Fix: Landcode van pakketpunt overnemen

## 1.5.33
* Fix: Communiceert nu verzendregel titel via de achtergrond.
* Fix: Fix voor layout problemen met bepaalde themes.
* Fix: Notice melding als er geen mail id ingesteld is
* Fix: Melding als er geen landing ingesteld zijn op woocommerce omgeving.
* Fix: Naam verandering voor Api Key en Gebruikers ID om verwarring te voorkomen.
* Fix: Automatisch aanmeldstatus aangepast bij standaard instellingen.
* Fix: Overschrijvende status bij aanmelding aangepast bij standaard instellingen.

## 1.5.32
* Nieuw: Melding 'geen afhaalpunten geselecteerd' nu geregristreerd als vertaalbaar.
* Fix: Meerdere deprecated jQuery functies geüpdatet.

## 1.5.31
* Nieuw: Optie toegevoegd om klant opmerkingen bij de order aan te melden.

## 1.5.30
* Fix: Homerr verbeterde punten check.

## 1.5.29
* Fix: Bug bij het veranderen van verzendmethode met pakketpunt in de checkout opgelost.

## 1.5.28
* Fix: Er wordt nu strenger gecontroleerd op Homerr punten en of deze ingevuld zijn in de checkout.

## 1.5.27
* Fix: Bug in checkout opgelost.

## 1.5.26
* Fix: Homerr verzendmethodes worden nu beter verwerkt.

## 1.5.25
* Nieuw: Bij het aanpassen van een verzendmethode wordt de afhaalpunt nu verwijderd inplaats van gebruikt als afleveradres.

## 1.5.24
* Fix: Foutmeldingen null defined variables opgelost.

## 1.5.23
* Fix: Extra diensten met buitenlandse afhaalpunten werken nu beter. Alleen voor DHL ondersteund.

## 1.5.22
* Fix: Gewichten en dimensies worden nu correct doorgezet naar Parcel Pro systeem op basis van gekozen eenheid.

## 1.5.21
* Nieuw: Vervoerder gebruikt om barcode te genereren wordt nu opgeslagen in een meta veld ([#orderid]_parcelpro_track_vervoerder) voor koppelingsmogelijkheden.
* Nieuw: Optie toegevoegd voor extra diensten om expliciet aan te kunnen geven of afhaalpunten gekozen moeten worden of niet.

## 1.5.20
* Nieuw: Pop-upscherm wordt getoond voor pakketpunten van Homerr.

## 1.5.19
* Nieuw: Barcode wordt nu toegevoegd aan de order notes zodra zending aangemeld wordt.
* Nieuw: Gewichten worden nu doorgestuurd met de zendingsgegevens.
* Fix: Sorteer tabel is nu iets gebruiksvriendelijker gemaakt.

## 1.5.18
* Nieuw: Optie om het berekenen met coupons aan/uit te zetten.
* Fix: Probleem opgelost dat ervoor zorgde dat bij het printen in bulk de meest recente label overgeslagen werd.

## 1.5.17
* Fix: Een warning die gegeven werd door parcelpro-admin.js opgelost.

## 1.5.16
* Fix: Opslaan van gekozen pakketpunt

## 1.5.15
* Optie om order status aan te passen na aanmelden toegevoegd.

## 1.5.14
* Problemen opgelost met deployment

## 1.5.13
* Problemen opgelost met deployment

## 1.5.12
* Opgelost dat de parcel pro locatie kiezer werd geopend op andere shipment methodes.
* Verbeterde documentatie voor custom hooks; zie read me.

## 1.5.11
* Update voor nieuwe versie van Woocommerce

## 1.5.10
* Update voor nieuwe versie van Woocommerce

## 1.5.9
* Update voor nieuwe versie van Woocommerce

## 1.5.8
* Update voor nieuwe versie van Woocommerce

## 1.5.7
* Update voor nieuwe versie van Woocommerce

## 1.5.6
* Update voor nieuwe versie van Woocommerce

## 1.5.5
* Variatie ID s die verkeerd werden opgehaald via oude methode nu verhopen.

## 1.5.4
* Handmatige orders kunnen nu correct aangemaakt worden met een verzendmethode.

## 1.5.3
* Orders worden nu correct binnengeschoten.

## 1.5.2
* Orders kunnen via de backend aangemaak worden zonder problemen.

## 1.5.1
* Betere beveiliging op de achtergrond.

## 1.5.0
* Er kan weer direct worden afgedrukt als de label opgevraagd wordt.
* Nieuw: Maatwerk verzendopties kunnen nu worden toevoegd.

## 1.4.3 - 2019-06-19
* Fix: Files

## 1.4.2
* Gekozen parcelshop

## 1.4.1
* Verzendopties achteraf via de backend wijzigen.

## 1.4.0
* Verzendopties achteraf via de backend wijzigen.
* E-mail variabelen

## 1.3.0 - 2018-06-15
* Nieuw: Module in repository Wordpress
* Fix: Tonen van de verzendmethoden bij het afrekenen

## 1.2.8
* PostNL Handtekening voor ontvangst ook bij buren.
* Fix Type aanroep via API.

## 1.2.7
* Fix m.b.t. Woocommerce check

## 1.2.6
* Fix m.b.t. php 7 'A non-numeric value encountered'

## 1.2.5
* Fix m.b.t. array_merge

## 1.2.4
* VSP Brievenbuspakketje
* Compatible wordpress 3.9 // Woocommerce 3.2.4
* array_key_exists() error bij nieuwe installatie.
* Auto export na status
* Same day

## 1.2.3
* Order items ophalen in woocommerce 3.x

## 1.2.2
* Controle of gekozen afhaalpunt ook hetzelfde is als de vervoerder van de verzendmethode.

## 1.2.1
* Soorteren van Parcel Pro verzendmethodes.
* Openen van popup bij het laden van het scherm wanneer een verzendmethode met afhaalpunt is geselecteerd.
* Wanneer verzendmethode met afhaalpunt gratis is geen undefined prijs.
* Lege titels in een rule krijgen een standaard titel.
* "Add Rule" button type gegeven tegen aanpassen input.

## 1.2.0
* Verzendoptie Brievenbuspakketje voor DHL en PostNL.
* Mogelijkheid om eigen titel mee te geven aan verzendmethode.
* Aanmelden van een zending bij Parcel Pro bij een specifieke statuss.
* Handleiding voor het installeren van de WooCommerce module.
* Openen van popup voor het kiezen van een afhaalpunt wanneer betalingsmethode veranderd.
* Verkeerd weergeven van prijs bij het kiezen van een afhaalpunt.

## 1.1.4
* Validatie van ingevoerde prijs en gewichten, fixt bug met variable btw.

## 1.1.3
* Quantity van producten bij bepalen gewicht zending en methodes.

## 1.1.2
* Ondersteuning voor WooCommerce Sequential Order Numbers plugin

## 1.1.1
* Bugfix m.b.t. automatisch aanmelden
* Klikgedrag afhaallocatie frame

## 1.1.0
* BTW tarief in winkelwagen
* Ondersteuning voor DHL, DPD, PostNL en UPS

## 1.0.0
* Verzendmethoden aanmaken
* Bestellingen aanmelden in het verzendsysteem
* Verzendlabel afdrukken
* Afhaallocatie kiezen voor zowel DHL als PostNL
* Ondersteuning voor DHL en PostNL
