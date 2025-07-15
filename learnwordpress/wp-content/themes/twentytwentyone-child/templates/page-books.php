<?php
/**
* Template Name: Books
*
* @package WordPress
* @subpackage Twenty_Fourteen
* @since Twenty Fourteen 1.0
*/

get_header();
?>

<div class="books-list">
    <h1>All Books</h1>
    <?php
    $books_query = new WP_Query([
        'post_type'      => 'book',
        'posts_per_page' => -1,
        'post_status'    => 'publish',
    ]);

    if ($books_query->have_posts()) :
        while ($books_query->have_posts()) : $books_query->the_post(); ?>
            <div class="book-item">
                <h2><?php the_title(); ?></h2>
                <div class="book-content">
                    <?php the_content(); ?>
                </div>
            </div>
        <?php endwhile;
        wp_reset_postdata();
    else :
        echo '<p>No books found.</p>';
    endif;
    ?>
</div>

<?php
get_footer();