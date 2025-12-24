<?php
/**
 * Email handler for sending result emails.
 *
 * @package Altitude_Audit
 */

if ( ! defined( 'WPINC' ) ) {
    die;
}

/**
 * Handles email sending with custom templates.
 */
class Altitude_Audit_Email_Handler {

    /**
     * Send result email to user.
     *
     * @param array $result_data Result data.
     * @param array $config Configuration array.
     * @return bool Success status.
     */
    public function send_result_email( $result_data, $config ) {
        // Get email template
        $template = get_option( 'altitude_audit_email_template', altitude_audit_get_default_email_template() );

        // Get result page URL
        $result_url = $this->get_result_url( $result_data['accountability_type'], $config );

        // Prepare merge tags
        $merge_data = array(
            'first_name' => $result_data['first_name'],
            'email' => $result_data['email'],
            'distraction_score' => $result_data['distraction_score'],
            'comfort_score' => $result_data['comfort_score'],
            'ego_score' => $result_data['ego_score'],
            'emotion_score' => $result_data['emotion_score'],
            'boundaries_score' => $result_data['boundaries_score'],
            'spiritual_score' => $result_data['spiritual_score'],
            'total_score' => $result_data['total_score'],
            'accountability_type' => $this->get_type_label( $result_data['accountability_type'], $config ),
            'type_description' => $this->get_type_description( $result_data['accountability_type'], $config ),
            'result_url' => $result_url,
        );

        // Hook: Filter email data
        $merge_data = apply_filters( 'altitude_audit_email_data', $merge_data, $result_data, $config );

        // Process template with merge tags
        $email_body = $this->process_template( $template, $merge_data, $result_url );

        // Email headers
        $headers = array(
            'Content-Type: text/html; charset=UTF-8',
            'From: ' . $config['email_settings']['from_name'] . ' <' . $config['email_settings']['from_email'] . '>',
        );

        // Send email to user
        $sent = wp_mail(
            $result_data['email'],
            $config['email_settings']['subject'],
            $email_body,
            $headers
        );

        // Send copy to admin if enabled
        if ( $config['email_settings']['send_to_admin'] ) {
            $admin_email = get_option( 'admin_email' );
            $admin_subject = '[Admin Copy] ' . $config['email_settings']['subject'];

            wp_mail(
                $admin_email,
                $admin_subject,
                $email_body,
                $headers
            );
        }

        // Log for debugging
        if ( defined( 'WP_DEBUG' ) && WP_DEBUG && ! $sent ) {
            error_log( 'Altitude Audit - Email failed to send to: ' . $result_data['email'] );
        }

        return $sent;
    }

    /**
     * Process template with merge tags.
     *
     * @param string $template Email template.
     * @param array  $merge_data Merge tag data.
     * @param string $result_url Result URL.
     * @return string Processed template.
     */
    private function process_template( $template, $merge_data, $result_url ) {
        // Replace simple merge tags
        foreach ( $merge_data as $key => $value ) {
            $template = str_replace( '{' . $key . '}', $value, $template );
        }

        // Handle conditional blocks
        if ( ! empty( $result_url ) ) {
            $template = preg_replace( '/\{if_result_url\}(.*?)\{endif_result_url\}/s', '$1', $template );
        } else {
            $template = preg_replace( '/\{if_result_url\}.*?\{endif_result_url\}/s', '', $template );
        }

        return $template;
    }

    /**
     * Get result URL for accountability type.
     *
     * @param string $type Accountability type.
     * @param array  $config Configuration array.
     * @return string URL or empty string.
     */
    private function get_result_url( $type, $config ) {
        if ( isset( $config['result_pages'][ $type ]['url'] ) ) {
            return $config['result_pages'][ $type ]['url'];
        }

        return '';
    }

    /**
     * Get label for accountability type.
     *
     * @param string $type Accountability type.
     * @param array  $config Configuration array.
     * @return string Label.
     */
    private function get_type_label( $type, $config ) {
        if ( isset( $config['result_pages'][ $type ]['label'] ) ) {
            return $config['result_pages'][ $type ]['label'];
        }

        return ucfirst( $type );
    }

    /**
     * Get description for accountability type.
     *
     * @param string $type Accountability type.
     * @param array  $config Configuration array.
     * @return string Description.
     */
    private function get_type_description( $type, $config ) {
        if ( isset( $config['result_pages'][ $type ]['description'] ) ) {
            return $config['result_pages'][ $type ]['description'];
        }

        return '';
    }

    /**
     * Send test email.
     *
     * @param string $to_email Email address to send test to.
     * @return bool Success status.
     */
    public function send_test_email( $to_email ) {
        $config = get_option( 'altitude_audit_config', altitude_audit_get_default_config() );

        // Create test data
        $test_data = array(
            'first_name' => 'John',
            'email' => $to_email,
            'distraction_score' => 6,
            'comfort_score' => 4,
            'ego_score' => 5,
            'emotion_score' => 3,
            'boundaries_score' => 7,
            'spiritual_score' => 5,
            'total_score' => 30,
            'accountability_type' => 'boundaries',
        );

        return $this->send_result_email( $test_data, $config );
    }

    /**
     * Preview email template.
     *
     * @param array $result_data Optional result data for preview.
     * @return string HTML email preview.
     */
    public function preview_email( $result_data = null ) {
        $config = get_option( 'altitude_audit_config', altitude_audit_get_default_config() );
        $template = get_option( 'altitude_audit_email_template', altitude_audit_get_default_email_template() );

        // Use test data if no result data provided
        if ( ! $result_data ) {
            $result_data = array(
                'first_name' => 'John',
                'email' => 'john@example.com',
                'distraction_score' => 6,
                'comfort_score' => 4,
                'ego_score' => 5,
                'emotion_score' => 3,
                'boundaries_score' => 7,
                'spiritual_score' => 5,
                'total_score' => 30,
                'accountability_type' => 'boundaries',
            );
        }

        $result_url = $this->get_result_url( $result_data['accountability_type'], $config );

        $merge_data = array(
            'first_name' => $result_data['first_name'],
            'email' => $result_data['email'],
            'distraction_score' => $result_data['distraction_score'],
            'comfort_score' => $result_data['comfort_score'],
            'ego_score' => $result_data['ego_score'],
            'emotion_score' => $result_data['emotion_score'],
            'boundaries_score' => $result_data['boundaries_score'],
            'spiritual_score' => $result_data['spiritual_score'],
            'total_score' => $result_data['total_score'],
            'accountability_type' => $this->get_type_label( $result_data['accountability_type'], $config ),
            'type_description' => $this->get_type_description( $result_data['accountability_type'], $config ),
            'result_url' => $result_url,
        );

        return $this->process_template( $template, $merge_data, $result_url );
    }
}
