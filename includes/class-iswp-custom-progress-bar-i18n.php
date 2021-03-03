<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://lunalopez.ml
 * @since      1.0.0
 *
 * @package    Iswp_Custom_Progress_Bar
 * @subpackage Iswp_Custom_Progress_Bar/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Iswp_Custom_Progress_Bar
 * @subpackage Iswp_Custom_Progress_Bar/includes
 * @author     Juan Manuel Luna López <lunalopezjm@gmail.com>
 */
class Iswp_Custom_Progress_Bar_i18n
{


    /**
     * Load the plugin text domain for translation.
     *
     * @since    1.0.0
     */
    public function load_plugin_textdomain()
    {

        load_plugin_textdomain(
            'iswp-custom-progress-bar',
            false,
            dirname(dirname(plugin_basename(__FILE__))) . '/languages/'
        );

    }


}
