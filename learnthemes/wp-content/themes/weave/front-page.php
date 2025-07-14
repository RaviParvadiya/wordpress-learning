<?php

/**
 * The header.
 * 
 * This is the template that displays all of the <head> section and everything up until main.
 */

get_header(); ?>

<!-- main-banner -->
<section class="" style="margin-bottom:80px;">
    <div class="main-banner" style="background-image: url('<?php echo get_template_directory_uri(); ?>/assets/images/mainbanner.jpg');">
        <div class="main-banner__content">
            <h2>Happy Spring! Happy Sale!</h2>
            <p>Enjoy quality fabric printing with 15% off</p>
            <a href="#">Create Now</a>
        </div>
    </div>
</section>

<!-- main-card -->
<section class="main-section">
    <div class="container main-section__card">
        <div class="main-card">
            <div class="main-card__image" style="background-image: url('<?php echo get_template_directory_uri(); ?>/assets/images/img_maincenterbox1.jpg');"></div>
            <div class="main-card__content">
                <span class="main-card__label">Digital Print</span>
                <h3 class="main-card__title">Custom Fabric Begins HERE</h3>
                <p class="main-card__desc">Upload Your Favorite Design to Print On</p>
                <a class="main-card__ellipse">
                    <img src="<?php echo get_template_directory_uri(); ?>/assets/images/down.png" alt="Down arrow" class="main-card__ellipse-icon">
                </a>
            </div>
        </div>
        <div class="main-card">
            <div class="main-card__image" style="background-image: url('<?php echo get_template_directory_uri(); ?>/assets/images/img_maincenterbox2.jpg');"></div>
            <div class="main-card__content">
                <span class="main-card__label">Shop Fabric</span>
                <h3 class="main-card__title">Shop Our Selections</h3>
                <p class="main-card__desc">Discover Your Favorite Fabric from Our Designers</p>
                <a class="main-card__ellipse">
                    <img src="<?php echo get_template_directory_uri(); ?>/assets/images/down.png" alt="Down arrow" class="main-card__ellipse-icon">
                </a>
            </div>
        </div>
        <div class="main-card">
            <div class="main-card__image" style="background-image: url('<?php echo get_template_directory_uri(); ?>/assets/images/img_maincenterbox3.jpg');"></div>
            <div class="main-card__content">
                <span class="main-card__label">Hello Designers</span>
                <h3 class="main-card__title">Be Our Designer</h3>
                <p class="main-card__desc">Join NOW! <br> For selling And Commissions</p>
                <a class="main-card__ellipse">
                    <img src="<?php echo get_template_directory_uri(); ?>/assets/images/down.png" alt="Down arrow" class="main-card__ellipse-icon">
                </a>
            </div>
        </div>
    </div>
</section>

