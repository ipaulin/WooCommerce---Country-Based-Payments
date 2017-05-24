=== WooCommerce - Country Based Payments ===
Contributors: ivan_paulin, mensmaximus
Donate link: http://ivanpaulin.com/
Tags: woocommerce, payment gateway, country, countries, payment gateways, country payment
Requires at least: 4.7
Tested up to: 4.7.5
Stable tag: 1.1.8
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Choose which payment gateway will be available in country/countries.

== Description ==

This plugin gives you option to choose which payment gateway will be available in certain country, or countries.

If you need to have certain payment gateway to be available in all countries, don't set option for it.

This WooCommerce addon is compatible with:
* WooCommerce 3.0.7

== Installation ==

1. Upload `woocommerce-country-based-payment` folder to the `/wp-content/plugins/` directory
2. Activate the "WooCommerce - Country Based Payments" through the 'Plugins' menu in WordPress


== Screenshots ==

1. Select WCCBP tab in WooCommerce settings.
2. You can choose multiple countries.

== Changelog ==

= 1.0 =
* Initial release.

= 1.1 =
* Fix 'Invalid payment method' error

= 1.1.2 =
* Update readme.txt file

= 1.1.3 =
* Fix - compatibility issue with WooCommerce 2.4.6 regarding to the Ajax request

= 1.1.4 =
* Fix - PHP notice undefined index "wc-ajax"

= 1.1.5 =
* Fix - Resolve issue with the payment gateway selection after cancellation of payment

= 1.1.6 =
* Version bump
* Remove assets folder

= 1.1.6.1 =
* Prevent direct access in php files

= 1.1.7 =
* Fix compatibility with third-party payment gateways

= 1.1.8 =
* Fix: settings tab doesn't show on multisite installation
