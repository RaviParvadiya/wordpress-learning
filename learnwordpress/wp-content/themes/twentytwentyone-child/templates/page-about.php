<?php
/**
 * 
 * Template Name: About Us
 * Description: A custom template for the About page.
 * Template Post Type: post, page, event, book
 * 
 */

get_header(); ?>

<main>
  <section>
    <h1><?php the_title(); ?></h1>
    <div><?php the_content(); ?></div>
  </section>
</main>

<?php get_footer(); ?>