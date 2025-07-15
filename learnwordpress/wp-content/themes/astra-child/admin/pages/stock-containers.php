<?php

function stock_containers_page()
{
?>
    <div class="wrap">
        <h1>🧊 Containers</h1>
        <p>Manage containers and product assignments here.</p>

        <br>

        <div class="ajax-response"></div>
        <h2>Add New Container</h2>
        <form method="post" id="stock-container-form">

            <table class="form-table">
                <tr>
                    <th scope="row"><label for="container_name">Container Name</label></th>
                    <td><input name="container_name" type="text" id="container_name" class="regular-text" required></td>
                </tr>
                <tr>
                    <th><label for="container_type">Type</label></th>
                    <td>
                        <select name="container_type" required>
                            <option value="">- Select Type -</option>
                            <option value="incoming">Incoming</option>
                            <option value="outgoing">Outgoing</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th><label for="container_status">Status</label></th>
                    <td>
                        <select name="container_status" required>
                            <option value="">- Select Status -</option>
                            <option value="pending">Pending</option>
                            <option value="shipped">Shipped</option>
                            <option value="completed">Completed</option>
                            <option value="cancelled">Cancelled</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th><label for="container_date">Date</label></th>
                    <td><input type="date" name="container_date" required></td>
                </tr>
            </table>

            <?php submit_button('Add Container', 'primary', 'save_container_submit'); ?>
        </form>

        <hr>

        <h2>All Containers</h2>

        <form method="post" id="stock-container-filter-form">
            <input type="hidden" name="page" value="stock-containers">
            <select name="filter_type">
                <option value="">- All Type -</option>
                <option value="incoming" <?php selected($_POST['filter_type'] ?? '', 'incoming'); ?>>Incoming</option>
                <option value="outgoing" <?php selected($_POST['filter_type'] ?? '', 'outgoing'); ?>>Outgoing</option>
            </select>
            <select name="filter_status">
                <option value="">- All Status -</option>
                <option value="pending" <?php selected($_POST['filter_status'] ?? '', 'pending'); ?>>Pending</option>
                <option value="shipped" <?php selected($_POST['filter_status'] ?? '', 'shipped'); ?>>Shipped</option>
                <option value="completed" <?php selected($_POST['filter_status'] ?? '', 'completed'); ?>>Completed</option>
                <option value="cancelled" <?php selected($_POST['filter_status'] ?? '', 'cancelled'); ?>>Cancelled</option>
            </select>

            <?php submit_button('Filter', '', '', false); ?>
        </form>
        <br>

        <table class="widefat fixed striped">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Type</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th>Created At</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody id="container-table-body">
                <!-- AJAX-filled rows go here -->
            </tbody>
        </table>
    </div>

<?php } ?>