<?php

add_action('after_switch_theme', 'create_custom_stock_tables');

function create_custom_stock_tables()
{
    global $wpdb;
    $charset_collate = $wpdb->get_charset_collate();

    require_once ABSPATH . 'wp-admin/includes/upgrade.php';

    // Containers table
    $containers_sql = "CREATE TABLE {$wpdb->prefix}stock_containers (
        id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(255) NOT NULL,
        type VARCHAR(50) NOT NULL,
        status VARCHAR(50) NOT NULL,
        date DATE NOT NULL,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    ) $charset_collate;";
    dbDelta($containers_sql);

    // Container-Product link table
    $container_products_sql = "CREATE TABLE {$wpdb->prefix}stock_container_products (
        id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        container_id BIGINT UNSIGNED NOT NULL,
        product_id BIGINT UNSIGNED NOT NULL,
        quantity INT NOT NULL DEFAULT 0
    ) $charset_collate;";
    dbDelta($container_products_sql);

    // Shipments table
    /* $shipments_sql = "CREATE TABLE {$wpdb->prefix}stock_shipments (
        id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        type VARCHAR(50) NOT NULL,
        status VARCHAR(50) NOT NULL,
        date DATE NOT NULL,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    ) $charset_collate;";
    dbDelta($shipments_sql); */

    // Shipment-Container link table
    /* $shipment_containers_sql = "CREATE TABLE {$wpdb->prefix}stock_shipment_containers (
        id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        shipment_id BIGINT UNSIGNED NOT NULL,
        container_id BIGINT UNSIGNED NOT NULL,
        assigned_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        
        KEY shipment_id (shipment_id),
        KEY container_id (container_id),
        UNIQUE KEY unique_assignment (shipment_id, container_id)
    ) $charset_collate;";
    dbDelta($shipment_containers_sql); */
}
