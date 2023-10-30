<?php

// Block direct access to the main file.

if (!defined('ABSPATH')) {
    exit('You are cheating!');
}


class T4E_Shortcode
{
    public function __construct()
    {
        $this->shortcodes_init();
    }

    public function shortcodes_init()
    {
        include T4E_POST_FILTER_DIR . "inc/shortcode-render.php";

        new T4E_Shortcode_Render();
    }
}

/**
 * Style and Scripts include 
 */
class T4E_Load_Scripts
{
    public function __construct()
    {
        // the_posts gets triggered before wp_head
        // Enqueue Scripts before load
        add_filter('the_posts', array($this, 't4e_conditionally_add_scripts_and_styles'));
        //  add_action('wp_enqueue_scripts', array($this, 't4e_enqueue_scripts'));
    }

    public function t4e_conditionally_add_scripts_and_styles($posts)
    {
        // var_dump($posts);
        if (empty($posts)) {
            return $posts;
        }

        $shortcode_found = false; // use this flag to see if styles and scripts need to be enqueued
        //   $short_id = array();

        foreach ($posts as $post) {
            //var_dump($post->post_content);

            //$html = str_get_html($post->post_content);
            if (stripos($post->post_content, '[t4e_post_filter') !== false) {

                $shortcode_found = true; // bingo!
                break;
            }
        }

        if ($shortcode_found) {

            wp_enqueue_style('t4e-common-style', T4E_POST_FILTER_URI . 'assets/css/common/common.min.css', array(), T4E_POST_FILTER_VERSION, 'all');

            wp_enqueue_style('t4e-post-layout', T4E_POST_FILTER_URI . 'assets/css/post/post-layout.min.css', array(), T4E_POST_FILTER_VERSION, 'all');

            wp_enqueue_style('t4e-filter-layout', T4E_POST_FILTER_URI . 'assets/css/filter/filter-layout.min.css', array(), T4E_POST_FILTER_VERSION, 'all');

            wp_enqueue_style('t4e-font-awesome-style', T4E_POST_FILTER_URI . 'assets/css/fontawesome/css/font-awesome.min.css', array(), T4E_POST_FILTER_VERSION, 'all');

            wp_enqueue_script('jquery');

            wp_enqueue_script('t4e-frontend-scripts', T4E_POST_FILTER_URI . 'assets/js/script.min.js', array('jquery'), T4E_POST_FILTER_VERSION);

            wp_localize_script('t4e-frontend-scripts', 't4e_ajax', array('ajax_url' => admin_url('admin-ajax.php'), 'nonce' => wp_create_nonce('t4e_ajax_nonce'), 'plugin_path' => T4E_POST_FILTER_DIR));
        }

        return $posts;
    }
}

/**
 * Ajax call handle and populate post 
 */
class T4E_Get_Filter_Posts
{
    public function __construct()
    {
        add_action('wp_ajax_t4e_get_filter_posts', array($this, 't4e_get_filter_posts'));
        add_action('wp_ajax_nopriv_t4e_get_filter_posts', array($this, 't4e_get_filter_posts'));
    }
    public function t4e_get_filter_posts()
    {
        /**
         * Parameter accepted 
         * - none 
         * - tax = [Taxonomy -> Induistry | Dsicipline ]
         * - post-type = [post | ]
         * - term = [Single | Array ]
         * - page = [ int ]
         * - per-page = [int]
         */


        if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 't4e_ajax_nonce')) {
            die('Permission denied');
        }


        /*** Default response ***/
        $response = [
            'status' => 500,
            'message' => 'Something is wrong, please try again later ...',
            'content' => false,
            'found' => 0,
        ];


        /**
         * [28-Oct-2023 11:19:59 UTC] Array
        (
            [action] => t4e_get_filter_posts
            [nonce] => d4427af499
            [params] => Array
                (
                 -   [page] => 1
                 -   [tax] => category,industry,discipline
                 -   [post-type] => post
                 -  [term] => all
                 -   [per-page] => 3
                    [data-target-div] => .data-target-div1
                )

        )

        [28-Oct-2023 18:27:39 UTC] Array
(
    [0] => industry
    [1] => 12
)

         */


        // Taxonomy 
        $tax = sanitize_text_field($_POST['params']['tax']);

        // post type [ now not use ]
        $post_type = sanitize_text_field($_POST['params']['post-type']);

        // term by using post filter 
        $term = sanitize_text_field($_POST['params']['term']);

        // page 
        $page = intval($_POST['params']['page']);

        // per-page
        $per_page = intval($_POST['params']['per-page']);


        //   $target_div = sanitize_text_field($_POST['params']['data-target-div']);

        if ($per_page == '-1') {
            $per_page = '5';
        }

        // error_log(print_r($terms, true));
        // die();

        $default_order_by = 'title';
        $default_order_by = apply_filters('tc_caf_filter_posts_order_by', $default_order_by);
        $default_order = "asc";
        $default_order = apply_filters('tc_caf_filter_posts_order', $default_order);

        /*** Setup query ***/
        $args = [
            'paged' => $page,
            'post_type' => $post_type,
            'post_status' => 'publish',
            'posts_per_page' => $per_page,
            'orderby' => $default_order_by,
            'order' => $default_order,
        ];

        /*** Check if term exists ***/
        $terms = explode('__', $term);

       // error_log(print_r($terms[0], true));
       // die();

        // Check if a taxonomy is specified and add it to the query
        if (!empty($terms[0]) &&  'all' != $terms[0]) {
         
            if (!is_array($terms)) {
                $response = [
                    'status' => 501,
                    'message' => 'Term doesn\'t exist',
                    'content' => 0,
                ];
                die(json_encode($response));
            } else {
                $tax_qry[] = [
                    'taxonomy' => $terms[0],
                    'field' => 'term_id',
                    'terms' => $terms[1],
                ];
            };
            $args['tax_query'] = $tax_qry;
        }

        $qry = new WP_Query($args);

        ob_start();
        echo '<div class="status"></div>';

        $filepath = T4E_POST_FILTER_DIR . "templates/post/post-layout.php";

        if (file_exists($filepath)) {
            include_once $filepath;
        } else {
            echo "<div class='error-of-post-layout error-caf'>" . esc_html('Post Layout is not Available.', 'tc_caf') . "</div>";
            $response = [
                'status' => 404,
                'message' => 'No posts found',
                //'content' =>'ok',
            ];
        }
        //  }
        $response['content'] = ob_get_clean();
        die(json_encode($response));
        //die();
    }
}

/**
 * Pagination
 */
class T4E_ajax_pagination
{
    public function t4e_number_pagination($query, $paged)
    {
        if (!$query) {
            return;
        }

        $prev_text = 'Prev';
        $next_text = 'Next';
        $prev_text = apply_filters('tc_caf_filter_prev_text', $prev_text);
        $next_text = apply_filters('tc_caf_filter_next_text', $next_text);

        $paginate = paginate_links([
            'base' => '%_%',
            'type' => 'array',
            'total' => $query->max_num_pages,
            'format' => '#page=%#%',
            'current' => max(1, $paged),
            'prev_text' => $prev_text,
            'next_text' => $next_text,
        ]);
        if ($query->max_num_pages > 1) : ?>
            <ul id="caf-layout-pagination" class="caf-pagination post-layout1">
                <?php foreach ($paginate as $page) : ?>
                    <li><?php echo $page; ?></li>
                <?php endforeach; ?>
            </ul>
<?php endif;
    }
}
