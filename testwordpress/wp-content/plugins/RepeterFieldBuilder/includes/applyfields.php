<?php
// 1. Add Meta Boxes for Each Post Type That Has a Repeater
add_action('add_meta_boxes', function () {
    global $wpdb;
    $table = $wpdb->prefix . 'repeatersBuilder';
    $results = $wpdb->get_results("SELECT * FROM $table");
    if (!$results) return;

    foreach ($results as $row) {
        add_meta_box(
            'custom_repeater_meta_' . $row->id,
            esc_html($row->repeater_name),
            function ($post) use ($row) {
                render_custom_repeater_fields($post, $row);
            },
            $row->post_type,
            'normal',
            'default'
        );
    }
});

// 2. Render Fields
function render_custom_repeater_fields($post, $repeater_row) {
    $fields = json_decode($repeater_row->fields, true);
    if (!is_array($fields)) return;

    $meta_key = '_repeater_data_' . $repeater_row->id;
    $repeater_data = get_post_meta($post->ID, $meta_key, true);
    if (!is_array($repeater_data)) $repeater_data = [];

    wp_nonce_field('custom_repeater_nonce_' . $repeater_row->id, 'custom_repeater_nonce_' . $repeater_row->id);
    echo '<div id="repeater-container-' . esc_attr($repeater_row->id) . '">';

    foreach ($repeater_data as $i => $row) {
        echo '<div class="repeater-group" style="border:1px solid #ccc;padding:10px;margin-bottom:10px;">';
        foreach ($fields as $field) {
            $required_yes_no = isset($field['required']) && $field['required'] == '1' ? $field['required'] : 0;
            $name = $field['name'];
            $label = $field['label'];
            $type = $field['type'];
            $value = $row[$name] ?? '';     
            $old_file = '';       
            if($type == 'file'){
                $old_file = "repeater_data_{$repeater_row->id}[{$i}][{$name}_old]" ?? '';
            }
            echo "<p><label><strong>{$label}:</strong></label><br>";
            echo render_input_field($type, "repeater_data_{$repeater_row->id}[{$i}][{$name}]", $value, $field['options'] ?? [], $repeater_row->id, $i, $name,$old_file,$required_yes_no);
            echo "</p>";
        }
        echo '<p><button type="button" class="button remove-row">Remove Row</button></p>';
        echo '</div>';
    }

    echo '</div>';
    echo '<p><button type="button" class="button add-repeater-row" data-repeater="' . esc_attr($repeater_row->id) . '">+ Add Row</button></p>';
    echo '<script>window.repeaterFields_' . $repeater_row->id . ' = ' . json_encode($fields) . ';</script>';
}

// 3. Render Input Fields
function render_input_field($type, $name, $value, $options = [], $repeater_id = null, $index = null, $key = null,$old_file = null,$required_yes_no = 0) {
    $required = '';
    if($required_yes_no == '1'){
        $required = 'required';
    }
    switch ($type) {
        case 'textarea':
            return "<textarea name='{$name}' class='widefat' ".$required.">" . esc_textarea($value) . "</textarea>";
        case 'select':
            $html = "<select name='{$name}' class='widefat' ".$required.">";
            foreach ($options as $opt) {
                $selected = selected($value, $opt, false);
                $html .= "<option value='" . esc_attr($opt) . "' $selected>$opt</option>";
            }
            $html .= "</select>";
            return $html;
        case 'radio':
            $html = '';
            foreach ($options as $opt) {
                $checked = checked($value, $opt, false);
                $html .= "<label><input type='radio' name='{$name}' value='" . esc_attr($opt) . "' $checked> $opt</label><br>";
            }
            return $html;
        case 'checkbox':
            $html = '';
            $saved = is_array($value) ? $value : [];
            foreach ($options as $opt) {
                $checked = in_array($opt, $saved) ? 'checked' : '';
                $html .= "<label><input type='checkbox' name='{$name}[]' value='" . esc_attr($opt) . "' $checked> $opt</label><br>";
            }
            return $html;
        case 'file':
            $file_input = "repeater_file_{$repeater_id}_{$index}_{$key}";
            $output = '';
            if (!empty($value)) {
                $required = '';
                $output .= "<a href='" . esc_url($value) . "' target='_blank'><img src='" . esc_url($value) . "' style='max-height:100px;max-width:100px;' /></a><br>";
                $output .= "<input type='hidden' name='$old_file' value='" . esc_attr($value) . "'>";
            }
            
            $output .= "<input type='file' name='{$file_input}' class='widefat' accept='.png,.jpg,.jpeg,.pdf' ".$required.">";
            return $output;
        default: // for text, email, url, etc.
            return "<input type='{$type}' name='{$name}' value='" . esc_attr($value) . "' class='widefat' ".$required.">";
    }
}

