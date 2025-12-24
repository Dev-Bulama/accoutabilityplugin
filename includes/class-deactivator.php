<?php
/**
 * Fired during plugin deactivation.
 *
 * @package Altitude_Audit
 */

if ( ! defined( 'WPINC' ) ) {
    die;
}

/**
 * Fired during plugin deactivation.
 */
class Altitude_Audit_Deactivator {

    /**
     * Deactivate the plugin.
     */
    public static function deactivate() {
        // Flush rewrite rules
        flush_rewrite_rules();

        // Note: We don't delete data on deactivation, only on uninstall
        // This preserves user data if they temporarily deactivate
    }
}
