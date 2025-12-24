<?php
/**
 * Database handler for the plugin.
 *
 * @package Altitude_Audit
 */

if ( ! defined( 'WPINC' ) ) {
    die;
}

/**
 * Handles all database operations for the plugin.
 */
class Altitude_Audit_Database {

    /**
     * Create database tables.
     */
    public static function create_tables() {
        global $wpdb;

        $charset_collate = $wpdb->get_charset_collate();
        $table_name = $wpdb->prefix . 'altitude_audit_results';

        $sql = "CREATE TABLE IF NOT EXISTS $table_name (
            id bigint(20) NOT NULL AUTO_INCREMENT,
            user_id bigint(20) DEFAULT NULL,
            submission_id bigint(20) NOT NULL,
            first_name varchar(255) NOT NULL,
            email varchar(255) NOT NULL,
            distraction_score int(11) NOT NULL DEFAULT 0,
            comfort_score int(11) NOT NULL DEFAULT 0,
            ego_score int(11) NOT NULL DEFAULT 0,
            emotion_score int(11) NOT NULL DEFAULT 0,
            boundaries_score int(11) NOT NULL DEFAULT 0,
            spiritual_score int(11) NOT NULL DEFAULT 0,
            total_score int(11) NOT NULL DEFAULT 0,
            accountability_type varchar(50) NOT NULL,
            created_at datetime DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY  (id),
            KEY user_id (user_id),
            KEY submission_id (submission_id),
            KEY email (email),
            KEY accountability_type (accountability_type)
        ) $charset_collate;";

        require_once ABSPATH . 'wp-admin/includes/upgrade.php';
        dbDelta( $sql );
    }

    /**
     * Save audit result.
     *
     * @param array $data Result data.
     * @return int|false
     */
    public static function save_result( $data ) {
        global $wpdb;

        $table_name = $wpdb->prefix . 'altitude_audit_results';

        // Sanitize data
        $insert_data = array(
            'user_id'              => ! empty( $data['user_id'] ) ? absint( $data['user_id'] ) : null,
            'submission_id'        => absint( $data['submission_id'] ),
            'first_name'           => sanitize_text_field( $data['first_name'] ),
            'email'                => sanitize_email( $data['email'] ),
            'distraction_score'    => absint( $data['distraction_score'] ),
            'comfort_score'        => absint( $data['comfort_score'] ),
            'ego_score'            => absint( $data['ego_score'] ),
            'emotion_score'        => absint( $data['emotion_score'] ),
            'boundaries_score'     => absint( $data['boundaries_score'] ),
            'spiritual_score'      => absint( $data['spiritual_score'] ),
            'total_score'          => absint( $data['total_score'] ),
            'accountability_type'  => sanitize_text_field( $data['accountability_type'] ),
        );

        $result = $wpdb->insert( $table_name, $insert_data );

        if ( $result ) {
            return $wpdb->insert_id;
        }

        return false;
    }

    /**
     * Get result by ID.
     *
     * @param int $id Result ID.
     * @return object|null
     */
    public static function get_result( $id ) {
        global $wpdb;

        $table_name = $wpdb->prefix . 'altitude_audit_results';
        $id = absint( $id );

        return $wpdb->get_row(
            $wpdb->prepare( "SELECT * FROM $table_name WHERE id = %d", $id )
        );
    }

    /**
     * Get results by email.
     *
     * @param string $email Email address.
     * @return array
     */
    public static function get_results_by_email( $email ) {
        global $wpdb;

        $table_name = $wpdb->prefix . 'altitude_audit_results';
        $email = sanitize_email( $email );

        return $wpdb->get_results(
            $wpdb->prepare(
                "SELECT * FROM $table_name WHERE email = %s ORDER BY created_at DESC",
                $email
            )
        );
    }

    /**
     * Get results by user ID.
     *
     * @param int $user_id User ID.
     * @return array
     */
    public static function get_results_by_user( $user_id ) {
        global $wpdb;

        $table_name = $wpdb->prefix . 'altitude_audit_results';
        $user_id = absint( $user_id );

        return $wpdb->get_results(
            $wpdb->prepare(
                "SELECT * FROM $table_name WHERE user_id = %d ORDER BY created_at DESC",
                $user_id
            )
        );
    }

    /**
     * Get statistics.
     *
     * @return array
     */
    public static function get_statistics() {
        global $wpdb;

        $table_name = $wpdb->prefix . 'altitude_audit_results';

        $stats = array(
            'total_submissions' => 0,
            'by_type' => array(),
            'avg_scores' => array(),
        );

        // Total submissions
        $stats['total_submissions'] = $wpdb->get_var( "SELECT COUNT(*) FROM $table_name" );

        // By type
        $type_counts = $wpdb->get_results(
            "SELECT accountability_type, COUNT(*) as count
             FROM $table_name
             GROUP BY accountability_type
             ORDER BY count DESC"
        );

        foreach ( $type_counts as $type ) {
            $stats['by_type'][ $type->accountability_type ] = $type->count;
        }

        // Average scores
        $avg_scores = $wpdb->get_row(
            "SELECT
                AVG(distraction_score) as distraction,
                AVG(comfort_score) as comfort,
                AVG(ego_score) as ego,
                AVG(emotion_score) as emotion,
                AVG(boundaries_score) as boundaries,
                AVG(spiritual_score) as spiritual
             FROM $table_name"
        );

        if ( $avg_scores ) {
            $stats['avg_scores'] = array(
                'distraction' => round( $avg_scores->distraction, 2 ),
                'comfort' => round( $avg_scores->comfort, 2 ),
                'ego' => round( $avg_scores->ego, 2 ),
                'emotion' => round( $avg_scores->emotion, 2 ),
                'boundaries' => round( $avg_scores->boundaries, 2 ),
                'spiritual' => round( $avg_scores->spiritual, 2 ),
            );
        }

        return $stats;
    }

    /**
     * Delete old results (for GDPR compliance).
     *
     * @param int $days Number of days to keep.
     * @return int|false Number of rows deleted or false on error.
     */
    public static function delete_old_results( $days = 365 ) {
        global $wpdb;

        $table_name = $wpdb->prefix . 'altitude_audit_results';
        $days = absint( $days );

        return $wpdb->query(
            $wpdb->prepare(
                "DELETE FROM $table_name WHERE created_at < DATE_SUB(NOW(), INTERVAL %d DAY)",
                $days
            )
        );
    }
}
