<?php
/**
 * Default Configuration for Accountability Audit
 *
 * @package Altitude_Audit
 */

if ( ! defined( 'WPINC' ) ) {
    die;
}

/**
 * Whether the options table can store 4-byte UTF-8 characters (emoji).
 *
 * Older databases use the "utf8" (utf8mb3) character set. MySQL refuses the
 * whole query when a value contains emoji, so the plugin settings (which
 * contain emoji category icons) could never be saved on those sites.
 *
 * @return bool
 */
function altitude_audit_options_support_utf8mb4() {
    static $supported = null;

    if ( null !== $supported ) {
        return $supported;
    }

    global $wpdb;
    $charset = false;

    if ( isset( $wpdb ) && method_exists( $wpdb, 'get_col_charset' ) ) {
        $charset = $wpdb->get_col_charset( $wpdb->options, 'option_value' );
    }

    if ( ! is_string( $charset ) || '' === $charset ) {
        $charset = isset( $wpdb->charset ) ? $wpdb->charset : '';
    }

    $supported = ( 'utf8mb4' === strtolower( $charset ) );

    return $supported;
}

/**
 * Replace 4-byte UTF-8 characters (emoji) with HTML numeric entities, recursively.
 *
 * @param mixed $value Value.
 * @return mixed
 */
function altitude_audit_encode_4byte_chars( $value ) {
    if ( is_array( $value ) ) {
        foreach ( $value as $key => $item ) {
            $value[ $key ] = altitude_audit_encode_4byte_chars( $item );
        }
        return $value;
    }

    if ( ! is_string( $value ) || '' === $value ) {
        return $value;
    }

    $encoded = preg_replace_callback(
        '/[\x{10000}-\x{10FFFF}]/u',
        function ( $match ) {
            $c = $match[0];
            $code = ( ( ord( $c[0] ) & 0x07 ) << 18 ) | ( ( ord( $c[1] ) & 0x3F ) << 12 ) | ( ( ord( $c[2] ) & 0x3F ) << 6 ) | ( ord( $c[3] ) & 0x3F );
            return '&#x' . dechex( $code ) . ';';
        },
        $value
    );

    return null === $encoded ? $value : $encoded;
}

/**
 * Turn HTML numeric entities for 4-byte characters back into the characters, recursively.
 *
 * @param mixed $value Value.
 * @return mixed
 */
function altitude_audit_decode_4byte_entities( $value ) {
    if ( is_array( $value ) ) {
        foreach ( $value as $key => $item ) {
            $value[ $key ] = altitude_audit_decode_4byte_entities( $item );
        }
        return $value;
    }

    if ( ! is_string( $value ) || false === strpos( $value, '&#' ) ) {
        return $value;
    }

    $decoded = preg_replace_callback(
        '/&#(?:x(1[0-9a-fA-F]{4}|10[0-9a-fA-F]{4})|(6[5-9][0-9]{3}|[7-9][0-9]{4}|[1-9][0-9]{5,6}));/',
        function ( $match ) {
            $code = ! empty( $match[1] ) ? hexdec( $match[1] ) : (int) $match[2];

            if ( $code < 0x10000 || $code > 0x10FFFF ) {
                return $match[0];
            }

            return html_entity_decode( '&#x' . dechex( $code ) . ';', ENT_QUOTES | ENT_HTML5, 'UTF-8' );
        },
        $value
    );

    return null === $decoded ? $value : $decoded;
}

/**
 * Before the plugin configuration is written: if the database cannot store
 * emoji, store them as HTML entities so the save is not rejected.
 *
 * @param mixed $value New option value.
 * @return mixed
 */
function altitude_audit_prepare_config_for_db( $value ) {
    if ( altitude_audit_options_support_utf8mb4() ) {
        return $value;
    }

    return altitude_audit_encode_4byte_chars( $value );
}
// Runs last in sanitize_option(), which WordPress applies on both update_option() and add_option()
// (the option row does not exist yet on sites where it could never be saved).
add_filter( 'sanitize_option_altitude_audit_config', 'altitude_audit_prepare_config_for_db', 99 );

/**
 * When the plugin configuration is read: restore any emoji stored as entities,
 * so the rest of the plugin always sees the original characters.
 *
 * @param mixed $value Option value.
 * @return mixed
 */
function altitude_audit_restore_config_from_db( $value ) {
    return altitude_audit_decode_4byte_entities( $value );
}
add_filter( 'option_altitude_audit_config', 'altitude_audit_restore_config_from_db' );

