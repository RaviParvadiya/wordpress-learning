<?php

function stock_container_detail_page()
{
    global $wpdb;

    // Validate and get container ID
    $container_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

    if (!$container_id) {
        echo '<div class="notice notice-error"><p>Invalid container ID.</p></div>';
        return;
    }

    // Fetch container
    $container = $wpdb->get_row($wpdb->prepare(
        "SELECT * FROM {$wpdb->prefix}stock_containers WHERE id = %d",
        $container_id
    ));

    if (!$container) {
        echo '<div class="notice notice-error"><p>Container not found.</p></div>';
        return;
    }

?>

    <div class="wrap">
        <h1>🧊 Manage Products in "<?php echo esc_html($container->name); ?>"</h1>

        <a href="<?php echo admin_url('admin.php?page=stock-containers'); ?>" class="button">← Back to Containers</a>

        <hr>

        <div class="ajax-response"></div>
        <h2>Assign New Product</h2>
        <form method="post" id="assign-product-form">
            <input type="hidden" name="container_id" value="<?php echo esc_attr($container_id); ?>">

            <table class="form-table">
                <tr>
                    <th><label for="product_id">Select Product</label></th>
                    <td>
                        <select name="product_id" required>
                            <option value="">— Select Product —</option>
                            <?php
                            $products = wc_get_products(['limit' => -1, 'status' => 'publish']);
                            foreach ($products as $product) {
                                echo '<option value="' . esc_attr($product->get_id()) . '">' . esc_html($product->get_name()) . '</option>';
                            }
                            ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th><label for="quantity">Quantity</label></th>
                    <td><input type="number" name="quantity" min="1" value="1" required></td>
                </tr>
            </table>

            <?php submit_button('Assign Product', 'primary', 'assign_product_submit'); ?>
        </form>

        <hr>

        <h2>Assigned Products</h2>
        <form method="post" id="update-quantity-form">
            <?php wp_nonce_field('update_quantities_action', 'update_quantities_nonce'); ?>

            <table class="widefat fixed striped">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Quantity</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="assigned-products-table-body">

                </tbody>
            </table>

            <p id="save-button-wrapper" style="display: none;">
                <?php submit_button('Save Changes', 'primary', 'save_quantity_changes', false); ?>
            </p>
        </form>
    </div>

<?php } ?>