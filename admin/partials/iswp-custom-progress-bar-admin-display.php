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


// This plugin consists of three areas:
// - user_list
// - steps_settings
// - email_settings
$active_tab = isset($_GET['tab']) ? $_GET['tab'] : 'user_list';

?>

<div class="wrap">

    <!-- Title -->
    <h2>
        <?php echo esc_html(get_admin_page_title()); ?>
    </h2>

    <!-- Navigation bar (tabs) -->

    <nav style="margin-bottom: 20px" class="nav-tab-wrapper wp-clearfix">
        <a href="?page=iswp-custom-progress-bar.php"
           class="nav-tab <?= $active_tab =='user_list'      ? 'nav-tab-active' : '' ?>"
           aria-current="page">
            User List
        </a>
        <a href="?page=iswp-custom-progress-bar.php&amp;tab=steps_settings"
           class="nav-tab <?= $active_tab =='steps_settings' ? 'nav-tab-active' : '' ?>"
           aria-current="page">
            Steps Settings
        </a>
        <a href="?page=iswp-custom-progress-bar.php&amp;tab=email_settings"
           class="nav-tab <?= $active_tab =='email_settings' ? 'nav-tab-active' : '' ?>"
           aria-current="page">
            E-mail Settings
        </a>
    </nav>

    <!-- Updateable fields -->
    <form method="post" name="iswp-custom-progress-bar-form" action="options.php">

        <?php
        settings_fields("iswp_cpb__ogroup__$active_tab");
        $options  = get_option("iswp_cpb__oname__$active_tab");
        ?>

        <!-- Actual form contents -->
        <?php
        switch ($active_tab) {
            case 'user_list':
                require_once 'iswp-cpb-admin--user_list.php';
                break;
            case 'steps_settings':
                require_once 'iswp-cpb-admin--steps_settings.php';
                break;
            case 'email_settings':
                require_once 'iswp-cpb-admin--email_settings.php';
                break;
        }
        ?>

        <?php submit_button('Save all changes', 'primary', 'submit', TRUE); ?>

    </form>
</div>