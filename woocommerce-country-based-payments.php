<?php

/*
Plugin Name: WooCommerce - Country Based Payments
Plugin URI:  http://URI_Of_Page_Describing_Plugin_and_Updates
Description: Choose in which country certain payment gateway will be available
Version:     1.0
Author:      Ivan Paulin
Author URI:  http://ivanpaulin.com
License:     GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Domain Path: /languages
Text Domain: wccbp
*/

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
        if(defined( 'DOING_AJAX' ) && DOING_AJAX) {
            add_filter( 'woocommerce_available_payment_gateways', array($this, 'availablePaymentGateways'), 10, 1 );
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
        $this->selected_country = sanitize_text_field($_POST['country']);
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

}

/**
 * Check if WooCommerce is active
 **/
if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
    require 'includes/admin/WCCBPSettings.php';
    
    new WoocommerceCountryBasedPayment();
}