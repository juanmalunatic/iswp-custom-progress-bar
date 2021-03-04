<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://lunalopez.ml
 * @since      1.0.0
 *
 * @package    Iswp_Custom_Progress_Bar
 * @subpackage Iswp_Custom_Progress_Bar/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Iswp_Custom_Progress_Bar
 * @subpackage Iswp_Custom_Progress_Bar/public
 * @author     Juan Manuel Luna López <lunalopezjm@gmail.com>
 */
class Iswp_Custom_Progress_Bar_Public
{

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
     * The plugin options
     *
     * @since  1.0.0
     * @access private
     * @var    mixed $plugin_options Stored options for this plugin
     */
    private $plugin_options;

    /**
     * Current user ID as reported by get_current_user_id. 0 if not logged in.
     *
     * @since  1.0.0
     * @access private
     * @var    integer $user_id Current user id
     */
    private $user_id;

    /**
     * Initialize the class and set its properties.
     *
     * @param string $plugin_name The name of the plugin.
     * @param string $version The version of this plugin.
     * @since    1.0.0
     */
    public function __construct($plugin_name, $version)
    {

        $this->plugin_name = $plugin_name;
        $this->version = $version;

    }

    public function initialize()
    {
        $this->plugin_options = get_option($this->plugin_name);
        $this->user_id = get_current_user_id();
    }

    /**
     * Register the stylesheets for the public-facing side of the site.
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

        wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/iswp-custom-progress-bar-public.css', array(), $this->version, 'all');

    }

    /**
     * Register the JavaScript for the public-facing side of the site.
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

        wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/iswp-custom-progress-bar-public.js', array('jquery'), $this->version, false);

    }

    // end Boilerplate
    // ------------------------------------------------------------------------


    // Shortcode functions

    public function register_shortcodes()
    {
        add_shortcode('iswp_custom_progress_bar', array($this, 'progress_bar_draw'));
    }

    public function progress_bar_draw(): string
    {
        // This function is currently only used on pages in which the user is logged in.

        // Setup data
        $steps = [
            [
                'code'      => 'Step 1',
                'name'      => $this->plugin_options['s1--name'],
                'link'      => $this->plugin_options['s1--link'],
                'quiz_id'   => (int)$this->plugin_options['s1--quiz_id'],
            ],
            [
                'code'      => 'Step 2',
                'name'      => $this->plugin_options['s2--name'],
                'link'      => $this->plugin_options['s2--link'],
                'course_id' => (int)$this->plugin_options['s2--course_id'],
            ],
            [
                'code'      => 'Step 3',
                'name'      => $this->plugin_options['s3--name'],
                'link'      => $this->plugin_options['s3--link'],
                'quiz_id'   => (int)$this->plugin_options['s3--quiz_id'],
            ],
            [
                'code'      => 'Step 4',
                'name'      => $this->plugin_options['s4--name'],
                'link'      => $this->plugin_options['s4--link'],
                'form_id'   => (int)$this->plugin_options['s4--form_id'],
            ],
            [
                'code'      => 'Step 5',
                'name'      => $this->plugin_options['s5--name'],
                'link'      => $this->plugin_options['s5--link'],
                'fees_paid' => get_the_author_meta('_wsp_fees_paid', $this->user_id),
            ]
        ];

        // Validate if each step satisfies the completion criteria
        foreach ($steps as $key => $data) {
            // This is basically the next line in a loop:
            // $steps[0]['completed'] = $this->checkStep1($data)
            $num = $key + 1;
            $steps[$key]['completed'] = $this->{"checkStep".$num}($data);
        }

        // Add a link for every step


        return $this->pb_div_render($steps);
    }

    private function pb_div_render($steps) : string
    {
        $template = "";

        $template .= "
            <div id='iswpcpb-holder' style='display:none'>
                <div class='iswpcpb-title'>
                    {$this->plugin_options['title']}
                </div>
                <div class='iwspcpb-progress'>
	                <div class='iswpcpb-bar'>";

        // Steps
        $counter = 0;
        $bgcolor = $this->plugin_options['bgcolor'];
        foreach ($steps as $key => $step_data) {
            $stepnum   = $key + 1;
            $completed = $step_data['completed'];
            $name      = $step_data['name'];
            $link      = $this->base_url() . '/' . $step_data['link'];

            $style  = 'background-color: ';
            $style .= $completed ? $bgcolor : 'transparent';

            $template .= "
                        <div
                            class='iswpcpb-step'
                            id='iswpcpb-step-{$stepnum}'
                            style='{$style}'
                        >
                            <a href='{$link}' class='iswpcpb-link iswp-tooltip'>
                                <span class='iswp-tooltiptext'>
                                    {$name}
                                </span>
                                <span class='iswpcpb-centerer'>";

            if ($completed):
                $template .= "
                                    <span class='iswpcpb-text'>
                                        {$name}
                                    </span>";
            else:
                $template .= "
                                    <span class='iswpcpb-number'>
                                        {$stepnum}
                                    </span>";
            endif;
            $template .= "
                                </span>
                            </a>
                        </div>";

            // Count how many have been completed so far
            if ($completed) {
                $counter++;
            }
        }
        $template .= "
	                </div>"; // end bar

        // Text
        $percent = round($counter/5*100, 0);
        $template .= "
                     <div class='iswpcpb-percentage'>
                        {$percent}% Complete
                     </div>";

	    $template .= "
                </div>
            </div>";

        return $template;
    }

    // Helper functions

    /**
     * Check whether a given user has finished a course with a given ID
     */
    private function user_completed_course($user_id, $course_id): bool
    {
        $completed_timestamp = $this->learndash_user_get_course_completed($user_id, $course_id);
        if ($completed_timestamp > 0) {
            return true;
        } else {
            return false;
        }
    }

