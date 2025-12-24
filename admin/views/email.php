<?php
/**
 * Email template view.
 *
 * @package Altitude_Audit
 */

if ( ! defined( 'WPINC' ) ) {
    die;
}

// Handle form submission
if ( isset( $_POST['altitude_audit_save_email'] ) && check_admin_referer( 'altitude_audit_email' ) ) {
    $email_template = wp_kses_post( $_POST['altitude_audit_email_template'] );
    update_option( 'altitude_audit_email_template', $email_template );

    echo '<div class="notice notice-success"><p>' . esc_html__( 'Email template saved successfully!', 'altitude-accountability-audit' ) . '</p></div>';
}

// Handle reset
if ( isset( $_POST['altitude_audit_reset_email'] ) && check_admin_referer( 'altitude_audit_email' ) ) {
    delete_option( 'altitude_audit_email_template' );

    echo '<div class="notice notice-success"><p>' . esc_html__( 'Email template reset to default.', 'altitude-accountability-audit' ) . '</p></div>';
}

$email_template = get_option( 'altitude_audit_email_template', altitude_audit_get_default_email_template() );
?>

<div class="wrap altitude-audit-admin">
    <h1><?php esc_html_e( 'Email Template', 'altitude-accountability-audit' ); ?></h1>

    <div class="altitude-audit-email">
        <form method="post" action="">
            <?php wp_nonce_field( 'altitude_audit_email' ); ?>

            <div class="altitude-audit-card">
                <h2><?php esc_html_e( 'Email Template Editor', 'altitude-accountability-audit' ); ?></h2>

                <p class="description">
                    <?php esc_html_e( 'Customize the email template sent to users with their audit results. Use HTML for formatting.', 'altitude-accountability-audit' ); ?>
                </p>

                <div class="template-editor">
                    <textarea name="altitude_audit_email_template" id="email-template-editor" rows="25" class="large-text code"><?php echo esc_textarea( $email_template ); ?></textarea>
                </div>

                <p class="submit">
                    <input type="submit" name="altitude_audit_save_email" class="button button-primary button-large" value="<?php esc_attr_e( 'Save Email Template', 'altitude-accountability-audit' ); ?>">
                    <input type="submit" name="altitude_audit_reset_email" class="button button-secondary" value="<?php esc_attr_e( 'Reset to Default', 'altitude-accountability-audit' ); ?>" onclick="return confirm('<?php esc_attr_e( 'Are you sure you want to reset to the default template?', 'altitude-accountability-audit' ); ?>');">
                    <button type="button" class="button" id="preview-email-btn"><?php esc_html_e( 'Preview Email', 'altitude-accountability-audit' ); ?></button>
                    <button type="button" class="button" id="test-email-btn"><?php esc_html_e( 'Send Test Email', 'altitude-accountability-audit' ); ?></button>
                </p>
            </div>
        </form>

        <!-- Available Merge Tags -->
        <div class="altitude-audit-card">
            <h2><?php esc_html_e( 'Available Merge Tags', 'altitude-accountability-audit' ); ?></h2>

            <div class="merge-tags">
                <div class="merge-tag-grid">
                    <div class="merge-tag-item">
                        <code>{first_name}</code>
                        <span><?php esc_html_e( 'User\'s first name', 'altitude-accountability-audit' ); ?></span>
                    </div>

                    <div class="merge-tag-item">
                        <code>{email}</code>
                        <span><?php esc_html_e( 'User\'s email address', 'altitude-accountability-audit' ); ?></span>
                    </div>

                    <div class="merge-tag-item">
                        <code>{accountability_type}</code>
                        <span><?php esc_html_e( 'Determined accountability type (label)', 'altitude-accountability-audit' ); ?></span>
                    </div>

                    <div class="merge-tag-item">
                        <code>{type_description}</code>
                        <span><?php esc_html_e( 'Description of the accountability type', 'altitude-accountability-audit' ); ?></span>
                    </div>

                    <div class="merge-tag-item">
                        <code>{distraction_score}</code>
                        <span><?php esc_html_e( 'Distraction category score', 'altitude-accountability-audit' ); ?></span>
                    </div>

                    <div class="merge-tag-item">
                        <code>{comfort_score}</code>
                        <span><?php esc_html_e( 'Comfort category score', 'altitude-accountability-audit' ); ?></span>
                    </div>

                    <div class="merge-tag-item">
                        <code>{ego_score}</code>
                        <span><?php esc_html_e( 'Ego category score', 'altitude-accountability-audit' ); ?></span>
                    </div>

                    <div class="merge-tag-item">
                        <code>{emotion_score}</code>
                        <span><?php esc_html_e( 'Emotion category score', 'altitude-accountability-audit' ); ?></span>
                    </div>

                    <div class="merge-tag-item">
                        <code>{boundaries_score}</code>
                        <span><?php esc_html_e( 'Boundaries category score', 'altitude-accountability-audit' ); ?></span>
                    </div>

                    <div class="merge-tag-item">
                        <code>{spiritual_score}</code>
                        <span><?php esc_html_e( 'Spiritual Delay category score', 'altitude-accountability-audit' ); ?></span>
                    </div>

                    <div class="merge-tag-item">
                        <code>{total_score}</code>
                        <span><?php esc_html_e( 'Total score across all categories', 'altitude-accountability-audit' ); ?></span>
                    </div>

                    <div class="merge-tag-item">
                        <code>{result_url}</code>
                        <span><?php esc_html_e( 'URL to detailed results page', 'altitude-accountability-audit' ); ?></span>
                    </div>
                </div>

                <h3><?php esc_html_e( 'Conditional Blocks', 'altitude-accountability-audit' ); ?></h3>
                <div class="conditional-example">
                    <code>{if_result_url}...content...{endif_result_url}</code>
                    <p><?php esc_html_e( 'Content inside this block will only show if a result URL is configured.', 'altitude-accountability-audit' ); ?></p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Email Preview Modal -->
