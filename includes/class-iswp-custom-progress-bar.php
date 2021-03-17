<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://lunalopez.ml
 * @since      1.0.0
 *
 * @package    Iswp_Custom_Progress_Bar
 * @subpackage Iswp_Custom_Progress_Bar/includes
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
 * @package    Iswp_Custom_Progress_Bar
 * @subpackage Iswp_Custom_Progress_Bar/includes
 * @author     Juan Manuel Luna López <lunalopezjm@gmail.com>
 */
class Iswp_Custom_Progress_Bar
{

    /**
     * The loader that's responsible for maintaining and registering all hooks that power
     * the plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      Iswp_Custom_Progress_Bar_Loader $loader Maintains and registers all hooks for the plugin.
     */
    protected $loader;

    /**
     * The unique identifier of this plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      string $plugin_name The string used to uniquely identify this plugin.
     */
    protected $plugin_name;

    /**
     * The current version of the plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      string $version The current version of the plugin.
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
        if (defined('ISWP_CUSTOM_PROGRESS_BAR_VERSION')) {
            $this->version = ISWP_CUSTOM_PROGRESS_BAR_VERSION;
        } else {
            $this->version = '1.0.0';
        }
        $this->plugin_name = 'iswp-custom-progress-bar';

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
     * - Iswp_Custom_Progress_Bar_Loader. Orchestrates the hooks of the plugin.
     * - Iswp_Custom_Progress_Bar_i18n. Defines internationalization functionality.
     * - Iswp_Custom_Progress_Bar_Admin. Defines all hooks for the admin area.
     * - Iswp_Custom_Progress_Bar_Public. Defines all hooks for the public side of the site.
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
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-iswp-custom-progress-bar-loader.php';

        /**
         * The class responsible for defining internationalization functionality
         * of the plugin.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-iswp-custom-progress-bar-i18n.php';

        /**
         * The class responsible for defining all actions that occur in the admin area.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'admin/class-iswp-custom-progress-bar-admin.php';

        /**
         * The class responsible for defining all actions that occur in the public-facing
         * side of the site.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'public/class-iswp-custom-progress-bar-public.php';

        $this->loader = new Iswp_Custom_Progress_Bar_Loader();

        // The class for email handling
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-iswp-cpb--email_queries.php';

        // The class for certificate handling
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-iswp-cpb--certificate.php';

    }

    /**
     * Define the locale for this plugin for internationalization.
     *
     * Uses the Iswp_Custom_Progress_Bar_i18n class in order to set the domain and to register the hook
     * with WordPress.
     *
     * @since    1.0.0
     * @access   private
     */
    private function set_locale()
    {

        $plugin_i18n = new Iswp_Custom_Progress_Bar_i18n();

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

        // Boilerplate start
        $plugin_admin = new Iswp_Custom_Progress_Bar_Admin($this->get_plugin_name(), $this->get_version());

        $this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_styles');
        $this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts');
        // Boilerplate end

        // Add menu item (Tools -> ISWP Progress Bar)
        $this->loader->add_action('admin_menu', $plugin_admin, 'add_plugin_admin_menu');

        // Add Settings link to the plugin
        $plugin_basename = plugin_basename(plugin_dir_path(__DIR__) . $this->plugin_name . '.php');
        $this->loader->add_filter('plugin_action_links_' . $plugin_basename, $plugin_admin, 'add_action_links');

        // Save/Update our plugin options
        $this->loader->add_action('admin_init', $plugin_admin, 'options_update');

        //--------------------------------------------------------------------------------------------------------------
        // Display step 5 (checkbox) on the user profile (user-edit.php)
        //--------------------------------------------------------------------------------------------------------------
        // Show the fields in the profile
        //   Needs two hooks: edit_ for other users and show_ for self.
        $this->loader->add_action('show_user_profile', $plugin_admin, 'extra_user_profile_fields');
        $this->loader->add_action('edit_user_profile', $plugin_admin, 'extra_user_profile_fields');
        // Save the fields' values when updated
        $this->loader->add_action('personal_options_update', $plugin_admin, 'save_extra_user_profile_fields');
        $this->loader->add_action('edit_user_profile_update', $plugin_admin, 'save_extra_user_profile_fields');

        // Register the action that handles the certificate - when logged in
        $certHandler = new Iswp_CPB__Certificate();
        // Has to be called through admin_post to get USER_ID
        // https://wordpress.org/support/topic/doesnt-call-user-information-to-include-wp-load-php-in-an-external-php-file/
        $this->loader->add_action('admin_post_iswp-cpb__certificate', $certHandler, 'execute');
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

        // Boilerplate start
        $plugin_public = new Iswp_Custom_Progress_Bar_Public($this->get_plugin_name(), $this->get_version());

        $this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_styles');
        $this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_scripts');
        // Boilerplate end

        $this->loader->add_action('init', $plugin_public, 'initialize');
        $this->loader->add_action('init', $plugin_public, 'register_shortcodes');

        // Cron job to send emails
        $email_handler = new Iswp_CPB__Email_Queries();
        if (!wp_next_scheduled('iswpcpb__cron_hook__emails')) {
            wp_schedule_event( time(), 'twicedaily', 'iswpcpb__cron_hook__emails' );
        }
        $this->loader->add_action('iswpcpb__cron_hook__emails', $email_handler, 'cronExecution');

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
     * @return    string    The name of the plugin.
     * @since     1.0.0
     */
    public function get_plugin_name()
    {
        return $this->plugin_name;
    }

    /**
     * The reference to the class that orchestrates the hooks with the plugin.
     *
     * @return    Iswp_Custom_Progress_Bar_Loader    Orchestrates the hooks of the plugin.
     * @since     1.0.0
     */
    public function get_loader()
    {
        return $this->loader;
    }

    /**
     * Retrieve the version number of the plugin.
     *
     * @return    string    The version number of the plugin.
     * @since     1.0.0
     */
    public function get_version()
    {
        return $this->version;
    }

}
