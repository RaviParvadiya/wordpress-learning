<?php

/* add_action('wp_enqueue_scripts', function() {
    // Try the most common handles for Hello Elementor
    wp_dequeue_style('hello-elementor');
    wp_dequeue_style('hello-elementor-theme-style');
    wp_dequeue_style('hello-elementor-header-footer');
    // Also dequeue the parent style.css if loaded by default
    wp_dequeue_style('parent-style');
    // You can also try:
    wp_dequeue_style('hello-elementor-style');
}, 20); */

add_action( 'wp_enqueue_scripts', function () {
    // Enqueue parent theme styles
    wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' );
} );

if ( class_exists( 'WooCommerce' ) ) {
    require_once get_stylesheet_directory() . '/inc/custom-fields.php';
}

add_action( 'after_setup_theme', function () {
    add_theme_support( 'woocommerce' );
} );

add_action( 'wp_enqueue_scripts', function () {
    if ( is_product() ) {
        // Ensure WooCommerce gallery scripts are loaded
        wp_enqueue_script( 'zoom' );
        wp_enqueue_script( 'photoswipe' );
        wp_enqueue_script( 'photoswipe-ui-default' );
        wp_enqueue_script( 'wc-single-product' );
        wp_enqueue_script( 'wc-add-to-cart-variation' ); // optional
    }
} );

add_action( 'woocommerce_after_shop_loop_item_title', function () {
    global $product;
    echo '<span class="product-design-number">';
    echo 'Design No. ' . get_post_meta( $product->get_id(), 'design_number', true );
    echo '</span>';
} );

add_action( 'woocommerce_after_shop_loop_item_title', function () {
    global $product;
    $brands = get_the_terms( $product->get_id(), 'product_brand' );
    if ( $brands && ! is_wp_error( $brands ) ) {
        echo '<span class="product-brand">' . esc_html( $brands[0]->name ) . '</span>';
    }
} );

add_action( 'woocommerce_widget_product_item_end', function ($args) {
    global $product;

    wp_enqueue_style( 'front-page	', get_stylesheet_directory_uri() . '/templates/front-page.css', array(), null );
    if ( ! $product ) return;

    // Get brand term
    $brands = get_the_terms( $product->get_id(), 'product_brand' );
    $brand_name = ! empty( $brands ) && ! is_wp_error( $brands ) ? $brands[ 0 ]->name : '';

    // Get prices
    $regular_price = $product->get_regular_price();
    $sale_price    = $product->get_sale_price();

    $design_number = get_post_meta( $product->get_id(), 'design_number', true );
    $todays_best_rank = get_post_meta( $product->get_id(), 'todays_best_rank', true );
    if ( ! empty( $todays_best_rank ) ) :
?>
        <span class="showcase-card__label-rect showcase-card__label-rect--best">
            <span class="showcase-card__label-text showcase-card__label-text--best">
                <?php echo esc_html( $todays_best_rank ); ?>
            </span>
        </span>
    <?php else : ?>
        <span class="showcase-card__label-rect">
            <span class="showcase-card__label-text">DESIGN No. <?php echo esc_html( $design_number ); ?></span>
        </span>
    <?php endif; ?>
    <div class="showcase-card__meta">
        <span class="showcase-card__brand">
            <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/color-palette.png" alt="Palette" class="showcase-card__brand-icon">
            <span class="showcase-card__brand-label">
                <?php
                $brands = get_the_terms( $product->get_id(), 'product_brand' );
                if ( $brands && ! is_wp_error( $brands ) ) {
                    echo esc_html( $brands[ 0 ]->name );
                }
                ?>
            </span>
        </span>
        <div class="showcase-card__price-group">
            <span class="showcase-card__price showcase-card__price--text">$
                <span>
                    <?php echo number_format( ( float )$product->get_regular_price(), 2 ); ?>
                </span>
            </span>
            <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/down_1.png" alt="Down" class="showcase-card__price-arrow">
            <?php if ( $product->is_on_sale() ) : ?>
                <span class="showcase-card__price-discount">
                    <?php echo number_format( ( float )$product->get_sale_price(), 2 ); ?>
                </span>
            <?php endif; ?>
        </div>
    </div>

<?php
} );

remove_all_actions( 'woocommerce_before_single_product_summary' );

add_action( 'wp_enqueue_scripts', function () {
    // Enqueue parent theme styles
    wp_enqueue_style( 'design-order', get_stylesheet_directory_uri() . '/templates/design-order.css', array(), null );
    wp_enqueue_style( 'create-fabric', get_stylesheet_directory_uri() . '/templates/create-fabric.css', array(), null );
    wp_enqueue_style( 'design-order-responsive', get_stylesheet_directory_uri() . '/templates/design-order-responsive.css', array( 'design-order' ), null );
}, 99 );

// Enqueue custom styles for this template
define( 'HELLO_CHILD_TEMPLATE_URI', get_stylesheet_directory_uri() . '/assets/images' );


