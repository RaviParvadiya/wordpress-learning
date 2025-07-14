<?php
get_header();
?>
    <main id="main" class="site-main <?php if ( is_woocommerce() ) echo 'woocommerce'; ?>">
        <?php woocommerce_content(); ?>
    </main>
<?php
get_footer();