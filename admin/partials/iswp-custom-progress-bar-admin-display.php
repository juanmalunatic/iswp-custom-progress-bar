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
        Please configure the proper options+ for the progress bar steps below.
    </p>

    <fieldset>

        <h4>
            Step 1
        </h4>

        <!-- S1: Quiz ID -->
        <legend class="screen-reader-text">
            <span>
                Quiz ID for the ISWP Basic Knowledge Test - English
            </span>
        </legend>
        <label for      ="<?= $this->plugin_name ?>[s1--quiz_id]">
            <input id   ="<?= $this->plugin_name ?>[s1--quiz_id]"
                   name ="<?= $this->plugin_name ?>[s1--quiz_id]"
                   value="<?= $options['s1--quiz_id'] ?>"
                   type="text"
                   class="regular-text"
            />
            <span class="description">
                <?php esc_attr_e('Quiz ID for the ISWP Basic Knowledge Test - English', $this->plugin_name); ?>
            </span>
        </label>
        <br />

        <!-- S1: Name -->
        <legend class="screen-reader-text">
            <span>
                Name to display in the bar
            </span>
        </legend>
        <label for      ="<?= $this->plugin_name ?>[s1--name]">
            <input id   ="<?= $this->plugin_name ?>[s1--name]"
                   name ="<?= $this->plugin_name ?>[s1--name]"
                   value="<?= $options['s1--name'] ?>"
                   type="text"
                   class="regular-text"
            />
            <span class="description">
                <?php esc_attr_e('Name to display in the bar', $this->plugin_name); ?>
            </span>
        </label>
        <br />

        <!-- S1: Link -->
        <legend class="screen-reader-text">
            <span>
                Link that takes to the step, without the site's domain (without https://wheelchairnetwork.org).
            </span>
        </legend>
        <label for      ="<?= $this->plugin_name ?>[s1--link]">
            <input id   ="<?= $this->plugin_name ?>[s1--link]"
                   name ="<?= $this->plugin_name ?>[s1--link]"
                   value="<?= $options['s1--link'] ?>"
                   type="text"
                   class="regular-text"
            />
            <span class="description">
                <?php esc_attr_e('Link that takes to the step, without the site\'s domain (without https://wheelchairnetwork.org).', $this->plugin_name); ?>
            </span>
        </label>
        <br />

    </fieldset>

    <fieldset>

        <h4>
            Step 2
        </h4>

        <!-- S2: Course ID -->
        <legend class="screen-reader-text">
            <span>
                Course ID for the Ethics and Professionalism Course
            </span>
        </legend>
        <label for      ="<?= $this->plugin_name ?>[s2--course_id]">
            <input id   ="<?= $this->plugin_name ?>[s2--course_id]"
                   name ="<?= $this->plugin_name ?>[s2--course_id]"
                   value="<?= $options['s2--course_id'] ?>"
                   type="text"
                   class="regular-text"
            />
            <span class="description">
                <?php esc_attr_e('Course ID for the Ethics and Professionalism Course', $this->plugin_name); ?>
            </span>
        </label>
        <br />

        <!-- S2: Name -->
        <legend class="screen-reader-text">
            <span>
                Name to display in the bar
            </span>
        </legend>
        <label for      ="<?= $this->plugin_name ?>[s2--name]">
            <input id   ="<?= $this->plugin_name ?>[s2--name]"
                   name ="<?= $this->plugin_name ?>[s2--name]"
                   value="<?= $options['s2--name'] ?>"
                   type="text"
                   class="regular-text"
            />
            <span class="description">
                <?php esc_attr_e('Name to display in the bar', $this->plugin_name); ?>
            </span>
        </label>
        <br />

        <!-- S2: Link -->
        <legend class="screen-reader-text">
            <span>
                Link that takes to the step, without the site's domain (without https://wheelchairnetwork.org).
            </span>
        </legend>
        <label for      ="<?= $this->plugin_name ?>[s2--link]">
            <input id   ="<?= $this->plugin_name ?>[s2--link]"
                   name ="<?= $this->plugin_name ?>[s2--link]"
                   value="<?= $options['s2--link'] ?>"
                   type="text"
                   class="regular-text"
            />
            <span class="description">
                <?php esc_attr_e('Link that takes to the step, without the site\'s domain (without https://wheelchairnetwork.org).', $this->plugin_name); ?>
            </span>
        </label>
        <br />

    </fieldset>

    <fieldset>
        
        <h4>
            Step 3
        </h4>

        <!-- S3: Quiz ID -->
        <legend class="screen-reader-text">
            <span>
                Quiz ID for the Ethics and Professionalism Test
            </span>
        </legend>
        <label for      ="<?= $this->plugin_name ?>[s3--quiz_id]">
            <input id   ="<?= $this->plugin_name ?>[s3--quiz_id]"
                   name ="<?= $this->plugin_name ?>[s3--quiz_id]"
                   value="<?= $options['s3--quiz_id'] ?>"
                   type="text"
                   class="regular-text"
            />
            <span class="description">
                <?php esc_attr_e('Quiz ID for the Ethics and Professionalism Test', $this->plugin_name); ?>
            </span>
        </label>
        <br />

        <!-- S3: Name -->
        <legend class="screen-reader-text">
            <span>
                Name to display in the bar
            </span>
        </legend>
        <label for      ="<?= $this->plugin_name ?>[s3--name]">
            <input id   ="<?= $this->plugin_name ?>[s3--name]"
                   name ="<?= $this->plugin_name ?>[s3--name]"
                   value="<?= $options['s3--name'] ?>"
                   type="text"
                   class="regular-text"
            />
            <span class="description">
                <?php esc_attr_e('Name to display in the bar', $this->plugin_name); ?>
            </span>
        </label>
        <br />

        <!-- S3: Link -->
        <legend class="screen-reader-text">
            <span>
                Link that takes to the step, without the site's domain (without https://wheelchairnetwork.org).
            </span>
        </legend>
        <label for      ="<?= $this->plugin_name ?>[s3--link]">
            <input id   ="<?= $this->plugin_name ?>[s3--link]"
                   name ="<?= $this->plugin_name ?>[s3--link]"
                   value="<?= $options['s3--link'] ?>"
                   type="text"
                   class="regular-text"
            />
            <span class="description">
                <?php esc_attr_e('Link that takes to the step, without the site\'s domain (without https://wheelchairnetwork.org).', $this->plugin_name); ?>
            </span>
        </label>
        <br />
        
    </fieldset>

    <fieldset>

        <h4>
            Step 4
        </h4>

        <!-- S4: Form ID -->
        <legend class="screen-reader-text">
            <span>
                Gravity Form ID for the WSP File Upload
            </span>
        </legend>
        <label for      ="<?= $this->plugin_name ?>[s4--form_id]">
            <input id   ="<?= $this->plugin_name ?>[s4--form_id]"
                   name ="<?= $this->plugin_name ?>[s4--form_id]"
                   value="<?= $options['s4--form_id'] ?>"
                   type="text"
                   class="regular-text"
            />
            <span class="description">
                <?php esc_attr_e('Gravity Form ID for the WSP File Upload', $this->plugin_name); ?>
            </span>
        </label>
        <br />

        <!-- S4: Name -->
        <legend class="screen-reader-text">
            <span>
                Name to display in the bar
            </span>
        </legend>
        <label for      ="<?= $this->plugin_name ?>[s4--name]">
            <input id   ="<?= $this->plugin_name ?>[s4--name]"
                   name ="<?= $this->plugin_name ?>[s4--name]"
                   value="<?= $options['s4--name'] ?>"
                   type="text"
                   class="regular-text"
            />
            <span class="description">
                <?php esc_attr_e('Name to display in the bar', $this->plugin_name); ?>
            </span>
        </label>
        <br />

        <!-- S4: Link -->
        <legend class="screen-reader-text">
            <span>
                Link that takes to the step, without the site's domain (without https://wheelchairnetwork.org).
            </span>
        </legend>
        <label for      ="<?= $this->plugin_name ?>[s4--link]">
            <input id   ="<?= $this->plugin_name ?>[s4--link]"
                   name ="<?= $this->plugin_name ?>[s4--link]"
                   value="<?= $options['s4--link'] ?>"
                   type="text"
                   class="regular-text"
            />
            <span class="description">
                <?php esc_attr_e('Link that takes to the step, without the site\'s domain (without https://wheelchairnetwork.org).', $this->plugin_name); ?>
            </span>
        </label>
        <br />

    </fieldset>

    <fieldset>

        <h4>
            Step 5
        </h4>

        <!-- S5: Name -->
        <legend class="screen-reader-text">
            <span>
                Name to display in the bar
            </span>
        </legend>
        <label for      ="<?= $this->plugin_name ?>[s5--name]">
            <input id   ="<?= $this->plugin_name ?>[s5--name]"
                   name ="<?= $this->plugin_name ?>[s5--name]"
                   value="<?= $options['s5--name'] ?>"
                   type="text"
                   class="regular-text"
            />
            <span class="description">
                <?php esc_attr_e('Name to display in the bar', $this->plugin_name); ?>
            </span>
        </label>
        <br />

        <!-- S5: Link -->
        <legend class="screen-reader-text">
            <span>
                Link that takes to the step, without the site's domain (without https://wheelchairnetwork.org).
            </span>
        </legend>
        <label for      ="<?= $this->plugin_name ?>[s5--link]">
            <input id   ="<?= $this->plugin_name ?>[s5--link]"
                   name ="<?= $this->plugin_name ?>[s5--link]"
                   value="<?= $options['s5--link'] ?>"
                   type="text"
                   class="regular-text"
            />
            <span class="description">
                <?php esc_attr_e('Link that takes to the step, without the site\'s domain (without https://wheelchairnetwork.org).', $this->plugin_name); ?>
            </span>
        </label>
        <br />

        <p>
            The rest of this step must be performed in an individual user's profile (which you can access through the
            <a href="./users.php">user list</a>).
        </p>
        <p>
            Please look for the section called "ISWP Progress Bar" and update the checkbox / date accordingly.
        </p>
    </fieldset>

    <?php submit_button('Save all changes', 'primary', 'submit', TRUE); ?>

</form>