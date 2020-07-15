=== WooCommerce Contributo Ambientale Conai - Consorzio Nazionale Imballaggi ===
Contributors: riccardodicurti
Author URI: https://riccardodicurti.it/
Plugin URL: https://github.com/riccardodicurti/wc_conai
Tags: 
Stable tag: 15062020

Plugin in fase di sviluppo per l'aggiunta del calcolo relativo al contributo conai in fase di checkout.

== Description ==

Il plugin WooCommerce Contributo Ambientale Conai - Consorzio Nazionale Imballaggi è la risposta all'esigenza di aggiungere il calcolo del CAC al tuo e-commerce. 

Il plugin sarà visibile unicamente se è attivo WooCommerce e richiederà l'inserimento di un json di configurazione come quello qui di seguito: 

`
[
    { "id":1, "name":"carta", "price":55, "unit":"€ per 1000kg" },
    { "id":2, "name":"plastica A", "price":150, "unit":"€ per 1000kg" },
    { "id":3, "name":"plastica B1", "price":208, "unit":"€ per 1000kg" },
    { "id":4, "name":"plastica B2", "price":436, "unit":"€ per 1000kg" },
    { "id":5, "name":"plastica C", "price":546, "unit":"€ per 1000kg" },
    { "id":6, "name":"acciaio", "price":3, "unit":"€ per 1000kg" }
]
`

Fai attenzione a non usare id 0 in qunato di def. per gli articoli non soggetti a conai. 

Una volta salvato senza errori il json di configurazione vai all'interno dei tuoi prodotti e specifica tipologia di materiale ed il relativo peso. 

Al valore del contibuto verrà aggiunto il 22% di iva. 

Il plugin sarà presto revisionato e raggiunta una versione stabile verrà rilasciato per il download dalla repository ufficiale di WordPress. Nel caso in cui troviate bugs o vogliate collaborare non esitate a contattarmi.  

== Frequently Asked Questions ==

= Dove posso trovare maggiorni informazioni sul contributo ambientale conai ? =

Ti consiglio di leggere la pagina presente sul sito ufficiale del conai al riguardo del [CONTRIBUTO AMBIENTALE](http://www.conai.org/imprese/contributo-ambientale/).

= Come posso installare il plugin ? =

#

= Non vedo il plugin in backend, cosa posso aver sbagliato ? =

#

== Changelog ==

= 15062020 =
* First release.
