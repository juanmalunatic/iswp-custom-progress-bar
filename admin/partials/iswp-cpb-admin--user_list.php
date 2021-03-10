<?php
if (!isset($options)) {$options = [];} // So the code highlighter won't complain

// Table setup
require_once plugin_dir_path(dirname(__FILE__)) . 'partials/iswp-cpb-admin--user_list--User_Steps_List_Table.php';
?>

<style>
    .iswp-step-circle {
        display: block;
        width : 10px;
        height: 10px;
        border-radius: 50%;
        border: 1px solid rgba(0,0,0,0.3)
    }

    .iswp-step-false {
        background-color: transparent;
    }
    .iswp-step-true {
        background-color: limegreen;
    }

    .column-id {
        width: 90px;
    }

    .column-step_1,
    .column-step_2,
    .column-step_3,
    .column-step_4,
    .column-step_5 {
        width: 60px;
    }
</style>

<form action="" method="get">
    <input type="hidden" name="page" value="iswp-custom-progress-bar" />

    <?php
    $wp_user_steps_table = new User_Steps_List_Table();
    $wp_user_steps_table->prepare_items();
    $wp_user_steps_table->search_box(__('Search'), 'iswp-cpb__search-box');
    $wp_user_steps_table->display();
    ?>
</form>
