<?php
/**
 * The core plugin class.
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @link       http://onebyte.nz
 * @since      1.0.0
 * @package    Eventz Lite
 * @subpackage Eventz/includes
 * @author     Craig Sugden - onebyte.nz <info@onebyte.nz>
 */
class Eventz_Lite {
    /**
     * The loader that's responsible for maintaining and registering all hooks that power
     * the plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      Eventz_Loader    $loader    Maintains and registers all hooks for the plugin.
     */
    protected $loader;
    /**
     * The unique identifier of this plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      string    $plugin_name    The string used to uniquely identify this plugin.
     */
    protected $plugin_name;
    /**
     * The current version of the plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      string    $version    The current version of the plugin.
     */
    protected $version;
    /**
     * Define the core functionality of the plugin.
     *
     * Set the plugin name and the plugin version that can be used throughout the plugin.
     * Load the dependencies, define the locale, and set the hooks for the admin area and
     * the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function __construct() {
        $this->plugin_name = 'eventz_lite';
        $this->version = '1.3.5';
        $this->load_dependencies();
        $this->set_locale();
        $this->define_admin_hooks();
        $this->define_public_hooks();
    }
    /**
     * Load the required dependencies for this plugin.
     *
     * Include the following files that make up the plugin:
     *
     * - Eventz_Loader. Orchestrates the hooks of the plugin.
     * - Eventz_i18n. Defines internationalization functionality.
     * - Eventz_Admin. Defines all hooks for the admin area.
     * - Eventz_Public. Defines all hooks for the public side of the site.
     *
     * Create an instance of the loader which will be used to register the hooks
     * with WordPress.
     *
     * @since    1.0.0
     * @access   private
     */
    private function load_dependencies() {
        /**
         * The class responsible for orchestrating the actions and filters of the
         * core plugin.
         */
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-eventz-loader.php';
        /**
         * The class responsible for defining internationalization functionality
         * of the plugin.
         */
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-eventz-i18n.php';
        /**
         * The class responsible for returning API HTTP responses for the site.
         *
         */
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-eventz-http.php';
        /**
         * The class responsible for returning html templates for the public facing side
         * of the site.
         */
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-eventz-templates.php';
        /**
         * The class responsible for defining all actions that occur in the admin area.
         */
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-eventz-debug.php';
        /**
         * The class responsible for debugging.
         */
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-eventz-admin.php';
        /**
         * The class responsible for defining all actions that occur in the public-facing
         * side of the site.
         */
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-eventz-public.php';
        $this->loader = new Eventz_Lite_Loader();
    }
    /**
     * Define the locale for this plugin for internationalization.
     *
     * Uses the Eventz_i18n class in order to set the domain and to register the hook
     * with WordPress.
     *
     * @since    1.0.0
     * @access   private
     */
    private function set_locale() {
        $plugin_i18n = new Eventz_Lite_i18n();
        $this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );
    }
    /**
     * Register all of the hooks related to the admin area functionality
     * of the plugin.
     *
     * @since    1.0.0
     * @access   private
     */
    private function define_admin_hooks() {
        $plugin_admin = new Eventz_Lite_Admin($this->get_plugin_name(), $this->get_version());
        /* Add Settings & Support links to the plugin */
        $plugin_basename = plugin_basename(plugin_dir_path(__DIR__) . 'eventz-lite.php');
        $this->loader->add_filter('plugin_action_links_' . $plugin_basename, $plugin_admin, 'add_settings_links');
        /* Add menu item */
        $this->loader->add_action('admin_menu', $plugin_admin, 'add_options_page');
        if (!empty ($GLOBALS['pagenow'])
            and ('options-general.php' === $GLOBALS['pagenow']
                or 'options.php' === $GLOBALS['pagenow'])) {
                    /* Register Settings Sections */
                    $this->loader->add_action('admin_init', $plugin_admin, 'register_setting');
                    /* Enqueue styles & scripts */
                    $this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_styles');
                    $this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts');
                }
    }
    /**
     * Register all of the hooks related to the public-facing functionality
     * of the plugin.
     *
     * @since    1.0.0
     * @access   private
     */
    private function define_public_hooks() {
        $plugin_public = new Eventz_Lite_Public( $this->get_plugin_name(), $this->get_version() );
        $this->loader->add_action( 'init', $plugin_public, 'eventz_lite_register_shortcodes' );
        $this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
        //$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
    }
    /**
     * Run the loader to execute all of the hooks with WordPress.
     *
     * @since    1.0.0
     */
    public function run() {
        $this->loader->run();
    }
    /**
     * The name of the plugin used to uniquely identify it within the context of
     * WordPress and to define internationalization functionality.
     *
     * @since     1.0.0
     * @return    string    The name of the plugin.
     */
    public function get_plugin_name() {
        return $this->plugin_name;
    }
    /**
     * The reference to the class that orchestrates the hooks with the plugin.
     *
     * @since     1.0.0
     * @return    Eventz_Loader    Orchestrates the hooks of the plugin.
     */
    public function get_loader() {
        return $this->loader;
    }
    /**
     * Retrieve the version number of the plugin.
     *
     * @since     1.0.0
     * @return    string    The version number of the plugin.
     */
    public function get_version() {
        return $this->version;
    }
}
