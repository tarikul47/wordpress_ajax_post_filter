<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
// Post Pagination
$t4e_pagination = new T4E_ajax_pagination();

$i = 0;
if ($qry->have_posts()) : while ($qry->have_posts()) : $qry->the_post();
        global $post;
        $i++;
        //  $taxonomies = ['post_industry', 'discipline'];

        $terms = get_the_terms(get_the_ID(), 'post_industry');
        $dterms = get_the_terms(get_the_ID(), 'post_discipline');
?>
        <article id="caf-post-layout1" class="caf-post-layout1 caf-col-md-4 caf-col-md-tablet6 caf-col-md-mobile12 caf-mb-5  animate-off tp_B_Category" data-post-id="1">
            <div class="manage-layout1"><a href="<?php the_permalink() ?>" target="_blank" class="">

                    <div class="caf-featured-img-box " style="">
                        <?php
                        // Get post thumbnail (featured image)
                        if (has_post_thumbnail()) {
                            the_post_thumbnail(); // This will display the post thumbnail
                        }
                        ?>
                    </div>
                </a>
                <div id="manage-post-area">
                    <div class="caf-post-title">
                        <h2><a href="<?php the_permalink() ?>" target="_blank"><?php the_title() ?></a></h2>
                        <?php
                        if (!empty($terms)) {
                            foreach ($terms as $term) {
                                echo '<a href="' . esc_url(get_term_link($term)) . '">' . esc_html($term->name) . '</a>' . ' ';
                            }
                        }
                        if (!empty($dterms)) {
                            foreach ($dterms as $dterm) {
                                echo '<a href="' . esc_url(get_term_link($dterm)) . '">' . esc_html($dterm->name) . '</a>';
                            }
                        }
                        ?>
                    </div>
                    <div class="caf-meta-content">
                        <span class="author caf-col-md-4 caf-pl-0">
                            <i class="fa fa-user" aria-hidden="true"></i> <?php the_author(); ?></span>
                        <span class="date caf-col-md-6 caf-pl-0">
                            <i class="fa fa-calendar" aria-hidden="true"></i>
                            <?php the_date() ?>
                        </span>
                        <!-- <span class="comment caf-col-md-3 caf-pl-0">
                            <i class="fa fa-comment" aria-hidden="true"></i> 1
                        </span> -->
                    </div>
                    <div class="caf-content"></div>
                    <div class="caf-content-read-more"><a class="caf-read-more" href="<?php the_permalink() ?>" target="_blank">Read More</a></div>
                </div>
            </div>
        </article>

<?php

    endwhile;
    /**** Pagination*****/
    if (isset($_POST["params"]["load_more"])) {
        //do something
    } else {
        $t4e_pagination->t4e_number_pagination($qry, $page);
    }
    $response = [
        'status' => 200,
        'found' => $qry->found_posts,
        'message' => 'ok',
    ];
    wp_reset_postdata();
else :
    // class='error-of-empty-result error-caf'
    do_action("caf_empty_result_error", $caf_empty_res);

    $response = [
        'status' => 201,
        'message' => 'No posts found',
        'content' => '',
    ];
endif;