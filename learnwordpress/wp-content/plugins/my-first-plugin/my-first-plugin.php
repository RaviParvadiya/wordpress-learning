<?php

/**
 * Plugin Name: My First Plugin
 * Description: A simple plugin that adds Hello word to the footer
 * version: 1.0
 * Author: Ravi
 * Author URI: https://ravi.com
 */

add_action('wp_footer', 'my_hello_world');
function my_hello_world()
{
    echo '<p style="text-align: center; color: red;"">Hello world from my first plugin!</p>';
}

add_action('init', function () {
    add_shortcode('hello', function () {
        return "Hello from shortcode!";
    });
});

add_action('admin_menu', 'my_plugin_add_admin_menu');
function my_plugin_add_admin_menu()
{
    add_menu_page(
        'My Plugin Page',
        'My Plugin',
        'manage_options',
        'my-plugin',
        'my_plugin_admin_page_html'
    );

    add_submenu_page(
        'my-plugin',
        'My Plugin Settings',
        'Settings',
        'manage_options',
        'my-plugin-settings',
        'my_plugin_settings_page_html'
    );

    add_submenu_page(
        'my-plugin',
        'About My Plugin',
        'About',
        'manage_options',
        'my-plugin-about',
        'my_plugin_about_page_html',
    );
}

add_action('init', function () {
    add_shortcode('my_message', function () {
        $message = get_option('my_plugin_message', '');
        if ($message) {
            return '<p style="color: green; font-weight: bold;">' . esc_html($message) . '</p>';
        } else {
            return '<p>No message saved yet.</p>';
        }
    });
});

function my_plugin_admin_page_html()
{
    if (!current_user_can('manage_options')) {
        return;
    }

    // Check if form was submitted
    if (isset($_POST['my_plugin_message'])) {
        // Verify nonce for security
        if (! isset($_POST['my_plugin_nonce']) || ! wp_verify_nonce($_POST['my_plugin_nonce'], 'my_plugin_save_settings')) {
            echo '<div class="notice notice-error"><p>Security check failed.</p></div>';
            return;
        }

        // Sanitize and save option
        $message = sanitize_text_field($_POST['my_plugin_message']);
        update_option('my_plugin_message', $message);
        echo '<div class="notice notice-success"><p>Settings saved.</p></div>';
    }

    // Get the saved option
    $message = get_option('my_plugin_message', '');
?>

    <div class="wrap">
        <h1>My Plugin Settings</h1>
        <form method="post" action="">
            <?php wp_nonce_field('my_plugin_save_settings', 'my_plugin_nonce'); ?>
            <table class="form-table">
                <tr valign="top">
                    <th scope="row">Message</th>
                    <td><input type="text" name="my_plugin_message" value="<?php echo esc_attr($message); ?>" class="regular-text" /></td>
                </tr>
            </table>
            <?php submit_button('Save Settings'); ?>
        </form>
        <?php if ($message): ?>
            <h2>Your saved message:</h2>
            <p><?php echo esc_html($message); ?></p>
        <?php endif; ?>
    </div>
<?php
}

function my_plugin_settings_page_html()
{
    echo '<div class="wrap"><h1>My Plugin Settings Page</h1><p>Here you could add more advanced settings.</p></div>';
}

function my_plugin_about_page_html()
{
    echo '<div class="wrap"><h1>About My Plugin</h1><p>This plugin was created as part of your learning project! 🚀</p></div>';
}

add_action('init', 'my_plugin_register_book_post_type');
function my_plugin_register_book_post_type()
{
    $labels = array(
        'name' => 'Books',
        'singular_name' => 'Book',
        'menu_name' => 'Books',
        'name_admin_bar' => 'Book',
        'add_new' => 'Add New',
        'add_new_item' => 'Add New Book',
        'new_item' => 'New Book',
        'edit_item' => 'Edit Book',
        'view_item' => 'View Book',
        'all_items' => 'All Books',
        'search_items' => 'Search Books',
        'not_found' => 'No books found.',
        'not_found_in_trash' => 'No books found in Trash.'
    );

    $args = array(
        'labels' => $labels,
        'public' => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'show_in_rest' => true,
        'query_var'          => true,
        'rewrite'            => array('slug' => 'book'),
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => 20,
        'menu_icon'          => 'dashicons-book',
        'supports'           => array('title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments'),
    );

    register_post_type('book', $args);
}

add_action('add_meta_boxes', 'my_plugin_add_book_meta_boxes');
function my_plugin_add_book_meta_boxes()
{
    add_meta_box(
        'my_plugin_book_details',
        'Book Details',
        'my_plugin_book_details_callback',
        'book',
        'normal',
        'high'
    );
}