<!-- favorites-section -->
<section class="container showcase-section">
    <div class="showcase-section__header">
        <a class="showcase-section__ellipse">
            <img src="<?php echo get_template_directory_uri(); ?>/assets/images/star.png" alt="Star" width="32" height="23.72">
        </a>
        <h2 class="showcase-section__title">Our Favorites</h2>
    </div>

    <?php
    $args = array(
        'type' => 'simple',
        'limit' => 10,
        'orderby' => 'date',
        'order' => 'ASC',
        'status' => 'publish',
        'meta_key' => 'section_type',
        'meta_value' => 'favorites',
    );
    $favorites = wc_get_products($args);

    if (! empty($favorites)) : ?>
        <div class="showcase-section__grid">

            <?php foreach ($favorites as $favorite) : ?>
                <div class="showcase-card showcase-card--favorite">
                    <div class="showcase-card__image-wrapper">
                        <img src="<?php echo get_the_post_thumbnail_url($favorite->get_id(), 'medium'); ?>" alt="<?php echo esc_attr($favorite->get_name()); ?>" class="showcase-card__image">
                        <span class="showcase-card__label-rect">
                            <span class="showcase-card__label-text">DESIGN No. <?php echo esc_html(get_post_meta($favorite->get_id(), 'design_number', true)); ?></span>
                        </span>
                    </div>
                    <div class="showcase-card__content">
                        <h3 class="showcase-card__title"><?php echo esc_html($favorite->get_name()); ?></h3>
                        <div class="showcase-card__meta">
                            <span class="showcase-card__brand">
                                <img src="<?php echo get_template_directory_uri(); ?>/assets/images/color-palette.png" alt="Palette" class="showcase-card__brand-icon">
                                <span class="showcase-card__brand-label">
                                    <?php
                                    $brands = get_the_terms($favorite->get_id(), 'product_brand');
                                    if ($brands && ! is_wp_error($brands)) {
                                        echo esc_html($brands[0]->name);
                                    }
                                    ?>
                                </span>
                            </span>
                            <div class="showcase-card__price-group">
                                <span class="showcase-card__price showcase-card__price--text">$
                                    <span>
                                        <?php echo esc_html($favorite->get_regular_price()); ?>
                                    </span>
                                </span>
                                <img src="<?php echo get_template_directory_uri(); ?>/assets/images/down_1.png" alt="Down" class="showcase-card__price-arrow">
                                <?php if ($favorite->is_on_sale()) : ?>
                                    <span class="showcase-card__price-discount">
                                        <?php echo esc_html($favorite->get_sale_price()); ?>
                                    </span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>

            <!-- <div class="showcase-card showcase-card--favorite">
                <div class="showcase-card__image-wrapper">
                    <img src="<?php echo get_template_directory_uri(); ?>/assets/images/1.jpg" alt="Favorite 1" class="showcase-card__image">
                    <span class="showcase-card__label-rect">
                        <span class="showcase-card__label-text">DESIGN No. 292</span>
                    </span>
                </div>
                <div class="showcase-card__content">
                    <h3 class="showcase-card__title">Flower's Gambit</h3>
                    <div class="showcase-card__meta">
                        <span class="showcase-card__brand">
                            <img src="<?php echo get_template_directory_uri(); ?>/assets/images/color-palette.png" alt="Palette" class="showcase-card__brand-icon">
                            <span class="showcase-card__brand-label">Real Fabric USA</span>
                        </span>
                        <div class="showcase-card__price-group">
                            <span class="showcase-card__price showcase-card__price--text">$ <span>13.90</span></span>
                            <img src="<?php echo get_template_directory_uri(); ?>/assets/images/down_1.png" alt="Down" class="showcase-card__price-arrow">
                            <span class="showcase-card__price-discount">11.82</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="showcase-card">
                <div class="showcase-card__image-wrapper">
                    <img src="<?php echo get_template_directory_uri(); ?>/assets/images/2.jpg" alt="Favorite 2" class="showcase-card__image">
                    <span class="showcase-card__label-rect">
                        <span class="showcase-card__label-text">DESIGN No. 626</span>
                    </span>
                </div>
                <div class="showcase-card__content">
                    <h3 class="showcase-card__title">Round Tulip - Red</h3>
                    <div class="showcase-card__meta">
                        <span class="showcase-card__brand">
                            <img src="<?php echo get_template_directory_uri(); ?>/assets/images/color-palette.png" alt="Palette" class="showcase-card__brand-icon">
                            <span class="showcase-card__brand-label">pompomya</span>
                        </span>
                        <div class="showcase-card__price-group">
                            <span class="showcase-card__price showcase-card__price--text">$ <span>15.29</span></span>
                            <img src="<?php echo get_template_directory_uri(); ?>/assets/images/down_1.png" alt="Down" class="showcase-card__price-arrow">
                            <span class="showcase-card__price-discount">13.00</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="showcase-card">
                <div class="showcase-card__image-wrapper">
                    <img src="<?php echo get_template_directory_uri(); ?>/assets/images/3.jpg" alt="Favorite 3" class="showcase-card__image">
                    <span class="showcase-card__label-rect">
                        <span class="showcase-card__label-text">DESIGN No. 315</span>
                    </span>
                </div>
                <div class="showcase-card__content">
                    <h3 class="showcase-card__title">Forest Floor Pattern in Blue</h3>
                    <div class="showcase-card__meta">
                        <span class="showcase-card__brand">
                            <img src="<?php echo get_template_directory_uri(); ?>/assets/images/color-palette.png" alt="Palette" class="showcase-card__brand-icon">
                            <span class="showcase-card__brand-label">Erica Catherine Illustration</span>
                        </span>
                        <div class="showcase-card__price-group">
                            <span class="showcase-card__price showcase-card__price--text">$ <span>15.29</span></span>
                            <img src="<?php echo get_template_directory_uri(); ?>/assets/images/down_1.png" alt="Down" class="showcase-card__price-arrow">
                            <span class="showcase-card__price-discount">13.00</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="showcase-card">
                <div class="showcase-card__image-wrapper">
                    <img src="<?php echo get_template_directory_uri(); ?>/assets/images/4.jpg" alt="Favorite 4" class="showcase-card__image">
                    <span class="showcase-card__label-rect">
                        <span class="showcase-card__label-text">DESIGN No. 157</span>
                    </span>
                </div>
                <div class="showcase-card__content">
                    <h3 class="showcase-card__title">tile checkｰspring green</h3>
                    <div class="showcase-card__meta">
                        <span class="showcase-card__brand">
                            <img src="<?php echo get_template_directory_uri(); ?>/assets/images/color-palette.png" alt="Palette" class="showcase-card__brand-icon">
                            <span class="showcase-card__brand-label">MEMI.fabric</span>
                        </span>
                        <div class="showcase-card__price-group">
                            <span class="showcase-card__price showcase-card__price--text">$ <span>15.29</span></span>
                            <img src="<?php echo get_template_directory_uri(); ?>/assets/images/down_1.png" alt="Down" class="showcase-card__price-arrow">
                            <span class="showcase-card__price-discount">13.00</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="showcase-card">
                <div class="showcase-card__image-wrapper">
                    <img src="<?php echo get_template_directory_uri(); ?>/assets/images/5.jpg" alt="Favorite 5" class="showcase-card__image">
                    <span class="showcase-card__label-rect">
                        <span class="showcase-card__label-text">DESIGN No. 169</span>
                    </span>
                </div>
                <div class="showcase-card__content">
                    <h3 class="showcase-card__title">RealFabric Color Chart</h3>
                    <div class="showcase-card__meta">
                        <span class="showcase-card__brand">
                            <img src="<?php echo get_template_directory_uri(); ?>/assets/images/color-palette.png" alt="Palette" class="showcase-card__brand-icon">
                            <span class="showcase-card__brand-label">Real Fabric USA</span>
                        </span>
                        <div class="showcase-card__price-group">
                            <span class="showcase-card__price showcase-card__price--text">$ <span>13.90</span></span>
                            <img src="<?php echo get_template_directory_uri(); ?>/assets/images/down_1.png" alt="Down" class="showcase-card__price-arrow">
                            <span class="showcase-card__price-discount">11.82</span>
                        </div>
                    </div>
                </div>
            </div> -->

            <div class="showcase-card">
                <div class="showcase-card__image-wrapper">
                    <img src="<?php echo get_template_directory_uri(); ?>/assets/images/6.jpg" alt="Favorite 6" class="showcase-card__image">
                    <span class="showcase-card__label-rect">
                        <span class="showcase-card__label-text">DESIGN No. 292</span>
                    </span>
                </div>
                <div class="showcase-card__content">
                    <h3 class="showcase-card__title">Flower's Gambit</h3>
                    <div class="showcase-card__meta">
                        <span class="showcase-card__brand">
                            <img src="<?php echo get_template_directory_uri(); ?>/assets/images/color-palette.png" alt="Palette" class="showcase-card__brand-icon">
                            <span class="showcase-card__brand-label">Real Fabric USA</span>
                        </span>
                        <div class="showcase-card__price-group">
                            <span class="showcase-card__price showcase-card__price--text">$ <span>13.90</span></span>
                            <img src="<?php echo get_template_directory_uri(); ?>/assets/images/down_1.png" alt="Down" class="showcase-card__price-arrow">
                            <span class="showcase-card__price-discount">11.82</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="showcase-card">
                <div class="showcase-card__image-wrapper">
                    <img src="<?php echo get_template_directory_uri(); ?>/assets/images/7.jpg" alt="Favorite 7" class="showcase-card__image">
                    <span class="showcase-card__label-rect">
                        <span class="showcase-card__label-text">DESIGN No. 626</span>
                    </span>
                </div>
                <div class="showcase-card__content">
                    <h3 class="showcase-card__title">Round Tulip - Red</h3>
                    <div class="showcase-card__meta">
                        <span class="showcase-card__brand">
                            <img src="<?php echo get_template_directory_uri(); ?>/assets/images/color-palette.png" alt="Palette" class="showcase-card__brand-icon">
                            <span class="showcase-card__brand-label">pompomya</span>
                        </span>
                        <div class="showcase-card__price-group">
                            <span class="showcase-card__price showcase-card__price--text">$ <span>15.29</span></span>
                            <img src="<?php echo get_template_directory_uri(); ?>/assets/images/down_1.png" alt="Down" class="showcase-card__price-arrow">
                            <span class="showcase-card__price-discount">13.00</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="showcase-card">
                <div class="showcase-card__image-wrapper">
                    <img src="<?php echo get_template_directory_uri(); ?>/assets/images/8.jpg" alt="Favorite 8" class="showcase-card__image">
                    <span class="showcase-card__label-rect">
                        <span class="showcase-card__label-text">DESIGN No. 315</span>
                    </span>
                </div>
                <div class="showcase-card__content">
                    <h3 class="showcase-card__title">Forest Floor Pattern in Blue</h3>
                    <div class="showcase-card__meta">
                        <span class="showcase-card__brand">
                            <img src="<?php echo get_template_directory_uri(); ?>/assets/images/color-palette.png" alt="Palette" class="showcase-card__brand-icon">
                            <span class="showcase-card__brand-label">Erica Catherine Illustration</span>
                        </span>
                        <div class="showcase-card__price-group">
                            <span class="showcase-card__price showcase-card__price--text">$ <span>15.29</span></span>
                            <img src="<?php echo get_template_directory_uri(); ?>/assets/images/down_1.png" alt="Down" class="showcase-card__price-arrow">
                            <span class="showcase-card__price-discount">13.00</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="showcase-card">
                <div class="showcase-card__image-wrapper">
                    <img src="<?php echo get_template_directory_uri(); ?>/assets/images/9.jpg" alt="Favorite 9" class="showcase-card__image">
                    <span class="showcase-card__label-rect">
                        <span class="showcase-card__label-text">DESIGN No. 157</span>
                    </span>
                </div>
                <div class="showcase-card__content">
                    <h3 class="showcase-card__title">tile checkｰspring green</h3>
                    <div class="showcase-card__meta">
                        <span class="showcase-card__brand">
                            <img src="<?php echo get_template_directory_uri(); ?>/assets/images/color-palette.png" alt="Palette" class="showcase-card__brand-icon">
                            <span class="showcase-card__brand-label">MEMI.fabric</span>
                        </span>
                        <div class="showcase-card__price-group">
                            <span class="showcase-card__price showcase-card__price--text">$ <span>15.29</span></span>
                            <img src="<?php echo get_template_directory_uri(); ?>/assets/images/down_1.png" alt="Down" class="showcase-card__price-arrow">
                            <span class="showcase-card__price-discount">13.00</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="showcase-card">
                <div class="showcase-card__image-wrapper">
                    <img src="<?php echo get_template_directory_uri(); ?>/assets/images/10.jpg" alt="Favorite 10" class="showcase-card__image">
                    <span class="showcase-card__label-rect">
                        <span class="showcase-card__label-text">DESIGN No. 169</span>
                    </span>
                </div>
                <div class="showcase-card__content">
                    <h3 class="showcase-card__title">RealFabric Color Chart</h3>
                    <div class="showcase-card__meta">
                        <span class="showcase-card__brand">
                            <img src="<?php echo get_template_directory_uri(); ?>/assets/images/color-palette.png" alt="Palette" class="showcase-card__brand-icon">
                            <span class="showcase-card__brand-label">Real Fabric USA</span>
                        </span>
                        <div class="showcase-card__price-group">
                            <span class="showcase-card__price showcase-card__price--text">$ <span>13.90</span></span>
                            <img src="<?php echo get_template_directory_uri(); ?>/assets/images/down_1.png" alt="Down" class="showcase-card__price-arrow">
                            <span class="showcase-card__price-discount">11.82</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</section>

