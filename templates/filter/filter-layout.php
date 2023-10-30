<?php

// Block direct access to the main file.

if (!defined('ABSPATH')) {
    exit('You are cheating!');
}
?>

<div id="caf-filter-layout3" class='caf-filter-layout data-target-div'>

    <?php
    $taxonomies = ['post_industry' => 'Industry', 'post_discipline' => 'Discipline'];
    ?>

    <?php
$i = 0;
    foreach ($taxonomies as $key => $taxonomy) {
    $i++;
    ?>
        <div class="accordion">
             <div class="head <?php echo $i == 1 ? "active" : ''; ?>">
                <?php echo "<h2>" . esc_html($taxonomy) . "</h2>"; ?>
                <i class="fas fa-angle-down arrow"></i>
            </div>
            <ul class="caf-filter-container caf-filter-layout3 content" style="display: <?php echo $i == 1 ? "block" : ''; ?>;">
                <?php
                $terms = get_terms(
                    array(
                        'taxonomy'   => $key,
                        'hide_empty' => false,
                    )
                );
                foreach ($terms as $term) {

                    $term_id = $term->term_id;
                    $term_tx = $term->taxonomy;

                    // individual category post count 
                    $args = array(
                        //'cat' => $term_id,
                        'post_type' => 'post',
                        'tax_query' => array(
                            array(
                                'taxonomy' => $term_tx,
                                'field' => 'id',
                                'terms' => $term_id,
                            )
                        )
                    );

                    $the_query = new WP_Query($args);

                    $post_count = esc_attr($the_query->found_posts);

                    $i_customize_term = esc_attr($term->taxonomy . '__' . $term->term_id);

                    $term_name = esc_html($term->name);
                    $for = rand().'_'.esc_html($term->name).'_'.esc_html($term->term_id);

                    $data = <<<EOD
          <li>
            <label for="{$for}" class="">
                <input id="{$for}" type="checkbox" name="job_profiles[]" class="" value="{$i_customize_term}" style="width: auto;" data-main-id='flt' data-target-div='data-target-div1' >
          {$term_name} ({$post_count})
            </label>
          </li>    
EOD;
                    echo $data;
                }
                ?>
            </ul>
        </div>
    <?php
    }

    ?>
</div>