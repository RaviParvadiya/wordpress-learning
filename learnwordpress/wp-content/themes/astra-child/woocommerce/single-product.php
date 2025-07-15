<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header( 'shop' );
?>

<div class="custom-single-product-container">
	<?php
	while ( have_posts() ) :
		the_post();
		
		global $product;
		?>

		<h1 class="product-title"><?php the_title(); ?></h1>
		
		<div class="product-image">
			<?php echo $product->get_image(); ?>
		</div>

		<div class="product-summary">
			<p class="price"><?php echo $product->get_price_html(); ?></p>
			<?php the_content(); ?>
			<?php woocommerce_template_single_add_to_cart(); ?>
		</div>

		<div class="product-meta">
			<?php woocommerce_template_single_meta(); ?>
		</div>

	<?php endwhile; ?>
</div>

<?php
get_footer( 'shop' );
