<?php
/**
 * Shortcodes for the plugin.
 *
 * @package Altitude_Audit
 */

if ( ! defined( 'WPINC' ) ) {
    die;
}

/**
 * Handles all shortcodes.
 */
class Altitude_Audit_Shortcodes {

    /**
     * Render the audit form shortcode.
     *
     * @param array $atts Shortcode attributes.
     * @return string Form HTML.
     */
    public function render_audit_form( $atts ) {
        // Hook: Before form
        do_action( 'altitude_audit_before_form' );

        $form_id = Altitude_Audit_Form_Builder::get_form_id();

        if ( ! $form_id ) {
            return '<div class="altitude-audit-error">' .
                   esc_html__( 'Audit form not found. Please contact the administrator.', 'altitude-accountability-audit' ) .
                   '</div>';
        }

        // Use Fluent Forms shortcode to render the form
        $output = do_shortcode( '[fluentform id="' . $form_id . '"]' );

        // Hook: After form
        do_action( 'altitude_audit_after_form' );

        return $output;
    }

    /**
     * Render user results shortcode.
     *
     * @param array $atts Shortcode attributes.
     * @return string Results HTML.
     */
    public function render_user_results( $atts ) {
        $atts = shortcode_atts(
            array(
                'show_history' => 'no',
            ),
            $atts,
            'audit_results'
        );

        $user = wp_get_current_user();

        if ( ! $user->ID ) {
            return '<div class="altitude-audit-message">' .
                   esc_html__( 'Please log in to view your results.', 'altitude-accountability-audit' ) .
                   '</div>';
        }

        // Get user results
        $results = Altitude_Audit_Database::get_results_by_user( $user->ID );

        if ( empty( $results ) ) {
            return '<div class="altitude-audit-message">' .
                   esc_html__( 'You haven\'t completed the audit yet.', 'altitude-accountability-audit' ) .
                   '</div>';
        }

        $config = get_option( 'altitude_audit_config', altitude_audit_get_default_config() );

        ob_start();

        if ( $atts['show_history'] === 'yes' ) {
            $this->render_results_history( $results, $config );
        } else {
            $this->render_latest_result( $results[0], $config );
        }

        return ob_get_clean();
    }

    /**
     * Render latest result.
     *
     * @param object $result Result object.
     * @param array  $config Configuration array.
     */
    private function render_latest_result( $result, $config ) {
        $type_label = isset( $config['result_pages'][ $result->accountability_type ]['label'] )
            ? $config['result_pages'][ $result->accountability_type ]['label']
            : ucfirst( $result->accountability_type );

        $type_description = isset( $config['result_pages'][ $result->accountability_type ]['description'] )
            ? $config['result_pages'][ $result->accountability_type ]['description']
            : '';

        ?>
        <div class="altitude-audit-results">
            <div class="altitude-audit-results-header">
                <h2><?php esc_html_e( 'Your Personal Accountability Audit Results', 'altitude-accountability-audit' ); ?></h2>
                <p class="result-date"><?php echo esc_html( date_i18n( get_option( 'date_format' ), strtotime( $result->created_at ) ) ); ?></p>
            </div>

            <div class="altitude-audit-type-card">
                <h3><?php esc_html_e( 'Your Accountability Type:', 'altitude-accountability-audit' ); ?></h3>
                <div class="type-badge"><?php echo esc_html( $type_label ); ?></div>
                <p class="type-description"><?php echo esc_html( $type_description ); ?></p>
            </div>

            <div class="altitude-audit-scores">
                <h3><?php esc_html_e( 'Score Breakdown', 'altitude-accountability-audit' ); ?></h3>

                <div class="score-bars">
                    <?php
                    $categories = array(
                        'distraction' => __( 'Distraction', 'altitude-accountability-audit' ),
                        'comfort' => __( 'Comfort', 'altitude-accountability-audit' ),
                        'ego' => __( 'Ego', 'altitude-accountability-audit' ),
                        'emotion' => __( 'Emotion', 'altitude-accountability-audit' ),
                        'boundaries' => __( 'Boundaries', 'altitude-accountability-audit' ),
                        'spiritual' => __( 'Spiritual Delay', 'altitude-accountability-audit' ),
                    );

                    foreach ( $categories as $key => $label ) {
                        $score = $result->{$key . '_score'};
                        $percentage = ( $score / 9 ) * 100;
                        $is_highest = ( $key === $result->accountability_type );
                        ?>
                        <div class="score-bar-item <?php echo $is_highest ? 'highest-score' : ''; ?>">
                            <div class="score-label">
                                <span><?php echo esc_html( $label ); ?></span>
                                <span class="score-value"><?php echo esc_html( $score ); ?>/9</span>
                            </div>
                            <div class="score-bar-container">
                                <div class="score-bar" style="width: <?php echo esc_attr( $percentage ); ?>%;"></div>
                            </div>
                        </div>
                        <?php
                    }
                    ?>
                </div>

                <div class="total-score">
                    <strong><?php esc_html_e( 'Total Score:', 'altitude-accountability-audit' ); ?></strong>
                    <span><?php echo esc_html( $result->total_score ); ?>/54</span>
                </div>
            </div>

            <?php
            $result_url = isset( $config['result_pages'][ $result->accountability_type ]['url'] )
                ? $config['result_pages'][ $result->accountability_type ]['url']
                : '';

            if ( $result_url ) {
                ?>
                <div class="altitude-audit-cta">
                    <a href="<?php echo esc_url( $result_url ); ?>" class="button altitude-audit-button">
                        <?php esc_html_e( 'View Detailed Results & Next Steps', 'altitude-accountability-audit' ); ?>
                    </a>
                </div>
                <?php
            }
            ?>
        </div>
        <?php
    }

    /**
     * Render results history.
     *
     * @param array $results Array of result objects.
     * @param array $config Configuration array.
     */
    private function render_results_history( $results, $config ) {
        ?>
        <div class="altitude-audit-results-history">
            <h2><?php esc_html_e( 'Your Audit History', 'altitude-accountability-audit' ); ?></h2>

            <div class="results-timeline">
                <?php foreach ( $results as $result ) : ?>
                    <div class="result-card">
                        <div class="result-date">
                            <?php echo esc_html( date_i18n( get_option( 'date_format' ), strtotime( $result->created_at ) ) ); ?>
                        </div>
                        <div class="result-type">
                            <?php
                            $type_label = isset( $config['result_pages'][ $result->accountability_type ]['label'] )
                                ? $config['result_pages'][ $result->accountability_type ]['label']
                                : ucfirst( $result->accountability_type );

                            echo esc_html( $type_label );
                            ?>
                        </div>
                        <div class="result-score">
                            <?php
                            /* translators: %1$d is the total score, %2$d is the maximum score */
                            printf( esc_html__( 'Score: %1$d/%2$d', 'altitude-accountability-audit' ), (int) $result->total_score, 54 );
                            ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php
    }
}
