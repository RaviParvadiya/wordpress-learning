<?php

if (! defined('WP_DEBUG')) {
	die('Direct access forbidden.');
}

add_action('wp_enqueue_scripts', function () {
	wp_enqueue_style('parent-style', get_template_directory_uri() . '/style.css');
});

// Register a custom checkbox field for the WooCommerce Checkout Block
add_action('woocommerce_init', function () {
	// Only proceed if the function exists (ensures compatibility with WooCommerce Blocks)
	if (! function_exists('woocommerce_register_additional_checkout_field')) {
		return;
	}

	// Register the additional checkbox field
	woocommerce_register_additional_checkout_field(
		array(
			'id' => 'my-namespace/add-fee', // Unique field ID
			'label' => __('Add applicable fee ₹100', 'blocksy'),
			'location' => 'order', // Show in the order section of the checkout block
			'type' => 'checkbox',
			'required' => false,
		)
	);
});

// Listen for updates to the checkout (when the block is updated/submitted)
add_action('woocommerce_store_api_checkout_update_order_from_request', function ($order, $request) {
	// Get all additional fields submitted from the checkout block
	$additional_fields = $request->get_param('additional_fields');

	error_log('REQUEST additional_fields: ' . print_r($additional_fields, true));

	// Store the checkbox value in the WooCommerce session for later use
	if (!empty($additional_fields['my-namespace/add-fee'])) {
		WC()->session->set('my_namespace_add_fee', true);
	} else {
		WC()->session->set('my_namespace_add_fee', false);
	}
}, 10, 2);

// Add a custom fee to the cart if the checkbox is checked
add_action('woocommerce_cart_calculate_fees', function ($cart) {
	// Only run on the frontend, not in admin or during AJAX requests
	if (is_admin() || (defined('DOING_AJAX') && DOING_AJAX)) return;

	// Retrieve the checkbox value from the session
	$value = WC()->session->get('my_namespace_add_fee');

	error_log('SESSION VALUE: ' . var_export($value, true));

	// If the checkbox was checked, add the custom fee
	if ($value) {
		$cart->add_fee(__('Custom Fee', 'blocksy'), 100.00);
	}
});

// After the order is created, clear the custom session value
// to prevent it from affecting future carts (especially if user reloads checkout).
// This does NOT affect the fee already saved into the order itself.
add_action('woocommerce_checkout_order_created', function () {
	WC()->session->__unset('my_namespace_add_fee');
});
