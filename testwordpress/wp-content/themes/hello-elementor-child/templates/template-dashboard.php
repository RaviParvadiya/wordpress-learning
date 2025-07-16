<?php

/**
 * Template Name: Template Dashboard
 */

get_header();
?>

<?php
$hero_bg = get_field('hero_bg_image');
$herp_bg_mobile = get_field('hero_mobile_bg_image');

if (! $hero_bg || ! $herp_bg_mobile) {
    $hero_bg = get_stylesheet_directory_uri() . '/assets/img/home_first_slider.png';
    $herp_bg_mobile = get_stylesheet_directory_uri() . '/assets/img/mobile_slider.png';
}
?>
<style>
    .slider_first {
        background-image: url('<?php echo esc_url($hero_bg); ?>');
        position: relative;
        background-size: 100% 70%;
        background-position: top;
        background-repeat: no-repeat;
    }

    @media screen and (max-width: 767px) {
        .slider_first {
            background-image: url('<?php echo esc_url($herp_bg_mobile); ?>');
        }
    }
</style>

<section class="slider_first">
    <div class="slider_first_cell">
        <div class="slider_first_cell_center">
            <h4><?php the_field('hero_heading'); ?></h4>
            <h1><?php the_field('hero_main_title'); ?></h1>
            <p><?php the_field('hero_description'); ?></p>
            <a href="#" class="mobile">Send a message</a>
            <!-- <div class="result_perk_forms">
                <h6>The results speak for themselves</h6>
                <h2>Close in 30 days or less!</h2>
                <form action="">
                    <div class="forms_grps">
                        <div class="forms_input">
                            <input type="text" class="form-control" id="exampleInputEmail1"
                                aria-describedby="emailHelp" placeholder="First Name">
                        </div>
                        <div class="forms_input">
                            <input type="text" class="form-control" id="exampleInputEmail1"
                                aria-describedby="emailHelp" placeholder="Last Name">
                        </div>
                    </div>
                    <div class="forms_grps">
                        <div class="forms_input">
                            <input type="text" class="form-control" id="exampleInputEmail1"
                                aria-describedby="emailHelp" placeholder="Phone">
                        </div>
                        <div class="forms_input">
                            <input type="email" class="form-control" id="exampleInputEmail1"
                                aria-describedby="emailHelp" placeholder="Email">
                        </div>
                    </div>
                    <div class="forms_grps">
                        <div class="forms_input">
                            <input type="text" class="form-control" id="exampleInputEmail1"
                                aria-describedby="emailHelp" placeholder="Property Address">
                        </div>
                    </div>
                    <div class="forms_grps">
                        <div class="forms_input">
                            <div class="form-floating">
                                <textarea class="form-control" placeholder="Additional Comments"
                                    id="floatingTextarea2" style="height: 100px"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="send_msg_form">
                        <button type="submit">Send a message</button>
                    </div>
                </form>
            </div> -->
        </div>
    </div>
</section>

<section class="selling_home">
    <div class="selling_home_dls">
        <h6><?php the_field('selling_home_subtitle'); ?></h6>
        <h2><?php the_field('selling_home_title'); ?></h2>
        <div class="selling_home_boxes">
            <?php
            for ($i = 1; $i <= 3; $i++) :
                $box_img = "selling_box_{$i}_image";
                $box_title = "selling_box_{$i}_title";
                $box_text = "selling_box_{$i}_text";
            ?>
                <div class="selling_home_sec">
                    <img src="<?php the_field($box_img) ?>" alt="">
                    <h4><?php the_field($box_title) ?></h4>
                    <p><?php the_field($box_text) ?></p>
                </div>
                <?php if ($i === 3) continue; ?>
                <div class="dividers"></div>
            <?php endfor; ?>
        </div>
    </div>
</section>

<section class="find_neighbor">
    <?php
    $find_neighbor_img = get_field('find_neighbor_img');
    ?>
    <div class="find_neighbor_img" style="background-image: url('<?php echo esc_url($find_neighbor_img); ?>');">
        <div class="bg_overlay_cover_on"></div>
        <div class="slider_first_cell">
            <div class="slider_first_cell_center">
                <h6><?php the_field('find_neighbor_subtitle'); ?></h6>
                <h2><?php the_field('find_neighbor_title'); ?></h2>
                <p><?php the_field('find_neighbor_description'); ?></p>
                <form action="">
                    <div class="input_forms_zip">
                        <input type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp"
                            placeholder="Enter Your Zipcode">
                    </div>
                </form>
                <div class="dont_see_para">
                    <p>Don’t see what you’re looking for? Contact us <a href="#">here.</a></p>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="real_estate_sec">
    <div class="real_estate_wrap">
        <div class="left_estate_wrap">
            <img src="<?php the_field('real_estate_img'); ?>" alt="">
        </div>
        <div class="right_estate_wrap">
            <h4><?php the_field('real_estate_subtitle'); ?></h4>
            <h2><?php the_field('real_estate_name'); ?></h2>
            <p><?php the_field('real_estate_description'); ?></p>
            <a href="#">About Me<svg xmlns="http://www.w3.org/2000/svg" width="10" height="8" viewBox="0 0 10 8"
                    fill="none">
                    <path
                        d="M2.05575 8H0V6.81818L2.38676 4L0 1.18182V0H2.05575L5.38328 4L2.05575 8ZM6.67247 8H4.61672V6.81818L7.02091 4L4.61672 1.18182V0H6.67247L10 4L6.67247 8Z"
                        fill="#A0551E" />
                </svg>
            </a>
        </div>
    </div>
