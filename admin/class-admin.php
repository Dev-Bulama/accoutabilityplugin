<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @package Altitude_Audit
 */

if ( ! defined( 'WPINC' ) ) {
    die;
}

/**
 * The admin-specific functionality of the plugin.
 */
class Altitude_Audit_Admin {

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
     * @param string $plugin_name The name of this plugin.
     * @param string $version The version of this plugin.
     */
    public function __construct( $plugin_name, $version ) {
        $this->plugin_name = $plugin_name;
        $this->version = $version;
    }

    /**
     * Register the stylesheets for the admin area.
     */
    public function enqueue_styles() {
        if ( $this->is_plugin_page() ) {
            wp_enqueue_style(
                $this->plugin_name,
                ALTITUDE_AUDIT_PLUGIN_URL . 'admin/css/admin.css',
                array(),
                $this->version,
                'all'
            );
        }
    }

    /**
     * Register the JavaScript for the admin area.
     */
    public function enqueue_scripts() {
        if ( $this->is_plugin_page() ) {
            wp_enqueue_script(
                $this->plugin_name,
                ALTITUDE_AUDIT_PLUGIN_URL . 'admin/js/admin.js',
                array( 'jquery' ),
                $this->version,
                true
            );

            wp_localize_script(
                $this->plugin_name,
                'altitudeAuditAdmin',
                array(
                    'ajaxUrl' => admin_url( 'admin-ajax.php' ),
                    'nonce' => wp_create_nonce( 'altitude_audit_admin' ),
                )
            );
        }
    }

    /**
     * Check if we're on a plugin page.
     *
     * @return bool
     */
    private function is_plugin_page() {
        $screen = get_current_screen();

        if ( ! $screen ) {
            return false;
        }

        return strpos( $screen->id, 'altitude-audit' ) !== false;
    }

    /**
     * Add plugin admin menu.
     */
    public function add_plugin_admin_menu() {
        // Main menu page
        add_menu_page(
            __( 'Accountability Audit', 'altitude-accountability-audit' ),
            __( 'Accountability Audit', 'altitude-accountability-audit' ),
            'manage_options',
            'altitude-audit',
            array( $this, 'display_dashboard_page' ),
            'dashicons-clipboard',
            58
        );

        // Dashboard submenu
        add_submenu_page(
            'altitude-audit',
            __( 'Dashboard', 'altitude-accountability-audit' ),
            __( 'Dashboard', 'altitude-accountability-audit' ),
            'manage_options',
            'altitude-audit',
            array( $this, 'display_dashboard_page' )
        );

        // Settings submenu
        add_submenu_page(
            'altitude-audit',
            __( 'Settings', 'altitude-accountability-audit' ),
            __( 'Settings', 'altitude-accountability-audit' ),
            'manage_options',
            'altitude-audit-settings',
            array( $this, 'display_settings_page' )
        );

        // Statistics submenu
        add_submenu_page(
            'altitude-audit',
            __( 'Statistics', 'altitude-accountability-audit' ),
            __( 'Statistics', 'altitude-accountability-audit' ),
            'manage_options',
            'altitude-audit-statistics',
            array( $this, 'display_statistics_page' )
        );

        // Email Template submenu
        add_submenu_page(
            'altitude-audit',
            __( 'Email Template', 'altitude-accountability-audit' ),
            __( 'Email Template', 'altitude-accountability-audit' ),
            'manage_options',
            'altitude-audit-email',
            array( $this, 'display_email_page' )
        );
    }

    /**
     * Display dashboard page.
     */
    public function display_dashboard_page() {
        require_once ALTITUDE_AUDIT_PLUGIN_DIR . 'admin/views/dashboard.php';
    }

    /**
     * Display settings page.
     */
    public function display_settings_page() {
        require_once ALTITUDE_AUDIT_PLUGIN_DIR . 'admin/views/settings.php';
    }

    /**
     * Display statistics page.
     */
    public function display_statistics_page() {
        require_once ALTITUDE_AUDIT_PLUGIN_DIR . 'admin/views/statistics.php';
    }

    /**
     * Display email template page.
     */
    public function display_email_page() {
        require_once ALTITUDE_AUDIT_PLUGIN_DIR . 'admin/views/email.php';
    }

    /**
     * Register settings.
     */
    public function register_settings() {
        // Register configuration option
        register_setting(
            'altitude_audit_settings',
            'altitude_audit_config',
            array(
                'sanitize_callback' => array( $this, 'sanitize_config' ),
            )
        );

        // Register email template option
        register_setting(
            'altitude_audit_email',
            'altitude_audit_email_template',
            array(
                'sanitize_callback' => 'wp_kses_post',
            )
        );

        // Handle AJAX requests
        add_action( 'wp_ajax_altitude_audit_rebuild_form', array( $this, 'ajax_rebuild_form' ) );
        add_action( 'wp_ajax_altitude_audit_test_email', array( $this, 'ajax_test_email' ) );
        add_action( 'wp_ajax_altitude_audit_preview_email', array( $this, 'ajax_preview_email' ) );
        add_action( 'wp_ajax_altitude_audit_export_config', array( $this, 'ajax_export_config' ) );
        add_action( 'wp_ajax_altitude_audit_import_config', array( $this, 'ajax_import_config' ) );
    }

