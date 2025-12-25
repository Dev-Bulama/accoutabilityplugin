<?php
/**
 * Dashboard view - Standalone version.
 *
 * @package Altitude_Audit
 */

if ( ! defined( 'WPINC' ) ) {
    die;
}

$stats = Altitude_Audit_Database::get_statistics();
$form_summary = Altitude_Audit_Form_Builder::get_form_summary();
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
                    <div class="stat-value"><?php echo esc_html( $form_summary['total_questions'] ); ?></div>
                    <div class="stat-label"><?php esc_html_e( 'Quiz Questions', 'altitude-accountability-audit' ); ?></div>
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

        <!-- Plugin Status -->
        <div class="altitude-audit-card">
            <h2><?php esc_html_e( 'Plugin Status', 'altitude-accountability-audit' ); ?></h2>

            <div class="status-grid">
                <div class="status-item">
                    <span class="status-icon">✅</span>
                    <div>
                        <strong><?php esc_html_e( 'Standalone Form System', 'altitude-accountability-audit' ); ?></strong>
                        <p><?php esc_html_e( 'No external dependencies - fully independent', 'altitude-accountability-audit' ); ?></p>
                    </div>
                </div>

                <div class="status-item">
                    <span class="status-icon">📋</span>
                    <div>
                        <strong><?php printf( esc_html__( '%d Categories with %d Questions', 'altitude-accountability-audit' ), $form_summary['total_categories'], $form_summary['total_questions'] ); ?></strong>
                        <p><?php esc_html_e( 'All questions configured and ready', 'altitude-accountability-audit' ); ?></p>
                    </div>
                </div>

                <div class="status-item">
                    <span class="status-icon">💾</span>
                    <div>
                        <strong><?php esc_html_e( 'Database Ready', 'altitude-accountability-audit' ); ?></strong>
                        <p><?php esc_html_e( 'All submissions are stored securely', 'altitude-accountability-audit' ); ?></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="altitude-audit-card">
            <h2><?php esc_html_e( 'Quick Actions', 'altitude-accountability-audit' ); ?></h2>

            <div class="altitude-audit-actions">
                <a href="<?php echo esc_url( admin_url( 'admin.php?page=altitude-audit-settings' ) ); ?>" class="button button-primary button-large">
                    <span class="dashicons dashicons-admin-settings"></span>
                    <?php esc_html_e( 'Configure Settings', 'altitude-accountability-audit' ); ?>
                </a>

                <a href="<?php echo esc_url( admin_url( 'admin.php?page=altitude-audit-email' ) ); ?>" class="button button-secondary button-large">
                    <span class="dashicons dashicons-email"></span>
                    <?php esc_html_e( 'Edit Email Template', 'altitude-accountability-audit' ); ?>
                </a>

                <a href="<?php echo esc_url( admin_url( 'admin.php?page=altitude-audit-statistics' ) ); ?>" class="button button-secondary button-large">
                    <span class="dashicons dashicons-chart-bar"></span>
                    <?php esc_html_e( 'View Statistics', 'altitude-accountability-audit' ); ?>
                </a>
            </div>
        </div>

        <!-- Shortcodes -->
        <div class="altitude-audit-card">
            <h2><?php esc_html_e( 'Shortcodes', 'altitude-accountability-audit' ); ?></h2>

            <div class="shortcode-list">
                <div class="shortcode-item">
                    <code>[accountability_audit]</code>
                    <p><?php esc_html_e( 'Display the accountability audit form on any page', 'altitude-accountability-audit' ); ?></p>
                    <button type="button" class="button button-small copy-shortcode" data-shortcode="[accountability_audit]">
                        <?php esc_html_e( 'Copy', 'altitude-accountability-audit' ); ?>
                    </button>
                </div>

                <div class="shortcode-item">
                    <code>[audit_results]</code>
                    <p><?php esc_html_e( 'Display the latest results for logged-in users', 'altitude-accountability-audit' ); ?></p>
                    <button type="button" class="button button-small copy-shortcode" data-shortcode="[audit_results]">
                        <?php esc_html_e( 'Copy', 'altitude-accountability-audit' ); ?>
                    </button>
                </div>

                <div class="shortcode-item">
                    <code>[audit_results show_history="yes"]</code>
                    <p><?php esc_html_e( 'Display all results history for logged-in users', 'altitude-accountability-audit' ); ?></p>
                    <button type="button" class="button button-small copy-shortcode" data-shortcode='[audit_results show_history="yes"]'>
                        <?php esc_html_e( 'Copy', 'altitude-accountability-audit' ); ?>
                    </button>
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
                    <p><?php esc_html_e( 'Enable WP_DEBUG to see the auto-fill button for testing submissions.', 'altitude-accountability-audit' ); ?></p>
                </li>
            </ol>
        </div>

        <!-- Features -->
        <div class="altitude-audit-card">
            <h2><?php esc_html_e( 'Key Features', 'altitude-accountability-audit' ); ?></h2>

            <div class="features-grid">
                <div class="feature-item">
                    <span class="feature-icon">🚀</span>
                    <strong><?php esc_html_e( 'Fully Standalone', 'altitude-accountability-audit' ); ?></strong>
                    <p><?php esc_html_e( 'No external plugin dependencies required', 'altitude-accountability-audit' ); ?></p>
                </div>

                <div class="feature-item">
                    <span class="feature-icon">🎯</span>
                    <strong><?php esc_html_e( 'Auto-Fill Testing', 'altitude-accountability-audit' ); ?></strong>
                    <p><?php esc_html_e( 'Built-in auto-fill button when WP_DEBUG is enabled', 'altitude-accountability-audit' ); ?></p>
                </div>

                <div class="feature-item">
                    <span class="feature-icon">📊</span>
                    <strong><?php esc_html_e( 'Real-time Scoring', 'altitude-accountability-audit' ); ?></strong>
                    <p><?php esc_html_e( 'Scores calculated as users answer questions', 'altitude-accountability-audit' ); ?></p>
                </div>

                <div class="feature-item">
                    <span class="feature-icon">📧</span>
                    <strong><?php esc_html_e( 'Email Results', 'altitude-accountability-audit' ); ?></strong>
                    <p><?php esc_html_e( 'Automatic email delivery with customizable templates', 'altitude-accountability-audit' ); ?></p>
                </div>

                <div class="feature-item">
                    <span class="feature-icon">🔀</span>
                    <strong><?php esc_html_e( 'Smart Redirects', 'altitude-accountability-audit' ); ?></strong>
                    <p><?php esc_html_e( 'Redirect users to type-specific result pages', 'altitude-accountability-audit' ); ?></p>
                </div>

                <div class="feature-item">
                    <span class="feature-icon">💾</span>
                    <strong><?php esc_html_e( 'Full Analytics', 'altitude-accountability-audit' ); ?></strong>
                    <p><?php esc_html_e( 'Track submissions and view detailed statistics', 'altitude-accountability-audit' ); ?></p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