<!-- today's best section -->
<!-- TODO: later: it has only one row so use of flex is appropriate -->
<section class="container showcase-section showcase-section--best">
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
    $ranked_products = wc_get_products($args);

    if (! empty($ranked_products)): ?>
        <div class="showcase-section__grid showcase-section__grid--best">

            <?php foreach ($ranked_products as $product) : ?>
                <div class="showcase-card">
                    <div class="showcase-card__image-wrapper">
                        <img src="<?php echo get_the_post_thumbnail_url($product->get_id(), 'medium'); ?>" alt="<?php esc_attr($product->get_name()); ?>" class="showcase-card__image">
                        <span class="showcase-card__label-rect showcase-card__label-rect--best">
                            <span class="showcase-card__label-text showcase-card__label-text--best">
                                <?php echo esc_html(get_post_meta($product->get_id(), 'todays_best_rank', true)); ?>
                            </span>
                        </span>
                    </div>
                    <div class="showcase-card__content">
                        <h3 class="showcase-card__title"><?php echo esc_html($product->get_name()); ?></h3>
                        <div class="showcase-card__meta">
                            <span class="showcase-card__brand">
                                <img src="<?php echo get_template_directory_uri(); ?>/assets/images/color-palette.png" alt="Palette" class="showcase-card__brand-icon">
                                <span class="showcase-card__brand-label">
                                    <?php
                                    $brands = get_the_terms($favorite->get_id(), 'product_brand');
                                    if ($brands && ! is_wp_error($brands)) {
                                        echo esc_html($brands[0]->name);
                                    }
                                    ?>
                                </span>
                            </span>
                            <div class="showcase-card__price-group">
                                <span class="showcase-card__price showcase-card__price--text">$
                                    <span>
                                        <?php echo esc_html($favorite->get_regular_price()); ?>
                                    </span>
                                </span>
                                <img src="<?php echo get_template_directory_uri(); ?>/assets/images/down_1.png" alt="Down" class="showcase-card__price-arrow">
                                <?php if ($favorite->is_on_sale()) : ?>
                                    <span class="showcase-card__price-discount">
                                        <?php echo esc_html($favorite->get_sale_price()); ?>
                                    </span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>

            <!-- <div class="showcase-card">
            <div class="showcase-card__image-wrapper">
                <img src="<?php echo get_template_directory_uri(); ?>/assets/images/11.jpg" alt="Favorite 11" class="showcase-card__image">
                <span class="showcase-card__label-rect showcase-card__label-rect--best">
                    <span class="showcase-card__label-text showcase-card__label-text--best">1</span>
                </span>
            </div>
            <div class="showcase-card__content">
                <h3 class="showcase-card__title">Flower's Gambit</h3>
                <div class="showcase-card__meta">
                    <span class="showcase-card__brand">
                        <img src="<?php echo get_template_directory_uri(); ?>/assets/images/color-palette.png" alt="Palette" class="showcase-card__brand-icon">
                        <span class="showcase-card__brand-label">그니</span>
                    </span>
                    <div class="showcase-card__price-group">
                        <span class="showcase-card__price showcase-card__price--text">$ <span>15.29</span></span>
                        <img src="<?php echo get_template_directory_uri(); ?>/assets/images/down_1.png" alt="Down" class="showcase-card__price-arrow">
                        <span class="showcase-card__price-discount">13.00</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="showcase-card">
            <div class="showcase-card__image-wrapper">
                <img src="<?php echo get_template_directory_uri(); ?>/assets/images/12.jpg" alt="Favorite 12" class="showcase-card__image">
                <span class="showcase-card__label-rect showcase-card__label-rect--best">
                    <span class="showcase-card__label-text showcase-card__label-text--best">2</span>
                </span>
            </div>
            <div class="showcase-card__content">
                <h3 class="showcase-card__title">A scarf filled with greenery</h3>
                <div class="showcase-card__meta">
                    <span class="showcase-card__brand">
                        <img src="<?php echo get_template_directory_uri(); ?>/assets/images/color-palette.png" alt="Palette" class="showcase-card__brand-icon">
                        <span class="showcase-card__brand-label">SmileLee</span>
                    </span>
                    <div class="showcase-card__price-group">
                        <span class="showcase-card__price showcase-card__price--text">$ <span>15.29</span></span>
                        <img src="<?php echo get_template_directory_uri(); ?>/assets/images/down_1.png" alt="Down" class="showcase-card__price-arrow">
                        <span class="showcase-card__price-discount">13.00</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="showcase-card">
            <div class="showcase-card__image-wrapper">
                <img src="<?php echo get_template_directory_uri(); ?>/assets/images/13.jpg" alt="Favorite 13" class="showcase-card__image">
                <span class="showcase-card__label-rect showcase-card__label-rect--best">
                    <span class="showcase-card__label-text showcase-card__label-text--best">3</span>
                </span>
            </div>
            <div class="showcase-card__content">
                <h3 class="showcase-card__title">MoGLA TEXTILE 066 c/#YELLOW</h3>
                <div class="showcase-card__meta">
                    <span class="showcase-card__brand">
                        <img src="<?php echo get_template_directory_uri(); ?>/assets/images/color-palette.png" alt="Palette" class="showcase-card__brand-icon">
                        <span class="showcase-card__brand-label">그니</span>
                    </span>
                    <div class="showcase-card__price-group">
                        <span class="showcase-card__price showcase-card__price--text">$ <span>15.29</span></span>
                        <img src="<?php echo get_template_directory_uri(); ?>/assets/images/down_1.png" alt="Down" class="showcase-card__price-arrow">
                        <span class="showcase-card__price-discount">13.00</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="showcase-card">
            <div class="showcase-card__image-wrapper">
                <img src="<?php echo get_template_directory_uri(); ?>/assets/images/14.jpg" alt="Favorite 14" class="showcase-card__image">
                <span class="showcase-card__label-rect showcase-card__label-rect--best">
                    <span class="showcase-card__label-text showcase-card__label-text--best">4</span>
                </span>
            </div>
            <div class="showcase-card__content">
                <h3 class="showcase-card__title">fancy cube fruit color</h3>
                <div class="showcase-card__meta">
                    <span class="showcase-card__brand">
                        <img src="<?php echo get_template_directory_uri(); ?>/assets/images/color-palette.png" alt="Palette" class="showcase-card__brand-icon">
                        <span class="showcase-card__brand-label">awazi_textile</span>
                    </span>
                    <div class="showcase-card__price-group">
                        <span class="showcase-card__price showcase-card__price--text">$ <span>15.29</span></span>
                        <img src="<?php echo get_template_directory_uri(); ?>/assets/images/down_1.png" alt="Down" class="showcase-card__price-arrow">
                        <span class="showcase-card__price-discount">13.00</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="showcase-card">
            <div class="showcase-card__image-wrapper">
                <img src="<?php echo get_template_directory_uri(); ?>/assets/images/15.jpg" alt="Favorite 15" class="showcase-card__image">
                <span class="showcase-card__label-rect showcase-card__label-rect--best">
                    <span class="showcase-card__label-text showcase-card__label-text--best">5</span>
                </span>
            </div>
            <div class="showcase-card__content">
                <h3 class="showcase-card__title">colorful dots</h3>
                <div class="showcase-card__meta">
                    <span class="showcase-card__brand">
                        <img src="<?php echo get_template_directory_uri(); ?>/assets/images/color-palette.png" alt="Palette" class="showcase-card__brand-icon">
                        <span class="showcase-card__brand-label">tieco</span>
                    </span>
                    <div class="showcase-card__price-group">
                        <span class="showcase-card__price showcase-card__price--text">$ <span>15.29</span></span>
                        <img src="<?php echo get_template_directory_uri(); ?>/assets/images/down_1.png" alt="Down" class="showcase-card__price-arrow">
                        <span class="showcase-card__price-discount">13.00</span>
                    </div>
                </div>
            </div>
        </div> -->
        </div>
    <?php endif; ?>
