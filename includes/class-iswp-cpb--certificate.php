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

    public function __construct ()
    {
        $this->folder_assets = dirname(__FILE__) . '/../assets/';
    }

    public function constructCertificate($text, $date)
    {
        header('Content-Type: image/png');
        $image_path =  $this->folder_assets . 'certificate_english.png';
        $image = imagecreatefrompng($image_path);

        $this->addName($image, "This is a name");
        $this->addDate($image, new DateTime());

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

    public function addDate ($image, $date)
    {
        $text_color = imagecolorallocate($image, 0,0,0);
        $font_path = $this->folder_assets . 'calibri_bold.ttf';
        $font_size = 16;
        $angle = 0;

        // Generate text
        $text = "Effective from 1 February 2021 – 31 January 2023";

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

$certHandler = new Iswp_CPB__Certificate();
$certHandler->constructCertificate("testing", 2019);
