<?php
/**
 * Sample WooCommerce and WordPress customizations for learning purposes.
 *
 * Implements:
 * - Manual enqueue of WooCommerce cart scripts
 * - Custom REST API endpoint
 * - Custom WooCommerce payment gateway
 * - Adds estimated delivery time to emails
 */

// Enqueue WooCommerce Cart Scripts Manually
add_action('wp_enqueue_scripts', 'enqueue_wc_cart_script_on_custom_cart_page');
function enqueue_wc_cart_script_on_custom_cart_page()
{
    if (is_page() && has_shortcode(get_post()->post_content, 'custom_cart_page')) {
        wp_enqueue_script('wc-cart');
    }
}

// Custom REST API
add_action('rest_api_init', function () {
    register_rest_route('custom/v1', '/my-data/', array(
        'methods' => 'GET',
        'callback' => 'my_custom_api_callback',
        'permission_callback' => '__return_true',
    ));
});

function my_custom_api_callback($data)
{
    return array('message' => 'Hello from custom API');
}

// Custom payment gateway
add_filter('woocommerce_payment_gateways', 'add_custom_gateway_class');
function add_custom_gateway_class($gateways)
{
    $gateways[] = 'WC_Gateway_Custom';
    return $gateways;
}

if (!class_exists('WC_Gateway_Custom') && class_exists('WC_Payment_Gateway')) {
    class WC_Gateway_Custom extends WC_Payment_Gateway
    {

        public function __construct()
        {
            $this->id                 = 'custom_gateway';
            $this->method_title       = 'Custom Gateway';
            $this->method_description = 'Offline or placeholder gateway.';
            $this->has_fields         = false;

            $this->init_form_fields();
            $this->init_settings();

            $this->title        = $this->get_option('title');
            $this->description  = $this->get_option('description');

            add_action('woocommerce_update_options_payment_gateways_' . $this->id, [$this, 'process_admin_options']);
        }

        public function init_form_fields()
        {
            $this->form_fields = [
                'enabled' => [
                    'title'       => 'Enable/Disable',
                    'label'       => 'Enable Custom Gateway',
                    'type'        => 'checkbox',
                    'default'     => 'yes',
                ],
                'title' => [
                    'title'       => 'Title',
                    'type'        => 'text',
                    'default'     => 'Custom Payment',
                ],
                'description' => [
                    'title'       => 'Customer Message',
                    'type'        => 'textarea',
                    'default'     => 'Pay with this method.',
                ],
            ];
        }

        public function process_payment($order_id)
        {
            $order = wc_get_order($order_id);

            // Mark as on-hold (or processing/complete if needed)
            $order->update_status('on-hold', 'Waiting for manual payment');

            // Reduce stock
            wc_reduce_stock_levels($order_id);

            // Remove cart
            WC()->cart->empty_cart();

            // Return thankyou redirect
            return [
                'result'   => 'success',
                'redirect' => $order->get_checkout_order_received_url(),
            ];
        }
    }
}

// Add Estimated time in emails
add_action('woocommerce_order_item_meta_end', function($item_id, $item, $order) {
    $week = $item->get_meta('_container_week');
    if ($week) {
        echo '<p><strong>' . __('Estimated Delivery Week:', 'astra-child') . '</strong> ' . esc_html($week) . '</p>';
    }
}, 10, 3);