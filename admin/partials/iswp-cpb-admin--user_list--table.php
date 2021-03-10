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
class Example_List_Table extends WP_List_Table
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

        // Sample data was hardcoded
        $data = $this->table_data();
        // Sample data was sorted in memory, here we fetch it ordered.
        usort($data, array(&$this, 'sort_data'));

        $perPage = 10;
        $currentPage = $this->get_pagenum();

        $users       = $this->fetch_users($perPage, $currentPage);
        $users_full  = $this->fetch_users_steps($users);
        $this->users_data = $users_full;

        $totalItems = count($data);

        $this->set_pagination_args(array(
            'total_items' => $totalItems,
            'per_page'    => $perPage
        ));

        // Sample data was sliced here
        $data = array_slice($data, (($currentPage - 1) * $perPage), $perPage);

        $this->_column_headers = array($columns, $hidden, $sortable);
        $this->items = $data;
    }

    /**
     * Override the parent columns method. Defines the columns to use in your listing table
     *
     * @return Array
     */
    public function get_columns()
    {
        $columns = array(
            'id'          => 'ID',
            'title'       => 'Title',
            'description' => 'Description',
            'year'        => 'Year',
            'director'    => 'Director',
            'rating'      => 'Rating'
        );

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
        return array('title' => array('title', false));
    }

    private function fetch_users ($limit = 10, $offset = 0)
    {
        global $wpdb;

        // [Search]
        $search = ( isset( $_REQUEST['s'] ) ) ? $_REQUEST['s'] : false;
        $search = "Wang";
        if ($search) {
            $do_search  = '';
            $do_search .= ($search) ? $wpdb->prepare("   display_name LIKE '%%%s%%' ", $search ) : '';
            $do_search .= ($search) ? $wpdb->prepare("OR user_login   LIKE '%%%s%%' ", $search ) : '';
            $do_search .= ($search) ? $wpdb->prepare("OR user_email   LIKE '%%%s%%' ", $search ) : '';
            $do_search .= ($search) ? $wpdb->prepare("OR ID              = '%s'     ", $search ) : '';
        }

        // [Ordering]
        $do_order = '';
        $order_by = $this->orderby;
        $asc_desc = $this->order;
        if ( !is_null($order_by) || !is_null($asc_desc) ) {
            $do_order = "ORDER BY $order_by $asc_desc";
        }

        $query = "
            SELECT
                `ID`, `display_name`, `user_login`
            FROM $wpdb->users
            WHERE 1 = 1
            AND ($do_search)
            $do_order
            LIMIT $limit OFFSET $offset
        ";

        return $wpdb->get_results($query);
    }

    private function fetch_users_steps (array $users) : array
    {
        $progbar = new Iswp_Custom_Progress_Bar_Public('iswp-custom-progress-bar', '1.0.0');

        foreach ($users as $key => $user) {

            $progbar->initialize_external($user->ID);
            $steps = $progbar->fetch_steps();

            $user->steps = [
                0 => $steps[0]['completed'],
                1 => $steps[1]['completed'],
                2 => $steps[2]['completed'],
                3 => $steps[3]['completed'],
                4 => $steps[4]['completed'],
            ];
        }
        return $users;
    }

    /**
     * Get the table data
     *
     * @return Array
     */
    private function table_data()
    {
        $data = array();

        $data[] = array(
            'id' => 1,
            'title' => 'The Shawshank Redemption',
            'description' => 'Two imprisoned men bond over a number of years, finding solace and eventual redemption through acts of common decency.',
            'year' => '1994',
            'director' => 'Frank Darabont',
            'rating' => '9.3'
        );

        $data[] = array(
            'id' => 2,
            'title' => 'The Godfather',
            'description' => 'The aging patriarch of an organized crime dynasty transfers control of his clandestine empire to his reluctant son.',
            'year' => '1972',
            'director' => 'Francis Ford Coppola',
            'rating' => '9.2'
        );

        $data[] = array(
            'id' => 3,
            'title' => 'The Godfather: Part II',
            'description' => 'The early life and career of Vito Corleone in 1920s New York is portrayed while his son, Michael, expands and tightens his grip on his crime syndicate stretching from Lake Tahoe, Nevada to pre-revolution 1958 Cuba.',
            'year' => '1974',
            'director' => 'Francis Ford Coppola',
            'rating' => '9.0'
        );

        $data[] = array(
            'id' => 4,
            'title' => 'Pulp Fiction',
            'description' => 'The lives of two mob hit men, a boxer, a gangster\'s wife, and a pair of diner bandits intertwine in four tales of violence and redemption.',
            'year' => '1994',
            'director' => 'Quentin Tarantino',
            'rating' => '9.0'
        );

        $data[] = array(
            'id' => 5,
            'title' => 'The Good, the Bad and the Ugly',
            'description' => 'A bounty hunting scam joins two men in an uneasy alliance against a third in a race to find a fortune in gold buried in a remote cemetery.',
            'year' => '1966',
            'director' => 'Sergio Leone',
            'rating' => '9.0'
        );

        $data[] = array(
            'id' => 6,
            'title' => 'The Dark Knight',
            'description' => 'When Batman, Gordon and Harvey Dent launch an assault on the mob, they let the clown out of the box, the Joker, bent on turning Gotham on itself and bringing any heroes down to his level.',
            'year' => '2008',
            'director' => 'Christopher Nolan',
            'rating' => '9.0'
        );

        $data[] = array(
            'id' => 7,
            'title' => '12 Angry Men',
            'description' => 'A dissenting juror in a murder trial slowly manages to convince the others that the case is not as obviously clear as it seemed in court.',
            'year' => '1957',
            'director' => 'Sidney Lumet',
            'rating' => '8.9'
        );

        $data[] = array(
            'id' => 8,
            'title' => 'Schindler\'s List',
            'description' => 'In Poland during World War II, Oskar Schindler gradually becomes concerned for his Jewish workforce after witnessing their persecution by the Nazis.',
            'year' => '1993',
            'director' => 'Steven Spielberg',
            'rating' => '8.9'
        );

        $data[] = array(
            'id' => 9,
            'title' => 'The Lord of the Rings: The Return of the King',
            'description' => 'Gandalf and Aragorn lead the World of Men against Sauron\'s army to draw his gaze from Frodo and Sam as they approach Mount Doom with the One Ring.',
            'year' => '2003',
            'director' => 'Peter Jackson',
            'rating' => '8.9'
        );

        $data[] = array(
            'id' => 10,
            'title' => 'Fight Club',
            'description' => 'An insomniac office worker looking for a way to change his life crosses paths with a devil-may-care soap maker and they form an underground fight club that evolves into something much, much more...',
            'year' => '1999',
            'director' => 'David Fincher',
            'rating' => '8.8'
        );

        return $data;
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
            case 'title':
            case 'description':
            case 'year':
            case 'director':
            case 'rating':
                return $item[$column_name];

            default:
                return print_r($item, true);
        }
    }

    /**
     * Allows you to sort the data by the variables set in the $_GET
     *
     * @return Mixed
     */
    private function sort_data($a, $b)
    {
        // Set defaults
        $orderby = 'title';
        $order   = 'asc';

        // If orderby is set, use this as the sort column
        if (!empty($_GET['orderby'])) {
            $orderby = $_GET['orderby'];
        }

        // If order is set use this as the order
        if (!empty($_GET['order'])) {
            $order = $_GET['order'];
        }

        $result = strcmp($a[$orderby], $b[$orderby]);

        if ($order === 'asc') {
            return $result;
        }

        return -$result;
    }
}