</section>

<section class="client_testimonial">
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
                <div class="owl-carousel owl-theme" id="client_testi">
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
                <div class="sli_aerrow_sec_mobile mobile">
                    <div class="sli_aerrow_sec_mobile_sec">
                        <svg xmlns="http://www.w3.org/2000/svg" width="69" height="69" viewBox="0 0 69 69"
                            fill="none">
                            <path
                                d="M30.6375 28.9713L25.1431 34.4657M25.1431 34.4657L30.6375 40M25.1431 34.4657L43 34.4657"
                                stroke="#101317" stroke-linecap="round" stroke-linejoin="round" />
                            <path
                                d="M30.6375 28.9713L25.1431 34.4657M25.1431 34.4657L30.6375 40M25.1431 34.4657L43 34.4657"
                                stroke="#101317" stroke-linecap="round" stroke-linejoin="round" />
                            <circle opacity="0.5" cx="34.5" cy="34.5" r="34" transform="rotate(-180 34.5 34.5)"
                                stroke="#101317" />
                        </svg>
                        <svg xmlns="http://www.w3.org/2000/svg" width="69" height="69" viewBox="0 0 69 69"
                            fill="none">
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
        </div>
    </div>
</section>

<section class="frequen_sec">
    <div class="frequen_sec_title">
        <h2><?php the_field('faq_title'); ?></h2>
    </div>
    <div class="frequen_sec_dls">
        <div class="accordion" id="accordionExample">
            <div class="row">
                <div class="col-6">
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingOne">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                <?php the_field('faq_question'); ?>
                            </button>
                        </h2>
                        <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne"
                            data-bs-parent="#accordionExample">
                            <div class="accordion-body">
                                <?php the_field('faq_answer'); ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingTwo">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                <?php the_field('faq_question'); ?>
                            </button>
                        </h2>
                        <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo"
                            data-bs-parent="#accordionExample">
                            <div class="accordion-body">
                                <?php the_field('faq_answer'); ?>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="col-6">
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingThree">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                <?php the_field('faq_question'); ?>
                            </button>
                        </h2>
                        <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree"
                            data-bs-parent="#accordionExample">
                            <div class="accordion-body">
                                <?php the_field('faq_answer'); ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingFour">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                data-bs-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                                <?php the_field('faq_question'); ?>
                            </button>
                        </h2>
                        <div id="collapseFour" class="accordion-collapse collapse" aria-labelledby="headingFour"
                            data-bs-parent="#accordionExample">
                            <div class="accordion-body">
                                <?php the_field('faq_answer'); ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingFive">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                data-bs-target="#collapseFive" aria-expanded="false" aria-controls="collapseFive">
                                <?php the_field('faq_question'); ?>
                            </button>
                        </h2>
                        <div id="collapseFive" class="accordion-collapse collapse" aria-labelledby="headingFive"
                            data-bs-parent="#accordionExample">
                            <div class="accordion-body">
                                <?php the_field('faq_answer'); ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingSixth">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                data-bs-target="#collapseSixth" aria-expanded="false" aria-controls="collapseSixth">
                                <?php the_field('faq_question'); ?>
                            </button>
                        </h2>
                        <div id="collapseSixth" class="accordion-collapse collapse" aria-labelledby="headingSixth"
                            data-bs-parent="#accordionExample">
                            <div class="accordion-body">
                                <?php the_field('faq_answer'); ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingSeventh">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                data-bs-target="#collapseSeventh" aria-expanded="false"
                                aria-controls="collapseSeventh">
                                <?php the_field('faq_question'); ?>
                            </button>
                        </h2>
                        <div id="collapseSeventh" class="accordion-collapse collapse"
                            aria-labelledby="headingSeventh" data-bs-parent="#accordionExample">
                            <div class="accordion-body">
                                <?php the_field('faq_answer'); ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingEighth">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                data-bs-target="#collapseEighth" aria-expanded="false"
                                aria-controls="collapseEighth">
                                <?php the_field('faq_question'); ?>
                            </button>
                        </h2>
                        <div id="collapseEighth" class="accordion-collapse collapse" aria-labelledby="headingEighth"
                            data-bs-parent="#accordionExample">
                            <div class="accordion-body">
                                <?php the_field('faq_answer'); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php get_footer(); ?>
</body>

<script src="<?php echo get_stylesheet_directory_uri(); ?>/assets/js/jquery-3.2.1.min.js"></script>
<!-- <script src="./js/bootstrap.bundle.min.js"></script> -->
<script src="<?php echo get_stylesheet_directory_uri(); ?>/assets/js/bootstrap.min.js"></script>
<script src="<?php echo get_stylesheet_directory_uri(); ?>/assets/js/owl.carousel.js"></script>
<script src="<?php echo get_stylesheet_directory_uri(); ?>/assets/js/owl.carousel.min.js"></script>

<script>
    $('#client_testi').owlCarousel({
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
                stagePadding: 0,
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

</html>