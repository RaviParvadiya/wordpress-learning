<?php

/**
 * Plugin Name: Book Store
 * Description: This is book store welcome!
 * Version: 1.0.0
 * Author: Ravi
 */

if (! defined('ABSPATH')) {
    exit;
}

// Register custom post type for books
add_action('init', 'bookstore_register_book_post_type');
function bookstore_register_book_post_type()
{
    $args = array(
        'labels' => array(
            'name'          => 'Books',
            'singular_name' => 'Book',
            'menu_name'     => 'Books',
            'add_new'       => 'Add New Book',
            'add_new_item'  => 'Add New Book',
            'new_item'      => 'New Book',
            'edit_item'     => 'Edit Book',
            'view_item'     => 'View Book',
            'all_items'     => 'All Books',
        ),
        'public' => true,
        'has_archive' => true,
        'show_in_rest' => true,
        'supports' => array('title', 'editor', 'author', 'thumbnail', 'excerpt'),
        'rewrite' => array('slug' => 'library'),
    );

    register_post_type('book', $args);
}

// Register taxonomy for genres
add_action('init', 'bookstore_register_genre_taxonomy');
function bookstore_register_genre_taxonomy()
{
    $args = array(
        'labels'       => array(
            'name'          => 'Genres',
            'singular_name' => 'Genre',
            'edit_item'     => 'Edit Genre',
            'update_item'   => 'Update Genre',
            'add_new_item'  => 'Add New Genre',
            'new_item_name' => 'New Genre Name',
            'menu_name'     => 'Genre',
        ),
        'hierarchical' => true,
        'rewrite'      => array('slug' => 'genre'),
        'show_in_rest'           => true,
    );

    register_taxonomy('genre', 'book', $args);
}

// Add meta box for book details
add_action('add_meta_boxes', function () {
    add_meta_box(
        'book_details_meta_box',
        'Book Details',
        'bookstore_render_book_meta_box',
        'book',
        'side',
        'default'
    );
});

// Render meta box fields
function bookstore_render_book_meta_box($post)
{
    $author = get_post_meta($post->ID, 'book_author', true);
    $publisher = get_post_meta($post->ID, 'book_publisher', true);
    $year = get_post_meta($post->ID, 'book_year', true);
    echo '<label for="book_author">Author:</label>';
    echo '<input type="text" name="book_author" value="' . esc_attr($author) . '" style="width:100%"><br><br>';
    echo '<label for="book_publisher">Publisher:</label>';
    echo '<input type="text" name="book_publisher" value="' . esc_attr($publisher) . '" style="width:100%"><br><br>';
    echo '<label for="book_year">Year:</label>';
    echo '<input type="number" name="book_year" value="' . esc_attr($year) . '" style="width:100%">';
}

// Save meta box data
add_action('save_post_book', function ($post_id) {
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;

    if (isset($_POST['book_author'])) {
        update_post_meta($post_id, 'book_author', sanitize_text_field($_POST['book_author']));
    }
    if (isset($_POST['book_publisher'])) {
        update_post_meta($post_id, 'book_publisher', sanitize_text_field($_POST['book_publisher']));
    }
    if (isset($_POST['book_year']) && is_numeric($_POST['book_year']) && $_POST['book_year'] > 0) {
        update_post_meta($post_id, 'book_year', intval($_POST['book_year']));
    }
});

// Register meta fields for REST API
register_post_meta('book', 'book_author', [
    'type' => 'string',
    'single' => true,
    'show_in_rest' => true,
]);
register_post_meta('book', 'book_publisher', [
    'type' => 'string',
    'single' => true,
    'show_in_rest' => true,
]);
register_post_meta('book', 'book_year', [
    'type' => 'integer',
    'single' => true,
    'show_in_rest' => true,
]);

// Append book meta to content on single book page
add_filter('the_content', 'bookstore_append_book_meta_to_content');
function bookstore_append_book_meta_to_content($content)
{
    if (is_singular('book') && in_the_loop() && is_main_query()) {
        $post_id = get_the_ID();
        $author = get_post_meta($post_id, 'book_author', true);
        $publisher = get_post_meta($post_id, 'book_publisher', true);
        $year = get_post_meta($post_id, 'book_year', true);

        $meta_html = '<div class="book-meta">';
        if ($author) {
            $meta_html .= '<p><strong>Author:</strong> ' . esc_html($author) . '</p>';
        }
        if ($publisher) {
            $meta_html .= '<p><strong>Publisher:</strong> ' . esc_html($publisher) . '</p>';
        }
        if ($year) {
            $meta_html .= '<p><strong>Year:</strong> ' . esc_html($year) . '</p>';
        }
        $meta_html .= '</div>';

        return $content . $meta_html;
    }

    return $content;
}
