<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       www.codepa.de
 * @since      1.0.0
 *
 * @package    Comment_Guardian
 * @subpackage Comment_Guardian/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Comment_Guardian
 * @subpackage Comment_Guardian/includes
 * @author     Codepa <info@codepa.de>
 */
class Comment_Guardian
{

    /**
     * The loader that's responsible for maintaining and registering all hooks that power
     * the plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      Comment_Guardian_Loader    $loader    Maintains and registers all hooks for the plugin.
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
    public function __construct()
    {
        if (defined('COMMENT_GUARDIAN_VERSION')) {
            $this->version = COMMENT_GUARDIAN_VERSION;
        } else {
            $this->version = '1.0.0';
        }
        $this->plugin_name = 'comment-guardian';

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
     * - Comment_Guardian_Loader. Orchestrates the hooks of the plugin.
     * - Comment_Guardian_i18n. Defines internationalization functionality.
     * - Comment_Guardian_Admin. Defines all hooks for the admin area.
     * - Comment_Guardian_Public. Defines all hooks for the public side of the site.
     *
     * Create an instance of the loader which will be used to register the hooks
     * with WordPress.
     *
     * @since    1.0.0
     * @access   private
     */
    private function load_dependencies()
    {

        /**
         * The class responsible for orchestrating the actions and filters of the
         * core plugin.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-comment-guardian-loader.php';

        /**
         * The class responsible for defining internationalization functionality
         * of the plugin.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-comment-guardian-i18n.php';

        /**
         * The class responsible for defining all actions that occur in the admin area.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'admin/class-comment-guardian-admin.php';

        /**
         * The class responsible for defining all actions that occur in the public-facing
         * side of the site.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'public/class-comment-guardian-public.php';

        /**
         * The class responsible for language detection
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/LanguageDetection/LanguageResult.php';
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/LanguageDetection/Tokenizer/TokenizerInterface.php';
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/LanguageDetection/Tokenizer/WhitespaceTokenizer.php';
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/LanguageDetection/NgramParser.php';
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/LanguageDetection/Language.php';

        $this->loader = new Comment_Guardian_Loader();

    }

    /**
     * Define the locale for this plugin for internationalization.
     *
     * Uses the Comment_Guardian_i18n class in order to set the domain and to register the hook
     * with WordPress.
     *
     * @since    1.0.0
     * @access   private
     */
    private function set_locale()
    {

        $plugin_i18n = new Comment_Guardian_i18n();

        $this->loader->add_action('plugins_loaded', $plugin_i18n, 'load_plugin_textdomain');

    }

    /**
     * Register all of the hooks related to the admin area functionality
     * of the plugin.
     *
     * @since    1.0.0
     * @access   private
     */
    private function define_admin_hooks()
    {

        $plugin_admin = new Comment_Guardian_Admin($this->get_plugin_name(), $this->get_version());

        $this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_styles');
        $this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts');
		$this->loader->add_action('admin_menu', $plugin_admin, 'cg_menu_page');
		$this->loader->add_action('admin_init', $plugin_admin, 'cg_setup_sections');
        $this->loader->add_action('admin_init', $plugin_admin, 'cg_setup_fields');

        $this->loader->add_action( 'admin_notices', $plugin_admin, 'admin_notice' );
        $this->loader->add_action( 'admin_init', $plugin_admin, 'admin_init' );
    }

    /**
     * Register all of the hooks related to the public-facing functionality
     * of the plugin.
     *
     * @since    1.0.0
     * @access   private
     */
    private function define_public_hooks()
    {

        $plugin_public = new Comment_Guardian_Public($this->get_plugin_name(), $this->get_version());

        // Filters
        $this->loader->add_filter('pre_comment_approved', $plugin_public, 'filter_comments_by_language', '99', 2);

    }

    /**
     * Run the loader to execute all of the hooks with WordPress.
     *
     * @since    1.0.0
     */
    public function run()
    {
        $this->loader->run();
    }

    /**
     * The name of the plugin used to uniquely identify it within the context of
     * WordPress and to define internationalization functionality.
     *
     * @since     1.0.0
     * @return    string    The name of the plugin.
     */
    public function get_plugin_name()
    {
        return $this->plugin_name;
    }

    /**
     * The reference to the class that orchestrates the hooks with the plugin.
     *
     * @since     1.0.0
     * @return    Comment_Guardian_Loader    Orchestrates the hooks of the plugin.
     */
    public function get_loader()
    {
        return $this->loader;
    }

    /**
     * Retrieve the version number of the plugin.
     *
     * @since     1.0.0
     * @return    string    The version number of the plugin.
     */
    public function get_version()
    {
        return $this->version;
    }

}
