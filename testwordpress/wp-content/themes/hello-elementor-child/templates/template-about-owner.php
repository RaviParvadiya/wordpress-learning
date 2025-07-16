<?php

/**
 * Template Name: Template About Owner
 */

get_header();
?>

<?php
$hero_bg = get_field('about_bg_img');
$herp_bg_mobile = get_field('about_bg_img_mobile');

if (! $hero_bg || ! $herp_bg_mobile) {
    $hero_bg = get_stylesheet_directory_uri() . '/assets/img/about_slider.png';
    $herp_bg_mobile = get_stylesheet_directory_uri() . '/assets/img/about_mobile_banner.png';
}
?>
<style>
    .bg_home_lan_img {
        background-image: url('<?php echo esc_url($hero_bg); ?>');
        position: relative;
        background-size: 100% 100%;
    }

    .full_height_100vh_home {
        height: 80vh;
    }

    @media screen and (max-width: 767px) {
        .bg_home_lan_img {
            background-image: url('<?php echo esc_url($herp_bg_mobile); ?>');
        }
    }
</style>

<section class="bg_home_lan_img full_height_100vh_home" id="home">
    <div class="bg_overlay_cover_on"></div>
    <div class="home_table_cell">
        <div class="home_table_cell_center">
            <h3>Home | About us</h3>
            <h1><?php the_field('about_title'); ?></h1>
            <a href="#">Send a message</a>
        </div>
    </div>
    </div>
</section>


<section class="realEstateInvestorSection">
    <div class="realEstateInvestorRow">
        <div class="imageBox">
            <div class="image">
                <img src="<?php the_field('real_estate_img'); ?>" alt="" class="img-fluid">
            </div>
        </div>
        <div class="contentBox">
            <div class="subtitle">
                <span><?php the_field('real_estate_subtitle'); ?></span>
            </div>
            <div class="title">
                <h3><?php the_field('real_estate_name'); ?></h3>
            </div>
            <div class="desc">
                <p>
                    <?php the_field('real_estate_description'); ?>
                </p>
                <p>
                    Have a property that you’re looking to sell for quick cash? <a href="">Give me a call</a>. 
                </p>
            </div>
        </div>
    </div>
</section>

<section class="client_testimonial about_client_testimonial">
    <div class="row">
        <div class="col-md-4">
            <div class="left_slide_wrap">
                <div class="left_slide_dls">
                    <h4>Client Testimonials</h4>
                    <h2>Why Our Customers Are Saying About Us</h2>
                </div>
                <div class="sli_aerrow_sec desktop">
                    <svg xmlns="http://www.w3.org/2000/svg" width="69" height="69" viewBox="0 0 69 69" fill="none">
                        <path
                            d="M30.6375 28.9713L25.1431 34.4657M25.1431 34.4657L30.6375 40M25.1431 34.4657L43 34.4657"
                            stroke="#101317" stroke-linecap="round" stroke-linejoin="round" />
                        <path
                            d="M30.6375 28.9713L25.1431 34.4657M25.1431 34.4657L30.6375 40M25.1431 34.4657L43 34.4657"
                            stroke="#101317" stroke-linecap="round" stroke-linejoin="round" />
                        <circle opacity="0.5" cx="34.5" cy="34.5" r="34" transform="rotate(-180 34.5 34.5)"
                            stroke="#101317" />
                    </svg>
                    <svg xmlns="http://www.w3.org/2000/svg" width="69" height="69" viewBox="0 0 69 69" fill="none">
                        <path
                            d="M38.3625 40.0287L43.8569 34.5343M43.8569 34.5343L38.3625 29M43.8569 34.5343L26 34.5343"
                            stroke="#101317" stroke-linecap="round" stroke-linejoin="round" />
                        <path
                            d="M38.3625 40.0287L43.8569 34.5343M43.8569 34.5343L38.3625 29M43.8569 34.5343L26 34.5343"
                            stroke="#101317" stroke-linecap="round" stroke-linejoin="round" />
                        <circle opacity="0.5" cx="34.5" cy="34.5" r="34" stroke="#101317" />
                    </svg>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="client_testi">
                <div class="owl-carousel owl-theme">
                    <?php
                    $args = [
                        'post_type'      => 'testimonial',
                        'post_status'    => 'publish',
                        'orderby'        => 'date',
                        'order'          => 'ASC',
                        'offset'         => 12,
                    ];
                    $testimonials = new WP_Query($args);

                    if ($testimonials->have_posts()) :
                        while ($testimonials->have_posts()) : $testimonials->the_post(); ?>
                            <div class="item">
                                <div class="item_boxes">
                                    <p><?php the_content(); ?></p>
                                    <div class="testi_users">
                                        <h2><?php the_title(); ?></h2>
                                        <div class="str_grps">
                                            <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/str.png" alt="">
                                            <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/str.png" alt="">
                                            <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/str.png" alt="">
                                            <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/str.png" alt="">
                                            <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/str.png" alt="">
                                        </div>
                                    </div>
                                </div>
                            </div>
                    <?php endwhile;
                        wp_reset_postdata();
                    endif ?>
                </div>
            </div>
            <div class="sli_aerrow_sec mobile">
                <svg xmlns="http://www.w3.org/2000/svg" width="69" height="69" viewBox="0 0 69 69" fill="none">
                    <path
                        d="M30.6375 28.9713L25.1431 34.4657M25.1431 34.4657L30.6375 40M25.1431 34.4657L43 34.4657"
                        stroke="#101317" stroke-linecap="round" stroke-linejoin="round" />
                    <path
                        d="M30.6375 28.9713L25.1431 34.4657M25.1431 34.4657L30.6375 40M25.1431 34.4657L43 34.4657"
                        stroke="#101317" stroke-linecap="round" stroke-linejoin="round" />
                    <circle opacity="0.5" cx="34.5" cy="34.5" r="34" transform="rotate(-180 34.5 34.5)"
                        stroke="#101317" />
                </svg>
                <svg xmlns="http://www.w3.org/2000/svg" width="69" height="69" viewBox="0 0 69 69" fill="none">
                    <path
                        d="M38.3625 40.0287L43.8569 34.5343M43.8569 34.5343L38.3625 29M43.8569 34.5343L26 34.5343"
                        stroke="#101317" stroke-linecap="round" stroke-linejoin="round" />
                    <path
                        d="M38.3625 40.0287L43.8569 34.5343M43.8569 34.5343L38.3625 29M43.8569 34.5343L26 34.5343"
                        stroke="#101317" stroke-linecap="round" stroke-linejoin="round" />
                    <circle opacity="0.5" cx="34.5" cy="34.5" r="34" stroke="#101317" />
                </svg>
            </div>
        </div>
    </div>
