<?php
/**
 * Settings view.
 *
 * @package Altitude_Audit
 */

if ( ! defined( 'WPINC' ) ) {
    die;
}

// Handle form submission
if ( isset( $_POST['altitude_audit_save_settings'] ) && check_admin_referer( 'altitude_audit_settings' ) ) {
    // Get the admin instance to call sanitize_config
    require_once ALTITUDE_AUDIT_PLUGIN_DIR . 'admin/class-admin.php';
    $admin = new Altitude_Audit_Admin( 'altitude-accountability-audit', ALTITUDE_AUDIT_VERSION );

    // Sanitize and save configuration (this preserves categories and scoring options)
    $sanitized_config = $admin->sanitize_config( $_POST['altitude_audit_config'] );
    update_option( 'altitude_audit_config', $sanitized_config );

    echo '<div class="notice notice-success"><p>' . esc_html__( 'Settings saved successfully! Form will use new settings immediately.', 'altitude-accountability-audit' ) . '</p></div>';
}

$config = get_option( 'altitude_audit_config', altitude_audit_get_default_config() );
?>

<div class="wrap altitude-audit-admin">
    <h1><?php esc_html_e( 'Audit Settings', 'altitude-accountability-audit' ); ?></h1>

    <form method="post" action="">
        <?php wp_nonce_field( 'altitude_audit_settings' ); ?>

        <div class="altitude-audit-settings">
            <!-- Form Settings -->
            <div class="altitude-audit-card">
                <h2><?php esc_html_e( 'Form Settings', 'altitude-accountability-audit' ); ?></h2>

                <table class="form-table">
                    <tr>
                        <th scope="row">
                            <label for="form_title"><?php esc_html_e( 'Form Title', 'altitude-accountability-audit' ); ?></label>
                        </th>
                        <td>
                            <input type="text" id="form_title" name="altitude_audit_config[form_settings][form_title]" value="<?php echo esc_attr( $config['form_settings']['form_title'] ); ?>" class="regular-text">
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="submit_button_text"><?php esc_html_e( 'Submit Button Text', 'altitude-accountability-audit' ); ?></label>
                        </th>
                        <td>
                            <input type="text" id="submit_button_text" name="altitude_audit_config[form_settings][submit_button_text]" value="<?php echo esc_attr( $config['form_settings']['submit_button_text'] ); ?>" class="regular-text">
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="thank_you_message"><?php esc_html_e( 'Thank You Message (HTML)', 'altitude-accountability-audit' ); ?></label>
                            <p class="description"><?php esc_html_e( 'Shown immediately after form submission', 'altitude-accountability-audit' ); ?></p>
                        </th>
                        <td>
                            <?php
                            $thank_you_message = isset( $config['form_settings']['thank_you_message'] )
                                ? $config['form_settings']['thank_you_message']
                                : '<h1>Thank You for Completing the Accountability Audit!</h1>
<p>Your results have been calculated and emailed to you.</p>
<h2>Your Highest Challenge Area: {accountability_type_label}</h2>
<p><strong>Score:</strong> {highest_score} out of 9 points</p>
<p>{accountability_type_description}</p>
<h3>Your Scores:</h3>
<ul>
    <li>Distraction: {distraction_score}/9</li>
    <li>Comfort: {comfort_score}/9</li>
    <li>Ego: {ego_score}/9</li>
    <li>Emotion: {emotion_score}/9</li>
    <li>Boundaries: {boundaries_score}/9</li>
    <li>Spiritual: {spiritual_score}/9</li>
