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
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Questrial&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <?php wp_head(); ?>
</head>

<script>
    document.addEventListener('keydown', function(e) {
        if (e.key === "Escape") {
            document.querySelector('.sidebar-nav').classList.remove('active');
        }
    });
</script>

<body <?php body_class(); ?>>
    <?php wp_body_open(); ?>
    <div id="page" class="site">

        <!-- top-header -->
        <div class="top-header">
            <div class="container top-header__container">
                <div class="top-header__left">
                    <span class="top-header__text">Sign Up for Rewards Points</span>
                </div>
                <div class="top-header__right">
                    <span class="top-header__email">
                        <img src="<?php echo get_template_directory_uri(); ?>/assets/images/email.png" alt="" width="16" height="12">
                        <span>info@email.com</span>
                    </span>
                    <span class="top-header__separator">|</span>
                    <span class="top-header__item ">
                        <img src="<?php echo get_template_directory_uri(); ?>/assets/images/login.png" alt="" width="13" height="13">
                        <span>Login</span>
                    </span>
                    <span class="top-header__item top-header__item--signup">
                        <img src="<?php echo get_template_directory_uri(); ?>/assets/images/profile-user.png" alt="Profile" width="13" height="13">
                        <span>Sign Up</span>
                    </span>
                </div>
            </div>
        </div>

        <!-- main-header with navbar -->
        <header class="main-header">
            <div class="container">

            <div class="logo-header__row">
                <div class="logo-header">
                    <a href="<?php echo get_home_url(); ?>">
                        <span>
                            <img src="<?php echo get_template_directory_uri(); ?>/assets/images/svgviewer-png-output.png" alt="" width="240" height="32.55">
                        </span>
                    </a>
                    <button class="navbar__toggle" aria-label="Toggle Menu" onclick="document.querySelector('.sidebar-nav').classList.toggle('active')">
                        <i class="fa fa-bars"></i>
                    </button>
                </div>

                <div class="logo-header__search-box">
                    <input type="text" name="search" id="search" class="logo-header__search-input" placeholder="Search Here...">
                    <a href="javascript:void(0);" class="logo-header__search-button">
                        <img src="<?php echo get_template_directory_uri(); ?>/assets/images/search.png" alt="" width="20" height="20">
                    </a>
                    <a href="<?php echo function_exists('wc_get_cart_url') ? wc_get_cart_url() : '#'; ?>" class="logo-header__item logo-header__item--cart">
                        <img src="<?php echo get_template_directory_uri(); ?>/assets/images/shopping-cart.png" alt="" width="24" height="22.13">
                        <span>My Cart</span>
                    </a>
                </div>
            </div>

                <div class="navbar">
                    <nav>
                        <ul>
                            <li><a href="/create-fabric">Create Your Fabric</a></li>
                            <li><a href="/design-order">Design</a></li>
                            <li><a href="#">Fabric</a></li>
                            <li><a href="<?php echo function_exists('wc_get_page_permalink') ? wc_get_page_permalink('shop') : '/shop'; ?>">Shop</a></li>
                            <li><a href="<?php echo function_exists('wc_get_checkout_url') ? wc_get_checkout_url() : '/checkout'; ?>">Checkout</a></li>
                            <li><a href="#">Review</a></li>
                            <li><a href="#">Help</a></li>
                        </ul>
                    </nav>
                </div>
            </div>

            <div class="sidebar-nav" id="sidebarNav">
                <button class="sidebar-nav__close" aria-label="Close Menu" onclick="document.querySelector('.sidebar-nav').classList.remove('active')">&times;</button>
                <nav>
                    <ul>
                        <li><a href="/create-fabric">Create Your Fabric</a></li>
                        <li><a href="/design-order">Design</a></li>
                        <li><a href="#">Fabric</a></li>
                        <li><a href="<?php echo function_exists('wc_get_page_permalink') ? wc_get_page_permalink('shop') : '/shop'; ?>">Shop</a></li>
                        <li><a href="<?php echo function_exists('wc_get_checkout_url') ? wc_get_checkout_url() : '/checkout'; ?>">Checkout</a></li>
                        <li><a href="#">Review</a></li>
                        <li><a href="#">Help</a></li>
                    </ul>
                </nav>
            </div>
            <div class="sidebar-nav__overlay" onclick="document.querySelector('.sidebar-nav').classList.remove('active')"></div>

        </header>