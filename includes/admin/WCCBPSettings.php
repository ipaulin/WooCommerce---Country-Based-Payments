<?php

/**
 * Admin settings in WooCommerce
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class WCCBPSettings {

	protected $id = 'wccbp';

	public function init() {
		add_filter( 'woocommerce_settings_tabs_array', array( $this, 'add_settings_settings_tab' ), 50 );
		add_action( 'woocommerce_settings_' . $this->id, array( $this, 'settings_page' ) );
		add_action( 'woocommerce_update_options_' . $this->id, array( $this, 'update_options' ) );
	}

	public function add_settings_settings_tab( $settings_tabs ) {

		$settings_tabs[ $this->id ] = __( 'WCCBP', 'wccbp' );

		return $settings_tabs;
	}

	public function settings_page() {
		woocommerce_admin_fields( $this->create_tab_section() );
		wp_nonce_field( 'wccbp_subscription_settings', '_wccbpnonce', false );
	}


	/**
	 * Cerate input field for every available payment gateway
	 *
	 * @return $fields array
	 */
	public function create_fields() {
		$available_gateways = WC()->payment_gateways->payment_gateways();

		$fields = array();

		foreach ( $available_gateways as $gateway ) {
			$fields[] = array(
				'name' => $gateway->method_title ? $gateway->method_title : $gateway->id,
				'type' => 'multi_select_countries',
				'id'   => $this->id . '_' . $gateway->id,
			);
		}

		return $fields;
	}


	/**
	 * Create section and include input fields in section
	 *
	 * @return array
	 */
	public function create_tab_section() {
		$section = array();

		$section[] = array(
			'name' => __( 'Country Based Payments', 'wccbp' ),
			'desc' => __( 'Select in which countries payment gateways will be available', 'wccbp' ),
			'type' => 'title',
			'id'   => $this->id . '_title',
		);

		$section = array_merge( $section, $this->create_fields() );

		$section[] = array(
			'type' => 'sectionend',
			'id'   => $this->id . '_title',
		);

		return $section;
	}


	/**
	 *  Update setting fields
	 */
	public function update_options() {
		if ( empty( $_POST['_wccbpnonce'] ) || ! wp_verify_nonce( $_POST['_wccbpnonce'], 'wccbp_subscription_settings' ) ) {
			return;
		}
		woocommerce_update_options( $this->create_fields() );
	}
}
