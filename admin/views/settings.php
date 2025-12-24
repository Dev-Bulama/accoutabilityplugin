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
    $config = $_POST['altitude_audit_config'];
    update_option( 'altitude_audit_config', $config );

    echo '<div class="notice notice-success"><p>' . esc_html__( 'Settings saved successfully!', 'altitude-accountability-audit' ) . '</p></div>';

    // Rebuild form with new config
    Altitude_Audit_Form_Builder::create_audit_form();
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
                            <label for="success_message"><?php esc_html_e( 'Success Message', 'altitude-accountability-audit' ); ?></label>
                        </th>
                        <td>
                            <textarea id="success_message" name="altitude_audit_config[form_settings][success_message]" rows="3" class="large-text"><?php echo esc_textarea( $config['form_settings']['success_message'] ); ?></textarea>
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