// 4. Save Repeater Data
add_action('save_post', function ($post_id) {
    global $wpdb;
    $table = $wpdb->prefix . 'repeatersBuilder';
    $rows = $wpdb->get_results("SELECT * FROM $table");
    if (!$rows) return;
    foreach ($rows as $row) {
        $field_key = 'repeater_data_' . $row->id;
        $nonce_key = 'custom_repeater_nonce_' . $row->id;
        
        if (!isset($_POST[$nonce_key]) || !wp_verify_nonce($_POST[$nonce_key], $nonce_key)) continue;
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) continue;
        
        $raw_data = $_POST[$field_key] ?? [];
        $field_definitions = json_decode($row->fields, true);
        $sanitized = [];
        
        foreach ($raw_data as $index => $row_data) {
            $clean = [];
            
            foreach ($field_definitions as $field) {
                $key = $field['name'];
                $field_type = $field['type'];
                
                if ($field_type === 'file') {
                    // echo '<pre>frgwr';
                    // print_r($field_definitions);
                    // print_r($row_data);
                    // exit;
                    $file_input = "repeater_file_{$row->id}_{$index}_{$key}";

                    if (isset($_FILES[$file_input]) && !empty($_FILES[$file_input]['tmp_name'])) {
                        // echo 'hii';
                        // print_r($row_data);
                        // exit;
                        require_once(ABSPATH . 'wp-admin/includes/file.php');
                        $uploaded = wp_handle_upload($_FILES[$file_input], ['test_form' => false]);

                        $clean[$key] = (!isset($uploaded['error']))
                            ? esc_url_raw($uploaded['url'])
                            : (isset($_POST[$file_input . '_old']) ? esc_url_raw($_POST[$file_input . '_old']) : '');
                    } elseif (isset($row_data[$key . '_old'])) {
                        // echo 'hiii';
                        // exit;
                        $clean[$key] = esc_url_raw($row_data[$key . '_old']);
                    } else {
                        $clean[$key] = '';
                    }
                } else {
                    // echo 'hello';
                    // echo $key;
                    // exit;
                    $val = $row_data[$key] ?? '';
                    if (is_array($val)) {
                        $clean[$key] = array_map('sanitize_text_field', $val);
                    } else {
                        switch ($field_type) {
                            case 'email':
                                $clean[$key] = sanitize_email($val);
                                break;
                            case 'url':
                                $clean[$key] = esc_url_raw($val);
                                break;
                            case 'color':
                                $clean[$key] = sanitize_hex_color($val);
                                break;
                            default:
                                $clean[$key] = sanitize_text_field($val);
                        }
                    }
                }
            }

            $sanitized[] = $clean;
        }
        // echo '<pre>';
        // print_r($sanitized);

        // exit;
        $meta_key = '_' . $field_key;
        if (!empty($sanitized)) {
            update_post_meta($post_id, $meta_key, $sanitized);
        } else {
            delete_post_meta($post_id, $meta_key);
        }
    }
});


// 5. Add/Remove Repeater Rows JavaScript
add_action('admin_footer', function () {
    $screen = get_current_screen();
    if (!in_array($screen->base, ['post', 'post-new'])) return;
    ?>
    <script>
        document.querySelectorAll('.add-repeater-row').forEach(button => {
            button.addEventListener('click', function () {
                const repeaterId = this.getAttribute('data-repeater');
                const container = document.getElementById('repeater-container-' + repeaterId);
                const index = container.querySelectorAll('.repeater-group').length;
                const fields = window['repeaterFields_' + repeaterId];

                let html = `<div class="repeater-group" style="border:1px solid #ccc;padding:10px;margin-bottom:10px;">`;

                fields.forEach(field => {
                    console.log(field);
                    const required_set = field?.required ? 'required' : '';
                    const name = `repeater_data_${repeaterId}[${index}][${field.name}]`;
                    html += `<p><label><strong>${field.label}:</strong></label><br>`;

                    if (field.type === 'textarea') {
                        html += `<textarea name="${name}" class="widefat" ${required_set}></textarea>`;
                    } else if (field.type === 'select') {
                        html += `<select name="${name}" class="widefat"  ${required_set}>`;
                        field.options.forEach(opt => {
                            html += `<option value="${opt}">${opt}</option>`;
                        });
                        html += `</select>`;
                    } else if (field.type === 'radio') {
                        field.options.forEach(opt => {
                            html += `<label><input type="radio" name="${name}" value="${opt}"> ${opt}</label><br>`;
                        });
                    } else if (field.type === 'checkbox') {
                        field.options.forEach(opt => {
                            html += `<label><input type="checkbox" name="${name}[]" value="${opt}"> ${opt}</label><br>`;
                        });
                    } else if (field.type === 'file') {
                        const fileInput = `repeater_file_${repeaterId}_${index}_${field.name}`;
                        html += `<input type="file" name="${fileInput}" class="widefat" accept=".png,.jpg,.jpeg,.pdf"  ${required_set}>
                                <input type="hidden" name="${name}_old" value="">`;
                    } else {
                        html += `<input type="${field.type}" name="${name}" class="widefat" ${required_set}>`;
                    }

                    html += `</p>`;
                });

                html += `<p><button type="button" class="button remove-row">Remove Row</button></p></div>`;
                container.insertAdjacentHTML('beforeend', html);
            });
        });

        document.addEventListener('click', function (e) {
            if (e.target.classList.contains('remove-row')) {
                e.target.closest('.repeater-group').remove();
            }
        });
    </script>
    <?php
});

// 6. Allow File Uploads in Form
add_action('post_edit_form_tag', function () {
    echo ' enctype="multipart/form-data"';
});
