<?php

// WP_List_Table is not loaded automatically so we need to load it in our application
if (!class_exists('WP_List_Table')) {
    require_once(ABSPATH . 'wp-admin/includes/class-wp-list-table.php');
}

// To  check the steps' completion
require_once plugin_dir_path(dirname(__FILE__)) . '../public/class-iswp-custom-progress-bar-public.php';

/**
 * Create a new table class that will extend the WP_List_Table
 */
class User_Steps_List_Table extends WP_List_Table
{
    /**
     * @var array|mixed
     */
    public $users_data;

    /**
     * Prepare the items for the table to process
     *
     * @return Void
     */
    public function prepare_items()
    {

        $columns  = $this->get_columns();
        $hidden   = $this->get_hidden_columns();
        $sortable = $this->get_sortable_columns();

        $perPage = 40;
        $currentPage = $this->get_pagenum();
        $sqlOffset = $currentPage - 1;

        $users = $this->fetch_users($perPage, $sqlOffset);
        $users_full = $this->fetch_users_steps($users);
        $this->items = $users_full;

        $totalItems = count($this->items);
        $this->set_pagination_args([
            'total_items' => $totalItems,
            'per_page'    => $perPage
        ]);

        $this->_column_headers = [$columns, $hidden, $sortable];
    }

    /**
     * Override the parent columns method. Defines the columns to use in your listing table
     *
     * @return Array
     */
    public function get_columns()
    {
        $columns = [
            'id'           => 'ID',
            'display_name' => 'Display Name',
            'user_email'   => 'User Login',
            'step_1'       => 'S1',
            'step_2'       => 'S2',
            'step_3'       => 'S3',
            'step_4'       => 'S4',
            'step_5'       => 'S5',
        ];
        // 'internal-name'  => 'Title'

        return $columns;
    }

    /**
     * Define which columns are hidden
     *
     * @return Array
     */
    public function get_hidden_columns()
    {
        return array();
    }

    /**
     * Define the sortable columns
     *
     * @return Array
     */
    public function get_sortable_columns()
    {
        // Currently not working

        return [];
        // return [
        //     'id'           => ['id', true],
        //     'display_name' => ['display_name', true],
        //     'user_email'   => ['user_email', true],
        // ];
    }

    /**
     * Define what data to show on each column of the table
     *
     * @param Array $item Data
     * @param String $column_name - Current column name
     *
     * @return Mixed
     */
    public function column_default($item, $column_name)
    {
        switch ($column_name) {
            case 'id':
            case 'display_name':
            case 'user_email':
                return $item[$column_name];

            case 'step_1':
            case 'step_2':
            case 'step_3':
            case 'step_4':
            case 'step_5':
                return ($item[$column_name] === true) ?
                    "<span class='iswp-step-circle iswp-step-true' ></span>" : // If true
                    "<span class='iswp-step-circle iswp-step-false'></span>";  // If false

            default:
                return print_r($item, true);
        }
    }

    // -----------------------------------------------------------------------------------------------------------------
    // DATA
    // -----------------------------------------------------------------------------------------------------------------

    private function fetch_users($limit = 10, $offset = 0)
    {
        global $wpdb;

        // [Search]
        $search = (isset($_REQUEST['s'])) ? $_REQUEST['s'] : false;
        //$search = "Wang";
        if ($search) {
            $do_search  = 'AND (';
            $do_search .=   $wpdb->prepare("   display_name LIKE '%%%s%%' ", $search);
            $do_search .=   $wpdb->prepare("OR user_login   LIKE '%%%s%%' ", $search);
            $do_search .=   $wpdb->prepare("OR user_email   LIKE '%%%s%%' ", $search);
            $do_search .=   $wpdb->prepare("OR ID              = %s       ", $search);
            $do_search .= ')';
        }

        // [Ordering]
        $do_order = '';
        $order_by = $this->orderby;
        $asc_desc = $this->order;
        if (!is_null($order_by) || !is_null($asc_desc)) {
            $do_order = "ORDER BY $order_by $asc_desc";
        } else {
            // Default
            $do_order = "ORDER BY ID DESC";
        }

        // Careful with the ID -> id alias
        // column_alias can be used in an ORDER BY clause, but it cannot be used in a WHERE, GROUP BY, or HAVING clause.

        $query = "
            SELECT
                `ID` as `id`, `display_name`, `user_email`
            FROM $wpdb->users
            WHERE 1 = 1
            $do_search
            $do_order
            LIMIT $limit OFFSET $offset
        ";

        return $wpdb->get_results($query);
    }

    private function fetch_users_steps(array $users): array
    {
        // Class used to validate the steps
        $progbar = new Iswp_Custom_Progress_Bar_Public('iswp-custom-progress-bar', '1.0.0');

        // We'll use the same loop to fetch the steps AND format the data for the table
        $data = [];

        foreach ($users as $key => $user) {

            // Needed as class wasn't initially designed for external access
            $progbar->initialize_external($user->id);

            // Fetch and fill the steps
            $steps = $progbar->fetch_steps();
            $user->step_1 = $steps[0]['completed'];
            $user->step_2 = $steps[1]['completed'];
            $user->step_3 = $steps[2]['completed'];
            $user->step_4 = $steps[3]['completed'];
            $user->step_5 = $steps[4]['completed'];

            // Convert stdClass to array via json_encode/decode
            $data[] = json_decode(json_encode($user), true);

        }

        return $data;
    }
}