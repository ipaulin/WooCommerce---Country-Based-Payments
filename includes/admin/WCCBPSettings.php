<?php

/**
 * Admin settings in WooCommerce
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class WCCBPSettings extends WC_Settings_Page {

    protected $id;

    private $text_domain;


    public function __construct()
    {
        $this->id = 'wccbp';

        add_filter('woocommerce_settings_tabs_array', array($this, 'addSettingsTab'), 40);

        add_action('woocommerce_settings_tabs_' . $this->id, array($this, 'addSectionToTab'));

        add_action('woocommerce_update_options_' . $this->id, array($this, 'updateOptions'));
    }


    /**
     * Create settings tab for WooCommercer settings
     */
    public function addSettingsTab($tabs)
    {
        $tabs[$this->id] = __( 'WCCBP', 'wccbp' );

        return $tabs;
    }


    /**
     * Cerate input field for every available payment gateway
     *
     * @return $fields array
     */
    public function createFields()
    {
        $available_gateways = WC()->payment_gateways->payment_gateways();

        $fields = array();

        foreach($available_gateways as $gateway) {
            $fields[] = array(
                    'title'   => $gateway->method_title ? $gateway->method_title : $gateway->id,
                    'type'    => 'multi_select_countries',
                    'id'      => $this->id . '_' . $gateway->id,
                );
        }

        return $fields;
    }


    /**
     * Create section and include input fields in section
     *
     * @return array
     */
    public function createTabSection()
    {
        $section = array();

        $section[] = array(
                'title' => __( 'Country Based Payments', 'wccbp' ),
                'desc'  => __( 'Select in which countries payment gateways will be available', 'wccbp' ),
                'type'  => 'title',
                'id'    => $this->id,
            );

        $section = array_merge($section, $this->createFields());

        $section[] = array( 'type' => 'sectionend', 'id' => $this->id);

        return $section;
    }


    /**
     * Add section to tab
     */
    public function addSectionToTab()
    {
        woocommerce_admin_fields($this->createTabSection());
    }

    /**
     *  Update setting fields
     */
    public function updateOptions()
    {
        woocommerce_update_options($this->createFields());
    }
}