jQuery(document).ready(function($) {
    // Copy shortcode to clipboard
    $('.copy-shortcode').on('click', function() {
        var shortcode = $(this).data('shortcode');
        var $temp = $('<input>');
        $('body').append($temp);
        $temp.val(shortcode).select();
        document.execCommand('copy');
        $temp.remove();

        var $btn = $(this);
        var originalText = $btn.text();
        $btn.text('✓ Copied!');

        setTimeout(function() {
            $btn.text(originalText);
        }, 2000);
    });
});
</script>

<style>
.status-grid {
    display: grid;
    gap: 20px;
}

.status-item {
    display: flex;
    gap: 15px;
    align-items: flex-start;
    padding: 15px;
    background: #f7f9fc;
    border-radius: 8px;
}

.status-icon {
    font-size: 24px;
    flex-shrink: 0;
}

.status-item strong {
    display: block;
    margin-bottom: 5px;
    color: #333;
}

.status-item p {
    margin: 0;
    color: #666;
    font-size: 14px;
}

.features-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
}

.feature-item {
    text-align: center;
    padding: 20px;
    background: #f7f9fc;
    border-radius: 8px;
}

.feature-icon {
    font-size: 32px;
    display: block;
    margin-bottom: 10px;
}

.feature-item strong {
    display: block;
    margin-bottom: 8px;
    color: #333;
}

.feature-item p {
    margin: 0;
    color: #666;
    font-size: 14px;
}

.shortcode-item {
    position: relative;
}

.copy-shortcode {
    margin-top: 10px;
}
</style>
