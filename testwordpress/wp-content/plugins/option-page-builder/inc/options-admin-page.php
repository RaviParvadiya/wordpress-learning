<?php
ob_start();
global $wpdb;
$table = $wpdb->prefix . 'options_builder';

$edit_id = isset($_GET['edit']) ? intval($_GET['edit']) : 0;
$is_edit = $edit_id > 0;

if ($is_edit) {
    $sql = $wpdb->prepare("SELECT * FROM `$table` WHERE id = %d", $edit_id);
    $existing = $wpdb->get_row($sql);
    $option_name = $existing->option_name;
    $selected_menu = $existing->parent_menu;
    $option_value = json_decode($existing->option_value, true) ?? [];
} else {
    $option_name = '';
    $selected_menu = '';
    $option_value = [];
}

?>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous" />

<div class="wrap">
    <h1 class="mb-4"><?php echo $is_edit ? 'Edit' : 'Add'; ?> Options Page</h1>

    <form id="option-page-builder-form">

        <div class="tab-content" id="myTabContent">
            <div class="tab-pane fade show active" id="fields" role="tabpanel" aria-labelledby="fields-tab">
                <input type="hidden" name="edit_id" value="<?php echo esc_attr($edit_id); ?>">
                <input type="hidden" name="option_nonce" value="<?php echo wp_create_nonce('option_save_nonce'); ?>">

                <div class="mb-3">
                    <label class="form-label">Options Page Name</label>
                    <input type="text" name="option_name" class="form-control" required value="<?php echo esc_attr($option_name); ?>">

                    <label for="parent_menu">Parent Menu</label>
                    <select name="parent_menu" id="parent_menu" class="form-select">
                        <?php
                        global $menu;

                        // Fetch all used parent_menu slugs except for the current edit (if editing)
                        $used_menus = [];
                        $results = $wpdb->get_results("SELECT parent_menu, id FROM `$table`");
                        foreach ($results as $row) {
                            // Allow the current edit to keep its menu
                            if (!$is_edit || $row->id != $edit_id) {
                                $used_menus[] = $row->parent_menu;
                            }
                        }

                        foreach ($menu as $item) {
                            if (! empty($item[0]) && isset($item[2])) {
                                $slug = $item[2];

                                // Remove HTML tags
                                $label = strip_tags($item[0]);

                                // Remove trailing numbers (e.g., "Plugins 0")
                                $label = preg_replace('/\s+\d.*$/', '', $label);

                                // Exclude "Repeater Fields"
                                if (trim($label) === 'Repeater Fields') {
                                    continue;
                                }

                                // Disable if already used, except for the current edit
                                $disabled = in_array($slug, $used_menus) ? 'disabled' : '';
                                $extra = $disabled ? ' (Already used)' : '';

                                // Final output
                                echo '<option value="' . esc_attr($slug) . '"' . selected($selected_menu, $slug, false) . " $disabled>" . esc_html($label . $extra) . "</option>";
                                // echo "<option value=\"$clean_title\">" . esc_html($clean_title) . "</option>";
                            }
                        }
                        ?>
                    </select>
                </div>
                <table class="table table-striped" id="field-builder-table">
                    <thead>
                        <tr>
                            <th>Label</th>
                            <th>Name (slug)</th>
                            <th>Type</th>
                            <th>Options</th>
                            <th>Required</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="field-builder-body">
                        <?php foreach ($option_value as $index => $field) : ?>
                            <tr class="field-row" data-index="<?php echo esc_attr($index); ?>">
                                <td><input type="text" name="fields[<?php echo $index; ?>][label]" class="form-control" value="<?php echo esc_attr($field['label']); ?>"></td>
                                <td><input type="text" name="fields[<?php echo $index; ?>][name]" class="form-control" value="<?php echo esc_attr($field['name']); ?>"></td>
                                <td>
                                    <select name="fields[<?php echo $index; ?>][type]" class="form-select field-type">
                                        <?php foreach (['text', 'textarea', 'select', 'checkbox', 'radio', 'number', 'email', 'url', 'file', 'date', 'time', 'datetime-local', 'month', 'week', 'range', 'color', 'tel'] as $type) : ?>
                                            <option value="<?php echo $type; ?>" <?php selected($field['type'], $type); ?>><?php echo $type; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </td>
                                <td>
                                    <div class="field-options" style="<?php echo in_array($field['type'], ['select', 'checkbox', 'radio']) ? '' : 'display: none;'; ?>">
                                        <?php
                                        $options = $field['options'] ?? [];
                                        foreach ($options as $optIndex => $option) {
                                            echo "<div class='input-group mb-2'><input type='text' name='fields[$index][options][$optIndex]' class='form-control' value='" . esc_attr($option) . "' />";
                                            echo "<button type='button' class='remove-option btn btn-outline-danger'>×</button></div>";
                                        }
                                        ?>
                                        <button type="button" class="add-option btn btn-outline-primary btn-sm">Add Option</button>
                                    </div>
                                </td>
                                <td><input type="checkbox" name="fields[<?php echo $index; ?>][required]" class="form-check-input" <?php checked($field['required'], true); ?>></td>
                                <td><button type="button" class="remove-field btn btn-outline-danger">Remove</button></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

                <p><button type="button" id="add-field" class="btn btn-primary">Add Field</button></p>
            </div>
        </div>
        <button type="submit" class="btn btn-success">Save Settings</button>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
