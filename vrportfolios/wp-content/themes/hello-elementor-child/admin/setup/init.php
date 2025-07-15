<?php

add_action('after_switch_theme', 'create_portfolio_messages_table');
function create_portfolio_messages_table()
{
    global $wpdb;
    $table_name = $wpdb->prefix . 'portfolio_messages';

    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE IF NOT EXISTS $table_name (
        id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        sender_id BIGINT UNSIGNED NULL,
        receiver_id BIGINT UNSIGNED NOT NULL,
        sender_name VARCHAR(255),
        sender_email VARCHAR(255),
        message TEXT NOT NULL,
        sent_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        is_read TINYINT(1) DEFAULT 0
    ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}
