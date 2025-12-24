<?php
/**
 * Form builder for creating Fluent Forms programmatically.
 *
 * @package Altitude_Audit
 */

if ( ! defined( 'WPINC' ) ) {
    die;
}

/**
 * Handles programmatic creation of Fluent Forms.
 */
class Altitude_Audit_Form_Builder {

    /**
     * Create or update the audit form.
     *
     * @return int|WP_Error Form ID or error.
     */
    public static function create_audit_form() {
        if ( ! defined( 'FLUENTFORM' ) ) {
            return new WP_Error( 'fluent_forms_missing', __( 'Fluent Forms is not active.', 'altitude-accountability-audit' ) );
        }

        global $wpdb;

        // Get configuration
        $config = get_option( 'altitude_audit_config', altitude_audit_get_default_config() );

        // Check if form already exists
        $existing_form_id = get_option( 'altitude_audit_form_id' );

        if ( $existing_form_id ) {
            $form = $wpdb->get_row(
                $wpdb->prepare(
                    "SELECT * FROM {$wpdb->prefix}fluentform_forms WHERE id = %d",
                    $existing_form_id
                )
            );

            if ( $form ) {
                // Update existing form
                return self::update_form( $existing_form_id, $config );
            }
        }

        // Create new form
        return self::insert_form( $config );
    }

    /**
     * Insert a new form.
     *
     * @param array $config Configuration array.
     * @return int|WP_Error Form ID or error.
     */
    private static function insert_form( $config ) {
        global $wpdb;

        $form_data = array(
            'title' => $config['form_settings']['form_title'],
            'form_fields' => wp_json_encode( self::build_form_fields( $config ) ),
            'status' => 'published',
            'has_payment' => 0,
            'type' => 'form',
            'created_by' => get_current_user_id(),
            'created_at' => current_time( 'mysql' ),
            'updated_at' => current_time( 'mysql' ),
        );

        $inserted = $wpdb->insert(
            $wpdb->prefix . 'fluentform_forms',
            $form_data
        );

        if ( ! $inserted ) {
            return new WP_Error( 'form_creation_failed', __( 'Failed to create form.', 'altitude-accountability-audit' ) );
        }

        $form_id = $wpdb->insert_id;

        // Store form ID
        update_option( 'altitude_audit_form_id', $form_id );

        // Set form settings
        self::set_form_settings( $form_id, $config );

        return $form_id;
    }

    /**
     * Update existing form.
     *
     * @param int   $form_id Form ID.
     * @param array $config Configuration array.
     * @return int Form ID.
     */
    private static function update_form( $form_id, $config ) {
        global $wpdb;

        $wpdb->update(
            $wpdb->prefix . 'fluentform_forms',
            array(
                'title' => $config['form_settings']['form_title'],
                'form_fields' => wp_json_encode( self::build_form_fields( $config ) ),
                'updated_at' => current_time( 'mysql' ),
            ),
            array( 'id' => $form_id )
        );

        // Update form settings
        self::set_form_settings( $form_id, $config );

        return $form_id;
    }

