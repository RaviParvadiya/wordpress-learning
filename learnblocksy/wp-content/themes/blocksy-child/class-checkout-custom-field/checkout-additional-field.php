<?php

/**
 * 
 * For Checkout class page only
 * 
 */

add_filter('woocommerce_checkout_fields', 'custom_fee_field_to_checkout');
function custom_fee_field_to_checkout($fields)
{
	$fields['billing']['billing_fee'] = array(
		'type' => 'checkbox',
		'label' => __('Add applicable fee ₹100'),
		'required' => false,
		'priority' => 120,
	);
	return $fields;
}

add_action('wp_footer', function () {
	if (is_checkout()) : ?>
		<script type="text/javascript">
			jQuery(function($) {
				$('form.checkout').on('change', 'input[name="billing_fee"]', function() {
					console.log("change detected")
					$('body').trigger('update_checkout');
				});
			});
		</script>
<?php endif;
});

// 1. Save checkbox state in WooCommerce session
add_action('woocommerce_checkout_update_order_review', function ($post_data) {
	parse_str($post_data, $output);
	$fee_checked = isset($output['billing_fee']) && $output['billing_fee'] === '1';
	WC()->session->set('custom_fee_checked', $fee_checked);
});

// 2. Add fee if session says checked
add_action('woocommerce_cart_calculate_fees', function ($cart) {
	if (is_admin() && !defined('DOING_AJAX')) return;
	$fee_checked = WC()->session->get('custom_fee_checked');
	if ($fee_checked) {
		$cart->add_fee('Custom Fee', 100);
	}
});

// 3. Set checkbox state on page load using JS
add_action('woocommerce_after_checkout_form', function () {
	if (is_checkout()) {
		$checked = WC()->session->get('custom_fee_checked') ? 'true' : 'false';
		echo "<script type=\"text/javascript\">
		jQuery(function($) {
			var checked = $checked;
			var cb = $('input[name=\\'billing_fee\\']');
			if (checked === 'true') cb.prop('checked', true);
			else cb.prop('checked', false);
			cb.on('change', function() {
				$(this).val(this.checked ? '1' : '');
				$('body').trigger('update_checkout');
			});
		});
		</script>";
	}
});