</section>

<section class="ourGuarantee desktop">
    <div class="img">
        <img src="<?php the_field('guarantee_bg_img'); ?>" alt="" class="img-fluid">
    </div>
    <div class="formBox">
        <div class="subtitle">
            <span><?php the_field('guarantee_subtitle'); ?></span>
        </div>
        <div class="title">
            <h3><?php the_field('guarantee_title'); ?></h3>
        </div>
        <div class="desc">
            <p>
                <?php the_field('guarantee_description'); ?>
            </p>
        </div>
        <!-- <form action=""> -->
            <div class="row">
                <?php echo do_shortcode('[fluentform id="3"]'); ?>
                <!-- <div class="col-lg-6 col-md-6">
                    <div class="form-group">
                        <input type="text" name="" id="" class="form-control" placeholder="First Name">
                    </div>
                </div>
                <div class="col-lg-6 col-md-6">
                    <div class="form-group">
                        <input type="text" name="" id="" class="form-control" placeholder="Last Name">
                    </div>
                </div>
                <div class="col-lg-6 col-md-6">
                    <div class="form-group">
                        <input type="text" name="" id="" class="form-control" placeholder="Email">
                    </div>
                </div>
                <div class="col-lg-6 col-md-6">
                    <div class="form-group">
                        <input type="text" name="" id="" class="form-control" placeholder="Phone">
                    </div>
                </div>
                <div class="col-lg-12 col-md-12">
                    <div class="form-group">
                        <input type="text" name="" id="" class="form-control" placeholder="Property Address">
                    </div>
                </div>
                <div class="col-lg-12 col-md-12">
                    <div class="form-group">
                        <textarea name="" class="form-control" id="" rows="5"
                            placeholder="Additional Comments"></textarea>
                    </div>
                </div> -->
                <!-- <div class="col-lg-12 col-md-12">
                        <div class="form-group">
                            <button class="btn ">Submit</button>
                        </div>
                    </div> -->
            </div>
        <!-- </form> -->
    </div>
</section>

<?php get_footer(); ?>

<script src="<?php echo get_stylesheet_directory_uri(); ?>/assets/js/jquery-3.2.1.min.js"></script>
<!-- <script src="./js/bootstrap.bundle.min.js"></script> -->
<script src="<?php echo get_stylesheet_directory_uri(); ?>/assets/js/bootstrap.min.js"></script>
<script src="<?php echo get_stylesheet_directory_uri(); ?>/assets/js/owl.carousel.js"></script>
<script src="<?php echo get_stylesheet_directory_uri(); ?>/assets/js/owl.carousel.min.js"></script>

<script>
    // $('.owl-carousel').owlCarousel({
    //     stagePadding: 200,
    //     loop: true,
    //     margin: 39,
    //     nav: true,
    //     responsive: {
    //         0: {
    //             items: 1
    //         },
    //         600: {
    //             items: 1
    //         },
    //         1000: {
    //             items: 1
    //         },
    //         1920: {
    //             items: 2
    //         }
    //     }
    // })
    $('.owl-carousel').owlCarousel({
        stagePadding: 200,
        loop: true,
        margin: 39,
        nav: true,
        responsive: {
            0: {
                items: 1,
                stagePadding: 0,
            },
            767: {
                items: 1,
                stagePadding: 20,
            },

            992: {
                items: 1,
                stagePadding: 100,
            },
            1024: {
                items: 1,
                stagePadding: 150,
            },
            1440: {
                items: 1,
                stagePadding: 230,
            },
            1920: {
                items: 2,
                stagePadding: 200,
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