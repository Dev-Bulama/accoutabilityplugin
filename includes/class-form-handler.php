<?php
/**
 * Form submission handler
 *
 * @package Altitude_Audit
 */

if ( ! defined( 'WPINC' ) ) {
    die;
}

/**
 * Handles form submission processing.
 */
class Altitude_Audit_Form_Handler {

    /**
     * Initialize the form handler.
     */
    public static function init() {
        // Handle form submission
        add_action( 'admin_post_altitude_audit_submit', array( __CLASS__, 'handle_submission' ) );
        add_action( 'admin_post_nopriv_altitude_audit_submit', array( __CLASS__, 'handle_submission' ) );
    }

    /**
     * Handle form submission.
     */
    public static function handle_submission() {
        // Verify nonce
        if ( ! isset( $_POST['altitude_audit_nonce'] ) || ! wp_verify_nonce( $_POST['altitude_audit_nonce'], 'altitude_audit_submit' ) ) {
            wp_die( esc_html__( 'Security check failed. Please try again.', 'altitude-accountability-audit' ), esc_html__( 'Error', 'altitude-accountability-audit' ), array( 'response' => 403 ) );
        }

        // Get and sanitize form data
        $form_data = self::sanitize_form_data( $_POST );

        // Validate required fields
        $errors = self::validate_form_data( $form_data );

        if ( ! empty( $errors ) ) {
            wp_die( implode( '<br>', $errors ), esc_html__( 'Validation Error', 'altitude-accountability-audit' ), array( 'response' => 400, 'back_link' => true ) );
        }

        // Calculate scores
        $scores = self::calculate_scores( $form_data );

        // Determine accountability type
        $accountability_type = self::determine_accountability_type( $scores );

        // Prepare result data (dynamic based on categories in scores)
        $result_data = array(
            'user_id' => get_current_user_id(),
            'submission_id' => 0, // We'll use the database ID as submission ID
            'first_name' => $form_data['first_name'],
            'email' => $form_data['email'],
            'total_score' => $scores['total'],
            'accountability_type' => $accountability_type,
        );

        // Add all category scores dynamically
        foreach ( $scores as $key => $value ) {
            if ( $key !== 'total' ) {
                $result_data[ $key . '_score' ] = $value;
            }
        }

        // Save to database
        $result_id = Altitude_Audit_Database::save_result( $result_data );

        if ( ! $result_id ) {
            wp_die( esc_html__( 'Failed to save results. Please try again.', 'altitude-accountability-audit' ), esc_html__( 'Error', 'altitude-accountability-audit' ), array( 'response' => 500, 'back_link' => true ) );
        }

        // Update submission ID
        $result_data['submission_id'] = $result_id;

        // Send email
        $config = get_option( 'altitude_audit_config', altitude_audit_get_default_config() );
        $email_handler = new Altitude_Audit_Email_Handler();
        $email_handler->send_result_email( $result_data, $config );

        // Show custom thank you message with results
        self::display_thank_you_page( $result_data, $scores, $accountability_type, $config );
    }

    /**
     * Sanitize form data.
     *
     * @param array $post_data POST data.
     * @return array Sanitized data.
     */
    private static function sanitize_form_data( $post_data ) {
        $sanitized = array();

        // Personal info
        $sanitized['first_name'] = isset( $post_data['first_name'] ) ? sanitize_text_field( $post_data['first_name'] ) : '';
        $sanitized['email'] = isset( $post_data['email'] ) ? sanitize_email( $post_data['email'] ) : '';

        // Quiz answers
        $config = get_option( 'altitude_audit_config', altitude_audit_get_default_config() );

        // Validate config structure
        if ( ! isset( $config['categories'] ) || ! is_array( $config['categories'] ) ) {
            $config = altitude_audit_get_default_config();
            update_option( 'altitude_audit_config', $config );
        }

        foreach ( $config['categories'] as $category_key => $category ) {
            if ( ! isset( $category['questions'] ) || ! is_array( $category['questions'] ) ) {
                continue;
            }

            foreach ( $category['questions'] as $index => $question ) {
                $field_name = $category_key . '_q' . ( $index + 1 );
                $sanitized[ $field_name ] = isset( $post_data[ $field_name ] ) ? absint( $post_data[ $field_name ] ) : null;
            }
        }

        return $sanitized;
    }

