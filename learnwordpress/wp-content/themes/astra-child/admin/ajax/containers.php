<?php

// Registers a handler for an custom(my own made up action name in JS) AJAX action in WordPress (admin-side)
// Handle new container form submit
add_action('wp_ajax_save_container_ajax', 'handle_save_container_ajax');
function handle_save_container_ajax()
{
    // First thing your AJAX handler should do is verify the nonce sent by jQuery
    check_ajax_referer('stock_admin_nonce', 'security');

    if (!current_user_can('manage_woocommerce')) {
        wp_send_json_error(['message' => 'Unauthorized']);
    }

    global $wpdb;

    $name   = sanitize_text_field($_POST['container_name']);
    $type   = sanitize_text_field($_POST['container_type']);
    $status = sanitize_text_field($_POST['container_status']);
    $date   = sanitize_text_field($_POST['container_date']);
    $edit_id = isset($_POST['edit_id']) ? intval($_POST['edit_id']) : 0;

    if (!$name || !$type || !$status || !$date) {
        wp_send_json_error(['message' => '<div class="notice notice-error is-dismissible"><p>All fields are required.</p></div>']);
    }

    if ($edit_id > 0) {
        $wpdb->update(
            "{$wpdb->prefix}stock_containers",
            compact('name', 'type', 'status', 'date'),
            ['id' => $edit_id],
            ['%s', '%s', '%s', '%s'],
            ['%d']
        );
        wp_send_json_success(['message' => '<div class="notice notice-success is-dismissible"><p>Container updated.</p></div>']);
    } else {
        $wpdb->insert(
            "{$wpdb->prefix}stock_containers",
            compact('name', 'type', 'status', 'date'),
            ['%s', '%s', '%s', '%s']
        );
        wp_send_json_success(['message' => 'Container added.', 'type' => 'success']);
    }

    // Not necessary wp_send_json* functions handles
    wp_die(); // All ajax handlers die when finished
}

// Handle deletion
add_action('wp_ajax_delete_container_ajax', 'handle_delete_container_ajax');
function handle_delete_container_ajax()
{
    check_ajax_referer('stock_admin_nonce', 'security');

    global $wpdb;

    $id = isset($_POST['container_id']) ? intval($_POST['container_id']) : 0;

    if (!$id) {
        wp_send_json_error(['message' => '<div class="notice notice-error is-dismissible"><p>Invalid ID.</p></div>']);
    }

    $delete_id = intval($_POST['container_id']);

    // Remove related product assignments first
    $wpdb->delete(
        "{$wpdb->prefix}stock_container_products",
        ['container_id' => $delete_id],
        ['%d']
    );

    // Delete the container
    $deleted = $wpdb->delete(
        "{$wpdb->prefix}stock_containers",
        ['id' => $delete_id],
        ['%d']
    );

    if ($deleted) {
        wp_send_json_success(['message' => '<div class="notice notice-success is-dismissible"><p>Container deleted successfully.</p></div>', 'id' => $id]);
    } else {
        wp_send_json_error(['message' => '<div class="notice notice-error is-dismissible"><p>Failed to delete container.</p></div>']);
    }
}

// Handle get all containers
add_action('wp_ajax_get_containers_ajax', 'handle_get_containers_ajax');
function handle_get_containers_ajax()
{
    check_ajax_referer('stock_admin_nonce', 'security');

    global $wpdb;

    // Fetch Containers || Filter
    /* $where = '1=1';

    if (!empty($_POST['filter_type'])) {
        $where .= $wpdb->prepare(" AND type = %s", $_POST['filter_type']);
    }
    if (!empty($_POST['filter_status'])) {
        $where .= $wpdb->prepare(" AND status = %s", $_POST['filter_status']);
    } */
    $where = [];
    $params = [];

    if (!empty($_POST['filter_type'])) {
        $where[] = 'type = %s';
        $params[] = $_POST['filter_type'];
    }
    if (!empty($_POST['filter_status'])) {
        $where[] = 'status = %s';
        $params[] = $_POST['filter_status'];
    }

    $sql = "SELECT * FROM {$wpdb->prefix}stock_containers";
    if (!empty($where)) {
        $sql .= ' WHERE ' . implode(' AND ', $where);
    }
    $sql .= " ORDER BY created_at DESC";

    $containers = $wpdb->get_results($wpdb->prepare($sql, ...$params));

    ob_start();
    if ($containers) {
        foreach ($containers as $container) {
?>
            <tr id="container-row-<?php echo esc_attr($container->id); ?>">
                <td><?php echo esc_html($container->name); ?></td>
                <td><?php echo esc_html(ucfirst($container->type)); ?></td>
                <td><?php echo esc_html(ucfirst($container->status)); ?></td>
                <td><?php echo esc_html($container->date); ?></td>
                <td><?php echo esc_html(date('Y-m-d H:i', strtotime($container->created_at))); ?></td>
                <td>
                    <a class="button" href="<?php echo admin_url('admin.php?page=stock-container-detail&id=' . $container->id); ?>">
                        Manage
                    </a>
                    <a href="#" class="button button-secondary edit-container" data-id="<?php echo esc_attr($container->id); ?>">Edit</a>
                    <a href="#" class="button button-secondary delete-container" data-id="<?php echo esc_attr($container->id); ?>">Delete</a>
                </td>
            </tr>
<?php
        }
    }
    $html = ob_get_clean();
    if (empty($html)) {
        wp_send_json_error(['message' => '<tr><td colspan="6">No containers found.</td></tr>']);
    }
    wp_send_json_success(['table' => $html]);
}

// Handle get single container for edit
add_action('wp_ajax_get_container_ajax', 'handle_get_container_ajax');
function handle_get_container_ajax()
{
    check_ajax_referer('stock_admin_nonce', 'security');

    global $wpdb;

    $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
    if (!$id) {
        wp_send_json_error(['message' => 'Invalid ID.']);
    }

    $container = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$wpdb->prefix}stock_containers WHERE id = %d", $id));
    if ($container) {
        wp_send_json_success(['container' => $container]);
    } else {
        wp_send_json_error(['message' => 'Failed to load container data.']);
    }
}
