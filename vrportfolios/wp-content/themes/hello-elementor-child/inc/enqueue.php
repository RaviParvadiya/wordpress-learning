<?php

add_action('wp_enqueue_scripts', 'register_scripts');
function register_scripts()
{
    wp_enqueue_script('register-js', get_stylesheet_directory_uri() . '/assets/js/register.js', ['jquery'], null, true);

    wp_localize_script('register-js', 'register_ajax', [
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('register_nonce')
    ]);
}

add_action('wp_enqueue_scripts', 'login_scripts');
function login_scripts()
{
    wp_enqueue_script('login-js', get_stylesheet_directory_uri() . '/assets/js/login.js', ['jquery'], null, true);

    wp_localize_script('login-js', 'login_ajax', [
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('login_nonce')
    ]);
}

/**
 * For a top-level menu with slug my-portfolio, the hook is toplevel_page_my-portfolio.
 * For a submenu, it would be something like my-portfolio_page_submenu-slug.
 */
add_action('admin_enqueue_scripts', 'enqueue_portfolio_admin_assets');
function enqueue_portfolio_admin_assets($hook)
{
    if ($hook !== 'toplevel_page_my-portfolio') return;
    wp_enqueue_style('portfolio-admin-style', get_stylesheet_directory_uri() . '/admin/css/portfolio-style.css');
    wp_enqueue_script('portfolio-admin-js', get_stylesheet_directory_uri() . '/admin/js/portfolio-form.js', [], false, true);
}
