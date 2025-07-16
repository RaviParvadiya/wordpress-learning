<?php

/**
 * Template Name: ACF Template
 */

get_header(); ?>

<div id="primary">
    <div id="content" role="main">

        <?php while (have_posts()) : the_post(); ?>

            <h1><?php the_field('name'); ?></h1>

            <?php
            $subtitle = get_field('subtitle');
            if ($subtitle) {
                echo '<h2 class="post-subtitle">' . esc_html($subtitle) . '</h2>';
            }
            ?>

            <?php
            $mood = get_field('post_mood');
            if ($mood) {
                echo '<p class="post-mood">Mood: ' . esc_html($mood) . '</p>';
            }
            ?>
            
            <p><?php the_content(); ?></p>

        <?php endwhile; // end of the loop. 
        ?>

    </div><!-- #content -->
</div><!-- #primary -->

<?php get_footer(); ?>