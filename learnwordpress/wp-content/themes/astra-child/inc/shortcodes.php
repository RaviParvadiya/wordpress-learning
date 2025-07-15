<?php

// Combined Product Search & Category Filter Shortcode
add_shortcode('custom_product_searchbar', 'custom_product_searchbar_shortcode');
function custom_product_searchbar_shortcode()
{
    ob_start();
    $terms = get_terms('product_cat');
?>
    <form role="search" method="get" action="<?php echo esc_url(home_url('/')); ?>" class="custom-shop-bar-full">
        <div class="custom-searchbar-flex">
            <input type="search" name="s" placeholder="Search products…" value="<?php echo esc_attr(get_search_query()); ?>" class="custom-searchbar-input" />
            <select name="product_cat" class="custom-searchbar-select">
                <option value="" selected>All Categories</option>
                <?php if (!empty($terms) && !is_wp_error($terms)) :
                    foreach ($terms as $term) : ?>
                        <option value="<?php echo esc_attr($term->slug); ?>" <?php selected(isset($_GET['product_cat']) && $_GET['product_cat'] === $term->slug); ?>><?php echo esc_html($term->name); ?></option>
                <?php endforeach;
                endif; ?>
            </select>
            <button type="submit" class="custom-searchbar-btn">Search</button>
            <input type="hidden" name="post_type" value="product" />
        </div>
    </form>
<?php
    return ob_get_clean();
}

// customised shop loop
add_shortcode('custom_shop_loop', 'custom_shop_loop_shortcode');
function custom_shop_loop_shortcode($atts)
{
    ob_start();

    $args = array(
        'post_type'      => 'product',
        'posts_per_page' => 12,
        'paged'          => max(1, get_query_var('paged') ? get_query_var('paged') : get_query_var('page'))
    );

    $loop = new WP_Query($args);

    if ($loop->have_posts()) {
        echo '<ul class="products columns-4">'; // Use the WooCommerce default class

        while ($loop->have_posts()) {
            $loop->the_post();
            global $product;

            // Start the <li> with WooCommerce-like classes
            $classes = wc_get_product_class('ast-grid-common-col ast-full-width ast-article-post');
            echo '<li class="' . esc_attr(implode(' ', $classes)) . '">';

            // Product thumbnail & link
            echo '<div class="astra-shop-thumbnail-wrap">';
            echo '<a href="' . esc_url(get_permalink()) . '" class="woocommerce-LoopProduct-link woocommerce-loop-product__link">';
            echo woocommerce_get_product_thumbnail();
            echo '</a>';

            // If product is on sale, show badge
            if ($product->is_on_sale()) {
                echo '<span class="ast-on-card-button ast-onsale-card" data-notification="default">Sale!</span>';
            }

            // Show "Add to cart" icon if simple product and purchasable
            if ($product->is_type('simple') && $product->is_purchasable()) {
                echo '<a href="' . esc_url('?add-to-cart=' . $product->get_id()) . '" 
                            data-quantity="1" 
                            class="ast-on-card-button ast-select-options-trigger product_type_simple add_to_cart_button ajax_add_to_cart" 
                            data-product_id="' . esc_attr($product->get_id()) . '" 
                            data-product_sku="' . esc_attr($product->get_sku()) . '" 
                            aria-label="' . esc_attr(sprintf(__('Add to cart: “%s”', 'woocommerce'), $product->get_name())) . '" 
                            rel="nofollow">
                            <span class="ast-card-action-tooltip">Add to cart</span>
                            <span class="ahfb-svg-iconset">
                                <span class="ast-icon icon-bag">
                                    ' . file_get_contents(get_stylesheet_directory() . '/assets/icons/bag.svg') . '
                                </span>
                            </span>
                        </a>';
            }

            echo '</div>';

            // Summary content
            echo '<div class="astra-shop-summary-wrap">';

            // Category
            $terms = get_the_terms(get_the_ID(), 'product_cat');
            if ($terms && !is_wp_error($terms)) {
                $term = array_shift($terms);
                echo '<span class="ast-woo-product-category">' . esc_html($term->name) . '</span>';
            }

            // Title
            echo '<a href="' . esc_url(get_permalink()) . '" class="ast-loop-product__link">';
            echo '<h2 class="woocommerce-loop-product__title">' . get_the_title() . '</h2>';
            echo '</a>';

            // Rating
            echo wc_get_rating_html($product->get_average_rating());

            // Price
            echo '<span class="price">' . $product->get_price_html() . '</span>';

            // Add to cart button
            woocommerce_template_loop_add_to_cart();

            echo '</div>';

            echo '</li>';
        }

        echo '</ul>';
        $total_pages = $loop->max_num_pages;
        $current_page = max(1, get_query_var('paged'));

        if ($total_pages > 1) {
            echo '<div class="custom-pagination">';

            if ($current_page > 1) {
                echo '<a class="prev-page" href="' . get_pagenum_link($current_page - 1) . '">← Previous</a>';
            }

            for ($i = 1; $i <= $total_pages; $i++) {
                if ($i == $current_page) {
                    echo '<span class="current-page">' . $i . '</span>';
                } else {
                    echo '<a class="page-num" href="' . get_pagenum_link($i) . '">' . $i . '</a>';
                }
            }

            if ($current_page < $total_pages) {
                echo '<a class="next-page" href="' . get_pagenum_link($current_page + 1) . '">Next →</a>';
            }

            echo '</div>';
        }
    } else {
        echo '<p>No products found</p>';
    }

    wp_reset_postdata();
    return ob_get_clean();
}

