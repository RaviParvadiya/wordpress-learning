<?php

add_action('admin_menu', function () {
    add_submenu_page(
        'repeater-fields',
        'All Options',
        'All Options',
        'manage_options',
        'options-page-builder',
        'render_options_page'
    );

    add_submenu_page(
        'repeater-fields',
        'Add Option',
        'Add Option',
        'manage_options',
        'options-page-builder&action=add',
        function () {
            wp_redirect(admin_url('admin.php?page=options-page-builder&action=add'));
            exit;
        }
    );
});

function render_options_page()
{
    global $wpdb;
    $table = $wpdb->prefix . 'options_builder';

    $action = $_GET['action'] ?? '';
    $edit_id = isset($_GET['edit']) ? intval($_GET['edit']) : 0;

    if ($action === 'delete' && isset($_GET['id'])) {
        $wpdb->delete($table, ['id' => intval($_GET['id'])]);
        $option_name = 'custom_options_' . intval($_GET['id']);
        delete_option($option_name);
        echo '<div class="notice notice-success is-dismissible"><p>Deleted!</p></div>';
    }

    if ($action === 'add' || ($action === 'edit' && $edit_id > 0)) {
        include plugin_dir_path(__FILE__) . 'options-admin-page.php';
        return;
    }

    echo '<div class="wrap"><h1>All options <a href="?page=options-page-builder&action=add" class="page-title-action">Add New</a></h1>';
    echo '<table class="wp-list-table widefat striped">';
    echo '<thead><tr><th>ID</th><th>Name</th><th>Slug</th><th>Actions</th></tr></thead><tbody>';

    $options = $wpdb->get_results("SELECT * FROM `$table`");
    foreach ($options as $o) {
        echo "<tr>
            <td>{$o->id}</td>
            <td>{$o->option_name}</td>
            <td>{$o->option_slug}</td>";
?>
        <td>
            <a href='?page=options-page-builder&action=edit&edit=<?php echo intval($o->id) ?>'>Edit</a> |
            <a href='?page=options-page-builder&action=delete&id=<?php echo intval($o->id) ?>' onclick="return confirm('Are you sure you want to delete this options page?')">Delete</a>
        </td>
        </tr>
<?php
    }

    echo '</tbody></table></div>';
}