</section>

<!-- customer-stories -->
<section class="container showcase-section showcase-section--testimonials">
    <div class="showcase-section__header">
        <a class="showcase-section__ellipse">
            <img src="<?php echo get_template_directory_uri(); ?>/assets/images/track.png" alt="Track" width="24" height="12.93">
        </a>
        <h2 class="showcase-section__title">Our Customer Stories</h2>
    </div>
    <div class="showcase-section__grid showcase-section__grid--testimonials">
        <div class="showcase-card showcase-card--testimonial">
            <div class="showcase-card__mask">
                <img src="<?php echo get_template_directory_uri(); ?>/assets/images/Knittermama.png" alt="" class="showcase-card__image showcase-card__image--testimonial">
            </div>
            <div class="showcase-card__content showcase-card__content--testimonial">
                <span class="showcase-card__label">Custom Printing</span>
                <h3 class="showcase-card__title">LOOKS FANTASTIC!!!</h3>
                <p class="centered-text">I was blown away at the quality of the print and the fabric. I'm...</p>
                <span class="showcase-card__line"></span>
                <img src="<?php echo get_template_directory_uri(); ?>/assets/images/user_trust_5.png" alt="Rating">
            </div>
        </div>
        <div class="showcase-card showcase-card--testimonial">
            <div class="showcase-card__mask">
                <img src="<?php echo get_template_directory_uri(); ?>/assets/images/15_1.jpg" alt="" class="showcase-card__image showcase-card__image--testimonial">
            </div>
            <div class="showcase-card__content showcase-card__content--testimonial">
                <span class="showcase-card__label">Custom Printing</span>
                <h3 class="showcase-card__title">Excellent Quality Fabric</h3>
                <p class="centered-text">I've used RF 3 times now and have been very happy with the resul...</p>
                <span class="showcase-card__line"></span>
                <img src="<?php echo get_template_directory_uri(); ?>/assets/images/user_trust_5.png" alt="Rating">
            </div>
        </div>
        <div class="showcase-card showcase-card--testimonial">
            <div class="showcase-card__mask">
                <img src="<?php echo get_template_directory_uri(); ?>/assets/images/16.jpg" alt="" class="showcase-card__image showcase-card__image--testimonial">
            </div>
            <div class="showcase-card__content showcase-card__content--testimonial">
                <div class="showcase-card__label-row">
                    <span class="showcase-card__label showcase-card__label--testimonial">Custom Printing</span>
                    <a href="#">
                        <img src="<?php echo get_template_directory_uri(); ?>/assets/images/insta.png" alt="Insta" width="23" height="23">
                    </a>
                </div>
                <h3 class="showcase-card__title">Shark Tooth Hill blue zone</h3>
                <p class="centered-text">My friend found this new area in Bakersfield, California and the...</p>
                <span class="showcase-card__line"></span>
                <img src="<?php echo get_template_directory_uri(); ?>/assets/images/user_trust_5.png" alt="Rating">
            </div>
        </div>
        <div class="showcase-card showcase-card--testimonial">
            <div class="showcase-card__mask">
                <img src="<?php echo get_template_directory_uri(); ?>/assets/images/17.jpg" alt="" class="showcase-card__image showcase-card__image--testimonial">
            </div>
            <div class="showcase-card__content showcase-card__content--testimonial">
                <span class="showcase-card__label">Custom Printing</span>
                <h3 class="showcase-card__title">Fossil stingrays</h3>
                <p class="centered-text">This fossil stingray was found by a friend and 100% perfect, abs...</p>
                <span class="showcase-card__line"></span>
                <img src="<?php echo get_template_directory_uri(); ?>/assets/images/user_trust_5.png" alt="Rating">
            </div>
        </div>
        <div class="showcase-card showcase-card--testimonial">
            <div class="showcase-card__mask">
                <img src="<?php echo get_template_directory_uri(); ?>/assets/images/18.jpg" alt="" class="showcase-card__image showcase-card__image--testimonial">
            </div>
            <div class="showcase-card__content showcase-card__content--testimonial">
                <span class="showcase-card__label">Custom Printing</span>
                <h3 class="showcase-card__title">Who loves bismuth</h3>
                <p class="centered-text">There are unique item for having fabric made, love the quality o...</p>
                <span class="showcase-card__line"></span>
                <img src="<?php echo get_template_directory_uri(); ?>/assets/images/user_trust_5.png" alt="Rating">
            </div>
        </div>
        <div class="showcase-card showcase-card--testimonial">
            <div class="showcase-card__mask">
                <img src="<?php echo get_template_directory_uri(); ?>/assets/images/19.jpg" alt="" class="showcase-card__image showcase-card__image--testimonial">
            </div>
            <div class="showcase-card__content showcase-card__content--testimonial">
                <span class="showcase-card__label">Custom Printing</span>
                <h3 class="showcase-card__title">Stunning</h3>
                <p class="centered-text">Show fabric to my clients and they are very happy with patterns ...</p>
                <span class="showcase-card__line"></span>
                <img src="<?php echo get_template_directory_uri(); ?>/assets/images/user_trust_5.png" alt="Rating">
            </div>
        </div>
        <div class="showcase-card showcase-card--testimonial">
            <div class="showcase-card__mask">
                <img src="<?php echo get_template_directory_uri(); ?>/assets/images/20.jpg" alt="" class="showcase-card__image showcase-card__image--testimonial">
            </div>
            <div class="showcase-card__content showcase-card__content--testimonial">
                <div class="showcase-card__label-row">
                    <span class="showcase-card__label showcase-card__label--testimonial">Custom Printing</span>
                    <a href="#">
                        <img src="<?php echo get_template_directory_uri(); ?>/assets/images/insta.png" alt="Insta" width="23" height="23">
                    </a>
                </div>
                <h3 class="showcase-card__title">Love love love</h3>
                <p class="centered-text">Love when my orders come in and how fabric turns out with my pro...</p>
                <span class="showcase-card__line"></span>
                <img src="<?php echo get_template_directory_uri(); ?>/assets/images/user_trust_5.png" alt="Rating">
            </div>
        </div>
        <div class="showcase-card showcase-card--testimonial">
            <div class="showcase-card__mask">
                <img src="<?php echo get_template_directory_uri(); ?>/assets/images/21.jpg" alt="" class="showcase-card__image showcase-card__image--testimonial">
            </div>
            <div class="showcase-card__content showcase-card__content--testimonial">
                <span class="showcase-card__label">Custom Printing</span>
                <h3 class="showcase-card__title">Great fabric</h3>
                <p class="centered-text">So happy with quality of fabrics, I’ve placed 50+ yards from t...</p>
                <span class="showcase-card__line"></span>
                <img src="<?php echo get_template_directory_uri(); ?>/assets/images/user_trust_5.png" alt="Rating">
            </div>
        </div>
        <div class="showcase-card showcase-card--testimonial">
            <div class="showcase-card__mask">
                <img src="<?php echo get_template_directory_uri(); ?>/assets/images/22.png" alt="" class="showcase-card__image showcase-card__image--testimonial">
            </div>
            <div class="showcase-card__content showcase-card__content--testimonial">
                <span class="showcase-card__label">Custom Printing</span>
                <h3 class="showcase-card__title">Fabric is amazing</h3>
                <p class="centered-text">Fabric is waterproof just as advertised! Printing is clear and t...</p>
                <span class="showcase-card__line"></span>
                <img src="<?php echo get_template_directory_uri(); ?>/assets/images/user_trust_5.png" alt="Rating">
            </div>
        </div>
        <div class="showcase-card showcase-card--testimonial">
            <div class="showcase-card__mask">
                <img src="<?php echo get_template_directory_uri(); ?>/assets/images/23.jpg" alt="" class="showcase-card__image showcase-card__image--testimonial">
            </div>
            <div class="showcase-card__content showcase-card__content--testimonial">
                <span class="showcase-card__label">Custom Printing</span>
                <h3 class="showcase-card__title">Perfectly done</h3>
                <p class="centered-text">I needed more fabric made for accessories for my antique toy col...</p>
                <span class="showcase-card__line"></span>
                <img src="<?php echo get_template_directory_uri(); ?>/assets/images/user_trust_5.png" alt="Rating">
            </div>
        </div>
        <div class="showcase-card showcase-card--testimonial">
            <div class="showcase-card__mask">
                <img src="<?php echo get_template_directory_uri(); ?>/assets/images/24.jpg" alt="" class="showcase-card__image showcase-card__image--testimonial">
            </div>
            <div class="showcase-card__content showcase-card__content--testimonial">
                <div class="showcase-card__label-row">
                    <span class="showcase-card__label showcase-card__label--testimonial">Custom Printing</span>
                    <a href="#">
                        <img src="<?php echo get_template_directory_uri(); ?>/assets/images/insta.png" alt="Insta" width="23" height="23">
                    </a>
                </div>
                <h3 class="showcase-card__title">New Black Terry's Apron</h3>
                <p class="centered-text">New addition to my Terry`s apron collection and I am so in love ...</p>
                <span class="showcase-card__line"></span>
                <img src="<?php echo get_template_directory_uri(); ?>/assets/images/user_trust_5.png" alt="Rating">
            </div>
        </div>
        <div class="showcase-card showcase-card--testimonial">
            <div class="showcase-card__mask">
                <img src="<?php echo get_template_directory_uri(); ?>/assets/images/25.jpg" alt="" class="showcase-card__image showcase-card__image--testimonial">
            </div>
            <div class="showcase-card__content showcase-card__content--testimonial">
                <span class="showcase-card__label">Custom Printing</span>
                <h3 class="showcase-card__title">Great work!</h3>
                <p class="centered-text">I needed some fabric made for an antique toy project. And Real F...</p>
                <span class="showcase-card__line"></span>
                <img src="<?php echo get_template_directory_uri(); ?>/assets/images/user_trust_5.png" alt="Rating">
            </div>
        </div>
    </div>
    <div class="showcase-section__footer">
        <a href="javascript:void(0);">more</a>
    </div>
</section>

<?php get_footer(); ?>