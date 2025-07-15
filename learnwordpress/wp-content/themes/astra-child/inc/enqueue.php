<?php

// Enqueue custom JS for quantity box
add_action('wp_enqueue_scripts', function () {
    if (is_cart()) {
        wp_enqueue_script(
            'astra-child-qty',
            get_stylesheet_directory_uri() . '/assets/js/qty-box.js',
            array('jquery'),
            '1.0.0',
            true
        );
    }
});

// Enqueue admin JS
add_action('admin_enqueue_scripts', function ($hook) {
    // Load only on your stock pages
    if (isset($_GET['page']) && strpos($hook, 'stock') !== false) {
        wp_enqueue_script('stock-admin-js', get_stylesheet_directory_uri() . '/assets/js/stock-admin.js', ['jquery'], '1.0', true);

        // Send PHP data (like ajax_url or nonce) to JavaScript.
        wp_localize_script('stock-admin-js', 'stock_ajax', [
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce'    => wp_create_nonce('stock_admin_nonce')
        ]);
    }
});

// Enqueue product JS
add_action('admin_enqueue_scripts', function ($hook) {
    if (isset($_GET['page']) && strpos($hook, 'stock') !== false) {
        wp_enqueue_script('stock-container-detail-js', get_stylesheet_directory_uri() . '/assets/js/stock-container-detail.js', ['jquery'], '1.0', true);

        wp_localize_script('stock-container-detail-js', 'stock_container_ajax', [
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce'    => wp_create_nonce('stock_container_nonce')
        ]);
    }
});

// Enqueue manual JS
add_action('admin_enqueue_scripts', function ($hook) {
    if (isset($_GET['page']) && strpos($hook, 'stock') !== false) {
        wp_enqueue_script('stock-manual-js', get_stylesheet_directory_uri() . '/assets/js/stock-manual.js', ['jquery'], '1.0', true);

        wp_localize_script('stock-manual-js', 'stock_manual_ajax', [
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce'    => wp_create_nonce('stock_manual_nonce')
        ]);
    }
});

// Enqueue single product JS and pass product ID to JS
add_action('wp_enqueue_scripts', function () {
    if (is_product()) {
        global $product;

        // Fallback to get_the_ID if $product is not set
        $product_id = ($product instanceof WC_Product) ? $product->get_id() : get_the_ID();

        wp_enqueue_script('jquery');

        wp_enqueue_script('stock-single-product-js', get_stylesheet_directory_uri() . '/assets/js/stock-single-product.js', ['jquery'], '1.0', true);

        wp_localize_script('stock-single-product-js', 'stock_product_ajax', [
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce'    => wp_create_nonce('stock_product_nonce'),
            'product_id' => $product_id,
        ]);
    }
});

// Enqueue cart JS and pass AJAX data to JS
add_action('wp_enqueue_scripts', function () {
    if (is_cart()) {

        wp_enqueue_script('jquery');

        wp_enqueue_script('stock-cart-js', get_stylesheet_directory_uri() . '/assets/js/stock-cart.js', ['jquery'], '1.0', true);

        wp_localize_script('stock-cart-js', 'stock_cart_ajax', [
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce'    => wp_create_nonce('stock_cart_nonce'),
        ]);
    }
});