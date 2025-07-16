<?php

/**
 * The header.
 * 
 * This is the template that displays all of the <head> section and everything up until main.
 */

?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?php the_title(); ?></title>

    <link rel="stylesheet" href="<?php echo get_stylesheet_directory_uri(); ?>/assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo get_stylesheet_directory_uri(); ?>/assets/css/owl.carousel.css">
    <link rel="stylesheet" href="<?php echo get_stylesheet_directory_uri(); ?>/assets/css/owl.carousel.min.css">
    <link rel="stylesheet" href="<?php echo get_stylesheet_directory_uri(); ?>/assets/css/style.css">
    <link rel="stylesheet" href="<?php echo get_stylesheet_directory_uri(); ?>/assets/css/media.css">
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
    <?php wp_body_open(); ?>

    <nav class="top_headbar">
        <div class="main_logo">
            <a href="<?php echo esc_url(home_url('/')); ?>">
                <?php
                if (has_custom_logo()) {
                    the_custom_logo();
                } else {
                    echo 'Cleveland Quick Sale';
                    // bloginfo('name'); // Site title as text
                }
                ?></a>
        </div>
        <div class="main_navs_menu tablate">
            <?php
            wp_nav_menu(array(
                'theme_location' => 'main-menu', // must match what you registered (based on the register_nav_menu() slug)
                'container' => false, // Prevents WordPress from wrapping the menu in an extra <div>
                'menu_class' => '', // use your wrapping div’s class
                'items_wrap' => '<ul>%3$s</ul>', // %3$s is where the actual <li><a></a></li> items go
                'fallback_cb' => false, // Prevents WordPress from printing a default menu when no custom menu is assigned yet
            ));
            ?>
        </div>
        <div class="right_side_menus tablate">
            <?php
            $data = get_option('custom_options_8');
            if (is_string($data)) {
                $data = json_decode($data, true);
            }
            ?>
            <a href="#">
                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 13 13" fill="none">
                    <path
                        d="M12.3741 8.73985L9.31197 7.36769L9.30352 7.36379C9.14455 7.2958 8.97114 7.26851 8.79897 7.2844C8.6268 7.30029 8.46131 7.35885 8.31746 7.45479C8.30052 7.46596 8.28424 7.47812 8.26871 7.49119L6.6866 8.83995C5.6843 8.3531 4.64949 7.32609 4.16264 6.33678L5.51334 4.73061C5.52634 4.71436 5.53869 4.69811 5.5504 4.68056C5.64427 4.53711 5.70123 4.37267 5.7162 4.20188C5.73117 4.03109 5.70369 3.85925 5.6362 3.70165V3.69385L4.26014 0.626474C4.17092 0.420595 4.01751 0.249091 3.8228 0.137565C3.6281 0.026039 3.40255 -0.0195271 3.17983 0.00766854C2.29905 0.123569 1.49058 0.556123 0.905419 1.22454C0.320255 1.89296 -0.00158662 2.75153 5.88166e-06 3.6399C5.88166e-06 8.80095 4.19904 13 9.36007 13C10.2484 13.0016 11.107 12.6797 11.7754 12.0946C12.4438 11.5094 12.8764 10.7009 12.9923 9.82016C13.0195 9.59751 12.9741 9.37202 12.8627 9.17733C12.7513 8.98263 12.5799 8.82918 12.3741 8.73985ZM9.36007 11.96C7.15419 11.9576 5.03935 11.0802 3.47956 9.52043C1.91977 7.96063 1.04242 5.84579 1.04001 3.6399C1.03757 3.00517 1.26625 2.39124 1.68335 1.91278C2.10045 1.43431 2.67744 1.12403 3.30658 1.03988C3.30632 1.04247 3.30632 1.04509 3.30658 1.04768L4.67159 4.10271L3.32803 5.71082C3.31436 5.72649 3.30198 5.74323 3.29098 5.76087C3.19316 5.91097 3.13578 6.08379 3.12439 6.26259C3.113 6.44138 3.148 6.62009 3.22598 6.78138C3.81488 7.98585 5.02844 9.19031 6.2459 9.77856C6.40838 9.85582 6.58812 9.8896 6.76756 9.87661C6.947 9.86362 7.12 9.80431 7.26966 9.70446C7.28638 9.69327 7.30244 9.68112 7.31776 9.66806L8.89792 8.31995L11.9529 9.68821H11.9601C11.877 10.3182 11.5671 10.8964 11.0886 11.3145C10.61 11.7327 9.99555 11.9621 9.36007 11.96Z"
                        fill="#FAFAFA" />
                </svg><?php echo esc_html($data['header_phone']); ?></a>
            <a href="<?php echo esc_url($data['header_contact_link']); ?>"><?php echo esc_html($data['header_contact_text']); ?></a>
        </div>
        <div class="mobile_menus">
            <div class="menu_btn_ig">
                <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/menu_btn.png" alt="">
            </div>
        </div>
    </nav>