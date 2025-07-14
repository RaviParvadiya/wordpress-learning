<?php get_header(); ?>

<main id="primary" class="site-main <?php if ( is_woocommerce() ) echo 'woocommerce'; ?>">
    <?php
    while ( have_posts() ) :
        the_post();
        the_content();
    endwhile;
    ?>
</main>

<?php get_footer(); ?>
