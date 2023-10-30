<?php

// Block direct access to the main file.

if (!defined('ABSPATH')) {
    exit('You are cheating!');
}

require_once T4E_POST_FILTER_DIR . "admin/helper.php";

new T4E_Shortcode();

// Style and Scripts include 
new T4E_Load_Scripts();

// Ajax call handle and post populate
new T4E_Get_Filter_Posts();

