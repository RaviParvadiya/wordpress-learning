<?php

// Handle assign product
add_action('wp_ajax_assign_product_ajax', 'handle_assign_product_ajax');
function handle_assign_product_ajax()
{
    check_ajax_referer('stock_container_nonce', 'security');

    global $wpdb;

    // Validate and get container ID
    $container_id = isset($_POST['container_id']) ? intval($_POST['container_id']) : 0;

    if (!$container_id) {
        wp_send_json_error(['message' => '<div class="notice notice-error is-dismissible"><p>Invalid container ID.</p></div>']);
    }

    // Handle form submit
    $product_id = intval($_POST['product_id']);
    $quantity   = intval($_POST['quantity']);

    if ($product_id && $quantity > 0) {
        // Check if already assigned
        $exists = $wpdb->get_var($wpdb->prepare(
            "SELECT COUNT(*) FROM {$wpdb->prefix}stock_container_products
            WHERE container_id = %d AND product_id = %d",
            $container_id,
            $product_id,
        ));

        if ($exists) {
            wp_send_json_error(['message' => '<div class="notice notice-error is-dismissible"><p>This product is already assigned to this container.</p></div>']);
        } else {
            $wpdb->insert(
                "{$wpdb->prefix}stock_container_products",
                [
                    'container_id' => $container_id,
                    'product_id'   => $product_id,
                    'quantity'     => $quantity
                ],
                ['%d', '%d', '%d']
            );
            wp_send_json_success(['message' => '<div class="notice notice-success is-dismissible"><p>Product assigned successfully.</p></div>']);
        }
    } else {
        wp_send_json_error(['message' => '<div class="notice notice-error is-dismissible"><p>All fields are required.</p></div>']);
    }
}

// Handle get assigned products
add_action('wp_ajax_get_assigned_products_ajax', 'handle_get_assigned_products_ajax');
function handle_get_assigned_products_ajax()
{
    check_ajax_referer('stock_container_nonce', 'security');

    global $wpdb;

    // Validate and get container ID
    $container_id = isset($_POST['container_id']) ? intval($_POST['container_id']) : 0;

    if (!$container_id) {
        wp_send_json_error(['message' => '<div class="notice notice-error is-dismissible"><p>Invalid container ID.</p></div>']);
    }

    // Fetch assigned products
    $assigned_products = $wpdb->get_results($wpdb->prepare(
        "SELECT cp.*, p.post_title
        FROM {$wpdb->prefix}stock_container_products cp
        LEFT JOIN {$wpdb->prefix}posts p ON cp.product_id = p.ID
        WHERE cp.container_id = %d",
        $container_id
    ));

    ob_start();
    if ($assigned_products) {
        foreach ($assigned_products as $item) {
?>
            <tr id="product-row-<?php echo esc_attr($item->id); ?>">
                <td><?php echo esc_html($item->post_title); ?></td>
                <td><input type="number" name="quantities[<?php echo esc_attr($item->id); ?>]" value="<?php echo esc_attr($item->quantity); ?>" min="1" required></td>
                <td>
                    <a href="#" class="button button-secondary delete-assignment" data-id="<?php echo esc_attr($item->id); ?>">Delete</a>
                </td>
            </tr>
<?php
        }
    }
    $html = ob_get_clean();
    if (empty($html)) {
        wp_send_json_error(['message' => '<tr><td colspan="3">No products assigned yet.</td></tr>']);
    }
    wp_send_json_success(['table' => $html]);
}

// Handle quantity updates
add_action('wp_ajax_update_assignment_quantity_ajax', 'handle_update_assignment_quantity_ajax');
function handle_update_assignment_quantity_ajax()
{
    check_ajax_referer('stock_container_nonce', 'security');

    global $wpdb;

    if (!empty($_POST['quantities']) && is_array($_POST['quantities'])) {
        foreach ($_POST['quantities'] as $assignment_id => $quantity) {
            $assignment_id = intval($assignment_id);
            $quantity = max(1, intval($quantity));

            $wpdb->update(
                "{$wpdb->prefix}stock_container_products",
                ['quantity' => $quantity],
                ['id' => $assignment_id],
                ['%d'],
                ['%d'],
            );
        }
        wp_send_json_success(['message' => '<div class="notice notice-success is-dismissible"><p>Quantities updated successfully.</p></div>']);
    }
}

// Handle delete assigned product
add_action('wp_ajax_delete_assignment_ajax', 'handle_delete_assignment_ajax');
function handle_delete_assignment_ajax()
{
    check_ajax_referer('stock_container_nonce', 'security');

    global $wpdb;

    $assignment_id = intval($_POST['id']);

    $deleted = $wpdb->delete(
        "{$wpdb->prefix}stock_container_products",
        ['id' => $assignment_id],
        ['%d'],
    );

    if ($deleted) {
        wp_send_json_success(['message' => '<div class="notice notice-success is-dismissible"><p>Product assignment removed.</p></div>']);
    } else {
        wp_send_json_error(['message' => '<div class="notice notice-error is-dismissible"><p>Failed to remove product assignment.</p></div>']);
    }
}
