<?php
ob_start();
global $wpdb;
$table = $wpdb->prefix . 'repeatersBuilder';

$edit_id = isset($_GET['edit']) ? intval($_GET['edit']) : 0;
$is_edit = $edit_id > 0;

if ($is_edit) {
    $existing = $wpdb->get_row("SELECT * FROM $table WHERE id = $edit_id");
    $selected_post_types = [$existing->post_type];
    $repeater_name = $existing->repeater_name;
    $fields = json_decode($existing->fields, true) ?? [];
} else {
    $selected_post_types = [];
    $repeater_name = '';
    $fields = [];
}

$post_types = get_post_types(['public' => true], 'objects');
unset($post_types['attachment']);
?>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous" />

<div class="wrap">
    <h1 class="mb-4"><?php echo $is_edit ? 'Edit' : 'Add'; ?> Repeater Field Builder</h1>

    <form id="repeater-builder-form">
        <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="fields-tab" data-bs-toggle="tab" data-bs-target="#fields" type="button" role="tab" aria-controls="fields" aria-selected="true">Fields</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="condition-tab" data-bs-toggle="tab" data-bs-target="#condition" type="button" role="tab" aria-controls="condition" aria-selected="false">Condition</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="extra-tab" data-bs-toggle="tab" data-bs-target="#extra" type="button" role="tab" aria-controls="extra" aria-selected="false">Extra</button>
            </li>
        </ul>

        <div class="tab-content" id="myTabContent">
            <div class="tab-pane fade show active" id="fields" role="tabpanel" aria-labelledby="fields-tab">
                <input type="hidden" name="edit_id" value="<?php echo esc_attr($edit_id); ?>">
                <input type="hidden" name="repeater_nonce" value="<?php echo wp_create_nonce('repeater_save_nonce'); ?>">

                <div class="mb-3">
                    <label class="form-label">Repeater Name</label>
                    <input type="text" name="repeater_name" class="form-control" required value="<?php echo esc_attr($repeater_name); ?>">
                </div>
                <?php
                // From DB: stored as 'post,page'
                $selected_post_types_string = $existing->post_type ?? '';
                $selected_post_types = array_filter(explode(',', $selected_post_types_string));

                $selected_posts_query_string = $existing->posts_query ?? '';
                $selected_posts_query = array_filter(explode(',', $selected_posts_query_string));
                ?>
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
                        <?php foreach ($fields as $index => $field) : ?>
                            <tr class="field-row" data-index="<?php echo $index; ?>">
                                <td><input type="text" name="fields[<?php echo $index; ?>][label]" class="form-control" value="<?php echo esc_attr($field['label']); ?>"></td>
                                <td><input type="text" name="fields[<?php echo $index; ?>][name]" class="form-control" value="<?php echo esc_attr($field['name']); ?>"></td>
                                <td>
                                    <select name="fields[<?php echo $index; ?>][type]" class="form-select field-type">
                                        <?php foreach (['text','textarea','select','checkbox','radio','number','email','url','file','date','time','datetime-local','month','week','range','color','tel'] as $type) : ?>
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
            <div class="tab-pane fade" id="condition" role="tabpanel" aria-labelledby="condition-tab">
                <div id="repeater-wrapper">
                    <!-- JavaScript will populate rows here -->
                </div>
                <p>
                    <button type="button" class="btn btn-primary" onclick="addRepeaterRow()">+ Add Row</button>
                </p>
            </div>
            <div class="tab-pane fade" id="extra" role="tabpanel" aria-labelledby="extra-tab">
                <!-- Extra tab content goes here -->
            </div>
        </div>
        <button type="submit" class="btn btn-success">Save Settings</button>
    </form>
</div>

<script>
const allPostTypes = <?php echo json_encode($post_types); ?>;
// const ajaxurl = "<?php echo admin_url('admin-ajax.php'); ?>";

function addRepeaterRow(postType = '', selectedPosts = []) {
    const rowIndex = document.querySelectorAll('.repeater-row').length;

    let postTypeOptions = `<option value="">Select Post Type</option>`;
    for (const [slug, pt] of Object.entries(allPostTypes)) {
        postTypeOptions += `<option value="${slug}" ${slug === postType ? 'selected' : ''}>${pt.labels.name}</option>`;
    }

    const rowHTML = `
        <div class="repeater-row" style="margin-bottom: 20px;">
            <div style="width:30%">
            <label>Post Type:</label>
            <select name="repeater[${rowIndex}][post_type]" onchange="loadPosts(this, ${rowIndex})">
                ${postTypeOptions}
            </select>
            </div>
            <div style="width:30%">
            <label>Posts:</label>
            <select name="repeater[${rowIndex}][posts][]" multiple class="posts-select"  multiselect-hide-x="true" data-index="${rowIndex}">
                <option value="">Select Posts</option>
                <!-- Options will be populated by JS -->
            </select>
            </div>
        </div>
    `;

    document.getElementById('repeater-wrapper').insertAdjacentHTML('beforeend', rowHTML);
    const postsSelect1134 = document.querySelectorAll('.multiselect-dropdown');
    if (postsSelect1134.length) {
        postsSelect1134.forEach(el => el.remove());
    }
 
    MultiselectDropdown(window.MultiselectDropdownOptions);
    if (postType) {
        loadPostsByAjax(postType, rowIndex, selectedPosts);
    }
}


function loadPosts(selectEl, rowIndex) {
    const postType = selectEl.value;

    let retu = loadPostsByAjax(postType, rowIndex);
    // if(retu){
    
    // }
}

function loadPostsByAjax(postType, rowIndex, selectedPosts = []) {
    fetch(ajaxurl + '?action=get_posts_by_type&post_type=' + postType)
        .then(response => response.json())
        .then(posts => {
           
            const postsSelect = document.querySelector(`select.posts-select[data-index="${rowIndex}"]`);
            postsSelect.innerHTML = '';
            posts.forEach(post => {
                const selected = selectedPosts.includes(post.ID.toString()) ? 'selected' : '';
                postsSelect.innerHTML += `<option value="${post.ID}" ${selected}>${post.post_title}</option>`;
            });
            setTimeout(() => { 
                const postsSelect1134 = document.querySelectorAll('.multiselect-dropdown');
                if (postsSelect1134.length) {
                    postsSelect1134.forEach(el => el.remove());
                }
                MultiselectDropdown(window.MultiselectDropdownOptions); 
            }, 100);
        });
}
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
<script>
    let fieldIndex = <?php echo count($fields); ?>;

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

    document.getElementById('add-field').addEventListener('click', function () {
        const tbody = document.getElementById('field-builder-body');
        const row = document.createElement('tr');
        row.classList.add('field-row');
        row.setAttribute('data-index', fieldIndex);

        const fieldTypes = ['text','textarea','select','checkbox','radio','number','email','url','file','date','time','datetime-local','month','week','range','color','tel'];
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

    document.addEventListener('click', function (e) {
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

    document.addEventListener('change', function (e) {
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

<?php ob_end_flush(); ?>