add_shortcode('custom_cart_page', 'custom_cart_page_shortcode');
function custom_cart_page_shortcode()
{
    if (!is_user_logged_in()) {
        return '<p class="woocommerce-info">You must be logged in to view your cart.</p>';
    }

    ob_start();

    // Ensure WooCommerce cart is loaded
    if (function_exists('WC')) {
        $cart = WC()->cart;
        if ($cart && $cart->get_cart_contents_count() > 0) {
            echo '<div class="custom-cart-wrapper">';
            echo '<form class="woocommerce-cart-form" method="post" action="' . esc_url(wc_get_cart_url()) . '">';
            echo '<h2>Your Cart</h2>';
            echo '<table class="shop_table shop_table_responsive cart woocommerce-cart-form__contents" cellspacing="0">';
            echo '<thead><tr>';
            echo '<th class="product-thumbnail">&nbsp;</th>';
            echo '<th class="product-name">Product</th>';
            echo '<th class="product-price">Price</th>';
            echo '<th class="product-quantity">Quantity</th>';
            echo '<th class="product-remove">Remove</th>';
            echo '</tr></thead><tbody>';

            foreach ($cart->get_cart() as $cart_item_key => $cart_item) {
                $product = $cart_item['data'];
                $product_id = $cart_item['product_id'];
                $product_permalink = get_permalink($product_id);
                $thumbnail = $product->get_image(array(60, 60));
                $name = $product->get_name();
                $price = wc_price($product->get_price());
                $quantity = $cart_item['quantity'];
                $subtotal = wc_price($cart_item['line_subtotal']);
                echo '<tr class="woocommerce-cart-form__cart-item">';
                echo '<td class="product-thumbnail"><a href="' . esc_url($product_permalink) . '">' . $thumbnail . '</a></td>';
                echo '<td class="product-name"><a href="' . esc_url($product_permalink) . '">' . esc_html($name) . '</a></td>';
                echo '<td class="product-price">' . $price . '</td>';
                echo '<td class="product-quantity">';
                echo '<div class="custom-quantity-box">';
                echo '<button type="button" class="qty-btn minus">-</button>';
                echo '<input type="number" name="cart[' . esc_attr($cart_item_key) . '][qty]" value="' . esc_attr($quantity) . '" min="1" />';
                echo '<button type="button" class="qty-btn plus">+</button>';
                echo '</div>';
                echo '</td>';
                echo '<td class="product-remove"><a title="Remove this item" href="' . esc_url(wc_get_cart_remove_url($cart_item_key)) . '" class="remove remove_from_cart_button" aria-label="Remove this item">&times;</a></td>';
                echo '</tr>';
            }

            echo '</tbody></table>';

            // Update Cart
            echo '<div class="update-cart-button">';
            echo '<button type="submit" class="button" name="update_cart" value="1">Update Cart</button>';
            echo wp_nonce_field('woocommerce-cart', '_wpnonce', true, false);
            echo '</div>';

            // Cart totals
            echo '<div class="cart-totals">';
            echo "<br />";
            echo '<h3>Cart Totals</h3>';
            echo '<table class="shop_table shop_table_responsive">';
            echo '<tr><th>Subtotal</th><td>' . wc_price($cart->get_subtotal()) . '</td></tr>';
            echo '<tr><th>Total</th><td>' . wc_price($cart->get_total('edit')) . '</td></tr>';
            echo '</table>';
            echo '</div>';

            // Cart actions
            echo '<div class="cart-actions">';
            echo '<a href="' . esc_url(wc_get_checkout_url()) . '" class="button checkout">Proceed to Checkout</a> ';
            echo '</div>';
            echo '</form>';

            echo '<div class="custom-coupon-section">';
            echo do_shortcode('[apply_coupon]');
            echo '</div>';

            echo '</div>';
        } else {
            echo '<p class="woocommerce-info">Your cart is currently empty.</p>';
        }
    } else {
        echo '<p class="woocommerce-error">WooCommerce is not active.</p>';
    }
    return ob_get_clean();
}

add_shortcode('apply_coupon', 'apply_coupon_shortcode');
function apply_coupon_shortcode()
{
    if (!function_exists('WC')) {
        return '<p class="woocommerce-error">WooCommerce is not active.</p>';
    }

    // Process the coupon form if submitted
    if (isset($_POST['custom_coupon_code']) && wp_verify_nonce($_POST['apply_coupon_nonce'], 'apply_coupon_action')) {
        $coupon_code = sanitize_text_field($_POST['custom_coupon_code']);
        $result = WC()->cart->apply_coupon($coupon_code);

        if (is_wp_error($result)) {
            echo '<p class="woocommerce-error">' . esc_html($result) . '</p>';
        } else {
            wc_print_notice(__('Coupon applied successfully!', 'woocommerce'), 'success');
        }
    }

    ob_start();

?>
    <form method="post" class="custom-coupon-form">
        <p><label for="custom_coupon_code">Have a coupon?</label></p>
        <input type="text" name="custom_coupon_code" id="custom_coupon_code" placeholder="Enter coupon code" required />
        <button type="submit" class="button">Apply Coupon</button>
        <?php wp_nonce_field('apply_coupon_action', 'apply_coupon_nonce'); ?>
    </form>
<?php

    return ob_get_clean();
}