    /**
     * Build form fields array.
     *
     * @param array $config Configuration array.
     * @return array Form fields.
     */
    private static function build_form_fields( $config ) {
        $fields = array(
            'fields' => array(),
            'submitButton' => array(
                'uniqElKey' => 'el_' . uniqid(),
                'element' => 'button',
                'attributes' => array(
                    'type' => 'submit',
                    'class' => 'ff-btn ff-btn-submit ff-btn-md altitude-audit-submit',
                ),
                'settings' => array(
                    'container_class' => '',
                    'align' => 'left',
                    'button_style' => 'default',
                    'button_size' => 'md',
                ),
                'editor_options' => array(
                    'title' => 'Submit Button',
                ),
                'button_ui' => array(
                    'text' => $config['form_settings']['submit_button_text'],
                    'type' => 'submit',
                    'img_url' => '',
                ),
            ),
            'stacked' => false,
        );

        $field_index = 0;

        // Add First Name field
        $fields['fields'][] = array(
            'index' => $field_index++,
            'element' => 'input_text',
            'attributes' => array(
                'type' => 'text',
                'name' => 'first_name',
                'value' => '',
                'id' => '',
                'class' => '',
                'placeholder' => __( 'Enter your first name', 'altitude-accountability-audit' ),
            ),
            'settings' => array(
                'container_class' => '',
                'label' => __( 'First Name', 'altitude-accountability-audit' ),
                'label_placement' => '',
                'help_message' => '',
                'admin_field_label' => 'First Name',
                'validation_rules' => array(
                    'required' => array(
                        'value' => true,
                        'message' => __( 'This field is required', 'altitude-accountability-audit' ),
                    ),
                ),
            ),
            'editor_options' => array(
                'title' => 'First Name',
                'icon_class' => 'ff-edit-name',
                'template' => 'inputText',
            ),
            'uniqElKey' => 'el_' . uniqid(),
        );

        // Add Email field
        $fields['fields'][] = array(
            'index' => $field_index++,
            'element' => 'input_email',
            'attributes' => array(
                'type' => 'email',
                'name' => 'email',
                'value' => '',
                'id' => '',
                'class' => '',
                'placeholder' => __( 'Enter your email', 'altitude-accountability-audit' ),
            ),
            'settings' => array(
                'container_class' => '',
                'label' => __( 'Email Address', 'altitude-accountability-audit' ),
                'label_placement' => '',
                'help_message' => __( 'We\'ll send your results here', 'altitude-accountability-audit' ),
                'admin_field_label' => 'Email',
                'validation_rules' => array(
                    'required' => array(
                        'value' => true,
                        'message' => __( 'This field is required', 'altitude-accountability-audit' ),
                    ),
                    'email' => array(
                        'value' => true,
                        'message' => __( 'Please provide a valid email address', 'altitude-accountability-audit' ),
                    ),
                ),
            ),
            'editor_options' => array(
                'title' => 'Email',
                'icon_class' => 'ff-edit-email',
                'template' => 'inputText',
            ),
            'uniqElKey' => 'el_' . uniqid(),
        );

        // Add category questions
        $question_number = 1;
        foreach ( $config['categories'] as $category_key => $category ) {
            // Add section heading
            $fields['fields'][] = array(
                'index' => $field_index++,
                'element' => 'section_break',
                'attributes' => array(
                    'id' => '',
                    'class' => 'altitude-audit-category-section',
                ),
                'settings' => array(
                    'label' => $category['icon'] . ' ' . $category['label'],
                    'description' => $category['description'],
                    'align' => 'left',
                    'container_class' => '',
                ),
                'editor_options' => array(
                    'title' => $category['label'],
                    'icon_class' => 'ff-edit-section-break',
                    'element' => 'section-break',
                ),
                'uniqElKey' => 'el_' . uniqid(),
            );

            // Add questions
            foreach ( $category['questions'] as $question ) {
                $fields['fields'][] = array(
                    'index' => $field_index++,
                    'element' => 'input_radio',
                    'attributes' => array(
                        'type' => 'radio',
                        'name' => $question['name'],
                        'value' => '',
                        'id' => '',
                        'class' => 'altitude-audit-question',
                    ),
                    'settings' => array(
                        'container_class' => 'altitude-audit-question-wrapper',
                        'label' => $question_number . '. ' . $question['label'],
                        'admin_field_label' => $question['label'],
                        'label_placement' => '',
                        'help_message' => $question['help_text'],
                        'validation_rules' => array(
                            'required' => array(
                                'value' => true,
                                'message' => __( 'Please select an option', 'altitude-accountability-audit' ),
                            ),
                        ),
                    ),
                    'options' => self::build_scoring_options( $config['scoring_options'] ),
                    'editor_options' => array(
                        'title' => 'Question ' . $question_number,
                        'icon_class' => 'ff-edit-radio',
                        'template' => 'inputRadio',
                    ),
                    'uniqElKey' => 'el_' . uniqid(),
                );

                $question_number++;
            }

            // Add hidden field for category score
            $fields['fields'][] = array(
                'index' => $field_index++,
                'element' => 'input_hidden',
                'attributes' => array(
                    'type' => 'hidden',
                    'name' => $category_key . '_score',
                    'value' => '0',
                    'class' => 'altitude-audit-category-score',
                    'data-category' => $category_key,
                ),
                'settings' => array(
                    'admin_field_label' => $category['label'] . ' Score',
                ),
                'editor_options' => array(
                    'title' => $category['label'] . ' Score',
                    'icon_class' => 'ff-edit-hidden',
                    'template' => 'inputHidden',
                ),
                'uniqElKey' => 'el_' . uniqid(),
            );
        }

        // Add hidden field for total score
        $fields['fields'][] = array(
            'index' => $field_index++,
            'element' => 'input_hidden',
            'attributes' => array(
                'type' => 'hidden',
                'name' => 'total_score',
                'value' => '0',
                'class' => 'altitude-audit-total-score',
            ),
            'settings' => array(
                'admin_field_label' => 'Total Score',
            ),
            'editor_options' => array(
                'title' => 'Total Score',
                'icon_class' => 'ff-edit-hidden',
                'template' => 'inputHidden',
            ),
            'uniqElKey' => 'el_' . uniqid(),
        );

        // Add hidden field for accountability type
        $fields['fields'][] = array(
            'index' => $field_index++,
            'element' => 'input_hidden',
            'attributes' => array(
                'type' => 'hidden',
                'name' => 'accountability_type',
                'value' => '',
                'class' => 'altitude-audit-accountability-type',
            ),
            'settings' => array(
                'admin_field_label' => 'Accountability Type',
            ),
            'editor_options' => array(
                'title' => 'Accountability Type',
                'icon_class' => 'ff-edit-hidden',
                'template' => 'inputHidden',
            ),
            'uniqElKey' => 'el_' . uniqid(),
        );

        return $fields;
    }

