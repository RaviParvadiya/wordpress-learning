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
    /* if (!is_array($option_data) || empty($option_data)) {
        $option_data = []; // One empty row to start with
    } */
    $i = 0; // default index
    $row = $option_data;

    echo '<div class="wrap"><h1>' . esc_html__('All Custom Options', 'option-page-builder') . '</h1>';
    echo '<form method="post" enctype="multipart/form-data" action="' . esc_url(admin_url('admin-post.php')) . '">';
    echo '<input type="hidden" name="action" value="save_custom_options">';
    echo '<input type="hidden" name="option_id" value="' . esc_attr($option_row->id) . '">';
    wp_nonce_field('custom_options_nonce_' . $option_row->id, 'custom_options_nonce_' . $option_row->id);

    foreach ($fields as $field) {
        if (!isset($field['name']) || !isset($field['label']) || !isset($field['type'])) {
            continue; // Skip invalid fields
        }

        $required_yes_no = isset($field['required']) && $field['required'] == '1' ? '1' : '0';
        $name = sanitize_key($field['name']);
        $label = sanitize_text_field($field['label']);
        $type = sanitize_key($field['type']);
        $options = isset($field['options']) && is_array($field['options']) ? array_map('sanitize_text_field', $field['options']) : [];
        $value = $row[$name] ?? '';
        $old_file = '';
        if ($type == 'file') {
            // NOTE: Index was unneccessary
            $old_file = "option_data_{$option_row->id}[{$i}][{$name}_old]";
        }

        echo '<div class="form-field">';
        echo '<label for="' . esc_attr("option_data_{$option_row->id}_{$name}") . '">' . esc_html($label) . '</label><br>';
        echo render_option_page_input_field($type, "option_data_{$option_row->id}[{$name}]", $value, $options, $option_row->id, $i, $name, $old_file, $required_yes_no);
        echo '</div>';
    }

    submit_button(__('Save Settings', 'option-page-builder'));
    echo '</form>';
    echo '</div>';
}

