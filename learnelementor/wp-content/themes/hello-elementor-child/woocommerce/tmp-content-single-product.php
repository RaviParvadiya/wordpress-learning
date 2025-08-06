<?php
/**
 * Customized single product template using custom design and WooCommerce dynamic data
 */

defined( 'ABSPATH' ) || exit;
global $product;

do_action( 'woocommerce_before_single_product' );
if ( post_password_required() ) {
    echo get_the_password_form();
    return;
}
// Enqueue custom styles for this template
define('HELLO_CHILD_TEMPLATE_URI', get_stylesheet_directory_uri() . '/assets/images');
wp_enqueue_style('design-order', get_stylesheet_directory_uri() . '/templates/design-order.css', array(), null);
wp_enqueue_style('create-fabric', get_stylesheet_directory_uri() . '/templates/create-fabric.css', array(), null);
wp_enqueue_style('design-order-responsive', get_stylesheet_directory_uri() . '/templates/design-order-responsive.css', array('design-order'), null);
?>
<section class="order-section">
    <div class="container order-section__container">
        <div class="order-section__notice">
            <p>The image below is the actual output size and layout. If it is not the size you want, please use the "Your Design Size" below.</p>
        </div>
        <div class="order-section__grid">
            <div class="order-section__left">
                <!-- Product Image -->
                <?php do_action('woocommerce_before_single_product_summary'); ?>
            </div>
            <div class="order-section__right">
                <!-- Product Info -->
                <div class="order-section__right-info">
                    <p><?php _e('Choose Fabric', 'hello-elementor-child'); ?></p>
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
                    <p><?php _e('Choose Quantity', 'hello-elementor-child'); ?></p>
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
                <div class="quantity-selector">
                    <button type="button" class="quantity-btn quantity-btn--minus">−</button>
                    <span class="quantity-value">1</span>
                    <button type="button" class="quantity-btn quantity-btn--plus">+</button>
                </div>
                <!-- WooCommerce Add to Cart Button -->
                <div class="order-section__add-to-cart">
                    <?php do_action('woocommerce_single_product_summary'); ?>
                </div>
            </div>
        </div>
    </div>
</section>
<?php do_action('woocommerce_after_single_product'); ?>
