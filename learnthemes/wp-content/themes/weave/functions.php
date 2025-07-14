<?php

/**
 * Weave functions & definitions
 * 
 * @package Weave
 * @since Twenty Twenty-Four 1.0.0
 */

function weave_enqueue_styles()
{
    // Google Fonts
    wp_enqueue_style(
        'weave-google-fonts',
        'https://fonts.googleapis.com/css2?family=Questrial:wght@400&display=swap',
        array(),
        null
    );

    // Main theme stylesheet (optional, if you have style.css in root)
    wp_enqueue_style('weave-style', get_stylesheet_uri());

    // Header styles
    wp_enqueue_style(
        'weave-header',
        get_template_directory_uri() . '/assets/css/header.css',
        array(), // dependencies
        filemtime(get_template_directory() . '/assets/css/header.css') // cache busting
    );
    

    // Home Page styles
    wp_enqueue_style(
        'weave-home-page',
        get_template_directory_uri() . '/assets/css/front-page.css',
        array(), // dependencies
        filemtime(get_template_directory() . '/assets/css/front-page.css') // cache busting
    );

    // Create Fabric Page styles
    wp_enqueue_style(
        'weave-create-fabric-page',
        get_template_directory_uri() . '/assets/css/create-fabric.css',
        array(), // dependencies
        filemtime(get_template_directory() . '/assets/css/create-fabric.css') // cache busting
    );

    // Design Order Page styles
    wp_enqueue_style(
        'weave-design-order-page',
        get_template_directory_uri() . '/assets/css/design-order.css',
        array(), // dependencies
        filemtime(get_template_directory() . '/assets/css/design-order.css') // cache busting
    );

    // Woocommerce custom styles
    wp_enqueue_style(
        'weave-woocommerce-custom',
        get_template_directory_uri() . '/assets/css/woocommerce-custom.css',
        array(), // dependencies
        filemtime(get_template_directory() . '/assets/css/woocommerce-custom.css') // cache busting
    );

    // Footer styles
    wp_enqueue_style(
        'weave-footer',
        get_template_directory_uri() . '/assets/css/footer.css',
        array(), // dependencies
        filemtime(get_template_directory() . '/assets/css/footer.css') // cache busting
    );

    // Responsive styles
    wp_enqueue_style(
        'weave-responsive',
        get_template_directory_uri() . '/assets/css/responsive.css',
        array(), // dependencies
        filemtime(get_template_directory() . '/assets/css/responsive.css') // cache busting
    );
}
add_action('wp_enqueue_scripts', 'weave_enqueue_styles');

// Rewrtie rule
function weave_add_custom_rewrite_rule()
{
    add_rewrite_rule('^create-fabric/?$', 'index.php?create_fabric_page=1', 'top');
    add_rewrite_rule('^design-order/?$', 'index.php?design_order_page=1', 'top');
}
add_action('init', 'weave_add_custom_rewrite_rule');

function weave_add_query_vars($vars)
{
    $vars[] = 'create_fabric_page';
    $vars[] = 'design_order_page';
    return $vars;
}
add_filter('query_vars', 'weave_add_query_vars');

// Template loader
function weave_template_redirect()
{
    if (get_query_var('create_fabric_page')) {
        include get_theme_file_path('templates/page-create-fabric.php');
        exit;
    }

    if (get_query_var('design_order_page')) {
        include get_theme_file_path('templates/page-design-order.php');
        exit;
    }
}
add_action('template_redirect', 'weave_template_redirect');

if (class_exists('WooCommerce')) {
    require_once get_template_directory() . '/inc/woocommerce/custom-fields.php';
}

add_action('after_setup_theme', function () {
    add_theme_support('woocommerce');
});
