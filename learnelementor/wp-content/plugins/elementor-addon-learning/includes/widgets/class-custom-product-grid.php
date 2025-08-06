<?php
use Elementor\Widget_Base;

if ( ! defined( 'ABSPATH' ) ) exit;

class Custom_Product_Grid extends Widget_Base {

    public function get_name() {
        return 'todays_best_section';
    }

    public function get_title() {
        return __( "Today's Best", 'custom-elementor' );
    }

    public function get_icon() {
        return 'eicon-star';
    }

    public function get_categories() {
        return [ 'general' ];
    }

    public function render() {
        // The custom HTML and product query
        ?>
        <div class="container showcase-section showcase-section--best">
            <div class="showcase-section__header">
                <a class="showcase-section__ellipse">
                    <img src="<?php echo get_template_directory_uri(); ?>/assets/images/like.png" alt="Like" width="26" height="26">
                </a>
                <h2 class="showcase-section__title">Today's Best</h2>
            </div>

            <?php
            $args = [
                'type' => 'simple',
                'limit' => 5,
                'orderby' => 'date',
                'order' => 'ASC',
                'status' => 'publish',
                'meta_key' => 'section_type',
                'meta_value' => 'todays_best',
            ];
            $ranked_products = wc_get_products( $args );

            if ( ! empty( $ranked_products ) ) : ?>
                <div class="showcase-section__grid showcase-section__grid--best">
                    <?php foreach ( $ranked_products as $product ) : ?>
                        <div class="showcase-card">
                            <div class="showcase-card__image-wrapper">
                                <img src="<?php echo get_the_post_thumbnail_url( $product->get_id(), 'medium' ); ?>" alt="<?php echo esc_attr( $product->get_name() ); ?>" class="showcase-card__image">
                                <span class="showcase-card__label-rect showcase-card__label-rect--best">
                                    <span class="showcase-card__label-text showcase-card__label-text--best">
                                        <?php echo esc_html( get_post_meta( $product->get_id(), 'todays_best_rank', true ) ); ?>
                                    </span>
                                </span>
                            </div>
                            <div class="showcase-card__content">
                                <h3 class="showcase-card__title"><?php echo esc_html( $product->get_name() ); ?></h3>
                                <div class="showcase-card__meta">
                                    <span class="showcase-card__brand">
                                        <img src="<?php echo get_template_directory_uri(); ?>/assets/images/color-palette.png" alt="Palette" class="showcase-card__brand-icon">
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
                                        <span class="showcase-card__price showcase-card__price--text">$<span>
                                            <?php echo esc_html( $product->get_regular_price() ); ?>
                                        </span></span>
                                        <img src="<?php echo get_template_directory_uri(); ?>/assets/images/down_1.png" alt="Down" class="showcase-card__price-arrow">
                                        <?php if ( $product->is_on_sale() ) : ?>
                                            <span class="showcase-card__price-discount">
                                                <?php echo esc_html( $product->get_sale_price() ); ?>
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
        <?php
    }
}
