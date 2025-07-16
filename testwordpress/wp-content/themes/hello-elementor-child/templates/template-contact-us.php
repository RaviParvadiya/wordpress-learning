<?php

/**
 * Template Name: Template Contact US
 */

get_header();
?>

<?php
$contact_bg_img = get_field('contact_bg_img');
?>
    <style>
        .bg_home_lan_img {
            position: relative;
            background-size: 100% 100%;
        }

        .contact_lan_img {
            background-image: url('<?php echo esc_url($contact_bg_img); ?>');
        }

        .full_height_100vh_home {
            height: 80vh;
        }
    </style>

    <section class="bg_home_lan_img full_height_100vh_home contact_lan_img">
        <div class="bg_overlay_cover_on"></div>
        <div class="home_table_cell">
            <div class="home_table_cell_center">
                <div class="contact_banner_dls">
                    <div class="contact_banner_title">
                        <h3>Home | <?php the_field('contact_title'); ?> </h3>
                        <h1><?php the_field('contact_title'); ?> </h1>
                    </div>
                    <div class="right_contact_banner_dls">
                        <div class="innner_contact">
                            <h6><?php the_field('contact_info_title'); ?></h6>
                            <a href="#"><?php the_field('contact_phone_number'); ?></a>
                            <a href="#"><?php the_field('contact_email'); ?></a>
                        </div>
                        <div class="innner_contact">
                            <h6><?php the_field('call_hours_title'); ?></h6>
                            <a href="#"><?php the_field('call_hours_days'); ?></a>
                            <a href="#"><?php the_field('call_hours_time'); ?></a>
                        </div>
                        <div class="innner_contact">
                            <h6><?php the_field('appointment_hours_title'); ?> </h6>
                            <a href="#"><?php the_field('appointment_hours_days'); ?></a>
                            <a href="#"><?php the_field('appointment_hours_time'); ?></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>
    </section>

    <section class="contact_us">
        <div class="container">
            <div class="enterInfoTitle">
                <h2>Please Enter Your Information Below</h2>
            </div>
            <div class="formBox">
                <!-- <form action=""> -->
                    <div class="row">
                        <?php echo FrmFormsController::get_form_shortcode( array( 'id' => 2 ) ); ?>
                        <!-- <div class="col-lg-6 col-md-12">
                            <div class="form-group">
                                <input type="text" name="" id="" class="form-control" placeholder="First Name">
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-12">
                            <div class="form-group">
                                <input type="text" name="" id="" class="form-control" placeholder="Last Name">
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-12">
                            <div class="form-group">
                                <input type="text" name="" id="" class="form-control" placeholder="Email">
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-12">
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
                                <textarea name="" class="form-control" id="" rows="5" placeholder="Message"></textarea>
                            </div>
                        </div>
                        <div class="col-lg-12 col-md-12">
                            <div class="form-group">
                                <button class="btn ">Submit</button>
                            </div>
                        </div> -->
                    </div>
                <!-- </form> -->
            </div>
        </div>
    </section>
    <section class="contact_us_map">
        <div class="responsive-map">
            <iframe
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2822.7806761080233!2d-93.29138368446431!3d44.96844997909819!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x52b32b6ee2c87c91%3A0xc20dff2748d2bd92!2sWalker+Art+Center!5e0!3m2!1sen!2sus!4v1514524647889"
                width="600" height="450" frameborder="0" style="border:0" allowfullscreen></iframe>
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
        $(window).scroll(function () {
            var sticky = $('nav'),
                scroll = $(window).scrollTop();

            if (scroll >= 100) sticky.addClass('fixed');
            else sticky.removeClass('fixed');
        });
    </script>
</body>

</html>