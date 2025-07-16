<?php
// If uninstall not called from WordPress, exit
if (!defined('WP_UNINSTALL_PLUGIN')) {
    exit;
}

register_uninstall_hook(__FILE__, function () {
    global $wpdb;
    $table_name = $wpdb->prefix . 'options_builder';
    $wpdb->query("DROP TABLE IF EXISTS `$table_name`");
});
