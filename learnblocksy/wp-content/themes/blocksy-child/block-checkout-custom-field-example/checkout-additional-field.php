<?php

/**
 * 
 * For Checkout block page only
 * 
 */

# https://developer.woocommerce.com/docs/block-development/cart-and-checkout-blocks/additional-checkout-fields/
# https://developer.woocommerce.com/docs/block-development/cart-and-checkout-blocks/how-to-additional-checkout-fields-guide/

// Add custom field to the checkout block page
// Rendering a text field
add_action(
    'woocommerce_init',
    function () {
        woocommerce_register_additional_checkout_field(
            array(
                'id'            => 'namespace/gov-id',
                'label'         => 'Government ID',
                'optionalLabel' => 'Government ID (optional)',
                'location'      => 'address',
                'required'      => true,
                'attributes'    => array(
                    'autocomplete'     => 'government-id',
                    'aria-describedby' => 'some-element',
                    'aria-label'       => 'custom aria label',
                    'pattern'          => '[A-Z0-9]{5}', // A 5-character string of capital letters and numbers.
                    'title'            => 'Title to show on hover',
                    'data-custom'      => 'custom data',
                ),
            ),
        );

        woocommerce_register_additional_checkout_field(
            array(
                'id'            => 'namespace/confirm-gov-id',
                'label'         => 'Confirm government ID',
                'location'      => 'address',
                'required'      => true,
                'attributes'    => array(
                    'autocomplete' => 'government-id',
                    'pattern'      => '[A-Z0-9]{5}', // A 5-character string of capital letters and numbers.
                    'title'        => 'Confirm your 5-digit Government ID',
                ),
            ),
        );
    }
);

// Rendering a checkbox field
add_action(
    'woocommerce_init',
    function () {
        woocommerce_register_additional_checkout_field(
            array(
                'id'       => 'namespace/marketing-opt-in',
                'label'    => 'Do you want to subscribe to our newsletter?',
                'location' => 'address', // to specify where to place
                'type'     => 'checkbox',
            )
        );
    }
);

// Ex of sanitization of custom field
add_action(
    'woocommerce_sanitize_additional_field',
    function ($field_value, $field_key) {
        if ('namespace/gov-id' === $field_key) {
            $field_value = str_replace(' ', '', $field_value);
            $field_value = strtoupper($field_value);
        }
        return $field_value;
    },
    10,
    2
);

// Ex of validion 
add_action(
    'woocommerce_validate_additional_field',
    function (WP_Error $errors, $field_key, $field_value) {
        if ('namespace/gov-id' === $field_key) {
            $match = preg_match('/[A-Z0-9]{5}/', $field_value);
            if (0 === $match || false === $match) {
                $errors->add('invalid_gov_id', 'Please ensure your government ID matches the correct format.');
            }
        }
        return $errors;
    },
    10,
    3
);

// Ex of location validation for multiple fields locations [address, contact,other, order maybe]
add_action(
    'woocommerce_blocks_validate_location_address_fields',
    function (\WP_Error $errors, $fields, $group) {
        if ($fields['namespace/gov-id'] !== $fields['namespace/confirm-gov-id']) {
            $errors->add('gov_id_mismatch', 'Please ensure your government ID matches the confirmation.');
        }
    },
    10,
    3
);

// After checkout accessing field values
use Automattic\WooCommerce\Blocks\Package;
use Automattic\WooCommerce\Blocks\Domain\Services\CheckoutFields;

$checkout_fields = Package::container()->get(CheckoutFields::class);
$order = wc_get_order($order_id);

// Get a specific field value
$business_email = $checkout_fields->get_field_from_object(
    'my-plugin/business-email',
    $order,
    'other' // Use 'billing' or 'shipping' for address fields
);

// Get all additional fields
$all_fields = $checkout_fields->get_all_fields_from_object($order, 'other');

// React to saving fields
add_action(
    'woocommerce_set_additional_field_value',
    function ($key, $value, $group, $wc_object) {
        if ('my-plugin-namespace/address-field' !== $key) {
            return;
        }

        if ('billing' === $group) {
            $my_plugin_address_key = 'existing_billing_address_field_key';
        } else {
            $my_plugin_address_key = 'existing_shipping_address_field_key';
        }

        $wc_object->update_meta_data($my_plugin_address_key, $value, true);
    },
    10,
    4
);

