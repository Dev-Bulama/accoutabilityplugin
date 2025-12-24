<?php
/**
 * Fired when the plugin is uninstalled.
 *
 * @package Altitude_Audit
 */

// If uninstall not called from WordPress, then exit.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
    exit;
}

global $wpdb;

// Delete options
delete_option( 'altitude_audit_config' );
delete_option( 'altitude_audit_email_template' );
delete_option( 'altitude_audit_form_id' );
delete_option( 'altitude_audit_version' );
delete_option( 'altitude_audit_show_wizard' );

// Delete database table
$table_name = $wpdb->prefix . 'altitude_audit_results';
$wpdb->query( "DROP TABLE IF EXISTS $table_name" );

// Delete form from Fluent Forms
$form_id = get_option( 'altitude_audit_form_id' );
if ( $form_id ) {
    $wpdb->delete(
        $wpdb->prefix . 'fluentform_forms',
        array( 'id' => $form_id ),
        array( '%d' )
    );

    $wpdb->delete(
        $wpdb->prefix . 'fluentform_form_meta',
        array( 'form_id' => $form_id ),
        array( '%d' )
    );
}

// Clear any cached data
wp_cache_flush();