    /**
     * Sanitize configuration.
     *
     * @param array $input Configuration input.
     * @return array Sanitized configuration.
     */
    public function sanitize_config( $input ) {
        if ( ! is_array( $input ) ) {
            return altitude_audit_get_default_config();
        }

        $sanitized = array();

        // Sanitize form settings
        if ( isset( $input['form_settings'] ) ) {
            $sanitized['form_settings'] = array(
                'form_title' => sanitize_text_field( $input['form_settings']['form_title'] ),
                'submit_button_text' => sanitize_text_field( $input['form_settings']['submit_button_text'] ),
                'success_message' => sanitize_textarea_field( $input['form_settings']['success_message'] ),
            );
        }

        // Sanitize email settings
        if ( isset( $input['email_settings'] ) ) {
            $sanitized['email_settings'] = array(
                'from_name' => sanitize_text_field( $input['email_settings']['from_name'] ),
                'from_email' => sanitize_email( $input['email_settings']['from_email'] ),
                'subject' => sanitize_text_field( $input['email_settings']['subject'] ),
                'send_to_admin' => ! empty( $input['email_settings']['send_to_admin'] ),
            );
        }

        // Sanitize result pages
        if ( isset( $input['result_pages'] ) ) {
            $sanitized['result_pages'] = array();

            foreach ( $input['result_pages'] as $key => $page ) {
                $sanitized['result_pages'][ $key ] = array(
                    'label' => sanitize_text_field( $page['label'] ),
                    'url' => esc_url_raw( $page['url'] ),
                    'description' => sanitize_textarea_field( $page['description'] ),
                );
            }
        }

        // Keep scoring options and categories as they are (validated on form rebuild)
        if ( isset( $input['scoring_options'] ) ) {
            $sanitized['scoring_options'] = $input['scoring_options'];
        }

        if ( isset( $input['categories'] ) ) {
            $sanitized['categories'] = $input['categories'];
        }

        return $sanitized;
    }

    /**
     * AJAX: Rebuild form (standalone version - form is always current).
     */
    public function ajax_rebuild_form() {
        check_ajax_referer( 'altitude_audit_admin', 'nonce' );

        if ( ! current_user_can( 'manage_options' ) ) {
            wp_send_json_error( array( 'message' => __( 'Permission denied', 'altitude-accountability-audit' ) ) );
        }

        // In standalone mode, form is rendered from config each time
        // No rebuild needed - just return success
        wp_send_json_success( array(
            'message' => __( 'Form is always up-to-date! Changes to settings take effect immediately.', 'altitude-accountability-audit' ),
        ) );
    }

    /**
     * AJAX: Send test email.
     */
    public function ajax_test_email() {
        check_ajax_referer( 'altitude_audit_admin', 'nonce' );

        if ( ! current_user_can( 'manage_options' ) ) {
            wp_send_json_error( array( 'message' => __( 'Permission denied', 'altitude-accountability-audit' ) ) );
        }

        $to_email = isset( $_POST['email'] ) ? sanitize_email( $_POST['email'] ) : get_option( 'admin_email' );

        $email_handler = new Altitude_Audit_Email_Handler();
        $sent = $email_handler->send_test_email( $to_email );

        if ( $sent ) {
            wp_send_json_success( array( 'message' => __( 'Test email sent successfully!', 'altitude-accountability-audit' ) ) );
        } else {
            wp_send_json_error( array( 'message' => __( 'Failed to send test email.', 'altitude-accountability-audit' ) ) );
        }
    }

    /**
     * AJAX: Preview email.
     */
    public function ajax_preview_email() {
        check_ajax_referer( 'altitude_audit_admin', 'nonce' );

        if ( ! current_user_can( 'manage_options' ) ) {
            wp_send_json_error( array( 'message' => __( 'Permission denied', 'altitude-accountability-audit' ) ) );
        }

        $email_handler = new Altitude_Audit_Email_Handler();
        $preview = $email_handler->preview_email();

        wp_send_json_success( array( 'html' => $preview ) );
    }

    /**
     * AJAX: Export configuration.
     */
    public function ajax_export_config() {
        check_ajax_referer( 'altitude_audit_admin', 'nonce' );

        if ( ! current_user_can( 'manage_options' ) ) {
            wp_send_json_error( array( 'message' => __( 'Permission denied', 'altitude-accountability-audit' ) ) );
        }

        $config = get_option( 'altitude_audit_config', altitude_audit_get_default_config() );
        $email_template = get_option( 'altitude_audit_email_template', altitude_audit_get_default_email_template() );

        $export = array(
            'config' => $config,
            'email_template' => $email_template,
            'version' => ALTITUDE_AUDIT_VERSION,
            'exported_at' => current_time( 'mysql' ),
        );

        wp_send_json_success( array( 'data' => $export ) );
    }

    /**
     * AJAX: Import configuration.
     */
    public function ajax_import_config() {
        check_ajax_referer( 'altitude_audit_admin', 'nonce' );

        if ( ! current_user_can( 'manage_options' ) ) {
            wp_send_json_error( array( 'message' => __( 'Permission denied', 'altitude-accountability-audit' ) ) );
        }

        $import_data = isset( $_POST['import_data'] ) ? json_decode( stripslashes( $_POST['import_data'] ), true ) : null;

        if ( ! $import_data || ! isset( $import_data['config'] ) ) {
            wp_send_json_error( array( 'message' => __( 'Invalid import data', 'altitude-accountability-audit' ) ) );
        }

        update_option( 'altitude_audit_config', $import_data['config'] );

        if ( isset( $import_data['email_template'] ) ) {
            update_option( 'altitude_audit_email_template', $import_data['email_template'] );
        }

        // Form will automatically use new config on next render
        wp_send_json_success( array( 'message' => __( 'Configuration imported successfully!', 'altitude-accountability-audit' ) ) );
    }
}
