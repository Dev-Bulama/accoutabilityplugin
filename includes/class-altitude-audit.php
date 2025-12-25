<?php
/**
 * The core plugin class.
 *
 * @package Altitude_Audit
 */

if ( ! defined( 'WPINC' ) ) {
    die;
}

/**
 * The core plugin class.
 */
class Altitude_Audit {

    /**
     * The loader that's responsible for maintaining and registering all hooks.
     *
     * @var Altitude_Audit_Loader
     */
    protected $loader;

    /**
     * The unique identifier of this plugin.
     *
     * @var string
     */
    protected $plugin_name;

    /**
     * The current version of the plugin.
     *
     * @var string
     */
    protected $version;

    /**
     * Initialize the plugin.
     */
    public function __construct() {
        $this->version = ALTITUDE_AUDIT_VERSION;
        $this->plugin_name = 'altitude-accountability-audit';

        $this->load_dependencies();
        $this->set_locale();
        $this->define_admin_hooks();
        $this->define_public_hooks();
    }

    /**
     * Load the required dependencies.
     */
    private function load_dependencies() {
        // Core classes
        require_once ALTITUDE_AUDIT_PLUGIN_DIR . 'includes/class-loader.php';
        require_once ALTITUDE_AUDIT_PLUGIN_DIR . 'includes/class-i18n.php';
        require_once ALTITUDE_AUDIT_PLUGIN_DIR . 'config/default-config.php';

        // Database
        require_once ALTITUDE_AUDIT_PLUGIN_DIR . 'includes/class-database.php';

        // Form builder
        require_once ALTITUDE_AUDIT_PLUGIN_DIR . 'includes/class-form-builder.php';

        // Form handler
        require_once ALTITUDE_AUDIT_PLUGIN_DIR . 'includes/class-form-handler.php';

        // Email handler
        require_once ALTITUDE_AUDIT_PLUGIN_DIR . 'includes/class-email-handler.php';

        // Admin
        require_once ALTITUDE_AUDIT_PLUGIN_DIR . 'admin/class-admin.php';

        // Public
        require_once ALTITUDE_AUDIT_PLUGIN_DIR . 'includes/class-public.php';

        // Shortcodes
        require_once ALTITUDE_AUDIT_PLUGIN_DIR . 'includes/class-shortcodes.php';

        $this->loader = new Altitude_Audit_Loader();
    }

    /**
     * Define the locale for internationalization.
     */
    private function set_locale() {
        $plugin_i18n = new Altitude_Audit_i18n();
        $this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );
    }

    /**
     * Register all admin-related hooks.
     */
    private function define_admin_hooks() {
        $plugin_admin = new Altitude_Audit_Admin( $this->get_plugin_name(), $this->get_version() );

        $this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
        $this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
        $this->loader->add_action( 'admin_menu', $plugin_admin, 'add_plugin_admin_menu' );
        $this->loader->add_action( 'admin_init', $plugin_admin, 'register_settings' );
    }

    /**
     * Register all public-facing hooks.
     */
    private function define_public_hooks() {
        $plugin_public = new Altitude_Audit_Public( $this->get_plugin_name(), $this->get_version() );

        $this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
        $this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

        // Shortcodes
        $shortcodes = new Altitude_Audit_Shortcodes();
        add_shortcode( 'accountability_audit', array( $shortcodes, 'render_audit_form' ) );
        add_shortcode( 'audit_results', array( $shortcodes, 'render_user_results' ) );

        // Form submission handler
        $scoring_engine = new Altitude_Audit_Scoring_Engine();
        $this->loader->add_action( 'fluentform/submission_inserted', $scoring_engine, 'process_submission', 10, 3 );
    }

    /**
     * Run the loader to execute all hooks.
     */
    public function run() {
        $this->loader->run();
    }

    /**
     * The name of the plugin.
     *
     * @return string
     */
    public function get_plugin_name() {
        return $this->plugin_name;
    }

    /**
     * The reference to the class that orchestrates the hooks.
     *
     * @return Altitude_Audit_Loader
     */
    public function get_loader() {
        return $this->loader;
    }

    /**
     * Retrieve the version number of the plugin.
     *
     * @return string
     */
    public function get_version() {
        return $this->version;
    }
}
