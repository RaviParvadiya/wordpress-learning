<?php

add_action('admin_menu', function () {
    global $wpdb;
    $table = $wpdb->prefix . 'options_builder';

    $results = $wpdb->get_results("SELECT * FROM `$table`");
    if (! $results) return;

    foreach ($results as $row) {
        add_submenu_page(
            sanitize_text_field($row->parent_menu),
            sanitize_text_field($row->option_name),
            ucwords(sanitize_text_field($row->option_name)),
            'manage_options',
            sanitize_key($row->option_slug),
            function () use ($row) {
                render_custom_option_fields($row);
            }
        );
    }
});

function render_custom_option_fields($option_row)
{
    $fields = json_decode($option_row->option_value, true);
    if (! $fields || !is_array($fields)) {
        echo '<div class="wrap"><h1>' . esc_html__('All Custom Options', 'option-page-builder') . '</h1>';
        echo '<div class="notice notice-error"><p>' . esc_html__('No valid fields found.', 'option-page-builder') . '</p></div></div>';
        return;
    }

    $option_name = "custom_options_" . $option_row->id;
    $option_data = get_option($option_name, []);

    echo '<div class="wrap"><h1>' . esc_html__('All Custom Options', 'option-page-builder') . '</h1>';
    echo '<form method="post" action="' . esc_url(admin_url('admin-post.php')) . '">';
    echo '<input type="hidden" name="action" value="save_custom_options">';
    echo '<input type="hidden" name="option_id" value="' . esc_attr($option_row->id) . '">';
    wp_nonce_field('custom_options_nonce_' . $option_row->id, 'custom_options_nonce_' . $option_row->id);

    foreach ($option_data as $i => $row) {
        foreach ($fields as $field) {
            if (!isset($field['name']) || !isset($field['label']) || !isset($field['type'])) {
                continue; // Skip invalid fields
            }

            $required_yes_no = isset($field['required']) && $field['required'] == '1' ? '1' : '0';
            $name = sanitize_key($field['name']);
            $label = sanitize_text_field($field['label']);
            $type = sanitize_key($field['type']);
            $options = isset($field['options']) && is_array($field['options']) ? array_map('sanitize_text_field', $field['options']) : [];

            echo '<div class="form-field">';
            echo '<label for="' . esc_attr("option_data_{$row->id}_{$name}") . '">' . esc_html($label) . '</label><br>';
            echo render_option_page_input_field($type, "option_data_{$row->id}[{$name}]", $options, $option_row->id, null, null, $name, $required_yes_no);
            echo '</div>';
        }
    }

    submit_button(__('Save Settings', 'option-page-builder'));
    echo '</form>';
    echo '</div>';
}

