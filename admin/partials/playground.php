<?php

if ( ! defined('ABSPATH') ) {
    /** Set up WordPress environment */
    require_once( dirname( __FILE__ ) . '/../../../../../wp-load.php' );
}

class Playground
{
    public function get_users() : array
    {
        global $wpdb;

        $search = ( isset( $_REQUEST['s'] ) ) ? $_REQUEST['s'] : false;
        $search = "4852";

        // Add several columns
        $do_search  = '';
        $do_search .= ($search) ? $wpdb->prepare("   display_name LIKE '%%%s%%' ", $search ) : '';
        $do_search .= ($search) ? $wpdb->prepare("OR user_login   LIKE '%%%s%%' ", $search ) : '';
        $do_search .= ($search) ? $wpdb->prepare("OR user_email   LIKE '%%%s%%' ", $search ) : '';
        $do_search .= ($search) ? $wpdb->prepare("OR ID              = '%s'     ", $search ) : '';

        $query = "
            SELECT *
            FROM $wpdb->users
            WHERE 1 = 1
            AND ($do_search)
        ";

        $sql_results = $wpdb->get_results($query);
        return $sql_results;
    }
}

$pg = new Playground();
$users = $pg->get_users();
$b = "b";
foreach ($users as $user ) {
    echo '<p>' . $user->display_name . '</p>';
}