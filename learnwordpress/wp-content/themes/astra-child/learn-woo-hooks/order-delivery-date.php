<?php

/**
 * Custom WooCommerce hooks:
 * - Delivery date field on checkout
 * - Admin notice after order
 * - Show delivery date in admin & frontend
 */

// 1. Set a flag in user session or option after order placed
// This hook runs after a WooCommerce order is placed (on the Thank You page).
// It sets a custom option in the database to trigger an admin notice.
add_action('woocommerce_thankyou', 'set_custom_order_notice_flag');
function set_custom_order_notice_flag($order_id)
{
    if (!is_admin()) {
        update_option('custom_order_admin_notice', 'Order #' . $order_id . ' placed. Check for notes.');
    }
}

// 2. Show notice in WP admin dashboard
// This hook displays a dismissible admin notice in the WordPress dashboard if the custom option is set.
// After displaying, it deletes the option so the notice only appears once per order.
add_action('admin_notices', 'show_custom_order_admin_notice');
function show_custom_order_admin_notice()
{
    if ($message = get_option('custom_order_admin_notice')) {
        echo '<div class="notice notice-warning is-dismissible">';
        echo '<p><strong>WooCommerce Alert:</strong> ' . esc_html($message) . '</p>';
        echo '</div>';
        delete_option('custom_order_admin_notice'); // Remove after showing
    }
}

// 3. Add custom delivery date field to WC checkout form
// This filter adds a new date field to the billing section of the checkout page.
add_filter('woocommerce_checkout_fields', 'custom_add_date_field_to_checkout');
function custom_add_date_field_to_checkout($fields)
{
    $fields['billing']['billing_delivery_date'] = array(
        'type'        => 'date',
        'label'       => __('Select a delivery date'),
        'required'    => false,
        'class'       => array('form-row-wide'),
        'priority'    => 120,
    );
    return $fields;
}

// 4. Save the custom delivery date field value to order meta
// This action saves the delivery date from the checkout form to the order's post meta.
add_action('woocommerce_checkout_update_order_meta', 'save_delivery_date_fields_to_order');
function save_delivery_date_fields_to_order($order_id)
{
    // Save billing field just in case Woo skips (redundant but safe)
    if (!empty($_POST['billing_delivery_date'])) {
        update_post_meta($order_id, '_billing_delivery_date', sanitize_text_field($_POST['billing_delivery_date']));
    }
}

// 'woocommerce_admin_order_data_after_order_details' - for after orrder data
// 5. Show delivery date in WC admin order details
// This action displays the delivery date in the admin order edit screen under the billing address.
add_action('woocommerce_admin_order_data_after_billing_address', 'show_delivery_date_in_admin');
function show_delivery_date_in_admin($order)
{
    $date = get_post_meta($order->get_id(), '_billing_delivery_date', true);
    if ($date) {
        echo '<p><strong>Billing Date:</strong> ' . esc_html($date) . '</p>';
    }
}

// 6. Show delivery date on the order Thank You page for customers
add_action('woocommerce_order_details_after_order_table', 'show_delivery_date_in_order_details', 10);
function show_delivery_date_in_order_details($order)
{
    $billing_delivery_date = $order->get_meta('_billing_delivery_date');
    if ($billing_delivery_date) {
        echo '<p><strong>Delivery Date:</strong> ' . esc_html($billing_delivery_date) . '</p>';
    }
}