function my_plugin_book_details_callback($post)
{
    // Add nonce for security
    wp_nonce_field('my_plugin_save_book_details', 'my_plugin_book_nonce');

    // Get existing values
    $author = get_post_meta($post->ID, '_my_plugin_book_author', true);
    $price = get_post_meta($post->ID, '_my_plugin_book_price', true);
    $isbn = get_post_meta($post->ID, '_my_plugin_book_isbn', true);

?>
    <p>
        <label for="my_plugin_book_author">Author Name:</label><br />
        <input type="text" id="my_plugin_book_author" name="my_plugin_book_author" value="<?php echo esc_attr($author); ?>" size="25" />
    </p>
    <p>
        <label for="my_plugin_book_price">Price:</label><br />
        <input type="text" id="my_plugin_book_price" name="my_plugin_book_price" value="<?php echo esc_attr($price); ?>" size="25" />
    </p>
    <p>
        <label for="my_plugin_book_isbn">ISBN:</label><br />
        <input type="text" id="my_plugin_book_isbn" name="my_plugin_book_isbn" value="<?php echo esc_attr($isbn); ?>" size="25" />
    </p>
    <?php
}

add_action('save_post', 'my_plugin_save_book_details');
function my_plugin_save_book_details($post_id)
{
    // Check nonce
    if (!isset($_POST['my_plugin_book_nonce']) || !wp_verify_nonce($_POST['my_plugin_book_nonce'], 'my_plugin_save_book_details')) {
        return;
    }

    // Check autosave
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    // Check permissions
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    // Save Author
    if (isset($_POST['my_plugin_book_author'])) {
        update_post_meta($post_id, '_my_plugin_book_author', sanitize_text_field($_POST['my_plugin_book_author']));
    }

    // Save Price
    if (isset($_POST['my_plugin_book_price'])) {
        update_post_meta($post_id, '_my_plugin_book_price', sanitize_text_field($_POST['my_plugin_book_price']));
    }

    // Save ISBN
    if (isset($_POST['my_plugin_book_isbn'])) {
        update_post_meta($post_id, '_my_plugin_book_isbn', sanitize_text_field($_POST['my_plugin_book_isbn']));
    }
}

add_shortcode('book_meta', 'my_plugin_book_meta_shortcode');
function my_plugin_book_meta_shortcode($atts)
{
    global $post;
    if (!$post || $post->post_type !== 'book') return '';
    if (! (is_singular('book') && in_the_loop() && is_main_query())) return '';

    $author = get_post_meta($post->ID, '_my_plugin_book_author', true);
    $price = get_post_meta($post->ID, '_my_plugin_book_price', true);
    $isbn = get_post_meta($post->ID, '_my_plugin_book_isbn', true);

    $output = '<div class="book-meta">';
    if ($author) {
        $output .= '<p><strong>Author:</strong> ' . esc_html($author) . '</p>';
    }
    if ($price) {
        $output .= '<p><strong>Price:</strong> ' . esc_html($price) . '</p>';
    }
    if ($isbn) {
        $output .= '<p><strong>ISBN:</strong> ' . esc_html($isbn) . '</p>';
    }
    $output .= '</div>';

    return $output;
}

add_filter('manage_book_posts_columns', 'my_plugin_book_custom_columns');
function my_plugin_book_custom_columns()
{
    $columns['book_author'] = 'Author';
    $columns['book_price'] = 'Price';
    $columns['book_isbn'] = 'ISBN';
    return $columns;
}

add_action('manage_book_posts_custom_column', 'my_plugin_book_custom_column_content', 10, 2);
function my_plugin_book_custom_column_content($column, $post_id)
{
    switch ($column) {
        case 'book_author':
            echo esc_html(get_post_meta($post_id, '_my_plugin_book_author', true));
            break;
        case 'book_price':
            echo esc_html(get_post_meta($post_id, '_my_plugin_book_price', true));
            break;
        case 'book_isbn':
            echo esc_html(get_post_meta($post_id, '_my_plugin_book_isbn', true));
            break;
    }
}

