<?php

/**
 * Class Iswp_CPB__Email_Queries
 *
 * Dates are stored as yyyy-mm-dd in the DB.
 * All date manipulation (and storage in this class) is done as yyyy-mm-dd.
 *
 */

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Load WordPress to have access to the core functionality
if ( ! defined('ABSPATH') ) {
    /** Set up WordPress environment */
    require_once(dirname(__FILE__) . '/../../../../wp-load.php');
}

if (!function_exists('iswp_cpb_log')) {
    function iswp_cpb_log($category, $log)
    {
        error_log("[Start] $category from ISWP-CPB-EmailQueries");
        if (is_array($log) || is_object($log)) {
            error_log(print_r($log, true));
        } else {
            error_log($log);
        }
        error_log("[End] $category from ISWP-CPB-EmailQueries");
    }
}

class Iswp_CPB__Email_Queries
{
    public $today;
    public $months_six;
    public $months_three;
    public $weeks_one;

    public $users_today;
    public $users_months_six;
    public $users_months_three;
    public $users_weeks_one;

    public function __construct ()
    {
        //'BETWEEN' comparison with 'type' date only works with dates in format YYYY-MM-DD.
        //See https://developer.wordpress.org/reference/classes/wp_query/#custom-field-post-meta-parameters

        $today    = date("Y-m-d");
        $this->today = DateTime::createFromFormat('Y-m-d', $today);
        $this->months_six   = $this->getMonthsSix();
        $this->months_three = $this->getMonthsThree();
        $this->weeks_one    = $this->getWeeksOne();
    }

    public function executeRoutine() : int
    {
        //$this->DbgOutputTest();
        $this->populateUsers();
        $email_settings = get_option('iswp_cpb__oname__email_settings');

        $sent['today']        = $this->sendMails($this->users_today       , $email_settings, 'email0');
        $sent['months_six']   = $this->sendMails($this->users_months_six  , $email_settings, 'email1');
        $sent['months_three'] = $this->sendMails($this->users_months_three, $email_settings, 'email2');
        $sent['weeks_one']    = $this->sendMails($this->users_weeks_one   , $email_settings, 'email3');

        $sent_emails = 0;
        foreach ($sent as $key => $value) {
            if ($value !== false) {
                $sent_emails += $value;
            }
        }

        return $sent_emails;
    }

    /**
     * @return DateTime|false
     *
     * We need to "reverse" a date.
     * There exists a date x such that
     * x + 2yr -6m = today
     *
     * So x = today -2yr +6m
     *    x : the date at what the user paid, such that we're 6 months short
     *        of the expiration date (+2yr after payment)
     */
    private function getMonthsSix ()
    {
        $date = clone $this->today;
        $date->sub(new DateInterval('P2Y'));
        $date->add(new DateInterval('P6M'));
        return $date;
    }

    /**
     * @return DateTime|false
     *
     * We need to "reverse" a date.
     * There exists a date y such that
     * y + 2yr -3m = today
     *
     * So y = today -2yr +3m
     *    y : the date at what the user paid, such that we're 3 months short
     *        of the expiration date (+2yr after payment)
     */
    private function getMonthsThree ()
    {
        $date = clone $this->today;
        $date->sub(new DateInterval('P2Y'));
        $date->add(new DateInterval('P3M'));
        return $date;
    }

    /**
     * @return DateTime|false
     *
     * We need to "reverse" a date.
     * There exists a date y such that
     * z + 2yr -1w = today
     *
     * So z = today -2yr +1w
     *    z : the date at what the user paid, such that we're 1 week short
     *        of the expiration date (+2yr after payment)
     */
    private function getWeeksOne ()
    {
        $date = clone $this->today;
        $date->sub(new DateInterval('P2Y'));
        $date->add(new DateInterval('P1W'));
        return $date;
    }

    // public function DbgOutputTest ()
    // {
    //     echo "<pre>";
    //     echo "Today: " . $this->today       ->format('Y-m-d') . "<br />";
    //     echo "M6   : " . $this->months_six  ->format('Y-m-d') . "<br />";
    //     echo "M3   : " . $this->months_three->format('Y-m-d') . "<br />";
    //     echo "W1   : " . $this->weeks_one   ->format('Y-m-d') . "<br />";
    //     echo "</pre>";
    // }

