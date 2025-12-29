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

        // Form Builder submenu
        add_submenu_page(
            'altitude-audit',
            __( 'Form Builder', 'altitude-accountability-audit' ),
            __( 'Form Builder', 'altitude-accountability-audit' ),
            'manage_options',
            'altitude-audit-builder',
            array( $this, 'display_builder_page' )
        );

        // Templates submenu
        add_submenu_page(
            'altitude-audit',
            __( 'Form Templates', 'altitude-accountability-audit' ),
            __( 'Templates', 'altitude-accountability-audit' ),
            'manage_options',
            'altitude-audit-templates',
            array( $this, 'display_templates_page' )
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

        // Documentation submenu
        add_submenu_page(
            'altitude-audit',
            __( 'Documentation', 'altitude-accountability-audit' ),
            __( 'Documentation', 'altitude-accountability-audit' ),
            'manage_options',
            'altitude-audit-docs',
            array( $this, 'display_documentation_page' )
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
     * Display form builder page.
     */
    public function display_builder_page() {
        require_once ALTITUDE_AUDIT_PLUGIN_DIR . 'admin/views/builder.php';
    }

    /**
     * Display templates page.
     */
    public function display_templates_page() {
        require_once ALTITUDE_AUDIT_PLUGIN_DIR . 'admin/views/templates.php';
    }

    /**
     * Display documentation page.
     */
    public function display_documentation_page() {
        require_once ALTITUDE_AUDIT_PLUGIN_DIR . 'admin/views/documentation.php';
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

        // Form Builder AJAX requests
        add_action( 'wp_ajax_altitude_audit_save_builder', array( $this, 'ajax_save_builder' ) );
        add_action( 'wp_ajax_altitude_audit_add_category', array( $this, 'ajax_add_category' ) );
        add_action( 'wp_ajax_altitude_audit_update_category', array( $this, 'ajax_update_category' ) );
        add_action( 'wp_ajax_altitude_audit_delete_category', array( $this, 'ajax_delete_category' ) );
        add_action( 'wp_ajax_altitude_audit_add_question', array( $this, 'ajax_add_question' ) );
        add_action( 'wp_ajax_altitude_audit_update_question', array( $this, 'ajax_update_question' ) );
        add_action( 'wp_ajax_altitude_audit_delete_question', array( $this, 'ajax_delete_question' ) );
        add_action( 'wp_ajax_altitude_audit_render_preview', array( $this, 'ajax_render_preview' ) );
        add_action( 'wp_ajax_altitude_audit_apply_template', array( $this, 'ajax_apply_template' ) );
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

        // Get current config to preserve categories and scoring options
        $current_config = get_option( 'altitude_audit_config', altitude_audit_get_default_config() );

        $sanitized = array();

        // Sanitize form settings
        if ( isset( $input['form_settings'] ) ) {
            $sanitized['form_settings'] = array(
                'form_title' => sanitize_text_field( $input['form_settings']['form_title'] ),
                'submit_button_text' => sanitize_text_field( $input['form_settings']['submit_button_text'] ),
                'success_message' => isset( $input['form_settings']['success_message'] ) ? sanitize_textarea_field( $input['form_settings']['success_message'] ) : '',
                'thank_you_message' => isset( $input['form_settings']['thank_you_message'] ) ? wp_kses_post( $input['form_settings']['thank_you_message'] ) : '',
            );
        } else {
            $sanitized['form_settings'] = $current_config['form_settings'];
        }

        // Sanitize email settings
        if ( isset( $input['email_settings'] ) ) {
            $sanitized['email_settings'] = array(
                'from_name' => sanitize_text_field( $input['email_settings']['from_name'] ),
                'from_email' => sanitize_email( $input['email_settings']['from_email'] ),
                'subject' => sanitize_text_field( $input['email_settings']['subject'] ),
                'send_to_admin' => ! empty( $input['email_settings']['send_to_admin'] ),
            );
        } else {
            $sanitized['email_settings'] = $current_config['email_settings'];
        }

        // Sanitize result pages
        if ( isset( $input['result_pages'] ) && is_array( $input['result_pages'] ) ) {
            $sanitized['result_pages'] = array();

            foreach ( $input['result_pages'] as $key => $page ) {
                if ( is_array( $page ) ) {
                    $sanitized['result_pages'][ $key ] = array(
                        'label' => isset( $page['label'] ) ? sanitize_text_field( $page['label'] ) : '',
                        'url' => isset( $page['url'] ) ? esc_url_raw( $page['url'] ) : '',
                        'description' => isset( $page['description'] ) ? sanitize_textarea_field( $page['description'] ) : '',
                    );
                }
            }
        } else {
            $sanitized['result_pages'] = isset( $current_config['result_pages'] ) ? $current_config['result_pages'] : array();
        }

        // ALWAYS preserve scoring options and categories from current config
        // These are only modified through Form Builder, not Settings page
        $sanitized['scoring_options'] = isset( $current_config['scoring_options'] ) && is_array( $current_config['scoring_options'] )
            ? $current_config['scoring_options']
            : altitude_audit_get_default_config()['scoring_options'];

        $sanitized['categories'] = isset( $current_config['categories'] ) && is_array( $current_config['categories'] )
            ? $current_config['categories']
            : altitude_audit_get_default_config()['categories'];

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

    /**
     * AJAX: Save entire form builder configuration.
     */
    public function ajax_save_builder() {
        check_ajax_referer( 'altitude_audit_admin', 'nonce' );

        if ( ! current_user_can( 'manage_options' ) ) {
            wp_send_json_error( array( 'message' => __( 'Permission denied', 'altitude-accountability-audit' ) ) );
        }

        $categories = isset( $_POST['categories'] ) ? json_decode( stripslashes( $_POST['categories'] ), true ) : null;

        if ( ! $categories ) {
            wp_send_json_error( array( 'message' => __( 'Invalid categories data', 'altitude-accountability-audit' ) ) );
        }

        $config = get_option( 'altitude_audit_config', altitude_audit_get_default_config() );
        $config['categories'] = $categories;
        update_option( 'altitude_audit_config', $config );

        wp_send_json_success( array( 'message' => __( 'Form builder saved successfully!', 'altitude-accountability-audit' ) ) );
    }

    /**
     * AJAX: Add new category.
     */
    public function ajax_add_category() {
        check_ajax_referer( 'altitude_audit_admin', 'nonce' );

        if ( ! current_user_can( 'manage_options' ) ) {
            wp_send_json_error( array( 'message' => __( 'Permission denied', 'altitude-accountability-audit' ) ) );
        }

        $category_key = sanitize_key( $_POST['key'] ?? 'new_category_' . time() );
        $label = sanitize_text_field( $_POST['label'] ?? 'New Category' );
        $icon = sanitize_text_field( $_POST['icon'] ?? '📝' );

        $config = get_option( 'altitude_audit_config', altitude_audit_get_default_config() );

        $config['categories'][ $category_key ] = array(
            'label' => $label,
            'description' => '',
            'icon' => $icon,
            'questions' => array(),
        );

        // Add result page entry
        $config['result_pages'][ $category_key ] = array(
            'label' => 'The ' . $label,
            'url' => '',
            'description' => '',
        );

        update_option( 'altitude_audit_config', $config );

        wp_send_json_success( array(
            'message' => __( 'Category added successfully!', 'altitude-accountability-audit' ),
            'category' => $config['categories'][ $category_key ],
            'category_key' => $category_key,
        ) );
    }

    /**
     * AJAX: Update category.
     */
    public function ajax_update_category() {
        check_ajax_referer( 'altitude_audit_admin', 'nonce' );

        if ( ! current_user_can( 'manage_options' ) ) {
            wp_send_json_error( array( 'message' => __( 'Permission denied', 'altitude-accountability-audit' ) ) );
        }

        $category_key = sanitize_key( $_POST['key'] ?? '' );
        $data = json_decode( stripslashes( $_POST['data'] ?? '{}' ), true );

        if ( ! $category_key || ! $data ) {
            wp_send_json_error( array( 'message' => __( 'Invalid category data', 'altitude-accountability-audit' ) ) );
        }

        $config = get_option( 'altitude_audit_config', altitude_audit_get_default_config() );

        if ( ! isset( $config['categories'][ $category_key ] ) ) {
            wp_send_json_error( array( 'message' => __( 'Category not found', 'altitude-accountability-audit' ) ) );
        }

        $config['categories'][ $category_key ]['label'] = sanitize_text_field( $data['label'] ?? '' );
        $config['categories'][ $category_key ]['description'] = sanitize_textarea_field( $data['description'] ?? '' );
        $config['categories'][ $category_key ]['icon'] = sanitize_text_field( $data['icon'] ?? '📝' );

        update_option( 'altitude_audit_config', $config );

        wp_send_json_success( array( 'message' => __( 'Category updated successfully!', 'altitude-accountability-audit' ) ) );
    }

    /**
     * AJAX: Delete category.
     */
    public function ajax_delete_category() {
        check_ajax_referer( 'altitude_audit_admin', 'nonce' );

        if ( ! current_user_can( 'manage_options' ) ) {
            wp_send_json_error( array( 'message' => __( 'Permission denied', 'altitude-accountability-audit' ) ) );
        }

        $category_key = sanitize_key( $_POST['key'] ?? '' );

        if ( ! $category_key ) {
            wp_send_json_error( array( 'message' => __( 'Invalid category key', 'altitude-accountability-audit' ) ) );
        }

        $config = get_option( 'altitude_audit_config', altitude_audit_get_default_config() );

        if ( ! isset( $config['categories'][ $category_key ] ) ) {
            wp_send_json_error( array( 'message' => __( 'Category not found', 'altitude-accountability-audit' ) ) );
        }

        unset( $config['categories'][ $category_key ] );
        unset( $config['result_pages'][ $category_key ] );

        update_option( 'altitude_audit_config', $config );

        wp_send_json_success( array( 'message' => __( 'Category deleted successfully!', 'altitude-accountability-audit' ) ) );
    }

    /**
     * AJAX: Add question.
     */
    public function ajax_add_question() {
        check_ajax_referer( 'altitude_audit_admin', 'nonce' );

        if ( ! current_user_can( 'manage_options' ) ) {
            wp_send_json_error( array( 'message' => __( 'Permission denied', 'altitude-accountability-audit' ) ) );
        }

        $category_key = sanitize_key( $_POST['category'] ?? '' );
        $label = sanitize_textarea_field( $_POST['label'] ?? 'New question?' );

        if ( ! $category_key ) {
            wp_send_json_error( array( 'message' => __( 'Invalid category key', 'altitude-accountability-audit' ) ) );
        }

        $config = get_option( 'altitude_audit_config', altitude_audit_get_default_config() );

        if ( ! isset( $config['categories'][ $category_key ] ) ) {
            wp_send_json_error( array( 'message' => __( 'Category not found', 'altitude-accountability-audit' ) ) );
        }

        // Ensure questions array exists
        if ( ! isset( $config['categories'][ $category_key ]['questions'] ) || ! is_array( $config['categories'][ $category_key ]['questions'] ) ) {
            $config['categories'][ $category_key ]['questions'] = array();
        }

        $question_count = count( $config['categories'][ $category_key ]['questions'] ) + 1;

        $new_question = array(
            'label' => $label,
            'name' => $category_key . '_q' . $question_count,
            'help_text' => '',
        );

        $config['categories'][ $category_key ]['questions'][] = $new_question;

        update_option( 'altitude_audit_config', $config );

        wp_send_json_success( array(
            'message' => __( 'Question added successfully!', 'altitude-accountability-audit' ),
            'question' => $new_question,
        ) );
    }

    /**
     * AJAX: Update question.
     */
    public function ajax_update_question() {
        check_ajax_referer( 'altitude_audit_admin', 'nonce' );

        if ( ! current_user_can( 'manage_options' ) ) {
            wp_send_json_error( array( 'message' => __( 'Permission denied', 'altitude-accountability-audit' ) ) );
        }

        $category_key = sanitize_key( $_POST['category'] ?? '' );
        $question_index = absint( $_POST['index'] ?? -1 );
        $data = json_decode( stripslashes( $_POST['data'] ?? '{}' ), true );

        if ( ! $category_key || $question_index < 0 || ! $data ) {
            wp_send_json_error( array( 'message' => __( 'Invalid question data', 'altitude-accountability-audit' ) ) );
        }

        $config = get_option( 'altitude_audit_config', altitude_audit_get_default_config() );

        if ( ! isset( $config['categories'][ $category_key ]['questions'][ $question_index ] ) ) {
            wp_send_json_error( array( 'message' => __( 'Question not found', 'altitude-accountability-audit' ) ) );
        }

        $config['categories'][ $category_key ]['questions'][ $question_index ]['label'] = sanitize_textarea_field( $data['label'] ?? '' );
        $config['categories'][ $category_key ]['questions'][ $question_index ]['help_text'] = sanitize_text_field( $data['help_text'] ?? '' );

        update_option( 'altitude_audit_config', $config );

        wp_send_json_success( array( 'message' => __( 'Question updated successfully!', 'altitude-accountability-audit' ) ) );
    }

    /**
     * AJAX: Delete question.
     */
    public function ajax_delete_question() {
        check_ajax_referer( 'altitude_audit_admin', 'nonce' );

        if ( ! current_user_can( 'manage_options' ) ) {
            wp_send_json_error( array( 'message' => __( 'Permission denied', 'altitude-accountability-audit' ) ) );
        }

        $category_key = sanitize_key( $_POST['category'] ?? '' );
        $question_index = absint( $_POST['index'] ?? -1 );

        if ( ! $category_key || $question_index < 0 ) {
            wp_send_json_error( array( 'message' => __( 'Invalid question index', 'altitude-accountability-audit' ) ) );
        }

        $config = get_option( 'altitude_audit_config', altitude_audit_get_default_config() );

        if ( ! isset( $config['categories'][ $category_key ]['questions'][ $question_index ] ) ) {
            wp_send_json_error( array( 'message' => __( 'Question not found', 'altitude-accountability-audit' ) ) );
        }

        array_splice( $config['categories'][ $category_key ]['questions'], $question_index, 1 );

        // Regenerate field names
        foreach ( $config['categories'][ $category_key ]['questions'] as $index => $question ) {
            $config['categories'][ $category_key ]['questions'][ $index ]['name'] = $category_key . '_q' . ( $index + 1 );
        }

        update_option( 'altitude_audit_config', $config );

        wp_send_json_success( array( 'message' => __( 'Question deleted successfully!', 'altitude-accountability-audit' ) ) );
    }

    /**
     * AJAX: Render form preview.
     */
    public function ajax_render_preview() {
        check_ajax_referer( 'altitude_audit_admin', 'nonce' );

        if ( ! current_user_can( 'manage_options' ) ) {
            wp_send_json_error( array( 'message' => __( 'Permission denied', 'altitude-accountability-audit' ) ) );
        }

        // Render the form
        $html = Altitude_Audit_Form_Builder::render_form();

        wp_send_json_success( array( 'html' => $html ) );
    }

    /**
     * AJAX: Apply form template.
     */
    public function ajax_apply_template() {
        check_ajax_referer( 'altitude_audit_admin', 'nonce' );

        if ( ! current_user_can( 'manage_options' ) ) {
            wp_send_json_error( array( 'message' => __( 'Permission denied', 'altitude-accountability-audit' ) ) );
        }

        $template_id = sanitize_key( $_POST['template_id'] ?? '' );

        if ( ! $template_id ) {
            wp_send_json_error( array( 'message' => __( 'Invalid template ID', 'altitude-accountability-audit' ) ) );
        }

        // Load templates
        require_once ALTITUDE_AUDIT_PLUGIN_DIR . 'config/templates.php';
        $templates = altitude_audit_get_templates();

        if ( ! isset( $templates[ $template_id ] ) ) {
            wp_send_json_error( array( 'message' => __( 'Template not found', 'altitude-accountability-audit' ) ) );
        }

        $template = $templates[ $template_id ];

        // Get current config to preserve form settings and result pages
        $current_config = get_option( 'altitude_audit_config', altitude_audit_get_default_config() );

        // Build new config with template categories
        $new_config = array(
            'categories' => $template['categories'],
            'scoring_options' => $current_config['scoring_options'],
            'form_settings' => $current_config['form_settings'],
            'email_settings' => $current_config['email_settings'],
            'result_pages' => array(),
        );

        // Create result pages for each category
        foreach ( $template['categories'] as $category_key => $category ) {
            $new_config['result_pages'][ $category_key ] = array(
                'label' => 'The ' . $category['label'],
                'url' => '',
                'description' => $category['description'],
            );
        }

        // Update configuration
        update_option( 'altitude_audit_config', $new_config );

        wp_send_json_success( array(
            'message' => sprintf(
                __( 'Template "%s" applied successfully! Redirecting to Form Builder...', 'altitude-accountability-audit' ),
                $template['name']
            ),
        ) );
    }
}
