<?php

/**
 * Output image container for an attachment.
 *
 * @param array $args various params that the function accepts.
 */
if (! function_exists('blocksy_media')) {
	function blocksy_media($args = []) {
		$args = wp_parse_args(
			$args,
			[
				'attachment_id' => null,
				'other_images' => [],
				'ratio' => '1/1',
				'class' => '',
				'aspect_ratio' => true,
				'tag_name' => 'div',
				'html_atts' => [],
				'img_atts' => [],
				'inner_content' => '',
				'lazyload' => true,
				'size' => 'medium',

				'include_original_image_size' => false,

				// default | woo
				'no_image_type' => 'default',

				'suffix' => '',

				// Used to trigger begin_fetch_post_thumbnail_html action
				'post_id' => null,

				// Attempt to display video
				'display_video' => false,
			]
		);

		if ($args['post_id']) {
			do_action(
				'begin_fetch_post_thumbnail_html',
				$args['post_id'],
				$args['attachment_id'],
				$args['size']
			);
		}

		$classes = $args['class'];

		$original_class = 'ct-media-container';

		if (! empty($args['suffix'])) {
			$original_class .= '-' . $args['suffix'];
		}

		$args['html_atts']['class'] = [$original_class];

		if (! empty($args['class'])) {
			$args['html_atts']['class'][] = $args['class'];
		}

		$args['html_atts']['class'] = implode(' ', $args['html_atts']['class']);

		$is_woo_placeholder_image = false;

		if ($args['no_image_type'] === 'woo') {
			$attachment_exists = !!wp_get_attachment_image_src(
				$args['attachment_id']
			);

			if (! $attachment_exists) {
				$placeholder_image = get_option('woocommerce_placeholder_image', 0);

				if ($placeholder_image) {
					if (is_numeric($placeholder_image)) {
						$args['attachment_id'] = $placeholder_image;

						$attachment_exists = !!wp_get_attachment_image_src(
							$args['attachment_id']
						);

						$is_woo_placeholder_image = true;
					} else {
						return apply_filters(
							'woocommerce_placeholder_img',
							blocksy_simple_image($placeholder_image, $args),
							$args['size'],
							[100, 100]
						);
					}
				}
			}
		}

		if ($args['aspect_ratio']) {
			if (! isset($args['img_atts']['style'])) {
				$args['img_atts']['style'] = '';
			}

			$args['img_atts']['style'] .= ' ' . blocksy_generate_ratio(
				$args['ratio'],
				$args['attachment_id'],
				$args['size']
			);

			$args['img_atts']['style'] = trim($args['img_atts']['style']);
		}

		if ($args['attachment_id']) {
			global $blocksy_is_quick_view, $post;

			if (
				function_exists('is_product')
				&&
				$post
				&&
				(
					$blocksy_is_quick_view
					||
					(
						isset($post->post_type)
						&&
						$post->post_type === 'product'
					)
				)
				&&
				wp_get_attachment_image_src($args['attachment_id'])
			) {
				$info = wp_get_attachment_metadata($args['attachment_id']);

				$args['img_atts']['data-caption'] = _wp_specialchars(
					get_post_field('post_excerpt', $args['attachment_id']),
					ENT_QUOTES,
					'UTF-8',
					true
				);

				$args['img_atts']['title'] = _wp_specialchars(
					get_post_field('post_title', $args['attachment_id']),
					ENT_QUOTES,
					'UTF-8',
					true
				);

				if (empty($args['img_atts']['data-caption'])) {
					unset($args['img_atts']['data-caption']);
				}

				if (empty($args['img_atts']['title'])) {
					unset($args['img_atts']['title']);
				}

				$alt = get_post_meta(
					$args['attachment_id'],
					'_wp_attachment_image_alt',
					true
				);

				if (empty($alt)) {
					$args['img_atts']['alt'] = get_post_field('post_title', $post->ID);
				}

				$attachment = get_post($args['attachment_id']);

				if ($args['include_original_image_size']) {
					$args['img_atts']['data-original'] = wp_get_attachment_image_src(
						$args['attachment_id'],
						'full'
					)[0];
				}

				$args['img_atts'] = apply_filters(
					'wp_get_attachment_image_attributes',
					array_merge(
						[
							'class' => ''
						],
						$args['img_atts']
					),
					$attachment,
					$args['size']
				);

				if (
					$info
					&&
					isset($info['width'])
					&&
					intval($info['width']) !== 0
					&&
					is_customize_preview()
				) {
					$args['html_atts']['data-w'] = $info['width'];
					$args['html_atts']['data-h'] = $info['height'];
				}
			}
		}

		$image_result = blocksy_get_image_element($args);

		if (
			$args['display_video']
			&&
			class_exists('\Blocksy\Plugin')
			&&
			\Blocksy\Plugin::instance()->premium
		) {
			$maybe_video_result = blocksy_has_video_element($args);

			if ($maybe_video_result) {
				$args['tag_name'] = 'div';

				unset($args['html_atts']['href']);
				unset($args['html_atts']['aria-label']);

				$new_default_based_on_old_value = blocksy_akg(
					'media_video_autoplay',
					$maybe_video_result,
					'no'
				) === 'yes' ? 'autoplay' : 'click';

				$play_event = blocksy_akg('media_video_event', $maybe_video_result, $new_default_based_on_old_value);

				if ($args['display_video'] !== 'pill') {
					$args['html_atts']['data-media-id'] = $args['attachment_id'];
				}

				$image_result .= $maybe_video_result['icon'];

				if (blocksy_akg('media_video_player', $maybe_video_result, 'no') === 'yes') {
					$args['html_atts']['class'] .= ' ct-simplified-player';
				}

				if (
					$play_event !== 'click'
					&&
					$args['display_video'] !== 'pill'
				) {
					$args['html_atts']['data-state'] = $play_event;

					$media_video_hover_revert = blocksy_akg('media_video_hover_revert', $maybe_video_result, 'yes');

					if (
						$play_event === 'hover'
						&&
						$media_video_hover_revert === 'yes'
					) {
						$args['html_atts']['data-state'] .= ':revert';
					}
				}
			}
		}

		if ($is_woo_placeholder_image) {
			$dimensions = [100, 100];

			if (isset($info)) {
				$dimensions = [
					$info['width'],
					$info['height']
				];
			}

			$image_result = apply_filters(
				'woocommerce_placeholder_img',
				$image_result,
				$args['size'],
				$dimensions
			);
		}

		if ( empty( $image_result ) ) {
			return '';
		}

		$class = $args['html_atts']['class'];
		unset($args['html_atts']['class']);

		$args['html_atts'] = array_merge([
			'class' => $class
		], $args['html_atts']);

		return blocksy_html_tag(
			$args['tag_name'],
			$args['html_atts'],
			$image_result . $args['inner_content']
		);
	}
}