    // public function DbgUserInfo (array $users)
    // {
    //     foreach ($users as $user) {
    //         echo "<p> id:{$user->ID} | {$user->display_name} </p>";
    //     }
    // }

    // public function DbgUpdateLastEmail($users, $date)
    // {
    //     foreach ($users as $user) {
    //         $this->updateLastEmail($user->ID, $date);
    //     }
    // }

    private function queryPaymentDate (DateTime $date) : array
    {
        // Add a tolerance window: if user was messaged b/w yesterday and today, don't remessage
        // This allows a higher cron frequence (i.e. two times a day).
        $window = "P1D"; // One day
        $before = clone $date;
        $before->sub(new DateInterval($window));

        $before_fmt = $before->format('Y-m-d');
        $date_fmt   = $date  ->format('Y-m-d');

        $user_query = new WP_User_Query(
            [
                // Pick the matching date
                'meta_query' => [
                    'relation' => 'AND',
                    'clause_today_reminder' => [
                        'key'     => '_wsp_payment_date',
                        'type'    => 'date',
                        'compare' => '=',
                        'value'   => $date_fmt,
                    ],
                    [
                        // Do pick either
                        'relation' => 'OR',
                        // "New" users
                        'clause_metafield_exists' => [
                            'key'     => '_wsp_last_email',
                            'compare' => 'NOT EXISTS',
                        ],
                        // Users that have not been emailed recently
                        'clause_no_repeats' => [
                            'key' => '_wsp_last_email',
                            'value' => [$before_fmt, $date_fmt],
                            'compare' => 'NOT BETWEEN',
                        ]
                    ]
                ]
            ]
        );

        if (!empty($user_query->get_results())) {
            return $user_query->get_results();
        } else {
            return [];
        }
    }

    public function populateUsers()
    {
        $this->users_today        = $this->queryPaymentDate($this->today);
        $this->users_months_six   = $this->queryPaymentDate($this->months_six);
        $this->users_months_three = $this->queryPaymentDate($this->months_three);
        $this->users_weeks_one    = $this->queryPaymentDate($this->weeks_one);
    }

    public function updateLastEmail(int $user_id, DateTime $date) : bool
    {
        update_user_meta($user_id, '_wsp_last_email', $date->format('Y-m-d'));
        return true;
    }

    public function sendMails (array $users, array $email_settings, string $key)
    {
        $mail_count = 0;
        $user_count = count($users);

        $subject  = $email_settings[$key . "--subject"];
        $template = $email_settings[$key . "--text"];

        if (empty($subject) || trim($subject) === '') {
            $subject = 'ISWP - Payment Reminder';
        }

        /** @var WP_User $user */
        foreach ($users as $user) {

            // Parse the template
            $tpl_parsed = $this->parseTemplate($user, $template);

            // Prepare the data
            $mail = [
                'to'      => $user->user_email,
                'subject' => $subject,
                'message' => $tpl_parsed,
            ];

            // Send the mail
            $success = wp_mail($mail['to'], $mail['subject'], $mail['message']);
            if ($success) {
                $mail_count++;
            }

            // Update user field
            $this->updateLastEmail($user->ID, $this->today);

        }

        // Successful if sent mails == found users
        if ($mail_count < $user_count) {
            write_log("ERROR", "Some mails couldn't be sent. Sent mails: $mail_count. Intended mails: $user_count. \r\n");
            return false;
        } else {
            return $mail_count;
        }
    }

    public function parseTemplate($user, $template)
    {
        // Replace tokens such as [NAME] for an actual value
        return str_replace('[NAME]', $user->display_name, $template);
    }

    public function cronExecution ()
    {
        try {
            $sent_emails = $this->executeRoutine();
            iswp_cpb_log("RUN", "Email queries were executed. $sent_emails emails were sent.");
        } catch (Exception $e) {
            iswp_cpb_log("ERROR", $e->getMessage());
        }

    }
}

