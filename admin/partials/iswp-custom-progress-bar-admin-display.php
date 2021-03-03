<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://lunalopez.ml
 * @since      1.0.0
 *
 * @package    Iswp_Custom_Progress_Bar
 * @subpackage Iswp_Custom_Progress_Bar/admin/partials
 */
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->

<h2>
    <?php echo esc_html(get_admin_page_title()); ?>
</h2>

<form method="post" name="iswp-custom-progress-bar-form" action="options.php">

    <?php
    settings_fields($this->plugin_name);
    $options = get_option($this->plugin_name);
    ?>

    <h4>
        Design settings
    </h4>
    <fieldset>
        <legend class="screen-reader-text">
            <span>Title shown above the progress bar.</span>
        </legend>
        <label for="<?php echo $this->plugin_name; ?>-title">
            <input type="text" class="regular-text"
                   value="<?= $options['title'] ?>"
                   id="<?php echo $this->plugin_name; ?>-title"
                   name="<?php echo $this->plugin_name; ?>[title]"
            />
            <span><?php esc_attr_e('Title shown above the progress bar.', $this->plugin_name); ?></span>
        </label>
    </fieldset>

    <fieldset>
        <legend class="screen-reader-text">
            <span>Background color for the bar's progress.</span>
        </legend>
        <label for="<?php echo $this->plugin_name; ?>-bgcolor">
            <input type="text" class="regular-text"
                   value="<?= $options['bgcolor'] ?>"
                   id="<?php echo $this->plugin_name; ?>-bgcolor"
                   name="<?php echo $this->plugin_name; ?>[bgcolor]"
            />
            <span><?php esc_attr_e('Background color for the bar\'s progress.', $this->plugin_name); ?></span>
        </label> <br />
    </fieldset>

    <p>
        Please configure the proper IDs for the progress bar steps below.
    </p>

    <fieldset>
        <h4>
            Step 1
        </h4>
        <legend class="screen-reader-text">
            <span>Quiz ID for the ISWP Basic Knowledge Test - English</span>
        </legend>
        <label for="<?php echo $this->plugin_name; ?>-s1">
            <input type="text" class="small-text"
                   value="<?= $options['s1'] ?>"
                   id="<?php echo $this->plugin_name; ?>-s1"
                   name="<?php echo $this->plugin_name; ?>[s1]" value="1"
            />
            <span><?php esc_attr_e('Quiz ID for the ISWP Basic Knowledge Test - English', $this->plugin_name); ?></span>
        </label>
    </fieldset>

    <fieldset>
        <h4>
            Step 2
        </h4>
        <legend class="screen-reader-text">
            <span>Course ID for the Ethics and Professionalism Course</span>
        </legend>
        <label for="<?php echo $this->plugin_name; ?>-s2">
            <input type="text" class="small-text"
                   value="<?= $options['s2'] ?>"
                   id="<?php echo $this->plugin_name; ?>-s2"
                   name="<?php echo $this->plugin_name; ?>[s2]"
            />
            <span><?php esc_attr_e('Course ID for the Ethics and Professionalism Course', $this->plugin_name); ?></span>
        </label>
    </fieldset>

    <fieldset>
        <h4>
            Step 3
        </h4>
        <legend class="screen-reader-text">
            <span>Quiz ID for the Ethics and Professionalism Test</span>
        </legend>
        <label for="<?php echo $this->plugin_name; ?>-s3">
            <input type="text" class="small-text"
                   value="<?= $options['s3'] ?>"
                   id="<?php echo $this->plugin_name; ?>-s3"
                   name="<?php echo $this->plugin_name; ?>[s3]"
            />
            <span><?php esc_attr_e('Quiz ID for the Ethics and Professionalism Test', $this->plugin_name); ?></span>
        </label>
    </fieldset>

    <fieldset>
        <h4>
            Step 4
        </h4>
        <legend class="screen-reader-text">
            <span>Gravity Form ID for the WSP File Upload</span>
        </legend>
        <label for="<?php echo $this->plugin_name; ?>-s4">
            <input type="text" class="small-text"
                   value="<?= $options['s4'] ?>"
                   id="<?php echo $this->plugin_name; ?>-s4"
                   name="<?php echo $this->plugin_name; ?>[s4]"
            />
            <span><?php esc_attr_e('Gravity Form ID for the WSP File Upload', $this->plugin_name); ?></span>
        </label>
    </fieldset>

    <fieldset>
        <h4>
            Step 5
        </h4>
        <p>
            This step must be performed in an individual user's profile (which you can access through the
            <a href="./users.php">user list</a>).
        </p>
        <p>
            Please look for the section called "ISWP Progress Bar" and update the checkbox accordingly.
        </p>
    </fieldset>

    <?php submit_button('Save all changes', 'primary', 'submit', TRUE); ?>

</form>