    /**
     * Validate form data.
     *
     * @param array $form_data Form data.
     * @return array Errors.
     */
    private static function validate_form_data( $form_data ) {
        $errors = array();

        // Validate name
        if ( empty( $form_data['first_name'] ) ) {
            $errors[] = __( 'First name is required.', 'altitude-accountability-audit' );
        }

        // Validate email
        if ( empty( $form_data['email'] ) ) {
            $errors[] = __( 'Email address is required.', 'altitude-accountability-audit' );
        } elseif ( ! is_email( $form_data['email'] ) ) {
            $errors[] = __( 'Please provide a valid email address.', 'altitude-accountability-audit' );
        }

        // Validate all questions are answered
        $config = get_option( 'altitude_audit_config', altitude_audit_get_default_config() );

        // Validate config structure
        if ( ! isset( $config['categories'] ) || ! is_array( $config['categories'] ) ) {
            $config = altitude_audit_get_default_config();
            update_option( 'altitude_audit_config', $config );
        }

        foreach ( $config['categories'] as $category_key => $category ) {
            if ( ! isset( $category['questions'] ) || ! is_array( $category['questions'] ) ) {
                continue;
            }

            foreach ( $category['questions'] as $index => $question ) {
                $field_name = $category_key . '_q' . ( $index + 1 );

                if ( ! isset( $form_data[ $field_name ] ) || $form_data[ $field_name ] === null ) {
                    $errors[] = sprintf(
                        /* translators: %s is the question text */
                        __( 'Please answer: %s', 'altitude-accountability-audit' ),
                        isset( $question['label'] ) ? $question['label'] : $field_name
                    );
                }
            }
        }

        return $errors;
    }

    /**
     * Calculate scores from form data.
     *
     * @param array $form_data Form data.
     * @return array Scores.
     */
    private static function calculate_scores( $form_data ) {
        $config = get_option( 'altitude_audit_config', altitude_audit_get_default_config() );

        // Validate config structure
        if ( ! isset( $config['categories'] ) || ! is_array( $config['categories'] ) ) {
            $config = altitude_audit_get_default_config();
            update_option( 'altitude_audit_config', $config );
        }

        // Initialize scores array dynamically based on config
        $scores = array( 'total' => 0 );

        foreach ( $config['categories'] as $category_key => $category ) {
            if ( ! isset( $category['questions'] ) || ! is_array( $category['questions'] ) ) {
                $scores[ $category_key ] = 0;
                continue;
            }

            $category_score = 0;

            foreach ( $category['questions'] as $index => $question ) {
                $field_name = $category_key . '_q' . ( $index + 1 );

                if ( isset( $form_data[ $field_name ] ) ) {
                    $category_score += absint( $form_data[ $field_name ] );
                }
            }

            $scores[ $category_key ] = $category_score;
            $scores['total'] += $category_score;
        }

        return $scores;
    }

    /**
     * Determine accountability type from scores.
     *
     * @param array $scores Scores array.
     * @return string Accountability type.
     */
    private static function determine_accountability_type( $scores ) {
        // Remove total from comparison
        $category_scores = $scores;
        unset( $category_scores['total'] );

        // Find highest scoring category
        $max_score = max( $category_scores );
        $highest_categories = array();

        foreach ( $category_scores as $category => $score ) {
            if ( $score === $max_score ) {
                $highest_categories[] = $category;
            }
        }

        // If tie, use priority order
        if ( count( $highest_categories ) > 1 ) {
            $priority = array( 'distraction', 'comfort', 'ego', 'emotion', 'boundaries', 'spiritual' );

            foreach ( $priority as $category ) {
                if ( in_array( $category, $highest_categories, true ) ) {
                    return $category;
                }
            }
        }

        return $highest_categories[0];
    }

    /**
     * Get redirect URL for accountability type.
     *
     * @param string $accountability_type Accountability type.
     * @param array  $result_data Result data.
     * @return string|false Redirect URL or false.
     */
    private static function get_redirect_url( $accountability_type, $result_data ) {
        $config = get_option( 'altitude_audit_config', altitude_audit_get_default_config() );

        if ( isset( $config['result_pages'][ $accountability_type ]['url'] ) && ! empty( $config['result_pages'][ $accountability_type ]['url'] ) ) {
            $url = $config['result_pages'][ $accountability_type ]['url'];

            // Add query parameters
            $url = add_query_arg( array(
                'name' => urlencode( $result_data['first_name'] ),
                'type' => urlencode( $accountability_type ),
                'score' => absint( $result_data['total_score'] ),
            ), $url );

            return $url;
        }

        return false;
    }

