<?php

// Load custom template for portfolio
add_filter('template_include', function ($template) {
    $username = get_query_var('portfolio_user');

    if ($username) {
        $user = get_user_by('login', $username);

        if ($user) {
            // Get user's selected template, e.g., from user meta
            $selected_template = get_user_meta($user->ID, 'portfolio_template', true) ?: 'template-default';
            $template_path = get_stylesheet_directory() . "/templates/{$selected_template}.php";

            if (file_exists($template_path)) {
                // Make $user available in template
                $GLOBALS['user'] = $user;
                return $template_path;
            }
        }
        // Optionally, show 404 if user/template not found
        return get_404_template();
    }
    return $template;
});

/* add_action('template_redirect', function () {
    $username = get_query_var('portfolio_user');

    if (!$username) return;

    $user = get_user_by('login', $username);
    if (!$user) {
        wp_die('Portfolio not found');
    }

    // Get the template selected by user
    $template_key = get_user_meta($user->ID, 'portfolio_template', true) ?: 'template-1';
    $template_file = get_stylesheet_directory() . "/templates/{$template_key}.php";

    if (!file_exists($template_file)) {
        wp_die('Template not found');
    }

    // Optional: Load header/footer if needed
    get_header();
    include $template_file;
    get_footer();
    exit;
}); */

/* 
// Another way
// Create a Portfolio Page Template Loader
add_action('template_redirect', function () {
    $username = get_query_var('portfolio_user');

    if ($username) {
        $user = get_user_by('login', $username);
        
        if ($user) {
            // Load the user's selected template, e.g. from user meta
            $template = get_user_meta($user->ID, 'portfolio_template', true) ?: 'design1';
            include get_stylesheet_directory() . '/templates/' . $template . '.php';
            exit;
        } else {
            // User not found, show 404
            global $wp_query;
            $wp_query->set_404();
            status_header(404);
            nocache_headers();
            include get_query_template('404');
            exit;
        }
    }
}); */