/**
 * Get the default label for the mandatory acceptance checkbox.
 *
 * @return string
 */
function altitude_audit_get_default_acceptance_text() {
    return __( 'I confirm that the information I have provided is accurate, and I agree to the accountability terms and conditions.', 'altitude-accountability-audit' );
}

/**
 * Get the acceptance checkbox label for a configuration.
 *
 * Existing installations saved their config before this setting existed,
 * so a missing or empty value falls back to the default wording.
 *
 * @param array|null $config Plugin configuration (optional).
 * @return string Label (may contain limited inline HTML such as links).
 */
function altitude_audit_get_acceptance_text( $config = null ) {
    if ( null === $config ) {
        $config = get_option( 'altitude_audit_config', array() );
    }

    if ( is_array( $config ) && ! empty( $config['form_settings']['acceptance_text'] ) && is_string( $config['form_settings']['acceptance_text'] ) ) {
        return $config['form_settings']['acceptance_text'];
    }

    return altitude_audit_get_default_acceptance_text();
}

/**
 * Allowed HTML for the acceptance checkbox label (e.g. a link to the terms page).
 *
 * @return array
 */
function altitude_audit_acceptance_allowed_html() {
    return array(
        'a'      => array(
            'href'   => true,
            'target' => true,
            'rel'    => true,
            'title'  => true,
        ),
        'strong' => array(),
        'em'     => array(),
        'b'      => array(),
        'i'      => array(),
    );
}

/**
 * Get default audit configuration
 *
 * @return array
 */