add_action( 'woocommerce_before_single_product_summary', function () {

?>
    <div class="order-section">
        <div class="container order-section__container">
            <div class="order-section__notice">
                <p>The image below is the actual output size and layout. If it is not the size you want, please use the "Your Design Size" below.</p>
            </div>
            <div class="order-section__grid">
                <div class="order-section__left">
                    <!-- Product Image -->
                    <?php woocommerce_show_product_sale_flash(); ?>
                    <?php woocommerce_show_product_images(); ?>
                </div>
                <div class="order-section__right">
                    <!-- Product Info -->
                    <div class="order-section__right-info">
                        <p><?php _e( 'Choose Fabric', 'hello-elementor-child' ); ?></p>
                        <span>
                            <img src="<?php echo HELLO_CHILD_TEMPLATE_URI; ?>/info.png" alt="" width="13" height="13">
                            <p class="order-section__right-info--text">Fabric Information</p>
                        </span>
                    </div>
                    <!-- Example: You can use ACF or product attributes for fabric options -->
                    <div class="order-section__right-options">
                        <div class="order-section__right-row">
                            <?php /* Example static options, replace with dynamic if needed */ ?>
                            <div class="col">
                                <div class="order-section__right-options-card order-section__right-options-card--active">
                                    <img src="<?php echo HELLO_CHILD_TEMPLATE_URI; ?>/cloud.png" alt="Cloud" width="40" height="23.44">
                                    <p>Cotton</p>
                                    <h5>100% cotton</h5>
                                </div>
                            </div>
                            <div class="col">
                                <div class="order-section__right-options-card">
                                    <img src="<?php echo HELLO_CHILD_TEMPLATE_URI; ?>/hash.png" alt="Polyester" width="25" height="25">
                                    <p>Polyester</p>
                                    <h5>100% Polyester</h5>
                                </div>
                            </div>
                            <div class="col">
                                <div class="order-section__right-options-card">
                                    <img src="<?php echo HELLO_CHILD_TEMPLATE_URI; ?>/silk.png" alt="Silk" width="25" height="25">
                                    <p>Silk</p>
                                    <h5>100% Natural Silk</h5>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="order-section__right-dropdown">
                        <div class="custom-dropdown">
                            <span class="custom-dropdown__label">Plain Weave</span>
                            <img class="custom-dropdown__arrow" src="<?php echo HELLO_CHILD_TEMPLATE_URI; ?>/down_1.png" alt="Dropdown arrow">
                        </div>
                    </div>
                    <div class="order-section__right-details">
                        <h2 class="order-section__details-title">Selected Fabric</h2>
                        <div class="order-section__details-card">
                            <img src="<?php echo HELLO_CHILD_TEMPLATE_URI; ?>/untitled_2.jpg" alt="" width="120" height="120">
                            <div class="order-section__card-content">
                                <h3 style="margin: 0; margin-top: 15px"><?php the_title(); ?> Plain Weave</h3>
                                <p style="margin-bottom: 0;">Everyday generic cotton fabric</p>
                                <p style="margin: 0;">Usage: Apparel, Home textile, Soft Toy</p>
                                <p style="margin-bottom: 0;">(w:43 X h:36)</p>
                            </div>
                            <a href="#" class="order-section__card-btn">Detail</a>
                        </div>
                    </div>
                    <div class="order-section__right-info order-section__right-info--qty">
                        <p><?php _e( 'Choose Quantity', 'hello-elementor-child' ); ?></p>
                        <span>
                            <img src="<?php echo HELLO_CHILD_TEMPLATE_URI; ?>/info.png" alt="" width="13" height="13">
                            <p class="order-section__right-info--text">Price Chart</p>
                        </span>
                    </div>
                    <!-- Example: Replace with dynamic product variations if needed -->
                    <label class="custom-checkbox custom-checkbox--qty" for="check-fat">
                        <input type="checkbox" id="check-fat" name="check-fat" />
                        <span class="checkmark custom-checkbox__checkmark--qty"></span>
                        Fat Quarter (21.5 in x 18 in) : $ 13.90
                    </label>
                    <label class="custom-checkbox custom-checkbox--qty" for="check-yard">
                        <input type="checkbox" id="check-yard" name="check-yard" checked />
                        <span class="checkmark custom-checkbox__checkmark--qty"></span>
                        1 Yard(s)(43 in x 36 in) : $ 18.90
                    </label>
                    <!-- WooCommerce Add to Cart Button -->
                    <div class="order-section__add-to-cart">
                        <?php woocommerce_template_single_add_to_cart(); ?>
                        <?php // do_action('woocommerce_single_product_summary'); 
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php
} );

remove_all_actions( 'woocommerce_single_product_summary' );

add_action( 'woocommerce_before_quantity_input_field', function () {
    echo '<button type="button" class="quantity-btn quantity-btn--minus">−</button>';
} );
add_action( 'woocommerce_after_quantity_input_field', function () {
    echo '<button type="button" class="quantity-btn quantity-btn--plus">+</button>';
} );

add_action( 'wp_footer', function () {
?>
    <script>
        jQuery( document ).ready( function($) {
            $( document ).on( 'click', '.quantity-btn--minus', function() {
                var $input = $( this ).siblings( 'input.qty' );
                var val = parseInt( $input.val(), 10 ) || 1;
                var min = parseInt( $input.attr( 'min' ), 10 ) || 1;
                if ( val > min ) {
                    $input.val( val - 1 ).trigger('change');
                }
            } );
            $( document ).on( 'click', '.quantity-btn--plus', function() {
                var $input = $( this ).siblings( 'input.qty' );
                var val = parseInt( $input.val(), 10 ) || 1;
                var max = parseInt( $input.attr( 'max' ), 10 );
                if ( ! max || val < max ) {
                    $input.val( val + 1 ).trigger( 'change' );
                }
            } );
        } );
    </script>
<?php
} );