add_action('init', 'my_plugin_register_genre_taxonomy');
function my_plugin_register_genre_taxonomy()
{
    $labels = array(
        'name' => 'Genres',
        'singular_name' => 'Genre',
        'search_items' => 'Search Genres',
        'all_items' => 'All Genres',
        'parent_item' => 'Parent Genre',
        'parent_item_colon' => 'Parent Genre:',
        'edit_item' => 'Edit Genre',
        'update_item' => 'Update Genre',
        'add_new_item' => 'Add New Genre',
        'new_item_name' => 'New Genre Name',
        'menu_item' => 'Genres',
    );

    $args = array(
        'hierarchical' => true,
        'labels' => $labels,
        'show_ui' => true,
        'show_admin_column' => true,
        'query_var' => true,
        'show_in_rest' => true,
        'rewrite' => array('slug' => 'genre')
    );

    register_taxonomy('genre', array('book'), $args);
}

add_filter('the_content', 'my_plugin_display_book_details');
function my_plugin_display_book_details($content)
{
    if (is_singular('book') && in_the_loop() && is_main_query()) {
        $post_id = get_the_ID();

        $author = get_post_meta($post_id, '_my_plugin_book_author', true);
        $price = get_post_meta($post_id, '_my_plugin_book_price', true);
        $isbn = get_post_meta($post_id, '_my_plugin_book_isbn', true);

        // Get Genres
        $genres = get_the_terms($post_id, 'genre');
        $genre_list = '';
        if ($genres && !is_wp_error($genres)) {
            $genre_names = wp_list_pluck($genres, 'name');
            $genre_list = implode(', ', $genre_names);
        }


        // Build custom HTML
        $custom_html = '<div class="my-plugin-book-details" style="border:1px solid #ddd; padding:15px; margin:15px 0;">';
        $custom_html .= '<h3>Book Details</h3>';
        if ($author) {
            $custom_html .= '<p><strong>Author:</strong> ' . esc_html($author) . '</p>';
        }
        if ($price) {
            $custom_html .= '<p><strong>Price:</strong> ' . esc_html($price) . '</p>';
        }
        if ($isbn) {
            $custom_html .= '<p><strong>ISBN:</strong> ' . esc_html($isbn) . '</p>';
        }
        if ($genre_list) {
            $custom_html .= '<p><strong>Genre:</strong> ' . esc_html($genre_list) . '</p>';
        }
        $custom_html .= '</div>';

        // Append to content
        return $content . $custom_html;
    }

    return $content;
}

// My Custom Book Widget
class My_Plugin_Book_Widget extends WP_Widget
{

    // Constructor
    function __construct()
    {
        parent::__construct(
            'my_plugin_book_widget', // Base ID
            __('Latest Books', 'text_domain'), // Name
            array('description' => __('Displays latest Books.', 'text_domain'),)
        );
    }

    // Frontend display
    public function widget($args, $instance)
    {
        echo $args['before_widget'];

        // Widget Title
        $title = ! empty($instance['title']) ? $instance['title'] : 'Latest Books';
        echo $args['before_title'] . apply_filters('widget_title', $title) . $args['after_title'];

        // Query latest Books CPT
        $query = new WP_Query(array(
            'post_type'      => 'book',
            'posts_per_page' => 5,
            'post_status'    => 'publish',
        ));

        if ($query->have_posts()) {
            echo '<ul>';
            while ($query->have_posts()) {
                $query->the_post();
                echo '<li><a href="' . esc_url(get_permalink()) . '">' . esc_html(get_the_title()) . '</a></li>';
            }
            echo '</ul>';
            wp_reset_postdata();
        } else {
            echo '<p>No books found.</p>';
        }

        echo $args['after_widget'];
    }

    // Backend form
    public function form($instance)
    {
        $title = ! empty($instance['title']) ? $instance['title'] : 'Latest Books';
    ?>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('title')); ?>">Title:</label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>"
                name="<?php echo esc_attr($this->get_field_name('title')); ?>"
                type="text" value="<?php echo esc_attr($title); ?>">
        </p>
    <?php
    }

    // Save widget settings
    public function update($new_instance, $old_instance)
    {
        $instance = array();
        $instance['title'] = sanitize_text_field($new_instance['title']);
        return $instance;
    }
}

function my_plugin_register_widgets()
{
    register_widget('My_Plugin_Book_Widget');
}
add_action('widgets_init', 'my_plugin_register_widgets');

// Register the block
function register_my_test_block()
{
    // Register the block with PHP
    register_block_type('my-plugin/test-block', array(
        'render_callback' => 'render_my_test_block',
        'attributes' => array(
            'title' => array(
                'type' => 'string',
                'default' => 'My Test Block'
            ),
            'content' => array(
                'type' => 'string',
                'default' => 'This is a test block content.'
            ),
            'backgroundColor' => array(
                'type' => 'string',
                'default' => '#f0f0f0'
            ),
            'textColor' => array(
                'type' => 'string',
                'default' => '#333333'
            )
        )
    ));
}
add_action('init', 'register_my_test_block');

