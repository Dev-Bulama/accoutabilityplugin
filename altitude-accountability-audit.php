<?php
/**
 * Plugin Name: Altitude Within - Accountability Audit
 * Plugin URI: https://altitudewithin.com
 * Description: A standalone Personal Accountability Audit form with quiz functionality - no external dependencies required
 * Version: 2.0.0
 * Author: Altitude Within
 * Author URI: https://altitudewithin.com
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: altitude-accountability-audit
 * Domain Path: /languages
 * Requires at least: 5.8
 * Requires PHP: 7.4
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
    die;
}

/**
 * Current plugin version.
 */
define( 'ALTITUDE_AUDIT_VERSION', '2.0.0' );
define( 'ALTITUDE_AUDIT_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'ALTITUDE_AUDIT_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'ALTITUDE_AUDIT_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );

/**
 * The code that runs during plugin activation.
 */
function activate_altitude_audit() {
    require_once ALTITUDE_AUDIT_PLUGIN_DIR . 'includes/class-activator.php';
    Altitude_Audit_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 */
function deactivate_altitude_audit() {
    require_once ALTITUDE_AUDIT_PLUGIN_DIR . 'includes/class-deactivator.php';
    Altitude_Audit_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_altitude_audit' );
register_deactivation_hook( __FILE__, 'deactivate_altitude_audit' );

/**
 * The core plugin class.
 */
require_once ALTITUDE_AUDIT_PLUGIN_DIR . 'includes/class-altitude-audit.php';

/**
 * Begins execution of the plugin.
 */
function run_altitude_audit() {
    $plugin = new Altitude_Audit();
    $plugin->run();
}

// Run the plugin
add_action( 'plugins_loaded', 'run_altitude_audit' );
