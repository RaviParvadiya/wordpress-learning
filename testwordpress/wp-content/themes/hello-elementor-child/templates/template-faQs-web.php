<?php

/**
 * Template Name: Template FAQs
 */

get_header();
?>

<?php
$hero_bg = get_field('faq_bg_img');
?>
<style>
    .bg_home_lan_img {
        position: relative;
        background-size: 100% 100%;
    }

    .faqs_pages {
        background-image: url('<?php echo esc_url($hero_bg); ?>');
    }

    .full_height_100vh_home {
        height: 80vh;
    }
</style>
<section class="bg_home_lan_img full_height_100vh_home faqs_pages" id="home">
    <div class="bg_overlay_cover_on"></div>
    <div class="home_table_cell">
        <div class="home_table_cell_center">
            <h3>Home | Testimonials </h3>
            <h1><?php the_field('faq_title'); ?> </h1>
            <a href="#">Send a message</a>
        </div>
    </div>
    </div>
</section>

<section class="faQs">
    <?php for ($i = 0; $i < 6; $i++) : ?>
        <div class="faq">
            <h3><?php the_field('faq_question'); ?></h3>
            <p><?php the_field('faq_answer'); ?></p>
        </div>
    <?php endfor; ?>
    <div class="faQs_loadmore_btn">
        <button class="load_more">Load more</button>
    </div>
</section>

<?php get_footer(); ?>

<script src="<?php echo get_stylesheet_directory_uri(); ?>/assets/js/jquery-3.2.1.min.js"></script>
<!-- <script src="./js/bootstrap.bundle.min.js"></script> -->
<script src="<?php echo get_stylesheet_directory_uri(); ?>/assets/js/bootstrap.min.js"></script>
<script src="<?php echo get_stylesheet_directory_uri(); ?>/assets/js/owl.carousel.js"></script>
<script src="<?php echo get_stylesheet_directory_uri(); ?>/assets/js/owl.carousel.min.js"></script>

<script>
    $('.owl-carousel').owlCarousel({
        stagePadding: 200,
        loop: true,
        margin: 39,
        nav: true,
        responsive: {
            0: {
                items: 1
            },
            600: {
                items: 1
            },
            1000: {
                items: 1
            },
            1920: {
                items: 2
            }
        }
    })
</script>

<script>
    $(window).scroll(function() {
        var sticky = $('nav'),
            scroll = $(window).scrollTop();

        if (scroll >= 100) sticky.addClass('fixed');
        else sticky.removeClass('fixed');
    });
</script>
</body>

</html>