function altitude_audit_get_default_config() {
    return array(
        'form_settings' => array(
            'form_title' => __( 'Personal Accountability Audit', 'altitude-accountability-audit' ),
            'submit_button_text' => __( 'Get My Results', 'altitude-accountability-audit' ),
            'success_message' => __( 'Thank you! Check your email for your personalized results.', 'altitude-accountability-audit' ),
            'thank_you_message' => '<h1>Thank You for Completing the Accountability Audit!</h1>
<p>Your results have been calculated and emailed to <strong>{email}</strong>.</p>
<h2>Your Highest Challenge Area: {accountability_type_label}</h2>
<p><strong>Score:</strong> {highest_score} out of 9 points</p>
<p>{accountability_type_description}</p>
<h3>Your Complete Scores:</h3>
<ul>
    <li><strong>Distraction:</strong> {distraction_score}/9</li>
    <li><strong>Comfort:</strong> {comfort_score}/9</li>
    <li><strong>Ego:</strong> {ego_score}/9</li>
    <li><strong>Emotion:</strong> {emotion_score}/9</li>
    <li><strong>Boundaries:</strong> {boundaries_score}/9</li>
    <li><strong>Spiritual:</strong> {spiritual_score}/9</li>
</ul>
<p><strong>Total Score:</strong> {total_score}/54</p>
<p>Check your email for detailed results and personalized recommendations for your journey forward.</p>',
            'acceptance_text' => altitude_audit_get_default_acceptance_text(),
        ),
        'email_settings' => array(
            'from_name' => get_bloginfo( 'name' ),
            'from_email' => get_option( 'admin_email' ),
            'subject' => __( 'Your Personal Accountability Audit Results', 'altitude-accountability-audit' ),
            'send_to_admin' => true,
        ),
        'result_pages' => array(
            'distraction' => array(
                'label' => __( 'The Drifter', 'altitude-accountability-audit' ),
                'url' => '',
                'description' => __( 'You struggle with maintaining focus and avoiding distractions.', 'altitude-accountability-audit' ),
            ),
            'comfort' => array(
                'label' => __( 'The Comfort Addict', 'altitude-accountability-audit' ),
                'url' => '',
                'description' => __( 'You tend to choose comfort over growth and challenge.', 'altitude-accountability-audit' ),
            ),
            'ego' => array(
                'label' => __( 'The Performer', 'altitude-accountability-audit' ),
                'url' => '',
                'description' => __( 'You are driven by external validation and recognition.', 'altitude-accountability-audit' ),
            ),
            'emotion' => array(
                'label' => __( 'The Reactor', 'altitude-accountability-audit' ),
                'url' => '',
                'description' => __( 'You are guided by emotions rather than principles.', 'altitude-accountability-audit' ),
            ),
            'boundaries' => array(
                'label' => __( 'The Open Door', 'altitude-accountability-audit' ),
                'url' => '',
                'description' => __( 'You struggle with setting and maintaining healthy boundaries.', 'altitude-accountability-audit' ),
            ),
            'spiritual' => array(
                'label' => __( 'The Spiritual Delayer', 'altitude-accountability-audit' ),
                'url' => '',
                'description' => __( 'You postpone spiritual growth and deeper meaning.', 'altitude-accountability-audit' ),
            ),
        ),
        'scoring_options' => array(
            array(
                'label' => __( 'Not true', 'altitude-accountability-audit' ),
                'value' => '0',
                'points' => 0,
            ),
            array(
                'label' => __( 'Sometimes true', 'altitude-accountability-audit' ),
                'value' => '1',
                'points' => 1,
            ),
            array(
                'label' => __( 'Often true', 'altitude-accountability-audit' ),
                'value' => '2',
                'points' => 2,
            ),
            array(
                'label' => __( 'This is my pattern', 'altitude-accountability-audit' ),
                'value' => '3',
                'points' => 3,
            ),
        ),
        'categories' => array(
            'distraction' => array(
                'label' => __( 'Distraction', 'altitude-accountability-audit' ),
                'description' => __( 'Measures your tendency to lose focus and be diverted from important tasks.', 'altitude-accountability-audit' ),
                'icon' => '📱',
                'questions' => array(
                    array(
                        'label' => __( 'I pick up my phone without thinking and lose time.', 'altitude-accountability-audit' ),
                        'name' => 'distraction_q1',
                        'help_text' => '',
                    ),
                    array(
                        'label' => __( 'I start tasks but rarely finish them before moving to something else.', 'altitude-accountability-audit' ),
                        'name' => 'distraction_q2',
                        'help_text' => '',
                    ),
                    array(
                        'label' => __( 'I find myself scrolling social media when I should be working on priorities.', 'altitude-accountability-audit' ),
                        'name' => 'distraction_q3',
                        'help_text' => '',
                    ),
                ),
            ),
            'comfort' => array(
                'label' => __( 'Comfort', 'altitude-accountability-audit' ),
                'description' => __( 'Assesses how much you prioritize comfort over growth and challenge.', 'altitude-accountability-audit' ),
                'icon' => '🛋️',
                'questions' => array(
                    array(
                        'label' => __( 'I avoid difficult conversations because they feel uncomfortable.', 'altitude-accountability-audit' ),
                        'name' => 'comfort_q1',
                        'help_text' => '',
                    ),
                    array(
                        'label' => __( 'I choose easy tasks over challenging ones that would help me grow.', 'altitude-accountability-audit' ),
                        'name' => 'comfort_q2',
                        'help_text' => '',
                    ),
                    array(
                        'label' => __( 'I stay in situations that no longer serve me because change feels too hard.', 'altitude-accountability-audit' ),
                        'name' => 'comfort_q3',
                        'help_text' => '',
                    ),
                ),
            ),
            'ego' => array(
                'label' => __( 'Ego', 'altitude-accountability-audit' ),
                'description' => __( 'Evaluates your need for external validation and recognition.', 'altitude-accountability-audit' ),
                'icon' => '🏆',
                'questions' => array(
                    array(
                        'label' => __( 'I need others to see my accomplishments for them to feel meaningful.', 'altitude-accountability-audit' ),
                        'name' => 'ego_q1',
                        'help_text' => '',
                    ),
                    array(
                        'label' => __( 'I feel threatened when others succeed or receive recognition.', 'altitude-accountability-audit' ),
                        'name' => 'ego_q2',
                        'help_text' => '',
                    ),
                    array(
                        'label' => __( 'I make decisions based on how they will make me look rather than what is right.', 'altitude-accountability-audit' ),
                        'name' => 'ego_q3',
                        'help_text' => '',
                    ),
                ),
            ),
            'emotion' => array(
                'label' => __( 'Emotion', 'altitude-accountability-audit' ),
                'description' => __( 'Measures how much emotions drive your decisions versus principles.', 'altitude-accountability-audit' ),
                'icon' => '😢',
                'questions' => array(
                    array(
                        'label' => __( 'My mood determines whether I follow through on commitments.', 'altitude-accountability-audit' ),
                        'name' => 'emotion_q1',
                        'help_text' => '',
                    ),
                    array(
                        'label' => __( 'I make impulsive decisions based on how I feel in the moment.', 'altitude-accountability-audit' ),
                        'name' => 'emotion_q2',
                        'help_text' => '',
                    ),
                    array(
                        'label' => __( 'I let emotions override logic when making important choices.', 'altitude-accountability-audit' ),
                        'name' => 'emotion_q3',
                        'help_text' => '',
                    ),
                ),
            ),
            'boundaries' => array(
                'label' => __( 'Boundaries', 'altitude-accountability-audit' ),
                'description' => __( 'Assesses your ability to set and maintain healthy boundaries.', 'altitude-accountability-audit' ),
                'icon' => '🚪',
                'questions' => array(
                    array(
                        'label' => __( 'I say yes when I want to say no because I fear disappointing others.', 'altitude-accountability-audit' ),
                        'name' => 'boundaries_q1',
                        'help_text' => '',
                    ),
                    array(
                        'label' => __( 'I allow others to violate my time and energy without speaking up.', 'altitude-accountability-audit' ),
                        'name' => 'boundaries_q2',
                        'help_text' => '',
                    ),
                    array(
                        'label' => __( 'I feel guilty when I prioritize my own needs over others\' requests.', 'altitude-accountability-audit' ),
                        'name' => 'boundaries_q3',
                        'help_text' => '',
                    ),
                ),
            ),
            'spiritual' => array(
                'label' => __( 'Spiritual Delay', 'altitude-accountability-audit' ),
                'description' => __( 'Evaluates how much you postpone spiritual growth and deeper meaning.', 'altitude-accountability-audit' ),
                'icon' => '🕊️',
                'questions' => array(
                    array(
                        'label' => __( 'I pray more than I plan.', 'altitude-accountability-audit' ),
                        'name' => 'spiritual_q1',
                        'help_text' => '',
                    ),
                    array(
                        'label' => __( 'I use "God\'s timing" to postpone hard work.', 'altitude-accountability-audit' ),
                        'name' => 'spiritual_q2',
                        'help_text' => '',
                    ),
                    array(
                        'label' => __( 'I repeat cycles and call it "attack" instead of a pattern.', 'altitude-accountability-audit' ),
                        'name' => 'spiritual_q3',
                        'help_text' => '',
                    ),
                ),
            ),
        ),
    );
}

