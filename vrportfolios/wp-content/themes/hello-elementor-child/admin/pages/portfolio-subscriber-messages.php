<?php

function render_subscriber_messages_page()
{
    global $wpdb;
    $current_user_id = get_current_user_id();
    $table = $wpdb->prefix . 'portfolio_messages';

    // Handle delete action
    if (isset($_POST['delete_message_id']) && is_numeric($_POST['delete_message_id'])) {
        $delete_id = intval($_POST['delete_message_id']);
        // Only allow deleting messages received by the current user
        $wpdb->delete($table, [
            'id' => $delete_id,
            'receiver_id' => $current_user_id
        ]);
        echo '<div class="notice-success" style="margin-bottom:16px;color:#16a34a;background:#f0fdf4;padding:12px 20px;border-radius:8px;">Message deleted.</div>';
    }

    $messages = $wpdb->get_results(
        $wpdb->prepare("SELECT * FROM $table WHERE receiver_id = %d ORDER BY sent_at DESC", $current_user_id)
    );
?>
    <style>
        .messages-container {
            max-width: 900px;
            margin: 40px auto;
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 4px 24px rgba(0, 0, 0, 0.08);
            padding: 32px 24px;
        }

        .messages-container h2 {
            text-align: center;
            margin-bottom: 32px;
            color: #2d3748;
            font-size: 2rem;
            font-weight: 700;
        }

        .messages-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            background: #fafbfc;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
        }

        .messages-table th,
        .messages-table td {
            padding: 16px 12px;
            text-align: left;
        }

        .messages-table th {
            background: #f3f4f6;
            color: #374151;
            font-weight: 600;
            border-bottom: 2px solid #e5e7eb;
        }

        .messages-table tr {
            transition: background 0.2s;
        }

        .messages-table tbody tr:hover {
            background: #f1f5f9;
        }

        .messages-table td {
            color: #4b5563;
            border-bottom: 1px solid #e5e7eb;
        }

        .messages-table tr:last-child td {
            border-bottom: none;
        }

        .no-messages-row td {
            text-align: center;
            color: #9ca3af;
            font-style: italic;
            padding: 32px 0;
            font-size: 1.1rem;
        }

        .delete-btn {
            background: #ef4444;
            color: #fff;
            border: none;
            border-radius: 6px;
            padding: 8px 18px;
            font-size: 1rem;
            cursor: pointer;
            transition: background 0.2s, box-shadow 0.2s;
            box-shadow: 0 1px 4px rgba(239, 68, 68, 0.08);
        }

        .delete-btn:hover {
            background: #dc2626;
        }

        @media (max-width: 600px) {
            .messages-container {
                padding: 12px 2px;
            }

            .messages-table th,
            .messages-table td {
                padding: 10px 6px;
                font-size: 0.95rem;
            }

            .delete-btn {
                padding: 6px 10px;
                font-size: 0.95rem;
            }
        }
    </style>
    <div class="messages-container">
        <h2>Messages Received</h2>
        <table class="messages-table">
            <thead>
                <tr>
                    <th>From</th>
                    <th>Email</th>
                    <th>Message</th>
                    <th>Sent At</th>
                    <th>Delete</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($messages) : ?>
                    <?php foreach ($messages as $msg): ?>
                        <tr>
                            <td><?php echo ucfirst(esc_html($msg->sender_name ?: 'Anonymous')); ?></td>
                            <td><?php echo esc_html($msg->sender_email); ?></td>
                            <td><?php echo esc_html($msg->message); ?></td>
                            <td><?php echo esc_html($msg->sent_at); ?></td>
                            <td>
                                <form method="post" style="margin:0;">
                                    <input type="hidden" name="delete_message_id" value="<?php echo esc_attr($msg->id); ?>">
                                    <button type="submit" class="delete-btn" onclick="return confirm('Are you sure you want to delete this message?');">Delete</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr class="no-messages-row">
                        <td colspan="5">No messages yet.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
<?php } ?>