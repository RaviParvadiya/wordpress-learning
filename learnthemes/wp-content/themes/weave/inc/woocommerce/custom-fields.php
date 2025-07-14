<?php

// Add Design Number field to Product Data > General tab
add_action('woocommerce_product_options_general_product_data', function () {
    global $post;

    woocommerce_wp_text_input([
        'id' => 'design_number_field',
        'label' => __('Design Number', 'weave'),
        'desc_tip' => 'true',
        'description' => __('Enter the design number for this product.', 'weave'),
        'type' => 'number',
        'custom_attributes' => [
            'required' => 'required',
            'min' => '0',
        ],
        'value' => get_post_meta($post->ID, 'design_number', true) ?: '',
    ]);
});

// Save the Design Number field value
add_action('woocommerce_process_product_meta', function ($post_id) {
    if (isset($_POST['design_number_field'])) {
        update_post_meta($post_id, 'design_number', intval($_POST['design_number_field']));
    }
});

// Add Section Type field
add_action('woocommerce_product_options_general_product_data', function () {
    // Get all assigned ranks
    $args = [
        'post_type' => 'product',
        'posts_per_page' => -1,
        'post_status' => 'publish',
        'meta_query' => [
            [
                'key' => 'section_type',
                'value' => 'todays_best',
            ],
        ],
    ];

    /**
     * Note:
     * WP_Queries or database queries is likely to break your code in future versions of WooCommerce as data moves towards custom tables for better performance.
     * https://github.com/woocommerce/woocommerce/wiki/wc_get_products-and-WC_Product_Query
     */
    $query = new WP_Query($args);
    $assigned_ranks = [];
    if ($query->have_posts()) {
        foreach ($query->posts as $product) {
            $rank = get_post_meta($product->ID, 'todays_best_rank', true);
            if ($rank) {
                $assigned_ranks[] = $rank;
            }
        }
    }
    wp_reset_postdata();

    // Get current product's rank (for editing)
    global $post;
    $current_rank = get_post_meta($post->ID, 'todays_best_rank', true);

    // Build options: always allow current product's rank if editing
    $rank_options = ['' => __('Select Rank', 'weave')];
    for ($i = 1; $i <= 5; $i++) {
        if (!in_array((string)$i, $assigned_ranks) || $current_rank == $i) {
            $rank_options[(string)$i] = (string)$i;
        }
    }

    // Select Section Type
    woocommerce_wp_select([
        'id' => 'section_type_field',
        'label' => __('Section Type', 'weave'),
        'options' => [
            '' => __('Select Section', 'weave'),
            'regular' => __('Regular', 'weave'),
            'favorites' => __('Favorites', 'weave'),
            'todays_best' => __("Today's Best", 'weave'),
        ],
        'desc_tip' => true,
        'description' => __('Choose the section type for this product.', 'weave'),
        'custom_attributes' => [
            'required' => 'required',
        ],
        'value' => get_post_meta($post->ID, 'section_type', true) ?: 'regular', // Default to 'regular'
    ]);

    // If 'Today's Best' selected show 'Select Ranking'
    woocommerce_wp_select([
        'id' => 'todays_best_rank_field',
        'label' => __("Today's Best Rank", 'weave'),
        'options' => $rank_options,
        'desc_tip' => true,
        'description' => __("Select the rank if 'Today's Best' is chosen.", 'weave'),
        'custom_attributes' => [
            'required' => 'required',
        ],
    ]);

?>
    <script>
        jQuery(function($) {
            // Disable the label option
            $('#section_type_field option[value=""]').attr('disabled', 'disabled');
            $('#todays_best_rank_field option[value=""]').attr('disabled', 'disabled');

            function toggleRankField() {
                if ($('#section_type_field').val() === 'todays_best') {
                    $('#todays_best_rank_field').closest('p.form-field').show();
                    $('#todays_best_rank_field').attr('required', 'required');
                } else {
                    $('#todays_best_rank_field').closest('p.form-field').hide();
                    $('#todays_best_rank_field').removeAttr('required');
                    $('#todays_best_rank_field').val(''); // Optionally clear value
                }
            }
            toggleRankField();
            $('#section_type_field').on('change', toggleRankField);
        });
    </script>
<?php
});

// Save both select fields
add_action('woocommerce_process_product_meta', function ($post_id) {
    if (isset($_POST['section_type_field'])) {
        update_post_meta($post_id, 'section_type', sanitize_text_field($_POST['section_type_field']));
    }

    if (isset($_POST['todays_best_rank_field'])) {
        update_post_meta($post_id, 'todays_best_rank', intval($_POST['todays_best_rank_field']));
    }
});

add_action('woocommerce_admin_process_product_object', function ($product) {
    // Design Number required
    if (empty($_POST['design_number_field'])) {
        wc_add_notice(__('Design Number is required.', 'weave'), 'error');
    }

    // Section Type required
    if (empty($_POST['section_type_field'])) {
        wc_add_notice(__('Section Type is required.', 'weave'), 'error');
    }

    // If "Today's Best" is selected, rank is required
    if (
        isset($_POST['section_type_field']) &&
        $_POST['section_type_field'] === 'todays_best' &&
        empty($_POST['todays_best_rank_field'])
    ) {
        wc_add_notice(__("Today's Best Rank is required when 'Today's Best' is selected.", 'weave'), 'error');
    }
});
