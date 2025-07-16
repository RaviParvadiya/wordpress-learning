<?php
add_action('admin_menu', function () {
    // Main menu
    add_menu_page(
        'Repeater Fields',
        'Repeater Fields',
        'manage_options',
        'repeater-fields',
        'render_repeater_page',
        'dashicons-editor-table',
        80
    );

    // Submenu - default list view (optional, usually repeats main page)
    add_submenu_page(
        'repeater-fields',
        'All Repeaters',
        'All Repeaters',
        'manage_options',
        'repeater-fields',
        'render_repeater_page'
    );

    // Submenu - add new repeater (custom action)
    add_submenu_page(
        'repeater-fields',
        'Add New',
        'Add New',
        'manage_options',
        'repeater-fields&action=add',
        function () {
            // Redirect to the main page with `action=add`
            wp_redirect(admin_url('admin.php?page=repeater-fields&action=add'));
            exit;
        }
    );
});

function render_repeater_page() {
    global $wpdb;
    $table = $wpdb->prefix . 'repeatersBuilder';

    $action = $_GET['action'] ?? '';
    $edit_id = isset($_GET['edit']) ? intval($_GET['edit']) : 0;
    $is_edit = $edit_id > 0;
    // Handle Save
    
    // DELETE
    if ($action === 'delete' && isset($_GET['id'])) {
        $wpdb->delete($table, ['id' => intval($_GET['id'])]);
        echo '<div class="notice notice-success"><p>Deleted!</p></div>';
    }

    // HANDLE ADD/EDIT (Placeholder for future form)
    if ($action === 'add' || ($action === 'edit' && $edit_id > 0)) {
        // Include or call your form handling code here
        include plugin_dir_path(__FILE__) . '/builderAdminPage.php';
        return;
    }

    // LISTING TABLE
    $repeaters = $wpdb->get_results("SELECT * FROM $table");

    echo '<div class="wrap"><h1>Repeater Field Sets <a href="?page=repeater-fields&action=add" class="page-title-action">Add New</a></h1>';
    echo '<table class="wp-list-table widefat striped">';
    echo '<thead><tr><th>ID</th><th>Name</th><th>Slug</th><th>How to use.</th><th>Post Type</th><th>Actions</th></tr></thead><tbody>';

    foreach ($repeaters as $r) {
        echo "<tr>
            <td>{$r->id}</td>
            <td>{$r->repeater_name}</td>
            <td>{$r->repeater_slug}</td>";
            ?>
            <td>
                <span class="copy-field" data-value="'<?php echo $r->repeater_slug; ?>'">
                <?php echo $r->repeater_slug; ?>
                </span>
            </td>
          <?php
        echo "<td>{$r->post_type}</td>
            <td>
                <a href='?page=repeater-fields&action=edit&edit={$r->id}'>Edit</a> |
                <a href='?page=repeater-fields&action=delete&id={$r->id}' onclick=\"return confirm('Are you sure you want to delete this repeater?')\">Delete</a>
            </td>
        </tr>";
    }

    echo '</tbody></table></div>';
    ?>
    <style>
        .copy-field {
            cursor: pointer;
            color: #000;
            padding: 5px 10px;
            background: #00ffff2e;
            display: flex;
            width: fit-content;
            border: 1px solid #f2f2f2;
        }
       
    </style>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.copy-field').forEach(function (element) {
            element.addEventListener('click', function () {
            const text = this.getAttribute('data-value');

            // Create a temporary input to hold the text
            const input = document.createElement('input');
            input.value = text;
            document.body.appendChild(input);
            input.select();
            input.setSelectionRange(0, 99999); // For mobile devices

            document.execCommand('copy');
            document.body.removeChild(input);

            // Optional: feedback to user
            // alert('Copied: ' + text);
            });
        });
        });
    </script>

    <?php
}