    /**
     * Get type label.
     *
     * @param string $type Accountability type.
     * @param array  $config Configuration.
     * @return string Label.
     */
    private static function get_type_label( $type, $config ) {
        if ( isset( $config['result_pages'][ $type ]['label'] ) ) {
            return $config['result_pages'][ $type ]['label'];
        }

        return ucfirst( $type );
    }

    /**
     * Display thank you page with custom HTML and merge tags.
     *
     * @param array  $result_data Result data.
     * @param array  $scores Scores array.
     * @param string $accountability_type Accountability type.
     * @param array  $config Configuration.
     */
    private static function display_thank_you_page( $result_data, $scores, $accountability_type, $config ) {
        // Get thank you message template
        $thank_you_message = isset( $config['form_settings']['thank_you_message'] )
            ? $config['form_settings']['thank_you_message']
            : '<h1>Thank You!</h1><p>Your results have been submitted successfully.</p>';

        // Decode HTML entities that WordPress may have added (e.g., {curly braces} -> &#123;...&#125;)
        $thank_you_message = html_entity_decode( $thank_you_message, ENT_QUOTES | ENT_HTML5, 'UTF-8' );

        // Get accountability type details
        $accountability_label = isset( $config['categories'][ $accountability_type ]['label'] )
            ? $config['categories'][ $accountability_type ]['label']
            : self::get_type_label( $accountability_type, $config );

        $accountability_description = isset( $config['categories'][ $accountability_type ]['description'] )
            ? $config['categories'][ $accountability_type ]['description']
            : '';

        // Get highest score (the accountability type score)
        $highest_score = $scores[ $accountability_type ];

        // Prepare merge tags - start with common tags (escape user-submitted data)
        $merge_tags = array(
            '{first_name}' => esc_html( $result_data['first_name'] ),
            '{email}' => esc_html( $result_data['email'] ),
            '{total_score}' => (string) $result_data['total_score'],
            '{accountability_type}' => esc_html( $accountability_type ),
            '{accountability_type_label}' => esc_html( $accountability_label ),
            '{accountability_type_description}' => esc_html( $accountability_description ),
            '{highest_score}' => (string) $highest_score,
        );

        // Dynamically add all category scores
        foreach ( $scores as $category_key => $category_score ) {
            if ( $category_key !== 'total' ) {
                $merge_tags[ '{' . $category_key . '_score}' ] = (string) $category_score;
            }
        }

        // Replace all merge tags
        $processed_message = str_replace(
            array_keys( $merge_tags ),
            array_values( $merge_tags ),
            $thank_you_message
        );

        // Build complete HTML page with nice styling
        $html = '<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>' . esc_html__( 'Thank You - Submission Successful', 'altitude-accountability-audit' ) . '</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 800px;
            margin: 0 auto;
            padding: 40px 20px;
            background: #f5f7fa;
        }
        .thank-you-container {
            background: white;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        h1 {
            color: #667eea;
            margin-top: 0;
        }
        h2 {
            color: #667eea;
            border-bottom: 2px solid #667eea;
            padding-bottom: 10px;
        }
        h3 {
            color: #555;
        }
        .scores-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin: 20px 0;
        }
        .score-item {
            background: #f7f9fc;
            padding: 15px;
            border-radius: 8px;
            border-left: 4px solid #667eea;
        }
        .score-item strong {
            color: #667eea;
            font-size: 24px;
        }
        .button-group {
            margin-top: 30px;
            text-align: center;
        }
        .button {
            display: inline-block;
            padding: 12px 30px;
            background: #667eea;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            margin: 10px 5px;
            transition: background 0.3s;
        }
        .button:hover {
            background: #764ba2;
        }
        .button-secondary {
            background: #6c757d;
        }
        .button-secondary:hover {
            background: #5a6268;
        }
        @media (max-width: 600px) {
            body {
                padding: 20px 10px;
            }
            .thank-you-container {
                padding: 20px;
            }
            .scores-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="thank-you-container">
        ' . $processed_message . '
        <div class="button-group">
            <a href="' . esc_url( home_url() ) . '" class="button">' . esc_html__( 'Return Home', 'altitude-accountability-audit' ) . '</a>
        </div>
    </div>
</body>
</html>';

        // Output and exit
        echo $html;
        exit;
    }
}

// Initialize the handler
Altitude_Audit_Form_Handler::init();
