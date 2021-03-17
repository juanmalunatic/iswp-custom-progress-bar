<?php

// Enable errors
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Load WordPress to have access to the core functionality
if ( ! defined('ABSPATH') ) {
    /** Set up WordPress environment */
    require_once(dirname(__FILE__) . '/../../../../wp-load.php');
}

class Iswp_CPB__Certificate
{
    private $folder_assets;
    private $user_name;
    private $user_date_from;
    private $user_date_to;
    public $error;

    public function __construct ()
    {
        $this->folder_assets = dirname(__FILE__) . '/../assets/';
    }


    public function execute ()
    {
        $validUser = $this->checkAccess();
        if ($validUser) {
            $this->constructCertificate();
        } else {
            echo "ERROR: " . $this->error;
        }
    }

    public function checkAccess ()
    {
        // ---------------------------------------------
        // Do some sanity checks
        // ---------------------------------------------

        $curr_user = wp_get_current_user();
        $user_id = $curr_user->ID;

        // Check if user is logged in
        if ($user_id === 0) {
            $this->error = 'No user is currently logged in.';
            return false;
        }

        // Check if user completed step 5
        $progbar = new Iswp_Custom_Progress_Bar_Public('iswp-custom-progress-bar', '1.0.0');
        $progbar->initialize_external($user_id);
        $steps = $progbar->fetch_steps();
        $user_step_5 = $steps[4]['completed'];

        if ($user_step_5 === false) {
            $this->error = 'User has not completed the payment step.';
            return false;
        }

        // ---------------------------------------------
        // Set name and date for the certificate
        // ---------------------------------------------

        $this->user_name = $curr_user->data->display_name;

        $cf_date = get_the_author_meta('_wsp_payment_date', $user_id);
        $this->user_date_from = DateTime::createFromFormat('Y-m-d', $cf_date);

        if ($cf_date === '' || $this->user_date_from === false) {
            $this->error = 'The user\'s payment date is invalid';
            return false;
        }

        $this->user_date_to   = clone $this->user_date_from;
        $this->user_date_to->add(new DateInterval("P2Y")); // Add 2 years

        return true;
    }

    public function constructCertificate()
    {
        header('Content-Type: image/png');
        $image_path =  $this->folder_assets . 'certificate_english.png';
        $image = imagecreatefrompng($image_path);

        $this->addName($image, $this->user_name);
        $this->addDate($image, $this->user_date_from, $this->user_date_to);

        imagepng($image);
        imagedestroy($image);
    }

    public function addName ($image, $text)
    {
        $text_color = imagecolorallocate($image, 0, 0, 0);
        $font_path = $this->folder_assets . 'arial_bold.ttf';
        $font_size = 28;
        $angle = 0;

        $imageftbox = imageftbbox($font_size, $angle, $font_path, $text);
        $dims = $this->calculateCenterOffsets($image, $imageftbox);

        imagettftext (
            $image,
            $font_size,
            $angle,
            $dims["x"],
            $dims["y"] - 40,
            $text_color,
            $font_path,
            $text
        );
    }

    public function addDate ($image, $date_from, $date_to)
    {
        $text_color = imagecolorallocate($image, 0,0,0);
        $font_path = $this->folder_assets . 'calibri_bold.ttf';
        $font_size = 16;
        $angle = 0;

        // Generate text
        $date_from_fmt = $date_from->format('d F Y');
        $date_to_fmt   = $date_to  ->format('d F Y');
        $text = "Effective from {$date_from_fmt} – {$date_to_fmt}";

        $imageftbox = imageftbbox($font_size, $angle, $font_path, $text);
        $dims = $this->calculateCenterOffsets($image, $imageftbox);

        imagettftext (
            $image,
            $font_size,
            $angle,
            $dims["x"],
            $dims["y"] + 80,
            $text_color,
            $font_path,
            $text
        );
    }

    public function calculateCenterOffsets($image, $imageftbox)
    {
        // Get image dimensions
        $width = imagesx($image);
        $height = imagesy($image);

        // Get center coordinates of image
        $centerX = $width / 2;
        $centerY = $height / 2;

        // Get size of text
        list($left, $bottom, $right, , , $top) = $imageftbox;

        // Determine offset of text
        $left_offset = ($right - $left) / 2;
        $top_offset = ($bottom - $top) / 2;

        // Generate coordinates
        $x = $centerX - $left_offset;
        $y = $centerY - $top_offset;

        return [
            'x' => $x,
            'y' => $y,
        ];
    }
}