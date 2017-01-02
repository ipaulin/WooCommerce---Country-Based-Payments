<?php
/*
Plugin Name: WooCommerce - Country Based Payments
Plugin URI:  https://wordpress.org/plugins/woocommerce-country-based-payments/
Description: Choose in which country certain payment gateway will be available
Version:     1.1.6.1
Author:      Ivan Paulin
Author URI:  http://ivanpaulin.com
License:     GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Domain Path: /languages
Text Domain: wccbp
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// define text domain
define('WCCBP_TEXT_DOMAIN', 'wccbp');


class WoocommerceCountryBasedPayment {

    private $selected_country;

    private $id;

    public function __construct()
    {
        $this->id = 'wccbp';

        add_action('woocommerce_loaded', array($this, 'loadSettings'));

        add_action('woocommerce_checkout_update_order_review', array($this, 'setSelectedCountry'), 10);

        // check if ajax request
        if(!is_admin() && (isset($_REQUEST['wc-ajax']) && 'update_order_review' == $_REQUEST['wc-ajax'])) {
            add_filter( 'woocommerce_available_payment_gateways', array($this, 'availablePaymentGateways'), 10, 1 );
        }

        // check if pay_for page
        if( !is_admin() && isset( $_GET['pay_for_order'] ) &&  true == $_GET['pay_for_order'] ) {
            add_filter( 'woocommerce_available_payment_gateways', array( $this, 'available_payment_gateways_after_cancelation'), 10, 1 );
        }
    }


    /**
     * Load admin settings
     */
    public function loadSettings()
    {
        new WCCBPSettings();
    }


    /**
     * Set selected country on Ajax request in checkout process
     * Country code is used.
     *
     */
    public function setSelectedCountry()
    {
        $this->selected_country = sanitize_text_field($_REQUEST['country']);
    }


    /**
     * List through available payment gateways,
     * check if certain payment gateway is enabled for country,
     * if no, unset it from $payment_gateways array
     *
     * @return array with updated list of available payment gateways
     */
    public function availablePaymentGateways($payment_gateways)
    {
        foreach ($payment_gateways as $gateway) {
            if(get_option($this->id . '_' . $gateway->id) && !in_array($this->selected_country, get_option($this->id . '_' . $gateway->id))) {
                unset($payment_gateways[$gateway->id]);
            }
        }

        return $payment_gateways;
    }

    /**
     * List through available payment gateways,
     * if customer gets redirected to the pay_for page
     * after a payment cancellation
     *
     * @return array with updated list of available payment gateways
     */
    public function available_payment_gateways_after_cancelation( $payment_gateways )
    {
    	$order_id = wc_get_order_id_by_order_key( $_GET['key'] );
    	$order = new WC_Order($order_id);
			$billing_address = $order->get_address();
			$this->selected_country = $billing_address['country'];

			foreach ( $payment_gateways as $gateway ) {
				if ( get_option( $this->id . '_' . $gateway->id ) && !in_array( $this->selected_country, get_option( $this->id . '_' . $gateway->id ) ) ) {
					unset( $payment_gateways[$gateway->id] );
				}
			}

			return $payment_gateways;
    }
}

/**
 * Check if WooCommerce is active
 **/
if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
    require 'includes/admin/WCCBPSettings.php';

    new WoocommerceCountryBasedPayment();
}
