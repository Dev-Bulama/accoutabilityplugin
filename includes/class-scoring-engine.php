<?php
/**
 * Scoring engine for processing form submissions.
 *
 * @package Altitude_Audit
 */

if ( ! defined( 'WPINC' ) ) {
    die;
}

/**
 * Handles scoring logic for audit submissions.
 */
class Altitude_Audit_Scoring_Engine {

    /**
     * Process form submission.
     *
     * @param int   $submission_id Submission ID.
     * @param array $form_data Form data.
     * @param object $form Form object.
     */
    public function process_submission( $submission_id, $form_data, $form ) {
        // Check if this is our audit form
        $audit_form_id = Altitude_Audit_Form_Builder::get_form_id();

        if ( ! $audit_form_id || $form->id != $audit_form_id ) {
            return;
        }

        // Hook: Before scoring
        do_action( 'altitude_audit_before_scoring', $submission_id, $form_data, $form );

        // Get configuration
        $config = get_option( 'altitude_audit_config', altitude_audit_get_default_config() );

        // Calculate scores
        $scores = $this->calculate_scores( $form_data, $config );

        // Determine accountability type
        $accountability_type = $this->determine_type( $scores, $config );

        // Hook: Filter type determination
        $accountability_type = apply_filters( 'altitude_audit_determine_type', $accountability_type, $scores, $form_data );

        // Prepare result data
        $result_data = array(
            'user_id' => get_current_user_id(),
            'submission_id' => $submission_id,
            'first_name' => isset( $form_data['first_name'] ) ? sanitize_text_field( $form_data['first_name'] ) : '',
            'email' => isset( $form_data['email'] ) ? sanitize_email( $form_data['email'] ) : '',
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

        // Hook: After scoring
        do_action( 'altitude_audit_after_scoring', $result_id, $result_data, $form_data );

        // Send email
        $this->send_result_email( $result_data, $config );

        // Log for debugging
        if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
            error_log( 'Altitude Audit - Result saved: ' . print_r( $result_data, true ) );
        }
    }

    /**
     * Calculate scores from form data.
     *
     * @param array $form_data Form submission data.
     * @param array $config Configuration array.
     * @return array Scores by category.
     */
    private function calculate_scores( $form_data, $config ) {
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

            foreach ( $category['questions'] as $question ) {
                $question_name = $question['name'];

                if ( isset( $form_data[ $question_name ] ) ) {
                    $answer = $form_data[ $question_name ];

                    // Convert answer to points
                    $points = $this->get_points_for_answer( $answer, $config );
                    $category_score += $points;
                }
            }

            $scores[ $category_key ] = $category_score;
            $scores['total'] += $category_score;
        }

        return $scores;
    }

    /**
     * Get points for an answer.
     *
     * @param mixed $answer Answer value.
     * @param array $config Configuration array.
     * @return int Points.
     */
    private function get_points_for_answer( $answer, $config ) {
        // Answer is already the point value in our radio setup
        $points = absint( $answer );

        // Validate against allowed range
        $max_points = count( $config['scoring_options'] ) - 1;

        if ( $points > $max_points ) {
            $points = 0;
        }

        return $points;
    }

    /**
     * Determine accountability type based on scores.
     *
     * @param array $scores Scores array.
     * @param array $config Configuration array.
     * @return string Accountability type.
     */
    private function determine_type( $scores, $config ) {
        // Remove 'total' from scores for comparison
        $category_scores = $scores;
        unset( $category_scores['total'] );

        // Find highest scoring category
        $highest_category = array_keys( $category_scores, max( $category_scores ) );

        // If there's a tie, prioritize based on defined order
        $category_priority = array( 'distraction', 'comfort', 'ego', 'emotion', 'boundaries', 'spiritual' );

        foreach ( $category_priority as $category ) {
            if ( in_array( $category, $highest_category, true ) ) {
                return $category;
            }
        }

        // Fallback (should never reach here)
        return $highest_category[0];
    }

    /**
     * Send result email.
     *
     * @param array $result_data Result data.
     * @param array $config Configuration array.
     */
    private function send_result_email( $result_data, $config ) {
        $email_handler = new Altitude_Audit_Email_Handler();
        $email_handler->send_result_email( $result_data, $config );
    }

    /**
     * Get scores for a submission.
     *
     * @param int $submission_id Submission ID.
     * @return array|false Scores or false on error.
     */
    public static function get_submission_scores( $submission_id ) {
        global $wpdb;

        $table_name = $wpdb->prefix . 'altitude_audit_results';
        $submission_id = absint( $submission_id );

        $result = $wpdb->get_row(
            $wpdb->prepare(
                "SELECT * FROM $table_name WHERE submission_id = %d",
                $submission_id
            ),
            ARRAY_A
        );

        if ( ! $result ) {
            return false;
        }

        return array(
            'distraction' => (int) $result['distraction_score'],
            'comfort' => (int) $result['comfort_score'],
            'ego' => (int) $result['ego_score'],
            'emotion' => (int) $result['emotion_score'],
            'boundaries' => (int) $result['boundaries_score'],
            'spiritual' => (int) $result['spiritual_score'],
            'total' => (int) $result['total_score'],
            'type' => $result['accountability_type'],
        );
    }
}
