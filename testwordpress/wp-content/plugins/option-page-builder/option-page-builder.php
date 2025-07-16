<?php

/**
 * Plugin Name: Option Page Builder
 * Plugin URI:
 * Description: Lorem ipsum dolor sit amet, consectetur adipisicing elit. Suscipit, animi.
 * Version: 1.0.0
 * Author: Ravi Parvadiya
 * Author URI: https://example.com
 * License: GPL2
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: option-page-builder
 */

// Exit if accessed directly
defined('ABSPATH') || exit;

if (! function_exists('is_plugin_active')) {
    require_once ABSPATH . 'wp-admin/includes/plugin.php';
}

// Dependency plugin path
$dependency = 'RepeterFieldBuilder/repeaterfieldbuilder.php';

// Check for missing dependency and deactivate this plugin if needed
add_action('admin_init', function () use ($dependency) {

    // If dependency is missing and this plugin is active
    if (! is_plugin_active($dependency) && is_plugin_active(plugin_basename(__FILE__))) {

        // Deactivate this plugin
        deactivate_plugins(plugin_basename(__FILE__));

        // Prevent "Plugin Activated" message
        if (isset($_GET['activate'])) {
            unset($_GET['activate']);
        }

        // Set a flag to show admin notice later
        set_transient('option_page_builder_missing_dependency', true, 5);
    }
});

// Show notice after redirect (now safe)
add_action('admin_notices', function () {
    if (get_transient('option_page_builder_missing_dependency')) {
        delete_transient('option_page_builder_missing_dependency');

        echo '<div class="notice notice-error is-dismissible"><p><strong>Option Page Builder:</strong> This plugin requires <strong>Repeater Field Builder</strong> to be active. The plugin has been deactivated..</p></div>';
    }
});

// Activation hook to create custom DB table (only runs if dependency is present)
register_activation_hook(__FILE__, function () use ($dependency) {
    if (! is_plugin_active($dependency)) {
        // Silence
        return;
    }

    global $wpdb;

    $table_name = $wpdb->prefix . 'options_builder';

    // Set charset and collation
    $charset_collate = $wpdb->get_charset_collate();

    // SQL to create table
    $sql = "CREATE TABLE $table_name (
       id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
        option_name VARCHAR(255) NOT NULL,
        option_slug VARCHAR(255) NOT NULL,
        parent_menu VARCHAR(255) DEFAULT NULL,
        option_value LONGTEXT DEFAULT NULL,
        PRIMARY KEY (id),
        UNIQUE KEY option_slug (option_slug)
    ) $charset_collate;";

    // Load the required file for dbDelta
    require_once ABSPATH . 'wp-admin/includes/upgrade.php';
    dbDelta($sql);
});

include plugin_dir_path(__FILE__) . 'inc/admin-menu.php';
include plugin_dir_path(__FILE__) . 'inc/apply-options.php';

add_action('admin_enqueue_scripts', function ($hook) {
    if ($hook !== 'repeater-fields_page_options-page-builder') return;
    wp_enqueue_script('option-builder', plugin_dir_url(__FILE__) . 'assets/js/option-builder.js', ['jquery'], null, true);

    wp_localize_script('option-builder', 'option_ajax', [
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce'    => wp_create_nonce('option_save_nonce'),
    ]);
});

add_action('wp_ajax_save_option_data', function () {
    if (!current_user_can('manage_options')) {
        wp_send_json_error('Unauthorized');
    }

    if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'option_save_nonce')) {
        wp_send_json_error('Invalid nonce');
    }

    parse_str($_POST['data'], $form_data);

    // Step 1: Basic required fields
    if (empty($form_data['option_name']) || empty($form_data['parent_menu'])) {
        wp_send_json_error('Option name and parent menu are required.');
    }

    // Step 2: At least one field must exist
    if (empty($form_data['fields']) || !is_array($form_data['fields'])) {
        wp_send_json_error('At least one field is required.');
    }

    // Step 3: Validate each field in loop
    foreach ($form_data['fields'] as $index => $field) {
        if (empty($field['label']) || empty($field['name'])) {
            wp_send_json_error("Field #" . ($index + 1) . " is missing a label or name.");
        }

        // Optional: enforce name as slug
        /* if (!preg_match('/^[a-zA-Z0-9_\-]+$/', $field['name'])) {
            wp_send_json_error("Field #" . ($index + 1) . " name must contain only letters, numbers, dashes or underscores.");
        } */

        // If type is checkbox/select/radio, check options
        if (in_array($field['type'], ['select', 'checkbox', 'radio'])) {
            if (empty($field['options']) || !is_array($field['options'])) {
                wp_send_json_error("Field #" . ($index + 1) . " requires at least one option.");
            }

            foreach ($field['options'] as $optIndex => $opt) {
                if (trim($opt) === '') {
                    wp_send_json_error("Field #" . ($index + 1) . " has an empty option at position #" . ($optIndex + 1) . ".");
                }
            }
        }
    }
    global $wpdb;
    $table = $wpdb->prefix . 'options_builder';

    $option_label = sanitize_text_field($form_data['option_name']);
    $parent_menu  = sanitize_text_field($form_data['parent_menu']);
    $fields       = $form_data['fields'] ?? [];
    $edit_id      = isset($form_data['edit_id']) ? intval($form_data['edit_id']) : 0;

    foreach ($fields as &$field) {
        $field['required'] = !empty($field['required']);
        if (!in_array($field['type'], ['select', 'checkbox', 'radio'])) {
            unset($field['options']);
        }
    }

    $slug_base = sanitize_title_with_dashes($option_label);
    $slug_base = str_replace('-', '_', $slug_base);
    $slug = $slug_base;

    if (!$edit_id) {
        $existing_name = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM $table WHERE option_name = %s", $option_label));
        $existing_slug = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM $table WHERE option_slug = %s", $slug));
        if ($existing_name || $existing_slug) {
            wp_send_json_error('Option name or slug already exists.');
        }

        // Ensure slug is unique
        $counter = 1;
        while ($wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM $table WHERE option_slug = %s", $slug)) > 0) {
            $slug = $slug_base . '_' . $counter++;
        }
    }

    $data = [
        'option_name'  => $option_label,
        'option_slug'  => $slug,
        'parent_menu'  => $parent_menu,
        'option_value' => wp_json_encode($fields),
    ];

    if ($edit_id > 0) {
        $wpdb->update($table, $data, ['id' => $edit_id]);
    } else {
        $wpdb->insert($table, $data);
        $edit_id = $wpdb->insert_id;
    }

    wp_send_json_success([
        'message' => 'Saved successfully',
        'redirect_url' => admin_url("admin.php?page=options-page-builder&action=edit&edit={$edit_id}")
    ]);
});