function render_option_page_input_field($type, $name, $value, $options = [], $option_id = 0, $index = null, $key = null, $old_file = null, $required_yes_no = '0')
{
    $required = ($required_yes_no === '1') ? 'required' : '';
    $field_id = "option_data_{$option_id}_{$key}";

    // Sanitize inputs
    $name = esc_attr($name);
    $field_id = esc_attr($field_id);
    $required = esc_attr($required);

    switch ($type) {
        case 'textarea':
            return sprintf(
                '<textarea id="%s" name="%s" class="widefat" %s></textarea>',
                $field_id,
                $name,
                $required
            );

        case 'select':
            $html = sprintf(
                '<select id="%s" name="%s" class="widefat" %s>',
                $field_id,
                $name,
                $required
            );
            $html .= '<option value="">' . esc_html__('Select an option', 'option-page-builder') . '</option>';
            foreach ($options as $option) {
                $option = esc_attr($option);
                $html .= sprintf('<option value="%s">%s</option>', $option, esc_html($option));
            }
            $html .= '</select>';
            return $html;

        case 'radio':
            $html = '<div class="radio-group">';
            foreach ($options as $option) {
                $option = esc_attr($option);
                $html .= sprintf(
                    '<label class="radio-label"><input type="radio" name="%s" value="%s" %s> %s</label><br>',
                    $name,
                    $option,
                    $required,
                    esc_html($option)
                );
            }
            $html .= '</div>';
            return $html;

        case 'checkbox':
            $html = '<div class="checkbox-group">';
            foreach ($options as $option) {
                $option = esc_attr($option);
                $html .= sprintf(
                    '<label class="checkbox-label"><input type="checkbox" name="%s[]" value="%s"> %s</label><br>',
                    $name,
                    $option,
                    esc_html($option)
                );
            }
            $html .= '</div>';
            return $html;

        case 'file':
            $file_input = "option_file_{$option_id}_{$index}_{$key}";
            $output = '';
            if (!empty($value)) {
                $required = '';
                $output .= "<a href='" . esc_url($value) . "' target='_blank'><img src='" . esc_url($value) . "' style='max-height:100px;max-width:100px;' /></a><br>";
                $output .= "<input type='hidden' name='$old_file' value='" . esc_attr($value) . "'>";
            }

            $output .= "<input type='file' name='{$file_input}' class='widefat' accept='.png,.jpg,.jpeg,.pdf' " . $required . ">";
            return $output;

        default:
            $allowed_types = ['text', 'email', 'url', 'number', 'date', 'time', 'color', 'tel', 'datetime-local', 'month', 'week', 'range'];
            if (!in_array($type, $allowed_types)) {
                $type = 'text'; // fallback to safe default
            }
            return sprintf(
                '<input type="%s" id="%s" name="%s" class="widefat" %s>',
                esc_attr($type),
                $field_id,
                $name,
                $required
            );
    }
}

add_action('admin_post_save_custom_options', function () {
    if (! current_user_can('manage_options')) {
        wp_die(esc_html__('Unauthorized access.', 'option-page-builder'));
    }

    $id = intval($_POST['option_id']);
    $nonce_field = 'custom_options_nonce_' . $id;

    if (!isset($_POST[$nonce_field]) || !wp_verify_nonce($_POST[$nonce_field], $nonce_field)) {
        wp_die(esc_html__('Security check failed.', 'option-page-builder'));
    }

    // Get the option data
    $option_data_key = "option_data_{$id}";
    if (!isset($_POST[$option_data_key]) || !is_array($_POST[$option_data_key])) {
        wp_redirect(admin_url('admin.php?page=' . sanitize_key($_POST['_wp_http_referer']) . '&error=no_data'));
        exit;
    }

    $raw_fields = $_POST[$option_data_key];
    // error_log(print_r($raw_fields, true));
    $sanitized_data = [];

    // Sanitize each field based on its type
    foreach ($raw_fields as $field_name => $field_value) {
        $field_name = sanitize_key($field_name);

        if (is_array($field_value)) {
            // Handle checkbox arrays
            $sanitized_data[$field_name] = array_map('sanitize_text_field', $field_value);
        } else {
            // Handle single values
            $sanitized_data[$field_name] = sanitize_text_field($field_value);
        }
    }

    // Save the sanitized data
    $option_name = 'custom_options_' . $id;
    update_option($option_name, $sanitized_data);

    // Redirect back with success message
    wp_redirect(admin_url('admin.php?page=' . sanitize_key($_POST['_wp_http_referer']) . '&updated=true'));
    exit;
});

// Add admin notices
add_action('admin_notices', function () {
    if (isset($_GET['page']) && isset($_GET['updated']) && $_GET['updated'] === 'true') {
        echo '<div class="notice notice-success is-dismissible"><p>' . esc_html__('Settings saved successfully!', 'option-page-builder') . '</p></div>';
    }

    if (isset($_GET['page']) && isset($_GET['error']) && $_GET['error'] === 'no_data') {
        echo '<div class="notice notice-error is-dismissible"><p>' . esc_html__('No data was submitted to save.', 'option-page-builder') . '</p></div>';
    }
});

// actually i am replicating his ideas but instead of post meta i am doing for admin pages and update options so what do you think about this code and also not only give me solution but also other ways of solution too like other variant of one problem