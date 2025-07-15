<?php

// Restrict access for not logged in user
add_action('template_redirect', 'restrict_pages_to_logged_in_users');
function restrict_pages_to_logged_in_users()
{
    // Allow these pages to remain public
    if (is_page(['login', 'register'])) {
        return;
    }

    // Allow access to /username/{username} style URLs
    /* $request_uri = trim($_SERVER['REQUEST_URI'], '/');
    
    // Check if URL matches the expected pattern like /username/ravi
    if (preg_match('#^[^/]+$#', $request_uri)) {
        return;
    } */

    // Allow user profile pages
    $username = get_query_var('portfolio_user');
    if ($username) {
        return; // Allow access to user profile pages
    }

    // Block access if not logged in
    if (!is_user_logged_in()) {
        wp_redirect(home_url('/login')); // Adjust to your login page URL
        exit;
    }
}
