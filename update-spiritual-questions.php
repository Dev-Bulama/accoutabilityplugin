<?php
/**
 * One-time script to update Spiritual Delay questions
 *
 * INSTRUCTIONS:
 * 1. Upload this file to your plugin directory
 * 2. Access it via browser: yourdomain.com/wp-content/plugins/accoutabilityplugin/update-spiritual-questions.php
 * 3. Delete this file after running it once
 */

// Load WordPress
require_once('../../../wp-load.php');

// Check if user is admin
if (!current_user_can('manage_options')) {
    die('Unauthorized access');
}

// Get current config
$config = get_option('altitude_audit_config', altitude_audit_get_default_config());

// Update spiritual delay questions
$config['categories']['spiritual']['questions'] = array(
    array(
        'label' => 'I pray more than I plan.',
        'name' => 'spiritual_q1',
        'help_text' => '',
    ),
    array(
        'label' => 'I use "God\'s timing" to postpone hard work.',
        'name' => 'spiritual_q2',
        'help_text' => '',
    ),
    array(
        'label' => 'I repeat cycles and call it "attack" instead of a pattern.',
        'name' => 'spiritual_q3',
        'help_text' => '',
    ),
);

// Save updated config
$updated = update_option('altitude_audit_config', $config);

if ($updated) {
    echo '<h1 style="color: green;">✓ Success!</h1>';
    echo '<p>The Spiritual Delay questions have been updated successfully.</p>';
    echo '<ul>';
    echo '<li>I pray more than I plan.</li>';
    echo '<li>I use "God\'s timing" to postpone hard work.</li>';
    echo '<li>I repeat cycles and call it "attack" instead of a pattern.</li>';
    echo '</ul>';
    echo '<p><strong>Next Steps:</strong></p>';
    echo '<ol>';
    echo '<li>Visit your form to verify the changes</li>';
    echo '<li>DELETE this file (update-spiritual-questions.php) for security</li>';
    echo '</ol>';
    echo '<p><a href="' . admin_url('admin.php?page=altitude-audit') . '">Go to Plugin Dashboard</a></p>';
} else {
    echo '<h1 style="color: orange;">Notice</h1>';
    echo '<p>Config was already up to date or no changes were needed.</p>';
}
?>
