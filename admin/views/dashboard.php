<?php
/**
 * Dashboard view.
 *
 * @package Altitude_Audit
 */

if ( ! defined( 'WPINC' ) ) {
    die;
}

$form_id = Altitude_Audit_Form_Builder::get_form_id();
$stats = Altitude_Audit_Database::get_statistics();
?>

<div class="wrap altitude-audit-admin">
    <h1><?php esc_html_e( 'Accountability Audit Dashboard', 'altitude-accountability-audit' ); ?></h1>

    <div class="altitude-audit-dashboard">
        <!-- Quick Stats -->
        <div class="altitude-audit-stats-grid">
            <div class="stat-card">
                <div class="stat-icon">📊</div>
                <div class="stat-content">
                    <div class="stat-value"><?php echo esc_html( number_format( $stats['total_submissions'] ) ); ?></div>
                    <div class="stat-label"><?php esc_html_e( 'Total Submissions', 'altitude-accountability-audit' ); ?></div>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon">📝</div>
                <div class="stat-content">
                    <div class="stat-value"><?php echo $form_id ? '#' . esc_html( $form_id ) : esc_html__( 'N/A', 'altitude-accountability-audit' ); ?></div>
                    <div class="stat-label"><?php esc_html_e( 'Form ID', 'altitude-accountability-audit' ); ?></div>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon">🎯</div>
                <div class="stat-content">
                    <div class="stat-value"><?php echo esc_html( count( $stats['by_type'] ) ); ?></div>
                    <div class="stat-label"><?php esc_html_e( 'Unique Types', 'altitude-accountability-audit' ); ?></div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="altitude-audit-card">
            <h2><?php esc_html_e( 'Quick Actions', 'altitude-accountability-audit' ); ?></h2>

            <div class="altitude-audit-actions">
                <?php if ( $form_id ) : ?>
                    <a href="<?php echo esc_url( admin_url( 'admin.php?page=fluent_forms&route=editor&form_id=' . $form_id ) ); ?>" class="button button-primary button-large">
                        <span class="dashicons dashicons-edit"></span>
                        <?php esc_html_e( 'Edit Form in Fluent Forms', 'altitude-accountability-audit' ); ?>
                    </a>

                    <button type="button" class="button button-secondary button-large" id="rebuild-form-btn">
                        <span class="dashicons dashicons-update"></span>
                        <?php esc_html_e( 'Rebuild Form from Config', 'altitude-accountability-audit' ); ?>
                    </button>

                    <a href="<?php echo esc_url( admin_url( 'admin.php?page=fluent_forms&route=entries&form_id=' . $form_id ) ); ?>" class="button button-secondary button-large">
                        <span class="dashicons dashicons-list-view"></span>
                        <?php esc_html_e( 'View Submissions', 'altitude-accountability-audit' ); ?>
                    </a>
                <?php else : ?>
                    <button type="button" class="button button-primary button-large" id="create-form-btn">
                        <span class="dashicons dashicons-plus"></span>
                        <?php esc_html_e( 'Create Audit Form', 'altitude-accountability-audit' ); ?>
                    </button>
                <?php endif; ?>

                <a href="<?php echo esc_url( admin_url( 'admin.php?page=altitude-audit-settings' ) ); ?>" class="button button-secondary button-large">
                    <span class="dashicons dashicons-admin-settings"></span>
                    <?php esc_html_e( 'Configure Settings', 'altitude-accountability-audit' ); ?>
                </a>

                <a href="<?php echo esc_url( admin_url( 'admin.php?page=altitude-audit-email' ) ); ?>" class="button button-secondary button-large">
                    <span class="dashicons dashicons-email"></span>
                    <?php esc_html_e( 'Edit Email Template', 'altitude-accountability-audit' ); ?>
                </a>
            </div>
        </div>

        <!-- Shortcodes -->
        <div class="altitude-audit-card">
            <h2><?php esc_html_e( 'Shortcodes', 'altitude-accountability-audit' ); ?></h2>

            <div class="shortcode-list">
                <div class="shortcode-item">
                    <code>[accountability_audit]</code>
                    <p><?php esc_html_e( 'Display the accountability audit form', 'altitude-accountability-audit' ); ?></p>
                </div>

                <div class="shortcode-item">
                    <code>[audit_results]</code>
                    <p><?php esc_html_e( 'Display the latest results for logged-in users', 'altitude-accountability-audit' ); ?></p>
                </div>

                <div class="shortcode-item">
                    <code>[audit_results show_history="yes"]</code>
                    <p><?php esc_html_e( 'Display all results history for logged-in users', 'altitude-accountability-audit' ); ?></p>
                </div>
            </div>
        </div>

        <!-- Getting Started -->
        <div class="altitude-audit-card">
            <h2><?php esc_html_e( 'Getting Started', 'altitude-accountability-audit' ); ?></h2>

            <ol class="getting-started-list">
                <li>
                    <strong><?php esc_html_e( 'Review Settings', 'altitude-accountability-audit' ); ?></strong>
                    <p><?php esc_html_e( 'Configure your quiz categories, questions, and result pages in the Settings page.', 'altitude-accountability-audit' ); ?></p>
                </li>
                <li>
                    <strong><?php esc_html_e( 'Customize Email', 'altitude-accountability-audit' ); ?></strong>
                    <p><?php esc_html_e( 'Edit the email template that will be sent to users with their results.', 'altitude-accountability-audit' ); ?></p>
                </li>
                <li>
                    <strong><?php esc_html_e( 'Add to Page', 'altitude-accountability-audit' ); ?></strong>
                    <p><?php esc_html_e( 'Use the [accountability_audit] shortcode to display the form on any page.', 'altitude-accountability-audit' ); ?></p>
                </li>
                <li>
                    <strong><?php esc_html_e( 'Test It Out', 'altitude-accountability-audit' ); ?></strong>
                    <p><?php esc_html_e( 'Complete a test submission to ensure everything works correctly.', 'altitude-accountability-audit' ); ?></p>
                </li>
            </ol>
        </div>
    </div>
</div>

<script>
jQuery(document).ready(function($) {
    $('#rebuild-form-btn, #create-form-btn').on('click', function(e) {
        e.preventDefault();

        if (!confirm('<?php esc_html_e( 'This will rebuild the form from your configuration. Any manual changes made in Fluent Forms will be overwritten. Continue?', 'altitude-accountability-audit' ); ?>')) {
            return;
        }

        var $btn = $(this);
        $btn.prop('disabled', true).text('<?php esc_html_e( 'Rebuilding...', 'altitude-accountability-audit' ); ?>');

        $.ajax({
            url: ajaxurl,
            type: 'POST',
            data: {
                action: 'altitude_audit_rebuild_form',
                nonce: altitudeAuditAdmin.nonce
            },
            success: function(response) {
                if (response.success) {
                    alert(response.data.message);
                    location.reload();
                } else {
                    alert(response.data.message);
                    $btn.prop('disabled', false).text('<?php esc_html_e( 'Rebuild Form', 'altitude-accountability-audit' ); ?>');
                }
            },
            error: function() {
                alert('<?php esc_html_e( 'An error occurred. Please try again.', 'altitude-accountability-audit' ); ?>');
                $btn.prop('disabled', false).text('<?php esc_html_e( 'Rebuild Form', 'altitude-accountability-audit' ); ?>');
            }
        });
    });
});
</script>
