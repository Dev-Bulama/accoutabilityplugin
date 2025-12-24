<?php
/**
 * The public-facing functionality of the plugin.
 *
 * @package Altitude_Audit
 */

if ( ! defined( 'WPINC' ) ) {
    die;
}

/**
 * The public-facing functionality of the plugin.
 */
class Altitude_Audit_Public {

    /**
     * The ID of this plugin.
     *
     * @var string
     */
    private $plugin_name;

    /**
     * The version of this plugin.
     *
     * @var string
     */
    private $version;

    /**
     * Initialize the class.
     *
     * @param string $plugin_name The name of the plugin.
     * @param string $version The version of this plugin.
     */
    public function __construct( $plugin_name, $version ) {
        $this->plugin_name = $plugin_name;
        $this->version = $version;
    }

    /**
     * Register the stylesheets for the public-facing side of the site.
     */
    public function enqueue_styles() {
        wp_enqueue_style(
            $this->plugin_name,
            ALTITUDE_AUDIT_PLUGIN_URL . 'public/css/public.css',
            array(),
            $this->version,
            'all'
        );
    }

    /**
     * Register the JavaScript for the public-facing side of the site.
     */
    public function enqueue_scripts() {
        wp_enqueue_script(
            $this->plugin_name,
            ALTITUDE_AUDIT_PLUGIN_URL . 'public/js/public.js',
            array( 'jquery' ),
            $this->version,
            true
        );

        // Get configuration for JS
        $config = get_option( 'altitude_audit_config', altitude_audit_get_default_config() );

        // Prepare config for JavaScript
        $js_config = array(
            'categories' => array_keys( $config['categories'] ),
            'scoringOptions' => $config['scoring_options'],
        );

        wp_localize_script(
            $this->plugin_name,
            'altitudeAuditConfig',
            $js_config
        );
    }
}