    private function user_passed_quiz($user_id, $quiz_id): bool
    {
        // Quiz must be BOTH completed AND passed for the progress bar to advance
        $quiz_complete = learndash_is_quiz_complete($user_id, $quiz_id);
        return $quiz_complete;
    }

    private function user_submitted_form($user_id, $form_id): bool
    {
        // Find entries for the user
        $search_criteria['field_filters'][] = [  // Filter by user
            'key' => 'created_by',
            'value' => (string)$user_id,
        ];

        $search_criteria['field_filters'][] = [ // Only active (non-deleted) submissions
            'key' => 'status',
            'value' => 'active',
        ];

        $sorting = [
            'key' => 'date_created',
            'direction' => 'DESC',
            'is_numeric' => false
        ];

        $total_count = 0;
        $entries = GFAPI::get_entries($form_id, $search_criteria, $sorting, null, $total_count);

        if ($total_count > 0) {
            return true;
        } else {
            return false;
        }

        // We could optionally check if the field has been filled, getting the entry.
        // Not done yet as the form has only one field: Submitting == filling the field.
    }

    // Step functions

    private function checkStep1($step_data): bool
    {
        $quiz_id = $step_data['quiz_id'];
        $state = $this->user_passed_quiz($this->user_id, $quiz_id);
        return $state;
    }

    private function checkStep2($step_data): bool
    {
        $course_id = $step_data['course_id'];
        $state = $this->user_completed_course($this->user_id, $course_id);
        return $state;
    }

    private function checkStep3($step_data): bool
    {
        $quiz_id = $step_data['quiz_id'];
        $state = $this->user_passed_quiz($this->user_id, $quiz_id);
        return $state;
    }

    private function checkStep4($step_data): bool
    {
        $form_id = $step_data['form_id'];
        $state = $this->user_submitted_form($this->user_id, $form_id);
        return $state;
    }

    private function checkStep5($step_data): bool
    {
        $fees_paid = $step_data['fees_paid'];
        if ($fees_paid == "" || is_null($fees_paid)) {
            return false;
        } else {
            return true;
        }
    }

    function learndash_user_get_course_completed($user_id = 0, $course_id = 0)
    {
        $completed_on_timestamp = 0;
        if ((!empty($user_id)) && (!empty($course_id))) {
            $completed_on = get_user_meta($user_id, 'course_completed_' . $course_id, true);

            if (empty($completed_on)) {
                $activity_query_args = array(
                    'post_ids' => $course_id,
                    'user_ids' => $user_id,
                    'activity_type' => 'course',
                    'per_page' => 1,
                );

                $activity = learndash_reports_get_activity($activity_query_args);
                //error_log('activity<pre>'. print_r($activity, true) .'</pre>');
                if (!empty($activity['results'])) {
                    foreach ($activity['results'] as $activity_item) {
                        if (property_exists($activity_item, 'activity_completed')) {
                            $completed_on_timestamp = $activity_item->activity_completed;

                            // To make the next check easier we update the user meta.
                            update_user_meta($user_id, 'course_completed_' . $course_id, $completed_on_timestamp);
                        }
                    }
                }
            } else {
                if ($completed_on > 0) {
                    $completed_on_timestamp = $completed_on;
                } else {
                    return 0;
                }
            }
        }

        return $completed_on_timestamp;
    }

    function base_url() : string
    {
        if (isset($_SERVER['HTTPS'])) {
            $protocol = ($_SERVER['HTTPS'] && $_SERVER['HTTPS'] != "off") ? "https" : "http";
        } else {
            $protocol = 'http';
        }
        return $protocol . "://" . $_SERVER['HTTP_HOST'];
    }
}