</ul>
<p><strong>Total Score:</strong> {total_score}/54</p>
<p>Check your email for detailed results and next steps.</p>';

                            wp_editor(
                                $thank_you_message,
                                'thank_you_message',
                                array(
                                    'textarea_name' => 'altitude_audit_config[form_settings][thank_you_message]',
                                    'textarea_rows' => 15,
                                    'media_buttons' => false,
                                    'teeny' => false,
                                    'tinymce' => array(
                                        'toolbar1' => 'formatselect,bold,italic,underline,bullist,numlist,link,unlink,forecolor,backcolor,removeformat',
                                        'toolbar2' => '',
                                    ),
                                )
                            );
                            ?>
                            <p class="description">
                                <strong><?php esc_html_e( 'Available Merge Tags:', 'altitude-accountability-audit' ); ?></strong><br>
                                <code>{first_name}</code>, <code>{email}</code>, <code>{total_score}</code><br>
                                <code>{distraction_score}</code>, <code>{comfort_score}</code>, <code>{ego_score}</code>, <code>{emotion_score}</code>, <code>{boundaries_score}</code>, <code>{spiritual_score}</code><br>
                                <code>{accountability_type}</code> - <?php esc_html_e( 'Category key (e.g., "distraction")', 'altitude-accountability-audit' ); ?><br>
                                <code>{accountability_type_label}</code> - <?php esc_html_e( 'Category name (e.g., "Distraction")', 'altitude-accountability-audit' ); ?><br>
                                <code>{accountability_type_description}</code> - <?php esc_html_e( 'Category description', 'altitude-accountability-audit' ); ?><br>
                                <code>{highest_score}</code> - <?php esc_html_e( 'Score of the highest category', 'altitude-accountability-audit' ); ?>
                            </p>
                        </td>
                    </tr>
                </table>
            </div>

            <!-- Email Settings -->
            <div class="altitude-audit-card">
                <h2><?php esc_html_e( 'Email Settings', 'altitude-accountability-audit' ); ?></h2>

                <table class="form-table">
                    <tr>
                        <th scope="row">
                            <label for="from_name"><?php esc_html_e( 'From Name', 'altitude-accountability-audit' ); ?></label>
                        </th>
                        <td>
                            <input type="text" id="from_name" name="altitude_audit_config[email_settings][from_name]" value="<?php echo esc_attr( $config['email_settings']['from_name'] ); ?>" class="regular-text">
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="from_email"><?php esc_html_e( 'From Email', 'altitude-accountability-audit' ); ?></label>
                        </th>
                        <td>
                            <input type="email" id="from_email" name="altitude_audit_config[email_settings][from_email]" value="<?php echo esc_attr( $config['email_settings']['from_email'] ); ?>" class="regular-text">
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="email_subject"><?php esc_html_e( 'Email Subject', 'altitude-accountability-audit' ); ?></label>
                        </th>
                        <td>
                            <input type="text" id="email_subject" name="altitude_audit_config[email_settings][subject]" value="<?php echo esc_attr( $config['email_settings']['subject'] ); ?>" class="regular-text">
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="send_to_admin"><?php esc_html_e( 'Send Copy to Admin', 'altitude-accountability-audit' ); ?></label>
                        </th>
                        <td>
                            <label>
                                <input type="checkbox" id="send_to_admin" name="altitude_audit_config[email_settings][send_to_admin]" value="1" <?php checked( ! empty( $config['email_settings']['send_to_admin'] ) ); ?>>
                                <?php esc_html_e( 'Send a copy of each result email to the site administrator', 'altitude-accountability-audit' ); ?>
                            </label>
                        </td>
                    </tr>
                </table>
            </div>

            <!-- Result Pages -->
            <div class="altitude-audit-card">
                <h2><?php esc_html_e( 'Result Pages', 'altitude-accountability-audit' ); ?></h2>
                <p class="description"><?php esc_html_e( 'Set the URLs where users will be directed based on their accountability type.', 'altitude-accountability-audit' ); ?></p>

                <table class="form-table">
                    <?php foreach ( $config['result_pages'] as $type => $page ) : ?>
                        <tr>
                            <th scope="row">
                                <label for="result_url_<?php echo esc_attr( $type ); ?>"><?php echo esc_html( $page['label'] ); ?></label>
                            </th>
                            <td>
                                <input type="url" id="result_url_<?php echo esc_attr( $type ); ?>" name="altitude_audit_config[result_pages][<?php echo esc_attr( $type ); ?>][url]" value="<?php echo esc_url( $page['url'] ); ?>" class="large-text" placeholder="https://">
                                <input type="hidden" name="altitude_audit_config[result_pages][<?php echo esc_attr( $type ); ?>][label]" value="<?php echo esc_attr( $page['label'] ); ?>">
                                <input type="hidden" name="altitude_audit_config[result_pages][<?php echo esc_attr( $type ); ?>][description]" value="<?php echo esc_attr( $page['description'] ); ?>">
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            </div>

            <!-- Import/Export -->
            <div class="altitude-audit-card">
                <h2><?php esc_html_e( 'Import/Export Configuration', 'altitude-accountability-audit' ); ?></h2>

                <div class="import-export-actions">
                    <button type="button" class="button" id="export-config-btn">
                        <span class="dashicons dashicons-download"></span>
                        <?php esc_html_e( 'Export Configuration', 'altitude-accountability-audit' ); ?>
                    </button>

                    <button type="button" class="button" id="import-config-btn">
                        <span class="dashicons dashicons-upload"></span>
                        <?php esc_html_e( 'Import Configuration', 'altitude-accountability-audit' ); ?>
                    </button>
                </div>

                <div id="import-area" style="display:none; margin-top: 15px;">
                    <textarea id="import-data" rows="10" class="large-text" placeholder="<?php esc_attr_e( 'Paste your configuration JSON here...', 'altitude-accountability-audit' ); ?>"></textarea>
                    <br>
                    <button type="button" class="button button-primary" id="process-import-btn"><?php esc_html_e( 'Process Import', 'altitude-accountability-audit' ); ?></button>
                    <button type="button" class="button" id="cancel-import-btn"><?php esc_html_e( 'Cancel', 'altitude-accountability-audit' ); ?></button>
                </div>
            </div>

            <!-- Pass through categories and scoring options -->
            <input type="hidden" name="altitude_audit_config[categories]" value="<?php echo esc_attr( wp_json_encode( $config['categories'] ) ); ?>">
            <input type="hidden" name="altitude_audit_config[scoring_options]" value="<?php echo esc_attr( wp_json_encode( $config['scoring_options'] ) ); ?>">

            <p class="submit">
                <input type="submit" name="altitude_audit_save_settings" class="button button-primary button-large" value="<?php esc_attr_e( 'Save Settings', 'altitude-accountability-audit' ); ?>">
            </p>
        </div>
    </form>
