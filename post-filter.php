<?php

// Block direct access to the main file.

if (!defined('ABSPATH')) {
    exit('You are cheating!');
}

// our main class 

class T4E_Post_Filter
{

    public function __construct()
    {
        $this->t4e_post_filter_constant();
        require_once T4E_POST_FILTER_DIR . "admin/admin.php";
    }

    public function t4e_post_filter_constant()
    {

        if (!defined('T4E_POST_FILTER_DIR')) {
            define('T4E_POST_FILTER_DIR', get_stylesheet_directory() . "/inc/post-filter/");
        }

        if (!defined('T4E_POST_FILTER_URI')) {
            define('T4E_POST_FILTER_URI', get_stylesheet_directory_uri() . "/inc/post-filter/");
        }
        if (!defined('T4E_POST_FILTER_VERSION')) {
            define('T4E_POST_FILTER_VERSION', "1.0.0");        }    }
}

// Instantiate our class
$t4e_post_filter = new T4E_Post_Filter();

//var_dump(T4E_POST_FILTER_DIR);
