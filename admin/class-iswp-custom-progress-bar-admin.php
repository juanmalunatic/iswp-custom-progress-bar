<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://lunalopez.ml
 * @since      1.0.0
 *
 * @package    Iswp_Custom_Progress_Bar
 * @subpackage Iswp_Custom_Progress_Bar/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Iswp_Custom_Progress_Bar
 * @subpackage Iswp_Custom_Progress_Bar/admin
 * @author     Juan Manuel Luna López <lunalopezjm@gmail.com>
 */
class Iswp_Custom_Progress_Bar_Admin
{

    // Boilerplate start

    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string $plugin_name The ID of this plugin.
     */
    private $plugin_name;

    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string $version The current version of this plugin.
     */
    private $version;

    /**
     * Initialize the class and set its properties.
     *
     * @param string $plugin_name The name of this plugin.
     * @param string $version The version of this plugin.
     * @since    1.0.0
     */
    public function __construct($plugin_name, $version)
    {

        $this->plugin_name = $plugin_name;
        $this->version = $version;
    }

    /**
     * Register the stylesheets for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_styles()
    {

        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Iswp_Custom_Progress_Bar_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Iswp_Custom_Progress_Bar_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */

        wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/iswp-custom-progress-bar-admin.css', array(), $this->version, 'all');
        wp_enqueue_style('jquery-ui-css', plugin_dir_url(__FILE__) . 'css/jquery-ui.min--1.11.4.css');
    }

    /**
     * Register the JavaScript for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts()
    {

        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Iswp_Custom_Progress_Bar_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Iswp_Custom_Progress_Bar_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */

        wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/iswp-custom-progress-bar-admin.js', array('jquery'), $this->version, false);
        wp_enqueue_script('jquery-ui-datepicker');
    }

    // -------------------------------------------------------------------------
    // Boilerplate end
    // -------------------------------------------------------------------------

    /**
     * Register the administration menu for this plugin into the WordPress Dashboard menu.
     *
     * @since    1.0.0
     */

    public function add_plugin_admin_menu()
    {

        /*
         * Add a settings page for this plugin to the Settings menu.
         *
         * NOTE:  Alternative menu locations are available via WordPress administration menu functions.
         *
         *        Administration Menus: http://codex.wordpress.org/Administration_Menus
         *
         */
        add_management_page(
            'ISWP Custom Progress Bar',
            'ISWP Progress Bar',
            'manage_options',
            $this->plugin_name,
            array($this, 'display_plugin_setup_page')
        );
    }

    /**
     * Add settings action link to the plugins page.
     *
     * @since    1.0.0
     */

    public function add_action_links($links)
    {
        /*
    *  Documentation : https://codex.wordpress.org/Plugin_API/Filter_Reference/plugin_action_links_(plugin_file_name)
    */
        $settings_link = array(
            '<a href="' . admin_url('tools.php?page=' . $this->plugin_name) . '">' . __('Settings', $this->plugin_name) . '</a>',
        );
        return array_merge($settings_link, $links);
    }

    /**
     * Render the settings page for this plugin.
     *
     * @since    1.0.0
     */

    public function display_plugin_setup_page()
    {
        include_once('partials/iswp-custom-progress-bar-admin-display.php');
    }

    /**
     * Validate the settings on save.
     *
     * @since    1.0.0
     */

    public function validate ($incoming_data)
    {

        // To store valid values
        $validated = array();

        // Defaults
        $default_data = [
            'title'   => 'ISWP Wheelchair Service Provider (WSP) Certification',
            'bgcolor' => '#b9f5ba',

            's1--name'    => 'Basic Knowledge Test',
            's1--link'    => 'courses/iswp-basic-knowledge-test',
            's1--quiz_id' => 2086,

            's2--name'      => 'Ethics and Professionalism Course',
            's2--link'      => 'courses/iswp-ethics-and-professionalism-course',
            's2--course_id' => 10784,

            's3--name'    => 'Ethics and Professionalism Test',
            's3--link'    => 'courses/iswp-pre-test',
            's3--quiz_id' => 2877,

            's4--name'    => 'Supporting Documents',
            's4--link'    => 'wsp-certification-initial-form',
            's4--form_id' => 33,

            's5--name' => 'Payment',
            's5--link' => 'payment',
        ];

        // Only fields that appear on default_data will be stored.
        // You can store additional fields using the following (outside of the loop)
        // $this->storeVal('additional-field', $validated, $incoming_data, $default_data);
        $fields = array_keys($default_data);
        foreach ($fields as $key => $field) {
            $this->storeVal($field, $validated, $incoming_data, $default_data);
        }
        return $validated;
    }

    public function storeVal($field_name, &$target, $values, $defaults)
    {
        $new_value = $values[$field_name];
        $is_valid = (isset($new_value)) && !empty($new_value);
        if ($is_valid) {
            $target[$field_name] = $new_value;
        } else {
            $target[$field_name] = $defaults[$field_name];
        }
    }

    /**
     * Update the plugin's options
     *
     * @since    1.0.0
     */
    public function options_update()
    {
        register_setting($this->plugin_name, $this->plugin_name, array($this, 'validate'));
    }

    // Step 5 functionality: extra field on user-edit.php

    public function extra_user_profile_fields($user)
    {
        $user_paid    = get_the_author_meta('_wsp_payment_received', $user->ID);
        $payment_date = get_the_author_meta('_wsp_payment_date', $user->ID);

        // Payment
        $checked = "checked";
        if (is_null($user_paid) || $user_paid == "") {
            $checked = "";
        }

        ?>
        <h3> ISWP Progress Bar </h3>

        <p>
            These fields indicate whether the user has paid the required fees for the WSP Certification.
        </p>

        <fieldset>

            <table class="form-table">
                <tbody>

                    <!-- User has paid the fees -->
                    <tr>
                        <th>
                            User paid the fees
                        </th>
                        <td>
                            <legend class="screen-reader-text">
                                    <span>
                                        User paid the fees.
                                    </span>
                            </legend>
                            <label for     ="_wsp_payment_received">
                                <input   id="_wsp_payment_received"
                                         name="_wsp_payment_received"
                                         type="checkbox"
                                    <?= $checked ?>
                                />
                                <br />
                                <span class="description">
                                        <?php esc_attr_e('Check if the user has paid.', $this->plugin_name); ?>
                                </span>
                            </label>
                        </td>
                    </tr>

                    <!-- Date of payment -->
                    <tr>
                        <th>
                            Date of last payment
                        </th>
                        <td>
                            <legend class="screen-reader-text">
                                <span>
                                    Date of last payment (mm-dd-yyyy).
                                </span>
                            </legend>
                            <label for     ="_wsp_payment_date">
                                <input   id="_wsp_payment_date"
                                         name="_wsp_payment_date"

                                         type  = "text"
                                         class = "jqueryui-datepicker"
                                         value = "<?=$payment_date?>"
                                />
                                <br />
                                <span class="description">
                                    <?php esc_attr_e('Input the date in a mm-dd-yyyy format.', $this->plugin_name); ?>
                                </span>
                            </label>
                        </td>
                    </tr>

                </tbody>
            </table>

        </fieldset>
        <?php
    }

    public function save_extra_user_profile_fields($user_id) : bool
    {
        if (empty($_POST['_wpnonce']) || !wp_verify_nonce($_POST['_wpnonce'], 'update-user_' . $user_id)) {
            return false; // was return
        }

        if (!current_user_can('edit_user', $user_id)) {
            return false;
        }

        // Payment received (Checkbox)
        update_user_meta($user_id, '_wsp_payment_received', $_POST['_wsp_payment_received']);

        // Payment date (jQuery UI datepicker)
        update_user_meta($user_id, '_wsp_payment_date', $_POST['_wsp_payment_date']);
        return true;
    }
}