/**
 * Output image element for all the cases.
 *
 * @param array $args various params that the function accepts.
 */
if (! function_exists('blocksy_get_image_element')) {
	function blocksy_get_image_element($args) {
		if (! wp_get_attachment_image_src($args['attachment_id'])) {
			return '';
		}

		$output = '';

		$parser = new Blocksy_Attributes_Parser();
		$global_lazyload = blocksy_get_theme_mod('has_lazy_load', 'yes') === 'yes';

		$image = wp_get_attachment_image(
			$args['attachment_id'],
			$args['size'],
			false,
			$global_lazyload && $args['lazyload'] ? ['loading' => 'lazy'] : ['loading' => false]
		);

		$has_srcset = strpos($image, 'srcset') !== false;

		if (blocksy_has_schema_org_markup()) {
			$image = $parser->add_attribute_to_images(
				$image,
				'itemprop',
				'image'
			);
		}

		foreach ($args['img_atts'] as $attr => $attr_value) {
			$image = $parser->add_attribute_to_images(
				$image,
				$attr,
				$attr_value
			);
		}

		if (! empty($args['other_images'])) {
			foreach ($args['other_images'] as $other_image) {
				$other_attachment_id = $other_image;

				$other_image = wp_get_attachment_image(
					$other_image,
					$args['size'],
					false,
					$global_lazyload && $args['lazyload'] ? [] : ['loading' => false]
				);

				$other_image = $parser->add_attribute_to_images(
					$other_image,
					'class',
					'ct-swap'
				);

				if ($args['include_original_image_size']) {
					$other_image = $parser->add_attribute_to_images(
						$other_image,
						'data-original',
						wp_get_attachment_image_src(
							$other_attachment_id,
							'full'
						)[0]
					);
				}

				if ($args['aspect_ratio']) {
					$other_image = $parser->add_attribute_to_images(
						$other_image,
						'style',
						blocksy_generate_ratio(
							$args['ratio'],
							$args['attachment_id'],
							$args['size']
						)
					);
				}

				$output = $other_image . $output;
			}
		}

		$output = $output . $image;

		return $output;
	}
}
