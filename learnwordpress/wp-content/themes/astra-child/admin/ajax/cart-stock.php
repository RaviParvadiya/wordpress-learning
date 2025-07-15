<?php

add_action('wp_ajax_check_cart_stock_ajax', 'handle_check_cart_stock_ajax');
add_action('wp_ajax_nopriv_check_cart_stock_ajax', 'handle_check_cart_stock_ajax');
function handle_check_cart_stock_ajax()
{
    check_ajax_referer('stock_cart_nonce', 'security');
    global $wpdb;

    $product_id = isset($_POST['product_id']) ? intval($_POST['product_id']) : 0;
    $needed_qty = isset($_POST['quantity']) ? intval($_POST['quantity']) : 1;

    if (!$product_id || !$needed_qty) {
        wp_send_json_error(['message' => 'Quantity should be at least 1']);
    }

    // Get containers with stock for the product, ordered by date
    $containers = $wpdb->get_results($wpdb->prepare(
        "SELECT c.*, p.quantity FROM {$wpdb->prefix}stock_containers c
        LEFT JOIN {$wpdb->prefix}stock_container_products p
        ON c.id = p.container_id
        WHERE p.product_id = %d
        AND c.date >= CURDATE()
        ORDER BY c.date ASC",
        $product_id
    ));

    $today = new DateTime();
    // $currentWeek = $today->format('W');

    $response = [
        'available' => false,
        'partial' => false,
        'message' => '',
        'container' => null
    ];

    foreach ($containers as $container) {
        $container_date = new DateTime($container->date);
        $container_qty = intval($container->quantity);

        // Calculate weeks from today more accurately
        $diff_days = (int)$today->diff($container_date)->format('%r%a');
        $weeks_from_now = max(0, ceil($diff_days / 7));

        if ($container_qty <= 0) {
            continue;
        }

        if ($needed_qty > $container_qty) {
            $response['partial'] = true;
            $response['message'] = 'Only ' . $container_qty . ' available, rest expected in ' . $weeks_from_now . ' weeks';
        } else {
            if ($weeks_from_now <= 1) {
                $response['available'] = true;
                $response['message'] = 'Available now';
                $response['container'] = [
                    'week' => $weeks_from_now,
                    'container' => $container,
                ];
                break;
            } else {
                $response['available'] = true;
                $response['message'] = 'Expected delivery in ' . $weeks_from_now . ' weeks';
                $response['container'] = [
                    'week' => $weeks_from_now,
                    'container' => $container,
                ];
                break;
            }
        }

        if ($response['partial']) {
            $response['partial'] = false;
        }
    }

    if (!$response['available'] || empty($containers)) {
        $response['message'] = 'Out of stock';
        wp_send_json_error($response);
    }

    wp_send_json_success($response);
}
