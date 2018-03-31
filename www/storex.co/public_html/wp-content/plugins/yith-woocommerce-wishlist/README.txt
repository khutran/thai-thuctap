=== YITH WooCommerce Wishlist === 

Contributors: yithemes
Tags: wishlist, woocommerce, products, themes, yit, e-commerce, shop, ecommerce wishlist, yith, woocommerce wishlist, woocommerce 2.3 ready, shop wishlist
Requires at least: 4.0
Tested up to: 4.3.1
Stable tag: 2.0.12
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

YITH WooCommerce Wishlist add all Wishlist features to your website. Needs WooCommerce to work.
WooCommerce 2.4.x compatible.


== Description ==

What can really make the difference in conversions and amount of sales is without a doubt the freedom to share your own wishlist, even on social networks, increasing indirect sales: can you imagine the sales volume you can generate during holidays or birthdays, when relatives and friends will be looking for the wishlist of your clients to buy a gift?

Offer to your visitors a chance to add the products of your woocommerce store to a wishlist page. With YITH WooCommerce Wishlist you can add a link in each product detail page,
in order to add the products to the wishlist page. The plugin will create you the specific page and the products will be added in this page and
afterwards add them to the cart or remove them.

Working demo are available:

**[LIVE DEMO 1](http://preview.yithemes.com/room09/product/africa-style/)** - **[LIVE DEMO 2](http://preview.yithemes.com/bazar/shop/ankle-shoes/)**

Full documentation is available [here](http://yithemes.com/docs-plugins/yith-woocommerce-wishlist).

This plugin is 100% compatible with [WPML](http://wpml.org/?aid=24889&affiliate_key=Qn1wDeBNTcZV)

= Available Languages =

**NOTE: The translation process of this plugin has been changed by WordPress. Please, read the correlated FAQ to be updated about the news changes.**

* Chinese - CHINA
* Chinese - TAIWAN
* English - UNITED KINGDOM (Default)
* French - FRANCE
* German - GERMANY
* Hebrew - ISRAEL
* Italian - ITALY
* Persian - IRAN, ISLAMIC REPUBLIC OF
* Polish - POLAND
* Portuguese - BRAZIL
* Portuguese - PORTUGAL
* Russian - RUSSIAN FEDERATION
* Spanish - ARGENTINA
* Spanish - SPAIN
* Spanish - MEXICO
* Swedish - SWEDEN
* Turkish - TURKEY
* Ukrainian - UKRAINE

== Installation ==

1. Unzip the downloaded zip file.
2. Upload the plugin folder into the `wp-content/plugins/` directory of your WordPress site.
3. Activate `YITH WooCommerce Wishlist` from Plugins page

YITH WooCommerce Wishlist will add a new submenu called "Wishlist" under "YIT Plugins" menu. Here you are able to configure all the plugin settings.

== Frequently Asked Questions ==

= Can I customize the wishlist page? =
Yes, the page is a simple template and you can override it by putting the file template "wishlist.php" inside the "woocommerce" folder of the theme folder.

= Can I move the position of "Add to wishlist" button? =
Yes, you can move the button to another default position or you can also use the shortcode inside your theme code.

= Can I change the style of "Add to wishlist" button? =
Yes, you can change the colors of background, text and border or apply a custom css. You can also use a link or a button for the "Add to wishlist" feature.

= Wishlist page returns a 404 error? =
Try to regenerate permalinks from Settings -> Permalinks by simply saving them again.

= Did icons of your theme disappear after update to Wishlist 2.0.x? =
It might be a compatibility problem with the old version of font-awesome, which has been solved with version 2.0.2 of the plugin. Be sure that you are using a plugin version that is greater or equal to 2.0.2. If, after update, you cannot see icons in your theme yet, save again options of YITH WooCommerce Wishlist plugin (that you can find in YIT Plugin -> Wishlist).

= Have you encountered anomalies after plugin update, that did not exist in the previous version? =
This might depend on the fact that your theme overrides plugin templates. Check if the developer of your theme has released a compatibility update with version 2.0 or later of YITH WooCommerce Wishlist. As an alternative you can try the plugin in WordPress default theme to leave out any possible influences by the theme.

= I am currently using Wishlist plugin with Catalog Mode enabled in my site. Prices for products should disappear, yet they still appear in the wishlist page. Can I remove them? =
Yes, of course you can. To avoid Wishlist page to show product prices, you can hide price column from wishlist table. Go to YIT plugins -> wishlist -> settings and disable option "Show Unit price".

= What are the main changes in plugin translation? =
Recently YITH WooCommerce Wishlist has been selected to be included in the "translate.wordpress.org" translate programme.
In order to import correctly the plugin strings in the new system, we had to change the text domain from 'yit' to 'yith-woocommerce-wishlist'.
Once the plugin is imported into the translate.wordpress.org system, the translations of other languages will be downloadable directly from WordPress, without using any .po and .mo files. Moreover, users will be able to participate in a more direct way to plugin translations, suggesting texts in their languages in the dedicated tab on translate.wordpress.org.
During this transition step, .po and .mo files will be used as usual, but in order to be recognized by WordPress, they must have a new nomenclature and be renamed as:
yith-woocommerce-wishlist-&lt;WORDPRESS LOCALE&gt;.po
yith-woocommerce-wishlist-&lt;WORDPRESS LOCALE&gt;.mo
If your theme overrides plugin templates, it might happen that they are still using the old textdomain ('yit'), which is no longer used as reference for translation.
If you are experiencing problems with translation of your YITH WooCommerce Wishlist and the theme you are using includes wishlist templates (such as add-to-wishlist.php,
add-to-wishlist-button.php, wishlist-view,php), you could try to update them with the most recent version included in the plugin
(never forget to make a copy of your project before you apply any change).
If you want to keep customisations applied by the theme to wishlist templates (still using the old textdomain), then,
you should ask theme developers to update custom templates and replace the old textdomain with the most recent one.

== Screenshots ==

1. The page with "Add to wishlist" button
2. The wishlist page
3. The Wishlist settings page
4. The Wishlist settings page

== Changelog ==

= 2.0.12 =

* Added: method to count all products in wishlist
* Tweak: Added wishlist js handling on 'yith_wcwl_init' triggered on document
* Tweak: Performance improved with new plugin core 2.0
* Fixed: occasional fatal error for users with outdated version of plugin-fw on their theme

= 2.0.11 =

* Added: spanish translation (thanks to Arman S.)
* Added: polish translation (thanks to Roan)
* Added: swedish translation (thanks to Lallex)
* Updated: changed text domain from yit to yith-woocommerce-wishlist
* Updated: changed all language file for the new text domain

= 2.0.10 =

* Added: Compatibility with WC 2.4.2
* Tweak: added nonce field to wishlist-view form
* Tweak: added yith_wcwl_custom_add_to_cart_text and yith_wcwl_ask_an_estimate_text filters
* Tweak: added check for presence of required function in wishlist script
* Fixed: admin colorpicker field (for WC 2.4.x compatibility)

= 2.0.9 =

* Added: russian translation
* Added: WooCommerce class to wishlist view form
* Added: spinner to plugin assets
* Added: check on "user_logged_in" for sub-templates in wishlist-view
* Added: WordPress 4.2.3 compatibility
* Added: WPML 3.2.2 compatibility (removed deprecated function)
* Added: new check on is_product_in_wishlist (for unlogged users/default wishlist)
* Tweak: escaped urls on share template
* Tweak: removed new line between html attributes, to improve themes compatibility
* Fixed: WPML 3.2.2 compatibility (fix suggested by Konrad)
* Fixed: regex used to find class attr in "Add to Cart" button
* Fixed: usage of product_id for add_to_wishlist shortcode, when global $product is not defined
* Fixed: icon attribute for yith_wcwl_add_to_wishlist shortcode

= 2.0.8 =

* Added: support WP 4.2.2
* Added: Persian translation
* Added: check on cookie content
* Added: Frequently Bought Together integration
* Tweak: moved cookie update before first cookie usage
* Updated: Italian translation
* Removed: login_redirect_url variable

= 2.0.7 =

* Added: WP 4.2.1 support
* Added: WC 2.3.8 support
* Added: "Added to cart" message in wishlist page
* Added: Portuguese translation
* Updated: revision of all templates
* Fixed: vulnerability for unserialize of cookie content (Warning: in this way all the old serialized plugins will be deleted and all the wishlists of the non-logged users will be lost)
* Fixed: Escaped add_query_arg() and remove_query_arg()
* Removed: use of pretty permalinks if WPML enabled

= 2.0.6 =

* Added: system to overwrite wishlist js
* Added: trailingslashit() to wishlist permalink
* Added: chinese translation
* Added: "show_empty" filter to get_wishlists() method
* Fixed: count wishlist items
* Fixed: problem with price inclusive of tax
* Fixed: remove from wishlist for not logged user
* Fixed: twitter share summary

= 2.0.5 =

* Added: icl_object_id to wishlist page id, to translate pages
* Tweak: updated rewrite rules, to include child pages as wishlist pages
* Tweak: moved WC notices from wishlist template to yith_wcwl_before_wishlist_title hook
* Tweak: added wishlist table id to .load(), to update only that part of template
* Fixed: yith_wcwl_locate_template causing 500 Internal Server Error

= 2.0.4 =

* Added: Options for browse wishlist/already in wishlist/product added strings
* Added: rel nofollow to add to wishlist button
* Tweak: moved wishlist response popup handling to separate js file
* Updated: WPML xml configuration
* Updated: string revision

= 2.0.3 =

* Tweak: set correct protocol for admin-ajax requests
* Tweak: used wc core function to set cookie
* Tweak: let customization of add_to_wishlist shortcodes
* Fixed: show add to cart column when stock status disabled
* Fixed: product existing in wishlist

= 2.0.2 =

* Updated: font-awesome library
* Fixed: option with old font-awesome classes

= 2.0.1 =

* Added: spinner image on loading
* Added: flush rewrite rules on database upgrade
* Fixed: wc_add_to_cart_params not defined issue

= 2.0.0 =

* Added: Support to woocommerce 2.3
* Added: New color options
* Tweak: Add to cart button from woocommerce template
* Tweak: Share links on template
* Tweak: Code revision
* Tweak: Use wordpress API in ajax call instead of custom script
* Updated: Plugin core framework


= 1.1.7 =

* Added: Support to WooCommerce Endpoints (@use yit_wcwl_add_to_cart_redirect_url filter)
* Added: Filter to shortcode html
* Added: Title to share

= 1.1.6 =

* Updated: Plugin Core Framework
* Updated: Languages file
* Tweek:   WPML Support Improved

= 1.1.5 =

* Added: Share wishlist by email 

= 1.1.4 =

* Fixed: wrong string for inline js on remove link
* Fixed: wrong string for inline js on add to cart link

= 1.1.3 =

* Added: Options Tabs Filter
* Fixed: Various Bugs

= 1.1.2 =

* Fixed: Warnings when Show Stock Status is disabled
* Fixed: Restored page options on WooCommerce 2.1.x

= 1.1.1 =

* Fixed: Inability to unistall plugin 
* Fixed: Redirect to cart page from wishlist page

= 1.1.0 =

* Added: Support to WooCommerce 2.1.x
* Added: Spanish (Mexico) translation by Gabriel Dzul
* Added: French translation by Virginie Garcin
* Fixed: Revision Italian Language po/mo files

= 1.0.6 =

* Added: Spanish (Argentina) partial translation by Sebastian Jeremias
* Added: Portuguese (Brazil) translation by Lincoln Lemos
* Fixed: Share buttons show also when not logged in
* Fixed: Price shows including or excluding tax based on WooCommerce settings
* Fixed: Better compatibility for WPML 
* Fixed: Price shows "Free!" if the product is without price
* Fixed: DB Table creation on plugin activation

= 1.0.5 =

* Added: Shared wishlists can be seens also by not logged in users
* Added: Support for WPML String translation
* Updated: German translation by Stephanie Schlieske
* Fixed: Add to cart button does not appear if the product is out of stock

= 1.0.4 =

* Added: partial Ukrainian translation
* Added: complete German translation. Thanks to Stephanie Schliesk
* Added: options to show/hide button add to cart, unit price and stock status in the wishlist page
* Added: Hebrew language (thanks to Gery Grinvald)

= 1.0.3 =

* Fixed: Minor bugs fixes

= 1.0.2 =

* Fixed: Fatal error to yit_debug with yit themes

= 1.0.1 =

* Tweak: Optimized images
* Updated: internal framework

= 1.0.0 =

* Initial release

== Suggestions ==

If you have suggestions about how to improve YITH WooCommerce Wishlist, you can [write us](mailto:plugins@yithemes.com "Your Inspiration Themes") so we can bundle them into YITH WooCommerce Wishlist.

== Translators ==

= Available Languages =
* Chinese - CHINA
* Chinese - TAIWAN
* English - UNITED KINGDOM (Default)
* German - GERMANY
* Spanish - ARGENTINA
* Spanish - SPAIN
* Spanish - MEXICO
* French - FRANCE
* Hebrew - ISRAEL
* Italian - ITALY
* Persian - IRAN, ISLAMIC REPUBLIC OF
* Portuguese - BRAZIL
* Portuguese - PORTUGAL
* Russian - RUSSIAN FEDERATION
* Turkish - TURKEY
* Ukrainian - UKRAINE

Some of these translations are not complete.
If you want to contribute to the translation of the plugin, please [go to WordPress official translator platform](https://translate.wordpress.org/ "Translating WordPress") and translate the strings in your own language. In this way, we will be able to increase the languages available for YITH WooCommerce Wishlist.


== Documentation ==

Full documentation is available [here](http://yithemes.com/docs-plugins/yith-woocommerce-wishlist).

== Upgrade notice ==

= 2.0.12 =

* Added: method to count all products in wishlist
* Tweak: Added wishlist js handling on 'yith_wcwl_init' triggered on document
* Tweak: Performance improved with new plugin core 2.0
* Fixed: occasional fatal error for users with outdated version of plugin-fw on their theme
