=== WooCommerce - Country Based Payments ===
Contributors: ivan_paulin, mensmaximus, freemius
Donate link: https://ivanpaulin.com/donate/?plugin=woocommerce-country-based-payments
Tags: woocommerce, payment gateway, country, countries, payment gateways, country payment
Requires at least: 5.0
Tested up to: 5.6
Stable tag: 1.4
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Choose which payment gateway will be available in country/countries.

== Description ==

This plugin gives you option to choose which payment gateway will be available in certain country, or countries.

If you need to have certain payment gateway to be available in all countries, don't set option for it.

This WooCommerce addon is compatible with:
* WooCommerce 4.8.0

__NOTE__: Backup your website before updating the plugin. Try to test a new version of the plugin on the staging server before using the plugin on a live site.
This plugin comes as is; there's no guarantee that it will work with all payment gateways available. 
I don't have access to the premium version of payment gateways, and I haven't tested this plugin with all payment gateways.
Known payment gateways that this plugin does not work with:
1. Amazon Payments
2. PayPal Checkout

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
    25.07.2018
* Tested with latest WordPress and WooCommerce version
* Added notice about GDPR in description

= 1.2.0 =
* Added languages folder and wccbp.pot file, load wccbcp textdomain
* Added Freemius integration

= 1.2.1 =
* Fix problem with missing settings page

= 1.2.2 =
* Fix fatal error when plugin WooCommerce Germanized is used

= 1.2.3 =
* Fix fatal error when plugin is used with WPML WooCommerce Multilingual

= 1.2.3.1 =
* Fix bug with WCML in previous release

= 1.2.4 =
* Update Freemius SDK

= 1.2.4.1 =
* Temporarily disable Freemius SDK until the bug is resolved

= 1.2.4.2 =
* Fix bug with Freemius SDK

= 1.3 =
* Fix issue with restrictions not applying on checkout page load - https://github.com/woocommerce/woocommerce/pull/24271
* Apply WP coding standards

= 1.3.2 =
* Fix Fatal error: Uncaught Error: Call to a member function get_billing_country() on null 

= 1.3.3 =
* Fix WP REST API error code: rest_setting_setting_group_invalid, message: Invalid setting group

= 1.4 =
* Update Freemius SDK
* Tested with WC 4.8.0