    /**
     * Build scoring options for radio buttons.
     *
     * @param array $scoring_options Scoring options from config.
     * @return array
     */
    private static function build_scoring_options( $scoring_options ) {
        $options = array();

        foreach ( $scoring_options as $option ) {
            $options[ $option['value'] ] = array(
                'label' => $option['label'],
                'value' => $option['value'],
                'calc_value' => (string) $option['points'],
            );
        }

        return $options;
    }

    /**
     * Set form settings.
     *
     * @param int   $form_id Form ID.
     * @param array $config Configuration array.
     */
    private static function set_form_settings( $form_id, $config ) {
        global $wpdb;

        // Confirmation settings
        $confirmation_settings = array(
            'redirectTo' => 'samePage',
            'messageToShow' => $config['form_settings']['success_message'],
            'customUrl' => '',
            'samePageFormBehavior' => 'hide_form',
        );

        // Email notification settings
        $email_settings = array(
            'sendTo' => array(
                'type' => 'field',
                'field' => 'email',
                'routing' => array(),
            ),
            'fromName' => $config['email_settings']['from_name'],
            'fromEmail' => $config['email_settings']['from_email'],
            'replyTo' => '',
            'bcc' => '',
            'subject' => $config['email_settings']['subject'],
            'message' => get_option( 'altitude_audit_email_template', altitude_audit_get_default_email_template() ),
            'conditionals' => array(),
        );

        // Delete existing settings
        $wpdb->delete(
            $wpdb->prefix . 'fluentform_form_meta',
            array( 'form_id' => $form_id ),
            array( '%d' )
        );

        // Insert confirmation settings
        $wpdb->insert(
            $wpdb->prefix . 'fluentform_form_meta',
            array(
                'form_id' => $form_id,
                'meta_key' => 'confirmations',
                'value' => wp_json_encode( $confirmation_settings ),
            )
        );

        // Insert notification settings
        $wpdb->insert(
            $wpdb->prefix . 'fluentform_form_meta',
            array(
                'form_id' => $form_id,
                'meta_key' => 'notifications',
                'value' => wp_json_encode( array(
                    'name' => 'Audit Results Email',
                    'enabled' => true,
                    'settings' => $email_settings,
                ) ),
            )
        );

        // Insert custom CSS
        $custom_css = self::get_form_custom_css();
        $wpdb->insert(
            $wpdb->prefix . 'fluentform_form_meta',
            array(
                'form_id' => $form_id,
                'meta_key' => 'custom_css',
                'value' => $custom_css,
            )
        );
    }

    /**
     * Get custom CSS for the form.
     *
     * @return string
     */
    private static function get_form_custom_css() {
        return <<<CSS
.altitude-audit-category-section h3 {
    font-size: 1.5em;
    margin-top: 30px;
    margin-bottom: 10px;
    color: #667eea;
}

.altitude-audit-question-wrapper {
    margin-bottom: 25px;
    padding: 15px;
    background: #f7f9fc;
    border-radius: 5px;
}

.altitude-audit-submit {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
    padding: 15px 40px;
    font-size: 16px;
    font-weight: bold;
    color: white;
    cursor: pointer;
    transition: transform 0.2s;
}

.altitude-audit-submit:hover {
    transform: translateY(-2px);
}
CSS;
    }

    /**
     * Get form ID.
     *
     * @return int|false
     */
    public static function get_form_id() {
        return get_option( 'altitude_audit_form_id', false );
    }

    /**
     * Delete the audit form.
     *
     * @return bool
     */
    public static function delete_audit_form() {
        global $wpdb;

        $form_id = self::get_form_id();

        if ( ! $form_id ) {
            return false;
        }

        // Delete form
        $wpdb->delete(
            $wpdb->prefix . 'fluentform_forms',
            array( 'id' => $form_id ),
            array( '%d' )
        );

        // Delete form meta
        $wpdb->delete(
            $wpdb->prefix . 'fluentform_form_meta',
            array( 'form_id' => $form_id ),
            array( '%d' )
        );

        // Delete form option
        delete_option( 'altitude_audit_form_id' );

        return true;
    }
}
