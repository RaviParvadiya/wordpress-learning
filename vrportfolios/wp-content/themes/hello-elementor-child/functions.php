<?php
/*
 * This is the child theme for Hello Elementor theme, generated with Generate Child Theme plugin by catchthemes.
 *
 * (Please see https://developer.wordpress.org/themes/advanced-topics/child-themes/#how-to-create-a-child-theme)
 */
add_action('wp_enqueue_scripts', 'hello_elementor_child_enqueue_styles');
function hello_elementor_child_enqueue_styles()
{
    wp_enqueue_style('parent-style', get_template_directory_uri() . '/style.css');
    wp_enqueue_style(
        'child-style',
        get_stylesheet_directory_uri() . '/style.css',
        array('parent-style')
    );
}
/*
 * Your code goes below
 */

add_action('after_setup_theme', function () {
    $current_user = wp_get_current_user();
    $username = get_query_var('portfolio_user');

    if (current_user_can('subscriber') && !current_user_can('manage_options')) {
        show_admin_bar(false);
        return;
    }

    // Also hide admin bar if admin is viewing someone else's profile page
    if ($current_user !== $username) {
        show_admin_bar(false);
        return;
    }
});

require_once get_stylesheet_directory() . '/admin/setup/init.php';
require_once get_stylesheet_directory() . '/admin/menu-pages.php';
foreach (glob(get_stylesheet_directory() . '/admin/pages/*.php') as $file) {
    require_once $file;
}
foreach (glob(get_stylesheet_directory() . '/admin/ajax/*.php') as $file) {
    require_once $file;
}
foreach (glob(get_stylesheet_directory() . '/inc/*.php') as $file) {
    require_once $file;
}