<script>
    let fieldIndex = <?php
                        if (empty($option_value)) {
                            echo '0';
                        } else {
                            // Find the highest index and add 1
                            $maxIndex = max(array_keys($option_value));
                            echo $maxIndex + 1;
                        }
                        ?>;

    function generateOptionsHTML(index) {
        return `
            <div class="field-options" style="margin-top: 8px;">
                <div class="input-group mb-2">
                    <input type="text" name="fields[${index}][options][0]" class="form-control" />
                    <button type="button" class="remove-option btn btn-outline-danger">×</button>
                </div>
                <button type="button" class="add-option btn btn-outline-primary btn-sm">Add Option</button>
            </div>`;
    }

    document.getElementById('add-field').addEventListener('click', function() {
        const tbody = document.getElementById('field-builder-body');
        const row = document.createElement('tr');
        row.classList.add('field-row');
        row.setAttribute('data-index', fieldIndex);

        const fieldTypes = ['text', 'textarea', 'select', 'checkbox', 'radio', 'number', 'email', 'url', 'file', 'date', 'time', 'datetime-local', 'month', 'week', 'range', 'color', 'tel'];
        const optionsHTML = fieldTypes.map(type => `<option value="${type}">${type}</option>`).join('');

        row.innerHTML = `
            <td><input type="text" name="fields[${fieldIndex}][label]" class="form-control"></td>
            <td><input type="text" name="fields[${fieldIndex}][name]" class="form-control"></td>
            <td>
                <select name="fields[${fieldIndex}][type]" class="form-select field-type">
                    ${optionsHTML}
                </select>
            </td>
            <td><div class="field-options" style="display: none;"></div></td>
            <td><input type="checkbox" name="fields[${fieldIndex}][required]" class="form-check-input"></td>
            <td><button type="button" class="remove-field btn btn-outline-danger">Remove</button></td>
        `;

        tbody.appendChild(row);
        fieldIndex++;
    });

    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-field')) {
            e.target.closest('tr').remove();
        }

        if (e.target.classList.contains('remove-option')) {
            e.target.closest('.input-group').remove();
        }

        if (e.target.classList.contains('add-option')) {
            const row = e.target.closest('.field-row');
            const index = row.dataset.index;
            const container = row.querySelector('.field-options');
            const newIndex = container.querySelectorAll('input').length;

            const newOption = document.createElement('div');
            newOption.className = 'input-group mb-2';
            newOption.innerHTML = `
                <input type="text" name="fields[${index}][options][${newIndex}]" class="form-control" />
                <button type="button" class="remove-option btn btn-outline-danger">×</button>
            `;
            container.insertBefore(newOption, e.target);
        }
    });

    document.addEventListener('change', function(e) {
        if (e.target.classList.contains('field-type')) {
            const row = e.target.closest('.field-row');
            const index = row.dataset.index;
            let optionsWrapper = row.querySelector('.field-options');

            if (['select', 'checkbox', 'radio'].includes(e.target.value)) {
                if (!optionsWrapper.innerHTML.trim()) {
                    optionsWrapper.outerHTML = generateOptionsHTML(index);
                }
                optionsWrapper.style.display = '';
            } else {
                optionsWrapper.style.display = 'none';
            }
        }
    });
</script>

<?php
ob_end_flush();
