<?php

function stock_manual_page()
{

?>

    <div class="wrap">
        <h1>📤 Manual Stock</h1>

        <div class="ajax-response"></div>
        <form id="manual-stock-form">

            <table class="form-table">
                <tr>
                    <th><label for="product_id">Product</label></th>
                    <td>
                        <select name="product_id" required>
                            <option value="">— Select Product —</option>
                            <?php
                            $products = wc_get_products(['limit' => -1]);
                            foreach ($products as $product) {
                                echo '<option value="' . $product->get_id() . '">' . esc_html($product->get_name()) . '</option>';
                            }
                            ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th><label for="container_id">Container</label></th>
                    <td>
                        <select name="container_id" required>
                            <option value="">— Select Container —</option>
                            <?php
                            global $wpdb;
                            $containers = $wpdb->get_results("SELECT id, name FROM {$wpdb->prefix}stock_containers ORDER BY created_at DESC");
                            foreach ($containers as $container) {
                                echo '<option value="' . $container->id . '">' . esc_html($container->name) . '</option>';
                            }
                            ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th><label for="stock_action">Action</label></th>
                    <td>
                        <select name="stock_action" required>
                            <option value="out">Stock Out</option>
                            <option value="in">Stock In</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th><label for="quantity">Quantity</label></th>
                    <td><input type="number" name="quantity" min="1" required></td>
                </tr>
            </table>

            <?php submit_button('Submit', 'primary', 'save_manual_stock_submit'); ?>
        </form>

        <!--         <hr>

        <h2>Total entry</h2>
        <form method="post" id="update-manual-stock-form">

            <table class="widefat fixed striped">
                <thead>
                    <tr>
                        <th>Container</th>
                        <th>Product</th>
                        <th>Quantity</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="manual-stock-table-body">

                </tbody>
            </table>

        </form> -->
    </div>

<?php } ?>