<?php
/**
 * Plugin Name: WooCommerce - Country Based Payments
 * Plugin URI:  https://wordpress.org/plugins/woocommerce-country-based-payments/
 * Description: Choose in which country certain payment gateway will be available
 * Version:     1.3.3
 * Author:      Ivan Paulin
 * Author URI:  http://ivanpaulin.com
 * License:     GPL2
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Domain Path: /languages
 * Text Domain: wccbp
 * WC requires at least: 4.0
 * WC tested up to: 4.6.2
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Initialize the plugin, settings page and handle plugin logic
 */
class WoocommerceCountryBasedPayment {

	/**
	 * Plugin ID
	 *
	 * @var String
	 */
	private $id;

	/**
	 * Construct plugin
	 */
	public function __construct() {
		$this->id = 'wccbp';

		if ( is_admin() && ! wp_doing_ajax() ) {
      $this->load_settings();
    }

		add_action( 'plugins_loaded', array( $this, 'load_plugin_textdomain' ) );

		// Check if ajax request.
		if ( ! is_admin() ) {
			// Fix WPML WooCommerce Multilingual error.
			add_filter( 'wcml_supported_currency_payment_gateways', array( $this, 'available_payment_gateways' ), 90, 1 );

			add_filter( 'woocommerce_available_payment_gateways', array( $this, 'available_payment_gateways' ), 10, 1 );
		}

		// Check if pay_for page.
		if ( ! is_admin() && isset( $_GET['pay_for_order'] ) && true == $_GET['pay_for_order'] ) {
			add_filter( 'woocommerce_available_payment_gateways', array( $this, 'available_payment_gateways_after_cancelation' ), 10, 1 );
		}
	}

	/**
	 * Load textdomain
	 */
	public function load_plugin_textdomain() {
		load_plugin_textdomain( 'wccbp', false, basename( dirname( __FILE__ ) ) . '/languages/' );
	}


	/**
	 * Load admin settings
	 */
	public function load_settings() {
		require 'includes/admin/WCCBPSettings.php';
		return ( new WCCBPSettings() )->init();
	}


	/**
	 * List through available payment gateways,
	 * check if certain payment gateway is enabled for country,
	 * if no, unset it from $payment_gateways array
	 *
	 * @param array $payment_gateways List of available gateways in system.
	 * @return array with updated list of available payment gateways
	 */
	public function available_payment_gateways( $payment_gateways ) {
		if ( is_null( WC()->customer ) || ! WC()->customer instanceof WC_Customer ) {
			return $payment_gateways;
		}
		foreach ( $payment_gateways as $key => $value ) {
			// Check if WCML array.
			$gateway_id = ( is_object( $value ) && isset( $value->id ) ) ? $value->id : $key;
			$gateway_availability = get_option( $this->id . '_' . $gateway_id );

			if ( $gateway_availability && ! in_array( WC()->customer->get_billing_country(), $gateway_availability ) ) {
				unset( $payment_gateways[ $gateway_id ] );
			}
		}
		return $payment_gateways;
	}

	/**
	 * List through available payment gateways,
	 * if customer gets redirected to the pay_for page
	 * after a payment cancellation
	 *
	 * @param array $payment_gateways List of available gateways in system.
	 * @return array with updated list of available payment gateways
	 */
	public function available_payment_gateways_after_cancelation( $payment_gateways ) {
		$order_id = wc_get_order_id_by_order_key( $_GET['key'] );
		$order = new WC_Order( $order_id );
		$billing_address = $order->get_address();
		$selected_country = $billing_address['country'];

		foreach ( $payment_gateways as $gateway ) {
			if ( get_option( $this->id . '_' . $gateway->id ) && ! in_array( $selected_country, get_option( $this->id . '_' . $gateway->id ) ) ) {
				unset( $payment_gateways[ $gateway->id ] );
			}
		}

		return $payment_gateways;
	}
}

/**
 * Check if WooCommerce is active
 */
if ( ! function_exists( 'is_plugin_active_for_network' ) ) {
	require_once( ABSPATH . '/wp-admin/includes/plugin.php' );
}

if ( is_plugin_active( 'woocommerce/woocommerce.php' ) ) {
	// Plugin is activated.
	new WoocommerceCountryBasedPayment();
}

// Load Freemius SDK
// Create a helper function for easy SDK access.
function wcbp_fs() {
	global $wcbp_fs;

	if ( ! isset( $wcbp_fs ) ) {
			// Include Freemius SDK.
			require_once dirname(__FILE__) . '/includes/freemius/start.php';

			$wcbp_fs = fs_dynamic_init( array(
					'id'                  => '2788',
					'slug'                => 'woocommerce-country-based-payments',
					'type'                => 'plugin',
					'public_key'          => 'pk_cbdb518bd47595e667e3992ea2e2f',
					'is_premium'          => false,
					'has_addons'          => false,
					'has_paid_plans'      => false,
					'menu'                => array(
							'slug'           => 'wc-settings',
							'override_exact' => true,
							'account'        => false,
							'contact'        => false,
							'support'        => false,
							'parent'         => array(
									'slug' => 'woocommerce',
							),
					),
			) );
	}

	return $wcbp_fs;
}

// Init Freemius.
wcbp_fs();
// Signal that SDK was initiated.
do_action( 'wcbp_fs_loaded' );

function wcbp_fs_settings_url() {
	return admin_url( 'admin.php?page=wc-settings&tab=wccbp' );
}

wcbp_fs()->add_filter( 'connect_url', 'wcbp_fs_settings_url' );
wcbp_fs()->add_filter( 'after_skip_url', 'wcbp_fs_settings_url' );
wcbp_fs()->add_filter( 'after_connect_url', 'wcbp_fs_settings_url' );
wcbp_fs()->add_filter( 'after_pending_connect_url', 'wcbp_fs_settings_url' );
