<?php

add_action('wp_ajax_submit_portfolio_message', 'handle_portfolio_message');
add_action('wp_ajax_nopriv_submit_portfolio_message', 'handle_portfolio_message');
function handle_portfolio_message()
{
    global $wpdb;
    $table = $wpdb->prefix . 'portfolio_messages';

    $receiver_id = intval($_POST['receiver_id']);
    $sender_id = intval($_POST['sender_id']) ?: null;
    $name = sanitize_text_field($_POST['name']);
    $email = sanitize_email($_POST['email']);
    $message = sanitize_textarea_field($_POST['message']);

    if (!$receiver_id || !$message || !$email) {
        wp_send_json_error(['message' => 'Missing required fields.']);
    }

    $to = get_userdata($receiver_id)->user_email;
    $subject = 'New Contact Form Submission';
    $body = "Hi John,\n\nYou've received a new message from your portfolio.\n\nName: $name\n\nEmail: $email\n\nMessage: I'd love to connect with you regarding a collaboration. $message\n\nThanks,\nvrportfolios.com";
    $headers = [
        'Content-Type: text/plain; charset=UTF-8',
        'Reply-To: ' . $name . ' <' . $email . '>'   // This is the user's submitted info
    ];

    $mail_sent = wp_mail($to, $subject, $body, $headers);

    if ($mail_sent) {
        wp_send_json_success(['message' => '<div class="notice success">Message sent successfully!</div>']);
    } else {
        wp_send_json_success(['message' => '<div class="notice error">Failed to send the message.</div>']);
    }

    $wpdb->insert(
        $table,
        [
            'sender_id' => $sender_id,
            'receiver_id' => $receiver_id,
            'sender_name' => $name,
            'sender_email' => $email,
            'message' => $message,
            'sent_at' => current_time('mysql'),
        ]
    );

    wp_send_json_success(['message' => 'Message sent successfully']);
}
