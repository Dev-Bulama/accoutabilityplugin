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

        // Prepare result data
        $result_data = array(
            'user_id' => get_current_user_id(),
            'submission_id' => 0, // We'll use the database ID as submission ID
            'first_name' => $form_data['first_name'],
            'email' => $form_data['email'],
            'distraction_score' => $scores['distraction'],
            'comfort_score' => $scores['comfort'],
            'ego_score' => $scores['ego'],
            'emotion_score' => $scores['emotion'],
            'boundaries_score' => $scores['boundaries'],
            'spiritual_score' => $scores['spiritual'],
            'total_score' => $scores['total'],
            'accountability_type' => $accountability_type,
        );

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

        // Redirect to result page or show success message
        $redirect_url = self::get_redirect_url( $accountability_type, $result_data );

        if ( $redirect_url ) {
            wp_redirect( $redirect_url );
            exit;
        } else {
            // Show success message
            wp_die(
                '<h1>' . esc_html__( 'Thank You!', 'altitude-accountability-audit' ) . '</h1>' .
                '<p>' . esc_html( $config['form_settings']['success_message'] ) . '</p>' .
                '<p><strong>' . esc_html__( 'Your Accountability Type:', 'altitude-accountability-audit' ) . '</strong> ' . esc_html( self::get_type_label( $accountability_type, $config ) ) . '</p>' .
                '<p><a href="' . esc_url( home_url() ) . '" class="button">' . esc_html__( 'Return Home', 'altitude-accountability-audit' ) . '</a></p>',
                esc_html__( 'Submission Successful', 'altitude-accountability-audit' ),
                array( 'response' => 200 )
            );
        }
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

        foreach ( $config['categories'] as $category_key => $category ) {
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

        foreach ( $config['categories'] as $category_key => $category ) {
            foreach ( $category['questions'] as $index => $question ) {
                $field_name = $category_key . '_q' . ( $index + 1 );

                if ( ! isset( $form_data[ $field_name ] ) || $form_data[ $field_name ] === null ) {
                    $errors[] = sprintf(
                        /* translators: %s is the question text */
                        __( 'Please answer: %s', 'altitude-accountability-audit' ),
                        $question['label']
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

        $scores = array(
            'distraction' => 0,
            'comfort' => 0,
            'ego' => 0,
            'emotion' => 0,
            'boundaries' => 0,
            'spiritual' => 0,
            'total' => 0,
        );

        foreach ( $config['categories'] as $category_key => $category ) {
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
}

// Initialize the handler
Altitude_Audit_Form_Handler::init();
