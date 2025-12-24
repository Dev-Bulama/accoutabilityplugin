<?php
/**
 * Fired during plugin activation.
 *
 * @package Altitude_Audit
 */

if ( ! defined( 'WPINC' ) ) {
    die;
}

/**
 * Fired during plugin activation.
 */
class Altitude_Audit_Activator {

    /**
     * Activate the plugin.
     */
    public static function activate() {
        // Check if Fluent Forms is active
        if ( ! defined( 'FLUENTFORM' ) ) {
            deactivate_plugins( plugin_basename( __FILE__ ) );
            wp_die(
                esc_html__( 'This plugin requires Fluent Forms to be installed and activated.', 'altitude-accountability-audit' ),
                esc_html__( 'Plugin Activation Error', 'altitude-accountability-audit' ),
                array( 'back_link' => true )
            );
        }

        // Create database tables
        require_once ALTITUDE_AUDIT_PLUGIN_DIR . 'includes/class-database.php';
        Altitude_Audit_Database::create_tables();

        // Set default options
        if ( ! get_option( 'altitude_audit_config' ) ) {
            require_once ALTITUDE_AUDIT_PLUGIN_DIR . 'config/default-config.php';
            $default_config = altitude_audit_get_default_config();
            update_option( 'altitude_audit_config', $default_config );
        }

        if ( ! get_option( 'altitude_audit_email_template' ) ) {
            require_once ALTITUDE_AUDIT_PLUGIN_DIR . 'config/default-config.php';
            $default_template = altitude_audit_get_default_email_template();
            update_option( 'altitude_audit_email_template', $default_template );
        }

        // Set activation flag for setup wizard
        update_option( 'altitude_audit_show_wizard', true );

        // Set plugin version
        update_option( 'altitude_audit_version', ALTITUDE_AUDIT_VERSION );

        // Flush rewrite rules
        flush_rewrite_rules();
    }
}
