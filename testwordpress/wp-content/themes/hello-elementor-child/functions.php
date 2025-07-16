<?php

add_action('wp_enqueue_scripts', function() {
    // Try the most common handles for Hello Elementor
    wp_dequeue_style('hello-elementor');
    wp_dequeue_style('hello-elementor-theme-style');
    wp_dequeue_style('hello-elementor-header-footer');
    // Also dequeue the parent style.css if loaded by default
    wp_dequeue_style('parent-style');
    // You can also try:
    wp_dequeue_style('hello-elementor-style');
}, 20);

add_action('wp_enqueue_scripts', function () {
    // Enqueue parent theme styles
    wp_enqueue_style('parent-style', get_stylesheet_directory_uri() . '/style.css');

    // Enqueue custom CSS files
/*     wp_enqueue_style('cleveland-bootstrap', get_stylesheet_directory_uri() . '/assets/css/bootstrap.min.css', array('parent-style'));
    wp_enqueue_style('cleveland-owl-carousel', get_stylesheet_directory_uri() . '/assets/css/owl.carousel.css', array('parent-style'));
    wp_enqueue_style('cleveland-owl-carousel-min', get_stylesheet_directory_uri() . '/assets/css/owl.carousel.min.css', array('parent-style'));
    wp_enqueue_style('cleveland-media', get_stylesheet_directory_uri() . '/assets/css/media.css', array('parent-style'));
    wp_enqueue_style('cleveland-style', get_stylesheet_directory_uri() . '/assets/css/style.css', array('parent-style'));

    // Enqueue JS files (jQuery first, then plugins, then custom)
    wp_enqueue_script('cleveland-jquery', get_stylesheet_directory_uri() . '/assets/js/jquery-3.2.1.min.js', array(), null, true);
    wp_enqueue_script('cleveland-bootstrap-bundle', get_stylesheet_directory_uri() . '/assets/js/bootstrap.bundle.min.js', array('cleveland-jquery'), null, true);
    wp_enqueue_script('cleveland-bootstrap', get_stylesheet_directory_uri() . '/assets/js/bootstrap.min.js', array('cleveland-jquery'), null, true);
    wp_enqueue_script('cleveland-owl-carousel', get_stylesheet_directory_uri() . '/assets/js/owl.carousel.js', array('cleveland-jquery'), null, true);
    wp_enqueue_script('cleveland-owl-carousel-min', get_stylesheet_directory_uri() . '/assets/js/owl.carousel.min.js', array('cleveland-jquery'), null, true); */
});

// Update ACF Value Before Save
add_filter('acf/update_value/name=subtitle', function($value, $post_id, $field) {
    return strtoupper($value);
}, 10, 3);

function hello_elementor_child_register_menus() {
    register_nav_menus([
        'main-menu' => __('Main Menu', 'hello-elementor-child'),
        'footer-menu' => __('Footer Menu', 'hello-elementor-child'),
    ]);
}
add_action('after_setup_theme', 'hello_elementor_child_register_menus');