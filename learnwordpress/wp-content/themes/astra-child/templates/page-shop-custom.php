<?php
/**
 * Template Name: Custom Shop Page
 */
get_header();

while ( have_posts() ) : the_post(); ?>

    <main id="primary" class="site-main custom-shop-page">
        <?php the_content(); // This is what Elementor needs to hook into ?>
    </main>

<?php endwhile;

get_footer();
