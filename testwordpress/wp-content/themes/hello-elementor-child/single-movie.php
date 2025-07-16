<?php

get_header();
?>

<main id="primary" class="site-main">

    <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

        <article <?php post_class(); ?>>

            <h1 class="movie-title"><?php the_title(); ?></h1>

            <?php if ( has_post_thumbnail() ) : ?>
                <div class="movie-poster">
                    <?php the_post_thumbnail('large'); ?>
                </div>
            <?php endif; ?>

            <div class="movie-content">
                <?php the_content(); ?>
            </div>

            <div class="movie-meta">
                <?php if ( function_exists('get_field') ) : ?>
                    <p><strong>Director:</strong> <?php the_field('director'); ?></p>
                    <p><strong>Release Date:</strong> <?php the_field('release_date'); ?></p>
                    <p><strong>Rating:</strong> <?php the_field('rating'); ?>/10</p>
                <?php endif; ?>
            </div>

            <div class="movie-genres">
                <strong>Genres:</strong>
                <?php
                $terms = get_the_terms( get_the_ID(), 'genre' );
                if ( !empty($terms) && !is_wp_error($terms) ) {
                    foreach ( $terms as $term ) {
                        echo '<span class="genre">' . esc_html( $term->name ) . '</span> ';
                    }
                } else {
                    echo '—';
                }
                ?>
            </div>

        </article>

    <?php endwhile; endif; ?>

</main>

<?php get_footer(); ?>