<div id="email-preview-modal" style="display:none;">
    <div class="email-preview-overlay"></div>
    <div class="email-preview-container">
        <div class="email-preview-header">
            <h2><?php esc_html_e( 'Email Preview', 'altitude-accountability-audit' ); ?></h2>
            <button type="button" class="email-preview-close">&times;</button>
        </div>
        <div class="email-preview-body">
            <iframe id="email-preview-iframe"></iframe>
        </div>
    </div>
</div>

<script>
jQuery(document).ready(function($) {
    // Preview email
    $('#preview-email-btn').on('click', function() {
        $.ajax({
            url: ajaxurl,
            type: 'POST',
            data: {
                action: 'altitude_audit_preview_email',
                nonce: altitudeAuditAdmin.nonce
            },
            success: function(response) {
                if (response.success) {
                    var iframe = document.getElementById('email-preview-iframe');
                    iframe.contentWindow.document.open();
                    iframe.contentWindow.document.write(response.data.html);
                    iframe.contentWindow.document.close();

                    $('#email-preview-modal').fadeIn();
                }
            }
        });
    });

    // Close preview modal
    $('.email-preview-close, .email-preview-overlay').on('click', function() {
        $('#email-preview-modal').fadeOut();
    });

    // Send test email
    $('#test-email-btn').on('click', function() {
        var email = prompt('<?php esc_html_e( 'Enter email address to send test to:', 'altitude-accountability-audit' ); ?>', '<?php echo esc_js( get_option( 'admin_email' ) ); ?>');

        if (!email) {
            return;
        }

        var $btn = $(this);
        $btn.prop('disabled', true).text('<?php esc_html_e( 'Sending...', 'altitude-accountability-audit' ); ?>');

        $.ajax({
            url: ajaxurl,
            type: 'POST',
            data: {
                action: 'altitude_audit_test_email',
                nonce: altitudeAuditAdmin.nonce,
                email: email
            },
            success: function(response) {
                if (response.success) {
                    alert(response.data.message);
                } else {
                    alert(response.data.message);
                }
                $btn.prop('disabled', false).text('<?php esc_html_e( 'Send Test Email', 'altitude-accountability-audit' ); ?>');
            },
            error: function() {
                alert('<?php esc_html_e( 'An error occurred. Please try again.', 'altitude-accountability-audit' ); ?>');
                $btn.prop('disabled', false).text('<?php esc_html_e( 'Send Test Email', 'altitude-accountability-audit' ); ?>');
            }
        });
    });
});
</script>
