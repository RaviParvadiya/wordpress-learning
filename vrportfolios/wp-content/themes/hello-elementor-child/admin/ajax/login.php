<?php

add_action('wp_ajax_user_login', 'user_login_handler');
add_action('wp_ajax_nopriv_user_login', 'user_login_handler');

function user_login_handler()
{
    check_ajax_referer('login_nonce', 'security');

    $username = sanitize_user($_POST['username']);
    $password = $_POST['password'];

    // Attempt to sign on
    $user = wp_signon([
        'user_login'    => $username,
        'user_password' => $password,
        'remember'      => true,
    ], is_ssl());

    if (is_wp_error($user)) {
        wp_send_json_error(['message' => 'Invalid username or password.']);
    }

    wp_send_json_success(['message' => 'Login successful.', 'username' => $username]);
}
