<?php
/**
 * Theme functions and customizations for Astra Child theme.
 *
 * Handles:
 * - Enqueueing styles and scripts
 * - WooCommerce customizations (cart, checkout, product, admin)
 * - AJAX handlers and admin includes
 * - Custom meta and order processing
 */

// Enqueue child theme styles
add_action('wp_enqueue_scripts', 'astra_child_enqueue_styles');
function astra_child_enqueue_styles()
{
    wp_enqueue_style('astra-child-style', get_stylesheet_uri(), array('astra-theme-css'), '1.0.0');
}

foreach (glob(get_stylesheet_directory() . '/inc/*.php') as $file) {
    require_once $file;
}

// Require all AJAX handler files from admin/ajax directory
foreach (glob(get_stylesheet_directory() . '/admin/ajax/*.php') as $file) {
    require_once $file;
}

$learn_hooks_dir = get_stylesheet_directory() . '/learn-woo-hooks/';

require_once $learn_hooks_dir .  'sample-functions.php';

require_once $learn_hooks_dir . 'order-delivery-date.php';

// Require all admin PHP files
foreach (glob(get_stylesheet_directory() . '/admin/*.php') as $file) {
    require_once $file;
}

// Require all admin page files
foreach (glob(get_stylesheet_directory() . '/admin/pages/*.php') as $file) {
    require_once $file;
}

// Include database initialization script
require_once get_stylesheet_directory() . '/admin/setup/db-init.php';

// Store custom container data in WooCommerce cart session
add_filter('woocommerce_add_cart_item_data', function ($cart_item_data, $product_id, $variation_id) {
    if (isset($_POST['container_id'])) {
        $cart_item_data['container_id'] = sanitize_text_field($_POST['container_id']);
    }
    if (isset($_POST['container_name'])) {
        $cart_item_data['container_name'] = sanitize_text_field($_POST['container_name']);
    }
    if (isset($_POST['container_week'])) {
        $cart_item_data['container_week'] = sanitize_text_field($_POST['container_week']);
    }
    return $cart_item_data;
}, 10, 3);

// Save custom container data to order item meta
add_action('woocommerce_checkout_create_order_line_item', function ($item, $cart_item_key, $values, $order) {
    if (!empty($values['container_id'])) {
        $item->add_meta_data('_container_id', $values['container_id']);
    }
    if (!empty($values['container_name'])) {
        $item->add_meta_data('_container_name', $values['container_name']);
    }
    if (!empty($values['container_week'])) {
        $item->add_meta_data('_container_week', $values['container_week']);
    }
}, 10, 4);

// Change display key for custom container meta in order item display
add_filter('woocommerce_order_item_display_meta_key', function ($display_key, $meta, $item) {
    switch ($meta->key) {
        case '_container_id':
            return 'Container ID';
        case '_container_name':
            return 'Container Name';
        case '_container_week':
            return 'Estimated Week';
    }
    return $display_key;
}, 10, 3);


/* add_filter('woocommerce_cart_item_name', 'add_expected_delivery_week_to_cart_item', 10, 3);
function add_expected_delivery_week_to_cart_item($name, $cart_item, $cart_item_key)
{
    if (!empty($cart_item['container_week'])) {
        $week = esc_html($cart_item['container_week']);
        $product_id = $cart_item['product_id'];
        $message = '<div class="expected-delivery-msg" data-product_id="' . esc_attr($product_id) . '"><strong style="color:#008000;">Expected Delivery In ' . $week . ' Weeks</strong></div>';
        $name .= $message;
        // $name .= '<br><small  class="expected-delivery-msg" data-product_id="' . esc_attr($product_id) . '"><strong style="color:#008000;">Expected Delivery In ' . $week . ' Weeks</strong></small>';
    }
    return $name;
} */

// Cart: show ETA under product name
add_filter('woocommerce_cart_item_name', 'add_eta_to_cart_item_name', 10, 3);
function add_eta_to_cart_item_name($name, $cart_item, $cart_item_key)
{
    if (is_cart() && !empty($cart_item['container_week'])) {
        $week = esc_html($cart_item['container_week']);
        $eta_html = '<br><small  class="expected-delivery-msg"><strong style="color:#008000;">Expected Delivery In ' . $week . ' Weeks</strong></small>';
        $name .= $eta_html;
    }
    return $name;
}

// Checkout: show ETA after quantity
add_filter('woocommerce_checkout_cart_item_quantity', 'add_eta_after_quantity_checkout', 10, 3);
function add_eta_after_quantity_checkout($quantity_html, $cart_item, $cart_item_key)
{
    if (!empty($cart_item['container_week'])) {
        $week = esc_html($cart_item['container_week']);
        $eta_html = '<br><small  class="expected-delivery-msg"><strong style="color:#008000;">Expected Delivery In ' . $week . ' Weeks</strong></small>';
        return $quantity_html . $eta_html;
    }
    return $quantity_html;
}

// Deduct stock from custom table when order is completed
add_action('woocommerce_thankyou', 'deduct_stock_on_order_complete', 10, 1);
function deduct_stock_on_order_complete($order_id)
{
    $order = wc_get_order($order_id);

    if (!$order) return;

    foreach ($order->get_items() as $item_id => $item) {
        $container_id = $item->get_meta('_container_id');
        $product_id = $item->get_product_id();
        $needed_qty = $item->get_quantity();

        if ($container_id && $product_id && $needed_qty) {
            global $wpdb;
            // Deduct the quantity from your custom table
            $wpdb->query(
                $wpdb->prepare(
                    "UPDATE {$wpdb->prefix}stock_container_products
                     SET quantity = GREATEST(quantity - %d, 0)
                     WHERE container_id = %d AND product_id = %d",
                    $needed_qty,
                    $container_id,
                    $product_id
                )
            );
        }
    }
}
