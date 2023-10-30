<?php

// Block direct access to the main file.

if (!defined('ABSPATH')) {
    exit('You are cheating!');
}

class T4E_Shortcode_Render
{
    public function __construct()
    {
        // var_dump('shortcode-rendfffffffffffffffffer.php');
        add_shortcode('t4e_post_filter', [$this, 't4e_post_filter_call']);
    }

    public function t4e_post_filter_call($atts)
    {
        ob_start();

        $cl = 'sidebar';
        $caf_filter_layout = 'filter-layout3';
        $caf_post_layout = 'post-layout1';
        $b = '1';
        $caf_cpt_value = 'post';
        $tax = 'category,industry,discipline';
        $trm = 'all';
        $caf_per_page = '3';
        $id = '1';


        echo '<div id="caf-post-layout-container" class="caf-post-layout-container ' . $cl . ' ' . $caf_filter_layout . ' ' . $caf_post_layout . ' data-target-div' . $b . '" data-post-type="' . $caf_cpt_value . '" data-tax="' . $tax . '" data-terms="' . $trm . '" data-per-page="' . $caf_per_page . '" data-selected-terms="' . $trm . '" data-filter-id="' . $id . '" data-post-layout="' . $caf_post_layout . '" data-target-div="data-target-div' . $b . '">';

        // new CAF_front_filter

        $filepath = T4E_POST_FILTER_DIR . "templates/filter/filter-layout.php";

        if (file_exists($filepath)) {
            include $filepath;
        } else {
            echo "File doesn't found";
        }

      //  echo " = Shortcode render file ";

        echo "<div id ='manage-ajax-response' class='caf-row'>";
        echo '<div class="status"><i class="fa fa-spinner" aria-hidden="true"></i></div>';
        echo '<div class="content"></div>';

        echo "</div>";
        echo "</div>";

        $output = ob_get_contents();
        ob_end_clean();

        return $output;
    }
}
