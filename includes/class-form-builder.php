<?php
/**
 * Standalone form builder - creates custom HTML forms
 *
 * @package Altitude_Audit
 */

if ( ! defined( 'WPINC' ) ) {
    die;
}

/**
 * Handles standalone form creation and rendering.
 */
class Altitude_Audit_Form_Builder {

    /**
     * Render the audit form.
     *
     * @param array $atts Shortcode attributes.
     * @return string Form HTML.
     */
    public static function render_form( $atts = array() ) {
        $config = get_option( 'altitude_audit_config', altitude_audit_get_default_config() );

        ob_start();
        ?>
        <div class="altitude-audit-form-wrapper">
            <form id="altitude-audit-form" class="altitude-audit-form" method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
                <?php wp_nonce_field( 'altitude_audit_submit', 'altitude_audit_nonce' ); ?>
                <input type="hidden" name="action" value="altitude_audit_submit">

                <!-- Form Header -->
                <div class="altitude-form-header">
                    <h2><?php echo esc_html( $config['form_settings']['form_title'] ); ?></h2>
                </div>

                <!-- Personal Information -->
                <div class="altitude-form-section altitude-personal-info">
                    <div class="altitude-form-row">
                        <div class="altitude-form-field">
                            <label for="first_name"><?php esc_html_e( 'First Name', 'altitude-accountability-audit' ); ?> <span class="required">*</span></label>
                            <input type="text" id="first_name" name="first_name" required placeholder="<?php esc_attr_e( 'Enter your first name', 'altitude-accountability-audit' ); ?>">
                        </div>

                        <div class="altitude-form-field">
                            <label for="email"><?php esc_html_e( 'Email Address', 'altitude-accountability-audit' ); ?> <span class="required">*</span></label>
                            <input type="email" id="email" name="email" required placeholder="<?php esc_attr_e( 'Enter your email', 'altitude-accountability-audit' ); ?>">
                            <small><?php esc_html_e( 'We\'ll send your results here', 'altitude-accountability-audit' ); ?></small>
                        </div>
                    </div>
                </div>

                <?php
                // Render quiz questions by category
                $question_num = 1;
                foreach ( $config['categories'] as $category_key => $category ) {
                    echo '<div class="altitude-form-section altitude-category-section" data-category="' . esc_attr( $category_key ) . '">';
                    echo '<h3>' . esc_html( $category['icon'] ) . ' ' . esc_html( $category['label'] ) . '</h3>';
                    echo '<p class="category-description">' . esc_html( $category['description'] ) . '</p>';

                    foreach ( $category['questions'] as $index => $question ) {
                        $field_name = $category_key . '_q' . ( $index + 1 );
                        ?>
                        <div class="altitude-question-wrapper">
                            <fieldset>
                                <legend><?php echo esc_html( $question_num ) . '. ' . esc_html( $question['label'] ); ?> <span class="required">*</span></legend>

                                <div class="altitude-radio-group">
                                    <?php foreach ( $config['scoring_options'] as $option ) : ?>
                                        <label class="altitude-radio-option">
                                            <input
                                                type="radio"
                                                name="<?php echo esc_attr( $field_name ); ?>"
                                                value="<?php echo esc_attr( $option['points'] ); ?>"
                                                data-category="<?php echo esc_attr( $category_key ); ?>"
                                                required
                                            >
                                            <span class="radio-label"><?php echo esc_html( $option['label'] ); ?></span>
                                        </label>
                                    <?php endforeach; ?>
                                </div>
                            </fieldset>
                        </div>
                        <?php
                        $question_num++;
                    }

                    echo '</div>';
                }
                ?>

                <!-- Hidden fields for scores -->
                <?php foreach ( array_keys( $config['categories'] ) as $category_key ) : ?>
                    <input type="hidden" name="<?php echo esc_attr( $category_key ); ?>_score" id="<?php echo esc_attr( $category_key ); ?>_score" value="0" class="category-score-field" data-category="<?php echo esc_attr( $category_key ); ?>">
                <?php endforeach; ?>

                <input type="hidden" name="total_score" id="total_score" value="0">
                <input type="hidden" name="accountability_type" id="accountability_type" value="">

                <!-- Submit Section -->
                <div class="altitude-form-submit">
                    <?php if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) : ?>
                        <button type="button" id="altitude-autofill-btn" class="altitude-btn altitude-btn-secondary">
                            <?php esc_html_e( '🧪 Auto-Fill (Test Mode)', 'altitude-accountability-audit' ); ?>
                        </button>
                    <?php endif; ?>

                    <button type="submit" class="altitude-btn altitude-btn-primary">
                        <?php echo esc_html( $config['form_settings']['submit_button_text'] ); ?>
                    </button>
                </div>

                <!-- Progress Indicator -->
                <div class="altitude-progress-bar">
                    <div class="altitude-progress-fill" style="width: 0%;"></div>
                </div>
                <p class="altitude-progress-text">
                    <span id="answered-count">0</span> of <?php echo esc_html( $question_num - 1 ); ?> questions answered
                </p>
            </form>
        </div>
        <?php
        return ob_get_clean();
    }

    /**
     * Get all questions (for reference).
     *
     * @return array
     */
    public static function get_all_questions() {
        $config = get_option( 'altitude_audit_config', altitude_audit_get_default_config() );
        $questions = array();

        foreach ( $config['categories'] as $category_key => $category ) {
            foreach ( $category['questions'] as $index => $question ) {
                $questions[] = array(
                    'category' => $category_key,
                    'field_name' => $category_key . '_q' . ( $index + 1 ),
                    'label' => $question['label'],
                );
            }
        }

        return $questions;
    }

    /**
     * Get form configuration summary.
     *
     * @return array
     */
    public static function get_form_summary() {
        $config = get_option( 'altitude_audit_config', altitude_audit_get_default_config() );

        return array(
            'total_categories' => count( $config['categories'] ),
            'total_questions' => array_sum( array_map( function( $cat ) {
                return count( $cat['questions'] );
            }, $config['categories'] ) ),
            'scoring_options' => count( $config['scoring_options'] ),
            'max_score' => count( $config['scoring_options'] ) - 1,
        );
    }
}
