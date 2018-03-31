=== YITH WooCommerce Compare ===

Contributors: yithemes
Tags: wc, shop, woocommerce, compare, compare products, product compare, widget, comparison, product comparison, compare table
Requires at least: 3.5.1
Tested up to: 4.3
Stable tag: 2.0.5
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

YITH WooCommerce Compare allows you to compare more products of your shop in one complete table.
WooCommerce Compatible up to 2.4.x


== Description ==

YITH WooCommerce Compare plugin is an extension of WooCommerce plugin that allow your users to compare some products of your shop.
All products are saved in one complete table where the user can see the difference between the products. Each product features can be
set with the woocommerce attributes in the product configuration.
You can also add a simple widget with the list of products the users have added, where you can manage them.
Also you can simply customize the compare table with your style, by editing the specific template.

Working demos are available here:
**[LIVE DEMO 1](http://preview.yithemes.com/room09/shop/)** - **[LIVE DEMO 2](http://preview.yithemes.com/bazar/shop/)**


Full documentation is available [here](http://yithemes.com/docs-plugins/yith-woocommerce-compare).


This plugin is 100% compatible with [WPML](http://wpml.org/?aid=24889&affiliate_key=Qn1wDeBNTcZV)


= Installation =

1. Unzip the downloaded zip file.
2. Upload the plugin folder into the `wp-content/plugins/` directory of your WordPress site.
3. Activate `YITH WooCommerce Compare` from Plugins page

= Configuration =

1. Add the features in each product by setting the attributes in prouct configuration;
2. Configure the options in YIT Plugin -> Compare;
3. The user of your shop will click in the "Compare" button located in the single product page;
4. Will be open a popup with the compare table inside.

You can also add the widget with the product list of compare table, in Appearance -> Widgets and adding the widget "YITH WooCommerce Compare Widget".

== Frequently Asked Questions ==

= Can I customize the compare table? =
Yes, you can copy the template from the plugin folder and paste it inside the folder "woocommerce" of your theme folder.

= Can I select what fields show inside the compare table? =
Yes, you can choose what fields to show in the compare table. You can do it in YIT Plugins -> Compare.

= Can I order the fields in the compare table? =
Yes, you can sort the fields in the compare table. You can do it in YIT Plugins -> Compare.

= What are the main changes in plugin translation? =
Recently YITH WooCommerce Compare has been selected to be included in the "translate.wordpress.org" translate programme.
In order to import correctly the plugin strings in the new system, we had to change the text domain form 'yit' to 'yith-woocommerce-compare'.
Once the plugin will be imported in the translate.wordpress.org system, the translations of other languages will be downloaded directly from WordPress, without using any .po and .mo files. Moreover, users will be able to participate in a more direct way to plugin translations, suggesting texts in their languages in the dedicated tab on translate.wordpress.org.
During this transition step, .po and .mo files will be used as always, but in order to be recognized by WordPress, they will need to have a new nomenclature, renaming them in:
yith-woocommerce-compare-<WORDPRESS LOCALE>.po
yith-woocommerce-compare-<WORDPRESS LOCALE >.mo

== Screenshots ==

1. The popup with compare table.
2. The button compare.
3. The settings of plugin

== Changelog ==

= 2.0.5 =

* Fixed: After you remove product from compare, you can re-add it without reload page
* Updated: Plugin Core

= 2.0.4 =

* Fixed: JS error when loading compare window
* Updated: Changed Text Domain from 'yith-wcmp' to 'yith-woocommerce-compare'
* Updated: Plugin Core Framework

= 2.0.3 =

* Added: Compatibility with WooPress 4.3

= 2.0.2 =

* Added: Compatibility with WooCommerce 2.4
* Updated: Core plugin

= 2.0.1 =

* Fixed: Compare table layout
* Fixed: undefined function unblock() in main js
* Updated: Core plugin

= 2.0.0 =

* Added: Added new plugin core
* Fixed: Error in class yith-woocompare-fontend
* Fixed: Lightbox doesn't close after click view cart
* Fixed: minor bug fix
* Updated: Language files
* Removed: old default.po catalog language file

= 1.2.3 =

* Added: Bulgarian Translation by Joanna Mikova
* Added: Spanish Translation by Rodoslav Angelov and Mariano Rivas

= 1.2.2 =

* Fixed: Shortcode compare

= 1.2.1 =

* Added: Support to WC 2.2.3

= 1.2.0 =

* Added: Support to WC 2.2.2
* Update: Compare Template
* Updated: Plugin Core Framework
* Tweek: WPML Support improved
* Fixed: Fields orders

= 1.1.4 =

* Updated: Colorbox Library Version 1.5.10
* Fixed: Horizontal scroll bar issue: show at the end of iframe

= 1.1.3 =

* Added: RTL Support

= 1.1.2 =

* Fixed: WPML Support on Compare Widget

= 1.1.1 =

* Fixed: Add to cart Button on Compare page

= 1.1.0 =

* Added: Support to WooCommerce 2.1.X
* Added: French translation by Paumo

= 1.0.5 =

* Added: Persian translation by Khalil Delavaran
* Added: Compare table title option
* Added: Compatibility with WPML plugin
* Added: Brazilian Portuguese translation by hotelwww
* Updated: Dutch translation by Frans Pronk
* Fixed: Responsive features
* Fixed: Dequeued wrong JS scripts

= 1.0.4 =

* Added: complete Dutch translation. Thanks to Frans Pronk
* Fixed: Sortable scripts included only in the plugin admin page
* Fixed: products list in popup don't update after have added a product

= 1.0.3 =

* Minor bugs fixes

= 1.0.2 =

* Tweak: trigger in javascript file for add to compare event

= 1.0.1 =

* Added: Link/Button text option in plugin options
* Added: ability to add a link in the menu top open the popup
* Fixed: bug with attributes added after installation
* Fixed: bug with plugin activated but not working for multisites

= 1.0.0 =

* Initial release

== Suggestions ==

If you have suggestions about how to improve YITH WooCommerce Compare, you can [write us](mailto:plugins@yithemes.com "Your Inspiration Themes") so we can bundle them into YITH WooCommerce Compare.

== Translators ==

= Available Languages =
* English (Default)
* Italiano

If you have created your own language pack, or have an update for an existing one, you can send [gettext PO and MO file](http://codex.wordpress.org/Translating_WordPress "Translating WordPress")
[use](http://yithemes.com/contact/ "Your Inspiration Themes") so we can bundle it into YITH WooCommerce Compare Languages.

== Documentation ==

Full documentation is available [here](http://yithemes.com/docs-plugins/yith-woocommerce-compare).

== Upgrade notice ==

= 2.0.5 =

* Fixed: After you remove product from compare, you can re-add it without reload page
* Updated: Plugin Core