/**
 * The function `render_option_page_input_field` in PHP generates HTML input fields based on the
 * specified type and parameters.
 * 
 * @param type The `type` parameter in the `render_option_page_input_field` function determines the
 * type of input field to render. It can be one of the following values:
 * @param name The `name` parameter in the `render_option_page_input_field` function represents the
 * name attribute of the input field. It is used to identify the input when the form is submitted and
 * to associate the input with a label for accessibility purposes.
 * @param value The `value` parameter in the `render_option_page_input_field` function represents the
 * current value of the input field. It is the value that will be displayed in the input field or
 * selected in the dropdown/select field when the option page is rendered. This value can be retrieved
 * from the database or set
 * @param options The `render_option_page_input_field` function is used to generate HTML input fields
 * based on the provided parameters. Here's a breakdown of the parameters:
 * @param option_id The `option_id` parameter in the `render_option_page_input_field` function
 * represents the unique identifier or ID of the option being rendered. It is used to generate specific
 * IDs and names for the input fields to ensure they are unique within the page or form. This helps in
 * identifying and handling the data
 * @param index The `index` parameter in the `render_option_page_input_field` function is used to
 * specify the index of the input field. It can be used when you have multiple input fields with the
 * same name but different indexes, such as in an array of values. This parameter helps differentiate
 * between these fields when
 * @param key The `key` parameter in the `render_option_page_input_field` function is used to specify a
 * unique identifier for the input field. It is used in generating the `id` attribute for the input
 * field element. This helps in ensuring that each input field has a distinct identifier, which is
 * important for
 * @param old_file The `old_file` parameter in the `render_option_page_input_field` function is used to
 * store the value of the previous file input. This parameter is used when rendering a file input field
 * in the option page form. If there is already a file uploaded and saved, the URL of the file is
 * @param required_yes_no The `required_yes_no` parameter in the `render_option_page_input_field`
 * function is used to determine if a field is required or not. It accepts a string value of either '0'
 * or '1', where '1' indicates that the field is required and '0' indicates that it
 * 
 * @return The function `render_option_page_input_field` returns HTML input fields based on the
 * provided parameters and the specified type. The specific HTML output returned depends on the type
 * parameter provided to the function. Here are the possible return values based on the type parameter:
 */
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
                '<textarea id="%s" name="%s" class="widefat" %s> %s</textarea>',
                $field_id,
                $name,
                $required,
                esc_textarea($value),
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
                $selected = selected($value, $option, false);
                $option = esc_attr($option);
                $html .= sprintf('<option value="%s" %s>%s</option>', $option, $selected, esc_html($option));
            }
            $html .= '</select>';
            return $html;

        case 'radio':
            $html = '<div class="radio-group">';
            foreach ($options as $option) {
                $checked = checked($value, $option, false);
                $option = esc_attr($option);
                $html .= sprintf(
                    '<label class="radio-label"><input type="radio" name="%s" value="%s" %s %s> %s</label><br>',
                    $name,
                    $option,
                    $required,
                    $checked,
                    esc_html($option)
                );
            }
            $html .= '</div>';
            return $html;

        case 'checkbox':
            $saved = (array) $value;
            $html = '<div class="checkbox-group">';
            $first = true;
            foreach ($options as $option) {
                $checked = in_array($option, $saved) ? 'checked' : '';
                $required_attr = '';

                // If field is required and this is the first checkbox, add required attribute
                if ($required && $first) {
                    $required_attr = 'required';
                }

                $option = esc_attr($option);
                $html .= sprintf(
                    '<label class="checkbox-label"><input type="checkbox" name="%s[]" value="%s" %s %s> %s</label><br>',
                    $name,
                    $option,
                    $checked,
                    $required_attr,
                    esc_html($option)
                );
                $first = false;
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
                '<input type="%s" id="%s" name="%s" value="%s" class="widefat" %s>',
                esc_attr($type),
                $field_id,
                $name,
                esc_attr($value),
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
        // Extract page parameter from referer URL
        $referer = $_POST['_wp_http_referer'] ?? '';
        $page_param = '';
        if (!empty($referer)) {
            $parsed_url = parse_url($referer);
            if (isset($parsed_url['query'])) {
                parse_str($parsed_url['query'], $query_params);
                $page_param = $query_params['page'] ?? '';
            }
        }
        wp_redirect(admin_url('admin.php?page=' . sanitize_key($page_param) . '&error=no_data'));
        exit;
    }

    global $wpdb;
    $table = $wpdb->prefix . 'options_builder';
    $row = $wpdb->get_row($wpdb->prepare("SELECT * FROM `$table` WHERE id = %d", $id));
    $field_definitions = json_decode($row->option_value, true);

    $raw_fields = $_POST[$option_data_key];
    $sanitized_data = [];

    foreach ($field_definitions as $field) {
        $name = sanitize_key($field['name'] ?? '');
        $type = $field['type'] ?? '';
        $val = isset($raw_fields[$name]) ? $raw_fields[$name] : [];

        if ($type === 'file') {
            $file_input = "option_file_{$id}_0_{$name}"; // match input name

            if (isset($_FILES[$file_input]) && !empty($_FILES[$file_input]['tmp_name'])) {
                if (! function_exists('wp_handle_upload')) {
                    require_once ABSPATH . 'wp-admin/includes/file.php';
                }
                $uploaded = wp_handle_upload($_FILES[$file_input], ['test_form' => false]);
                $sanitized_data[$name] = !isset($uploaded['error'])
                    ? esc_url_raw($uploaded['url'])
                    : ($_POST["option_data_{$id}"][0][$name . '_old'] ?? '');
            } else {
                $sanitized_data[$name] = $_POST["option_data_{$id}"][0][$name . '_old'] ?? '';
            }
        } else {
            if (is_array($val)) {
                $sanitized_data[$name] = array_map('sanitize_text_field', $val);
            } else {
                switch ($type) {
                    case 'email':
                        $sanitized_data[$name] = sanitize_email($val);
                        break;
                    case 'url':
                        $sanitized_data[$name] = esc_url_raw($val);
                        break;
                    case 'color':
                        $sanitized_data[$name] = sanitize_hex_color($val);
                        break;
                    default:
                        $sanitized_data[$name] = sanitize_text_field($val);
                }
            }
        }
    }

    // Sanitize each field based on its type
    /*     foreach ($raw_fields as $field_name => $field_value) {
        $field_name = sanitize_key($field_name);

        if (is_array($field_value)) {
            // Handle checkbox arrays
            $sanitized_data[$field_name] = array_map('sanitize_text_field', $field_value);
        } else {
            // Handle single values
            $sanitized_data[$field_name] = sanitize_text_field($field_value);
        }
    } */

    // Save the sanitized data
    $option_name = 'custom_options_' . $id;
    update_option($option_name, $sanitized_data);

    // Redirect back with success message
    // Extract page parameter from referer URL
    $referer = $_POST['_wp_http_referer'] ?? '';
    $page_param = '';
    if (!empty($referer)) {
        $parsed_url = parse_url($referer);
        if (isset($parsed_url['query'])) {
            parse_str($parsed_url['query'], $query_params);
            $page_param = $query_params['page'] ?? '';
        }
    }
    wp_redirect(admin_url('admin.php?page=' . sanitize_key($page_param) . '&updated=true'));
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
