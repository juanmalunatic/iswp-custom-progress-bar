<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://lunalopez.ml
 * @since             1.0.0
 * @package           Iswp_Custom_Progress_Bar
 *
 * @wordpress-plugin
 * Plugin Name:       ISWP Custom Progress Bar
 * Plugin URI:        https://lunalopez.ml/project/iswp-custom-progress-bar
 * Description:       Enables a custom 5 step progress bar to be used in the user's profile.
 * Version:           1.0.0
 * Author:            Juan Manuel Luna López
 * Author URI:        https://lunalopez.ml
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       iswp-custom-progress-bar
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define('ISWP_CUSTOM_PROGRESS_BAR_VERSION', '1.0.0');

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-iswp-custom-progress-bar-activator.php
 */
function activate_iswp_custom_progress_bar()
{
    require_once plugin_dir_path(__FILE__) . 'includes/class-iswp-custom-progress-bar-activator.php';
    Iswp_Custom_Progress_Bar_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-iswp-custom-progress-bar-deactivator.php
 */
function deactivate_iswp_custom_progress_bar()
{
    require_once plugin_dir_path(__FILE__) . 'includes/class-iswp-custom-progress-bar-deactivator.php';
    Iswp_Custom_Progress_Bar_Deactivator::deactivate();
}

register_activation_hook(__FILE__, 'activate_iswp_custom_progress_bar');
register_deactivation_hook(__FILE__, 'deactivate_iswp_custom_progress_bar');

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path(__FILE__) . 'includes/class-iswp-custom-progress-bar.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_iswp_custom_progress_bar()
{

    $plugin = new Iswp_Custom_Progress_Bar();
    $plugin->run();

}

run_iswp_custom_progress_bar();
