# Vegapo

Vegapo mapuje vegánske potraviny a produkty na [Slovenskom](https://vegapo.sk) a [Českom](https://vegapo.cz) trhu. Aplikácia je napísaná v objektovo orientovanom PHP a je v nej integrovaný môj ľahký [MVC framework](https://github.com/Matoo125/m4mvc). 

## Poznámka

Toto je technická dokumentácia pre developerov. Nasledujúci text predpokladá ovládanie základov programovania a vývoja webových aplikácií. Ak hľadáte informácie pre bežného užívateľa, čoskoro budú dostupné na web stránke.

## Technické požiadavky

**Vyvojové prostredie**

- PHP 7.0 + 
- MySQL 5.6 +
- Apache 2.4 + 
- Moderný Internetový Prehliadač

**Ďalšie požiadavky**

- [Composer](https://getcomposer.org)
- [Bower](https://bower.io)
- Virtuálna doména (veg.dev.sk a veg.dev.cz)
****
## Použité knižnice a frameworky

**Backend cez Composer**

https://github.com/Matoo125/Vegapo/blob/master/composer.json
- [Twig](https://twig.symfony.com) 2.0 (Template engine pre views)
- [Parsedown](http://parsedown.org) 1.6+ (Prelklad z Markdown formátu do html, použité pri footer info stránkach)
- [PHP Mailer](https://github.com/PHPMailer/PHPMailer) 5.2 + (Posielanie emailov pri obnove hesla a odpovedanie na správy ľudí cez kontakt)
- [Image Intervention](http://image.intervention.io) 2.3 + (Vytváranie thumbnailov a manipulácia obrázkov)
- [Whops](http://filp.github.io/whoops/) 2.1 + (Správa error a exception počas vývoja)

**Front End cez bower**

https://github.com/Matoo125/Vegapo/blob/master/bower.json
- [Botstrap](http://getbootstrap.com) 4 (Hlavný framework)
- [Animate.css](https://daneden.github.io/animate.css/) 5.3.2 + (Css animácie)
- [Ionicons](http://ionicons.com) 2 + (ikony)
- [jQueryValidation](https://jqueryvalidation.org) 1.16 + (validácia formulárov)
- [bLazy](http://dinbror.dk/blazy/) 1.8 + (Lenivé / Postupné načitavanie obrázkov)
- [Fancybox](http://fancyapps.com/fancybox/3/) 3.0.47 + (Zväčšivanie obrázkov po kliknutí)
- [Quaga](https://serratus.github.io/quaggaJS/) 0.11.6 + (Načítanie čiarového kódu z obrázku)
- [SimpleMDE](https://simplemde.com) 1.12. + (MarkDown Editor pre foter info stránky)

## Inštalácia

V prvom rade je treba mať nainštalovaný *AMP server (na windowse odporúčam použiť [Laragon](https://laragon.org)), [composer](https://getcomposer.org/doc/00-intro.md) a [bower](https://bower.io/#install-bower) (node, npm, git).
```sh
// vývojové prostredie pripravené
```
Potom si môžeme naklonovať repozitár
```sh
git clone https://github.com/Matoo125/Vegapo.git
```
Otvoríme zložku
```sh
cd Vegapo
```
Nainštalujeme composer knižnice
```sh
composer install
```
Nainštalujeme Bower knižnice
```sh
bower instal
```
Vytvoríme virtuálnu doménu (v závyslosti od operačného systému si treba vygogliť ako)
```sh
// google search: How to create virtual host (XAMP / LAMP / WAMP / MAMP / Laragon)
// vegapo.dev.sk
// vegapo.dev.cz
```
Nainštalujeme databázu z __instal zložky
```sh
// Databáza bola úspešne vytvorená
```
Premenujeme app/core/config.example.php na config.php
```sh
mv app/core/config.example.php app/core/config.php
```
Vložíme prihlasovacie údaje k databáze do config súboru
```php
define("DB_HOST", "localhost");
define("DB_NAME", 'vegapo');
define("DB_USER", "phpmyadmin");
define("DB_PASSWORD", "123456");
define("SHOW_ERRORS", true);
```
Hotovo, aplikácia je nainštalovaná. Obrázky k demo databáze sa dajú stiahnuť [na tomto odkaze](https://drive.google.com/file/d/0B8Do53AIinx0dHI4MFFqbTZ6aEk/view?usp=sharing), [avatary](https://drive.google.com/open?id=0B8Do53AIinx0WUREZXdsZHVqblU)

## Ako sa zapojiť

Ako prvé je najlepšie si prezrieť issues a prípadne založiť nový. Po vytvorení zmien v kóde, prosím pošlite pull request.

## Vývojová konvencia

Viem, že zatiaľ to tak nieje všade ale bolo by dobré písať kód s 4 alebo 2 medzerami namiesto tabu (je možné si to nastaviť v editore) a taktiež aj preformátovať súbor na ktorom zrovna robíťe zmeny ak sú v ňom klasické taby.

Zo začiatku som používal slovenské slová, ako názvy tried a metód, bolo by však dobré písať v angličtine.

## Podpora

Ak máte problém s inštaláciou alebo ste našli nejaký bug, či máte návrh na vylepšenie alebo zmenu, prosím otvorte nový “isue” tu na Githube, alebo nás kontaktujte cez formulár na web stránke.

## Licencia

GNU General Public License v3.0


