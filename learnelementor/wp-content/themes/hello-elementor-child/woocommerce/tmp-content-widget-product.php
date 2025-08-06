<?php
/**
 * The template for displaying product widget entries.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-widget-product.php.
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.5.5
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $product;

if ( ! is_a( $product, 'WC_Product' ) ) {
	return;
}

wp_enqueue_style('front-page	', get_stylesheet_directory_uri() . '/templates/front-page.css', array(), null);
?>
<li>
	<?php do_action( 'woocommerce_widget_product_item_start', $args ); ?>

	<a href="<?php echo esc_url( $product->get_permalink() ); ?>">
		<?php echo $product->get_image(); // PHPCS:Ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		<span class="product-title"><?php echo wp_kses_post( $product->get_name() ); ?></span>
	</a>

	<div class="showcase-card__meta">
        <span class="showcase-card__brand">
            <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/color-palette.png" alt="Palette" class="showcase-card__brand-icon">
            <span class="showcase-card__brand-label">
                <?php
                $brands = get_the_terms($product->get_id(), 'product_brand');
                if ($brands && ! is_wp_error($brands)) {
                    echo esc_html($brands[0]->name);
                }
                ?>
            </span>
        </span>
        <div class="showcase-card__price-group">
            <span class="showcase-card__price showcase-card__price--text">$
                <span>
                    <?php echo esc_html($product->get_regular_price()); ?>
                </span>
            </span>
            <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/down_1.png" alt="Down" class="showcase-card__price-arrow">
            <?php if ($product->is_on_sale()) : ?>
                <span class="showcase-card__price-discount">
                    <?php echo esc_html($product->get_sale_price()); ?>
                </span>
            <?php endif; ?>
        </div>
    </div>

	<?php if ( ! empty( $show_rating ) ) : ?>
		<?php echo wc_get_rating_html( $product->get_average_rating() ); // PHPCS:Ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
	<?php endif; ?>

	<?php // echo $product->get_price_html(); // PHPCS:Ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>

	<?php do_action( 'woocommerce_widget_product_item_end', $args ); ?>
</li>
