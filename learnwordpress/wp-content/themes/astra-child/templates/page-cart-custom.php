<?php
/**
 * Template Name: Custom Cart Page
 */
get_header();

while ( have_posts() ) : the_post(); ?>

    <main id="primary" class="site-main custom-cart-page">
        <?php the_content(); ?>
    </main>

<?php endwhile;

get_footer();