add_action(
    'woocommerce_set_additional_field_value',
    function ($key, $value, $group, $wc_object) {
        if ('my-plugin-namespace/my-other-field' !== $key) {
            return;
        }

        $my_plugin_key = 'existing_order_field_key';

        $wc_object->update_meta_data($my_plugin_key, $value, true);
    },
    10,
    4
);

// React to reading fields
add_filter(
    "woocommerce_get_default_value_for_my-plugin-namespace/address-field",
    function ($value, $group, $wc_object) {

        if ('billing' === $group) {
            $my_plugin_key = 'existing_billing_address_field_key';
        } else {
            $my_plugin_key = 'existing_shipping_address_field_key';
        }

        return $wc_object->get_meta($my_plugin_key);
    },
    10,
    3
);

add_filter(
    "woocommerce_get_default_value_for_my-plugin-namespace/my-other-field",
    function ($value, $group, $wc_object) {

        $my_plugin_key = 'existing_order_field_key';

        return $wc_object->get_meta($my_plugin_key);
    },
    10,
    3
);

/**
 * 
 * How to add additional conditional fields in checkout
 * It uses JSON schema
 * Example:
 * 
 */

add_action('woocommerce_init', function () {
    if (! function_exists('woocommerce_register_additional_checkout_field')) {
        return;
    }

    // Delivery preference - only for physical products
    woocommerce_register_additional_checkout_field(
        array(
            'id'       => 'my-store/delivery-preference',
            'label'    => __('Delivery preference', 'your-text-domain'),
            'location' => 'order',
            'type'     => 'select',
            'options'  => array(
                array('value' => 'doorstep', 'label' => __('Leave at doorstep', 'your-text-domain')),
                array('value' => 'neighbor', 'label' => __('Leave with neighbor', 'your-text-domain')),
                array('value' => 'pickup_point', 'label' => __('Delivery to pickup point', 'your-text-domain')),
            ),
            'required' => [
                'cart' => [
                    'properties' => [
                        'needs_shipping' => [
                            'const' => true
                        ]
                    ]
                ]
            ],
            'hidden' => [
                'cart' => [
                    'properties' => [
                        'needs_shipping' => [
                            'const' => false
                        ]
                    ]
                ]
            ]
        )
    );

    // Delivery instructions - only when 'doorstep' is selected
    woocommerce_register_additional_checkout_field(
        array(
            'id'       => 'my-store/doorstep-instructions',
            'label'    => __('Specific doorstep delivery instructions', 'your-text-domain'),
            'location' => 'order',
            'type'     => 'text',
            'required' => [
                'checkout' => [
                    'properties' => [
                        'additional_fields' => [
                            'properties' => [
                                'my-store/delivery-preference' => [
                                    'const' => 'doorstep'
                                ]
                            ]
                        ]
                    ]
                ]
            ],
            'hidden' => [
                'checkout' => [
                    'properties' => [
                        'additional_fields' => [
                            'properties' => [
                                'my-store/delivery-preference' => [
                                    'not' => [
                                        'const' => 'doorstep'
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        )
    );

    // Digital delivery email - only for digital products
    woocommerce_register_additional_checkout_field(
        array(
            'id'       => 'my-store/digital-delivery-email',
            'label'    => __('Alternative email for digital products', 'your-text-domain'),
            'location' => 'contact',
            'type'     => 'text',
            'required' => [
                'cart' => [
                    'properties' => [
                        'needs_shipping' => [
                            'const' => false
                        ]
                    ]
                ]
            ],
            'hidden' => [
                'cart' => [
                    'properties' => [
                        'needs_shipping' => [
                            'const' => true
                        ]
                    ]
                ]
            ],
            'sanitize_callback' => function ($field_value) {
                return sanitize_email($field_value);
            },
            'validate_callback' => function ($field_value) {
                if (! is_email($field_value)) {
                    return new \WP_Error('invalid_alt_email', __('Please ensure your alternative email matches the correct format.', 'your-text-domain'));
                }
            },
        )
    );
});
