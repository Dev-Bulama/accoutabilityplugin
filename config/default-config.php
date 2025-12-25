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
                        'label' => __( 'I know I should deepen my spiritual practice but keep putting it off.', 'altitude-accountability-audit' ),
                        'name' => 'spiritual_q1',
                        'help_text' => '',
                    ),
                    array(
                        'label' => __( 'I avoid reflecting on life\'s bigger questions and my purpose.', 'altitude-accountability-audit' ),
                        'name' => 'spiritual_q2',
                        'help_text' => '',
                    ),
                    array(
                        'label' => __( 'I prioritize material success over spiritual growth and inner peace.', 'altitude-accountability-audit' ),
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
