<?php
/*
Plugin Name: Repeater Field Builder
Plugin URI: 
Description: A free and easy-to-use repeater field builder for all WordPress post types — including posts, pages, and custom post types. 
Version: 1.0.0
Author: Latesh Patil
Author URI: https://example.com
License: GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Text Domain: repeter-field-builder
*/

register_activation_hook(__FILE__, function () {
    global $wpdb;
    $table_name = $wpdb->prefix . 'repeatersBuilder';

    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $table_name (
        id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
        repeater_name VARCHAR(255) NOT NULL,
        repeater_slug VARCHAR(255) NOT NULL,
        post_type VARCHAR(255) NOT NULL,
        fields LONGTEXT NOT NULL,
        PRIMARY KEY (id),
        KEY repeater_slug_index (repeater_slug)
    ) $charset_collate;";

    require_once ABSPATH . 'wp-admin/includes/upgrade.php';
    dbDelta($sql);
});
include plugin_dir_path(__FILE__) . 'includes/listing.php';
include plugin_dir_path(__FILE__) . 'includes/applyfields.php';
include plugin_dir_path(__FILE__) . 'includes/documentation.php';

function my_get_field($repeater_name = null,$field_name = null, $post_id = null) {
    global $wpdb;
    
    $post_id = $post_id ?: get_the_ID();
    if (!$post_id) return null;
    
    $table = $wpdb->prefix . 'repeatersBuilder';
    
    // Get repeater definition    
    $repeater = $wpdb->get_row($wpdb->prepare(
        "SELECT * FROM $table WHERE repeater_slug = %s",
        $repeater_name
    ));
      
    // echo $repeater_name;
    // echo 'hii';
    // print_r($repeater);
    // exit;
    if (!$repeater) return null;

    $meta_key = '_repeater_data_' . $repeater->id;
    $data = get_post_meta($post_id, $meta_key, true);

    if (!is_array($data)) return null;

    // If field_name is null, return full repeater data
    if (is_null($field_name)) {
        return $data;
    }

    // Otherwise, return values for specific field across all rows
    $results = [];
    foreach ($data as $row) {
        if (isset($row[$field_name])) {
            $results[] = $row[$field_name];
        }
    }

    return count($results) > 1 ? $results : ($results[0] ?? null);
}


add_action('admin_enqueue_scripts', 'load_custom_admin_css');

function load_custom_admin_css() {
    // Adjust path depending on whether this is inside a theme or plugin
    wp_enqueue_style(
        'custom-repeater-style',
        plugin_dir_url(__FILE__) . 'assets/css/repeater_style.css',
        array(),
        '1.0.0'
    );
}


add_shortcode('custom_repeater_table', function($atts) {
    $atts = shortcode_atts(array(
        'repeater_slug' => '',
        'post_id'       => '',
    ), $atts);
    
    $repeater_slug = sanitize_text_field($atts['repeater_slug']);
    $post_id = intval($atts['post_id']);
    
    // Fallback if not provided or invalid
    if (!$post_id) {
        $post_id = get_the_ID();
    }
    
    if (empty($repeater_slug)) {
        return '<p><strong>Error:</strong> repeater_slug is required.</p>';
    }

    $repeater_data = my_get_field($repeater_slug, null, $post_id);

    if (empty($repeater_data) || !is_array($repeater_data)) {
        return '<p>No data found for repeater slug: <code>' . esc_html($repeater_slug) . '</code></p>';
    }

    ob_start();
    echo '<table border="1" cellpadding="6" cellspacing="0" style="border-collapse: collapse; width: 100%;">';

    // Table Headers
    echo '<thead><tr>';
    foreach ($repeater_data[0] as $key => $value) {
        echo '<th>' . esc_html(ucwords(str_replace('_', ' ', $key))) . '</th>';
    }
    echo '</tr></thead>';

    // Table Body
    echo '<tbody>';
    foreach ($repeater_data as $row) {
        echo '<tr>';
        foreach ($row as $cell) {
            echo '<td>';
            $cell = trim($cell);

            // Check if value is a valid URL
            if (filter_var($cell, FILTER_VALIDATE_URL)) {
                $ext = strtolower(pathinfo(parse_url($cell, PHP_URL_PATH), PATHINFO_EXTENSION));

                if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
                    echo '<img src="' . esc_url($cell) . '" style="max-width:100px; max-height:100px;" />';
                } elseif ($ext === 'pdf') {
                    echo '<a href="' . esc_url($cell) . '" target="_blank">View PDF</a>';
                } elseif (in_array($ext, ['mp4', 'webm'])) {
                    echo '<video width="160" height="90" controls><source src="' . esc_url($cell) . '" type="video/' . esc_attr($ext) . '"></video>';
                } elseif (in_array($ext, ['mp3', 'wav'])) {
                    echo '<audio controls><source src="' . esc_url($cell) . '" type="audio/' . esc_attr($ext) . '"></audio>';
                } else {
                    echo '<a href="' . esc_url($cell) . '" target="_blank">Download File</a>';
                }
            }

            // Detect hex/named color (exclude numbers)
            elseif (
                preg_match('/^#([a-f0-9]{3}|[a-f0-9]{6})$/i', $cell) || 
                in_array(strtolower($cell), ['red','blue','green','black','white','yellow','orange','purple','gray','grey','pink','brown'])
            ) {
                echo '<div style="width:50px; height:50px; background:' . esc_attr($cell) . '; border:1px solid #ccc;"></div>';
                echo '<div style="margin-top:5px;">' . esc_html($cell) . '</div>';
            }

            // Default plain text
            else {
                echo esc_html($cell);
            }

            echo '</td>';
        }
        echo '</tr>';
    }
    echo '</tbody>';
    echo '</table>';

    return ob_get_clean();
});


