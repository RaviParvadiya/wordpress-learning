<?php

add_action('wp_ajax_save_manual_stock_ajax', 'handle_save_manual_stock_ajax');
function handle_save_manual_stock_ajax()
{
    check_ajax_referer('stock_manual_nonce', 'security');
    global $wpdb;

    $container_id = isset($_POST['container_id']) ? intval($_POST['container_id']) : 0;
    $product_id = isset($_POST['product_id']) ? intval($_POST['product_id']) : 0;
    $quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 0;

    if (!$container_id || !$product_id) {
        wp_send_json_error(['message' => 'Inavlid data send.']);
    }

    $assignment = $wpdb->get_row($wpdb->prepare(
        "SELECT * FROM {$wpdb->prefix}stock_container_products WHERE product_id = %d AND container_id = %d",
        $product_id,
        $container_id
    ));

    if (!$assignment && $_POST['stock_action'] === 'out') {
        wp_send_json_error(['type' => 'warning', 'message' => 'This product is not assigned to that container.']);
    }

    if ($assignment) {
        // If product exist and stock could be In/Out
        if ($_POST['stock_action'] === 'out') {
            $new_qty = max(0, $assignment->quantity - $quantity);
        } else {
            $new_qty = $assignment->quantity + $quantity;
        }
        $wpdb->update(
            "{$wpdb->prefix}stock_container_products",
            ['quantity' => $new_qty],
            ['id' => $assignment->id],
            ['%d'],
            ['%d']
        );

        wp_send_json_success(['type' => 'success', 'message' => 'Successfully updated']);
    } elseif ($_POST['stock_action'] === 'in') {
        // If product not assigned and Stock In
        $wpdb->insert(
            "{$wpdb->prefix}stock_container_products",
            [
                'container_id' => $container_id,
                'product_id' => $product_id,
                'quantity' => $quantity
            ],
            ['%d', '%d', '%d']
        );
    } else {
        wp_send_json_error(['type' => 'error', 'message' => 'Something Went Wrong. Please try again later!']);
    }

    wp_send_json_success(['type' => 'success', 'message' => 'Successfully added']);
}

/* add_action('wp_ajax_get_products_ajax', 'handle_get_products_ajax');
function handle_get_products_ajax()
{
    check_ajax_referer('stock_manual_nonce', 'security');
    global $wpdb;
}
 */