/**
 * Get default email template
 *
 * @return string
 */
function altitude_audit_get_default_email_template() {
    return <<<HTML
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
            border-radius: 10px 10px 0 0;
        }
        .content {
            background: #ffffff;
            padding: 30px;
            border: 1px solid #e5e5e5;
        }
        .result-card {
            background: #f7f9fc;
            padding: 20px;
            margin: 20px 0;
            border-left: 4px solid #667eea;
            border-radius: 5px;
        }
        .score-breakdown {
            margin: 20px 0;
        }
        .score-item {
            display: flex;
            justify-content: space-between;
            padding: 10px;
            border-bottom: 1px solid #e5e5e5;
        }
        .cta-button {
            display: inline-block;
            background: #667eea;
            color: white;
            padding: 15px 30px;
            text-decoration: none;
            border-radius: 5px;
            margin: 20px 0;
        }
        .footer {
            text-align: center;
            padding: 20px;
            color: #666;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Your Personal Accountability Audit Results</h1>
    </div>
    <div class="content">
        <p>Hi {first_name},</p>

        <p>Thank you for completing the Personal Accountability Audit. Based on your responses, here are your results:</p>

        <div class="result-card">
            <h2>Your Accountability Type: {accountability_type}</h2>
            <p>{type_description}</p>
        </div>

        <div class="score-breakdown">
            <h3>Your Scores by Category:</h3>
            <div class="score-item">
                <span>Distraction:</span>
                <strong>{distraction_score}/9</strong>
            </div>
            <div class="score-item">
                <span>Comfort:</span>
                <strong>{comfort_score}/9</strong>
            </div>
            <div class="score-item">
                <span>Ego:</span>
                <strong>{ego_score}/9</strong>
            </div>
            <div class="score-item">
                <span>Emotion:</span>
                <strong>{emotion_score}/9</strong>
            </div>
            <div class="score-item">
                <span>Boundaries:</span>
                <strong>{boundaries_score}/9</strong>
            </div>
            <div class="score-item">
                <span>Spiritual Delay:</span>
                <strong>{spiritual_score}/9</strong>
            </div>
        </div>

        <p>Your highest score indicates your primary accountability challenge. This is where focused work can create the most significant transformation in your life.</p>

        {if_result_url}
        <a href="{result_url}" class="cta-button">View Your Detailed Results & Next Steps</a>
        {endif_result_url}

        <p>Remember: Awareness is the first step to transformation. Now that you know your primary challenge, you can take intentional action to grow.</p>
    </div>
    <div class="footer">
        <p>&copy; Altitude Within - Empowering Your Personal Growth Journey</p>
    </div>
</body>
</html>
HTML;
}