add_action('admin_enqueue_scripts', function($hook) {
    if ($hook !== 'toplevel_page_repeater-fields') return;

    wp_enqueue_script('repeater-builder', plugin_dir_url(__FILE__) . 'assets/js/repeater-builder.js', ['jquery'], null, true);
    
    wp_localize_script('repeater-builder', 'RepeaterAjax', [
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce'    => wp_create_nonce('repeater_save_nonce'),
    ]);
});

add_action('wp_ajax_save_repeater_data', function() {
    if (!current_user_can('manage_options')) {
        wp_send_json_error('Unauthorized');
    }

    if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'repeater_save_nonce')) {
        wp_send_json_error('Invalid nonce');
    }

    parse_str($_POST['data'], $form_data);

    global $wpdb;
    $table = $wpdb->prefix . 'repeatersBuilder';

    $post_type      = sanitize_text_field($form_data['post_type']);
    $repeater_label = sanitize_text_field($form_data['repeater_name']);
    $fields         = $form_data['fields'] ?? [];
    $edit_id        = isset($form_data['edit_id']) ? intval($form_data['edit_id']) : 0;
    // echo $edit_id;
    // exit;
    foreach ($fields as &$field) {
        $field['required'] = !empty($field['required']);
        if (!in_array($field['type'], ['select', 'checkbox', 'radio'])) {
            unset($field['options']);
        }
    }

    $slug_base = sanitize_title_with_dashes($repeater_label);
    $slug_base = str_replace('-', '_', $slug_base);
    $slug = $slug_base;

    $error_message = '';

    if (!$edit_id) {
        $existing_name = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM $table WHERE repeater_name = %s", $repeater_label));
        $existing_slug = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM $table WHERE repeater_slug = %s", $slug));
        if ($existing_name || $existing_slug) {
            wp_send_json_error('Repeater name or slug already exists.');
        }

        // Ensure slug is unique
        $counter = 1;
        while ($wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM $table WHERE repeater_slug = %s", $slug)) > 0) {
            $slug = $slug_base . '_' . $counter++;
        }
    }

    $data = [
        'repeater_name' => $repeater_label,
        'repeater_slug' => $slug,
        'post_type'     => $post_type,
        'fields'        => wp_json_encode($fields),
    ];

    if ($edit_id > 0) {
        $wpdb->update($table, $data, ['id' => $edit_id]);
    } else {
        $wpdb->insert($table, $data);
        $edit_id = $wpdb->insert_id;
    }

    wp_send_json_success([
        'message' => 'Saved successfully',
        'redirect_url' => admin_url("admin.php?page=repeater-fields&action=edit&edit={$edit_id}")
    ]);
});

// Enqueue script only for specific admin pages
add_action('admin_enqueue_scripts', function ($hook_suffix) {
    // Only load for our plugin page(s)
    if (
        $hook_suffix === 'toplevel_page_repeater-fields' ||
        (isset($_GET['page']) && $_GET['page'] === 'repeater-fields')
    ) {
        wp_enqueue_script(
            'repeater-builder-dropdown',
            plugin_dir_url(__FILE__) . 'assets/js/multiselect-dropdown.js',
            ['jquery'],
            null,
            true
        );
    }
});

add_action('wp_ajax_get_posts_by_type', function () {
    $post_type = sanitize_text_field($_GET['post_type'] ?? '');
    if (!$post_type) wp_send_json([]);

    $posts = get_posts([
        'post_type' => $post_type,
        'post_status' => 'publish',
        'numberposts' => -1,
    ]);

    wp_send_json($posts);
});
