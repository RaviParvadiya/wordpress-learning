<?php

add_action('admin_post_save_portfolio_skills_data', 'save_portfolio_skills_data');
function save_portfolio_skills_data()
{
    if (!isset($_POST['save_portfolio_skills_data']) || !wp_verify_nonce($_POST['_wpnonce'], 'save_portfolio_skills_data')) {
        return;
    }

    $skills = array_map(function ($skill) {
        return [
            'title' => sanitize_text_field($skill['title']),
            'link' => esc_url_raw($skill['link']),
        ];
    }, $_POST['portfolio_skills'] ?? []);
    update_option('portfolio_skills', json_encode($skills));

    // Redirect back with success flag
    wp_redirect(add_query_arg('updated', 'true', admin_url('admin.php?page=portfolio-skills')));
    exit;
}
