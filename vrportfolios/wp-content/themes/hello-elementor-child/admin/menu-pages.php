<?php

add_action('admin_menu', 'register_portfolio_menu_page');
function register_portfolio_menu_page() {
    add_menu_page(
        'My Portfolio',
        'My Portfolio',
        'subscriber',
        'my-portfolio',
        'render_portfolio_form_page',
        'dashicons-id',
        6
    );
}

add_action('admin_menu', function() {
    // Only show to subscribers
    if (current_user_can('subscriber')) {
        add_menu_page(
            'Messages',                // Page title
            'Messages',                // Menu title
            'read',                    // Capability (subscriber has 'read')
            'subscriber-messages',     // Menu slug
            'render_subscriber_messages_page', // Callback function
            'dashicons-email',         // Icon
            7                          // Position
        );
    }
});

add_action('admin_menu', 'portfolio_admin_menu');
function portfolio_admin_menu() {
    // Main menu page
    add_menu_page(
        'Portfolios',
        'Portfolios',
        'manage_options',
        'portfolio',
        'portfolio_list_page',
        'dashicons-id',
        6
    );

    // Submenu to manually edit skills/roles if needed
    add_submenu_page(
        'portfolio',
        'Manage Skills & Roles',
        'Skills & Roles',
        'manage_options',
        'portfolio-skills',
        'portfolio_skills_page'
    );

    add_submenu_page(
        'portfolio',
        'Messages',
        'Messages',
        'manage_options',
        'portfolio-messages',
        'portfolio_messages_page'
    );
}
