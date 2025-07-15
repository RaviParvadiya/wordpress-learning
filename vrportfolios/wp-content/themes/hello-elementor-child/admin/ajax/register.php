<?php

add_action('wp_ajax_user_register', 'user_register_handler'); // Allow logged-in users to register too
add_action('wp_ajax_nopriv_user_register', 'user_register_handler');

function user_register_handler()
{
    check_ajax_referer('register_nonce', 'security');

    $username = sanitize_user($_POST['username']);
    $email = sanitize_email($_POST['email']);
    $password = $_POST['password'];

    if (username_exists($username) || email_exists($email)) {
        wp_send_json_error(['message' => 'Username or email already exists.']);
    }

    $user_id = wp_create_user($username, $password, $email);
    if (is_wp_error($user_id)) {
        wp_send_json_error(['message' => 'Registration failed.', 'data' => [$username, $password, $email]]);
    }

    $user = new WP_User($user_id);
    $user->set_role('subscriber');

    // Optional: auto login
    wp_set_current_user($user_id);
    wp_set_auth_cookie($user_id);

    wp_send_json_success(['message' => 'Registration successful.']);
}
