<?php
/**
 * Statistics view.
 *
 * @package Altitude_Audit
 */

if ( ! defined( 'WPINC' ) ) {
    die;
}

$stats = Altitude_Audit_Database::get_statistics();
$config = get_option( 'altitude_audit_config', altitude_audit_get_default_config() );
?>

<div class="wrap altitude-audit-admin">
    <h1><?php esc_html_e( 'Audit Statistics', 'altitude-accountability-audit' ); ?></h1>

    <div class="altitude-audit-statistics">
        <!-- Overview -->
        <div class="altitude-audit-card">
            <h2><?php esc_html_e( 'Overview', 'altitude-accountability-audit' ); ?></h2>

            <div class="stats-overview">
                <div class="stat-large">
                    <div class="stat-number"><?php echo esc_html( number_format( $stats['total_submissions'] ) ); ?></div>
                    <div class="stat-label"><?php esc_html_e( 'Total Submissions', 'altitude-accountability-audit' ); ?></div>
                </div>
            </div>
        </div>

        <!-- Submissions by Type -->
        <div class="altitude-audit-card">
            <h2><?php esc_html_e( 'Submissions by Accountability Type', 'altitude-accountability-audit' ); ?></h2>

            <?php if ( ! empty( $stats['by_type'] ) ) : ?>
                <div class="type-stats">
                    <?php
                    $max_count = max( $stats['by_type'] );

                    foreach ( $stats['by_type'] as $type => $count ) :
                        $percentage = $max_count > 0 ? ( $count / $max_count ) * 100 : 0;
                        $type_label = isset( $config['result_pages'][ $type ]['label'] )
                            ? $config['result_pages'][ $type ]['label']
                            : ucfirst( $type );
                        ?>
                        <div class="type-stat-row">
                            <div class="type-label"><?php echo esc_html( $type_label ); ?></div>
                            <div class="type-bar-container">
                                <div class="type-bar" style="width: <?php echo esc_attr( $percentage ); ?>%;"></div>
                            </div>
                            <div class="type-count"><?php echo esc_html( $count ); ?></div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else : ?>
                <p><?php esc_html_e( 'No submissions yet.', 'altitude-accountability-audit' ); ?></p>
            <?php endif; ?>
        </div>

        <!-- Average Scores -->
        <div class="altitude-audit-card">
            <h2><?php esc_html_e( 'Average Scores by Category', 'altitude-accountability-audit' ); ?></h2>

            <?php if ( ! empty( $stats['avg_scores'] ) && $stats['total_submissions'] > 0 ) : ?>
                <div class="avg-scores">
                    <?php
                    $categories = array(
                        'distraction' => __( 'Distraction', 'altitude-accountability-audit' ),
                        'comfort' => __( 'Comfort', 'altitude-accountability-audit' ),
                        'ego' => __( 'Ego', 'altitude-accountability-audit' ),
                        'emotion' => __( 'Emotion', 'altitude-accountability-audit' ),
                        'boundaries' => __( 'Boundaries', 'altitude-accountability-audit' ),
                        'spiritual' => __( 'Spiritual Delay', 'altitude-accountability-audit' ),
                    );

                    foreach ( $categories as $key => $label ) :
                        $avg_score = isset( $stats['avg_scores'][ $key ] ) ? $stats['avg_scores'][ $key ] : 0;
                        $percentage = ( $avg_score / 9 ) * 100;
                        ?>
                        <div class="avg-score-row">
                            <div class="avg-label"><?php echo esc_html( $label ); ?></div>
                            <div class="avg-bar-container">
                                <div class="avg-bar" style="width: <?php echo esc_attr( $percentage ); ?>%;"></div>
                            </div>
                            <div class="avg-value"><?php echo esc_html( number_format( $avg_score, 2 ) ); ?>/9</div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else : ?>
                <p><?php esc_html_e( 'No submissions yet.', 'altitude-accountability-audit' ); ?></p>
            <?php endif; ?>
        </div>
    </div>
</div>