// Render callback for the block
function render_my_test_block($attributes)
{
    $title = esc_html($attributes['title']);
    $content = wp_kses_post($attributes['content']);
    $bg_color = esc_attr($attributes['backgroundColor']);
    $text_color = esc_attr($attributes['textColor']);

    $output = sprintf(
        '<div class="my-test-block" style="background-color: %s; color: %s; padding: 20px; border-radius: 8px; margin: 20px 0; border-left: 4px solid #0073aa;">
            <h3 style="margin-top: 0; color: %s;">%s</h3>
            <p style="margin-bottom: 0;">%s</p>
            <small style="opacity: 0.7;">Block ID: my-plugin/test-block</small>
        </div>',
        $bg_color,
        $text_color,
        $text_color,
        $title,
        $content
    );

    return $output;
}

// Enqueue block editor assets
function enqueue_my_test_block_assets()
{
    // Inline JavaScript for the block
    wp_add_inline_script('wp-block-editor', '
        (function() {
            const { registerBlockType } = wp.blocks;
            const { InspectorControls } = wp.blockEditor;
            const { PanelBody, TextControl, TextareaControl, ColorPicker } = wp.components;
            const { Fragment, createElement: el } = wp.element;
            
            registerBlockType("my-plugin/test-block", {
                title: "My Test Block",
                icon: "admin-generic",
                category: "widgets",
                attributes: {
                    title: {
                        type: "string",
                        default: "My Test Block"
                    },
                    content: {
                        type: "string", 
                        default: "This is a test block content."
                    },
                    backgroundColor: {
                        type: "string",
                        default: "#f0f0f0"
                    },
                    textColor: {
                        type: "string",
                        default: "#333333"
                    }
                },
                
                edit: function(props) {
                    const { attributes, setAttributes } = props;
                    const { title, content, backgroundColor, textColor } = attributes;
                    
                    return el(Fragment, {},
                        el(InspectorControls, {},
                            el(PanelBody, { title: "Block Settings", initialOpen: true },
                                el(TextControl, {
                                    label: "Title",
                                    value: title,
                                    onChange: function(value) {
                                        setAttributes({ title: value });
                                    }
                                }),
                                el(TextareaControl, {
                                    label: "Content",
                                    value: content,
                                    onChange: function(value) {
                                        setAttributes({ content: value });
                                    }
                                }),
                                el("p", {}, "Background Color:"),
                                el(ColorPicker, {
                                    color: backgroundColor,
                                    onChange: function(value) {
                                        setAttributes({ backgroundColor: value });
                                    }
                                }),
                                el("p", {}, "Text Color:"),
                                el(ColorPicker, {
                                    color: textColor,
                                    onChange: function(value) {
                                        setAttributes({ textColor: value });
                                    }
                                })
                            )
                        ),
                        el("div", {
                            className: "my-test-block",
                            style: {
                                backgroundColor: backgroundColor,
                                color: textColor,
                                padding: "20px",
                                borderRadius: "8px",
                                margin: "20px 0",
                                borderLeft: "4px solid #0073aa"
                            }
                        },
                            el("h3", { style: { marginTop: 0, color: textColor } }, title),
                            el("p", { style: { marginBottom: 0 } }, content),
                            el("small", { style: { opacity: 0.7 } }, "Block ID: my-plugin/test-block")
                        )
                    );
                },
                
                save: function() {
                    // Return null to use PHP render callback
                    return null;
                }
            });
        })();
    ');
}
add_action('enqueue_block_editor_assets', 'enqueue_my_test_block_assets');

// Add block category (optional)
function add_my_block_category($categories)
{
    return array_merge(
        $categories,
        array(
            array(
                'slug'  => 'my-plugin-blocks',
                'title' => 'My Plugin Blocks',
            ),
        )
    );
}
add_filter('block_categories_all', 'add_my_block_category', 10, 2);

// Add some frontend styles
function my_test_block_frontend_styles()
{
    ?>
    <style>
        .my-test-block {
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .my-test-block:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }
    </style>
    <?php
}
add_action('wp_head', 'my_test_block_frontend_styles');

// Admin notice
function my_test_block_admin_notice()
{
    if (isset($_GET['activate']) && $_GET['activate'] == 'true') {
    ?>
        <div class="notice notice-success is-dismissible">
            <p><?php _e('My Block Widget plugin activated! Go to any post/page editor to use the "My Test Block".', 'text_domain'); ?></p>
        </div>
<?php
    }
}
add_action('admin_notices', 'my_test_block_admin_notice');
