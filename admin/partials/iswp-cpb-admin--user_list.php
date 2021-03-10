    <?php

if (!isset($options)) {$options = [];} // So the code highlighter won't complain

// Table setup
require_once plugin_dir_path(dirname(__FILE__)) . 'partials/iswp-cpb-admin--user_list--table.php';
$wp_user_steps_table = new Example_List_Table();
?>


<div id="icon-users" class="icon32"></div>
<h4>
    User Step Status
</h4>

<form action="" method="get">
    <input type="hidden" name="page" value="iswp-custom-progress-bar" />

    <?php
    // Table display
    $wp_user_steps_table->prepare_items();
    // Search after preparing items
    $wp_user_steps_table->search_box(__('Search'), 'iswp-cpb__search-box');
    $wp_user_steps_table->display();
    ?>
</form>
