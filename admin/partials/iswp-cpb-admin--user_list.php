<?php

if (!isset($options)) {$options = [];} // So the code highlighter won't complain

// Table setup
require_once plugin_dir_path(dirname(__FILE__)) . 'partials/iswp-cpb-admin--user_list--table.php';
$wp_user_steps_table = new Example_List_Table();
$wp_user_steps_table->prepare_items();
?>

<div class="wrap">
    <div id="icon-users" class="icon32"></div>
    <h3>
        User Step Status
    </h3>

    <?php
        // Table display
        $wp_user_steps_table->display();
    ?>

</div>