</div>

<script>
jQuery(document).ready(function($) {
    // Export configuration
    $('#export-config-btn').on('click', function() {
        $.ajax({
            url: ajaxurl,
            type: 'POST',
            data: {
                action: 'altitude_audit_export_config',
                nonce: altitudeAuditAdmin.nonce
            },
            success: function(response) {
                if (response.success) {
                    var dataStr = JSON.stringify(response.data.data, null, 2);
                    var dataUri = 'data:application/json;charset=utf-8,'+ encodeURIComponent(dataStr);
                    var exportFileDefaultName = 'altitude-audit-config-' + new Date().toISOString().slice(0,10) + '.json';

                    var linkElement = document.createElement('a');
                    linkElement.setAttribute('href', dataUri);
                    linkElement.setAttribute('download', exportFileDefaultName);
                    linkElement.click();
                }
            }
        });
    });

    // Import configuration
    $('#import-config-btn').on('click', function() {
        $('#import-area').slideDown();
    });

    $('#cancel-import-btn').on('click', function() {
        $('#import-area').slideUp();
        $('#import-data').val('');
    });

    $('#process-import-btn').on('click', function() {
        var importData = $('#import-data').val();

        if (!importData) {
            alert('<?php esc_html_e( 'Please paste configuration data first.', 'altitude-accountability-audit' ); ?>');
            return;
        }

        if (!confirm('<?php esc_html_e( 'This will overwrite your current configuration. Continue?', 'altitude-accountability-audit' ); ?>')) {
            return;
        }

        $.ajax({
            url: ajaxurl,
            type: 'POST',
            data: {
                action: 'altitude_audit_import_config',
                nonce: altitudeAuditAdmin.nonce,
                import_data: importData
            },
            success: function(response) {
                if (response.success) {
                    alert(response.data.message);
                    location.reload();
                } else {
                    alert(response.data.message);
                }
            }
        });
    });
});
</script>
