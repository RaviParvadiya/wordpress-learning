<?php

/**
 * Stock
 *   - Shipments
 *   - Containers
 *   - Stock Overview
 *   - Add Product to Container
 */
add_action('admin_menu', 'my_custom_stock_admin_menu');
function my_custom_stock_admin_menu()
{
    add_menu_page(
        'Stock Management',
        'Stock',
        'manage_options',
        'stock-dashboard',
        'stock_dashboard_page',
        'dashicons-archive',
        25
    );

    // Submenu: Containers
    add_submenu_page(
        'stock-dashboard',
        'Containers',
        'Containers',
        'manage_options',
        'stock-containers',
        'stock_containers_page'
    );

    add_submenu_page(
        null, // hidden from the sidebar
        'Manage Container Products',
        'Manage Container Products',
        'manage_options',
        'stock-container-detail',
        'stock_container_detail_page'
    );

    add_submenu_page(
        'stock-dashboard',
        'Manual',
        'Manual',
        'manage_options',
        'stock-manual',
        'stock_manual_page'
    );
}
