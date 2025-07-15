<?php

defined('ABSPATH') || exit;

get_header('shop'); ?>

<div class="custom-shop-wrapper">
	<h1 class="custom-shop-title"><?php woocommerce_page_title(); ?></h1>

	<?php woocommerce_output_all_notices(); ?>

	<div class="custom-product-loop">
		<?php woocommerce_product_loop_start(); ?>
		<?php if (wc_get_loop_prop('total')) : ?>
			<?php while (have_posts()) : ?>
				<?php the_post(); ?>
				<?php wc_get_template_part('content', 'product'); ?>
			<?php endwhile; ?>
		<?php endif; ?>
		<?php woocommerce_product_loop_end(); ?>
	</div>
</div>

<?php get_footer('shop'); ?>