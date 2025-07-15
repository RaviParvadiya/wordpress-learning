<?php

function portfolio_list_page()
{
    $users = get_users(['role__not_in' => ['Administrator']]); // Exclude admins, adjust as needed
?>
    <style>
        .portfolios-container {
            max-width: 900px;
            margin: 40px auto;
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 4px 24px rgba(0, 0, 0, 0.08);
            padding: 32px 24px;
        }

        .portfolios-container h2 {
            text-align: center;
            margin-bottom: 32px;
            color: #2d3748;
            font-size: 2rem;
            font-weight: 700;
        }

        .portfolios-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            background: #fafbfc;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
        }

        .portfolios-table th,
        .portfolios-table td {
            padding: 16px 12px;
            text-align: left;
        }

        .portfolios-table th {
            background: #f3f4f6;
            color: #374151;
            font-weight: 600;
            border-bottom: 2px solid #e5e7eb;
        }

        .portfolios-table tr {
            transition: background 0.2s;
        }

        .portfolios-table tbody tr:hover {
            background: #f1f5f9;
        }

        .portfolios-table td {
            color: #4b5563;
            border-bottom: 1px solid #e5e7eb;
        }

        .portfolios-table tr:last-child td {
            border-bottom: none;
        }

        .no-portfolios-row td {
            text-align: center;
            color: #9ca3af;
            font-style: italic;
            padding: 32px 0;
            font-size: 1.1rem;
        }

        .view-btn {
            background: #2563eb;
            color: #fff;
            border: none;
            border-radius: 6px;
            padding: 8px 18px;
            font-size: 1rem;
            cursor: pointer;
            transition: background 0.2s, box-shadow 0.2s;
            box-shadow: 0 1px 4px rgba(37, 99, 235, 0.08);
            text-decoration: none;
            display: inline-block;
        }

        .view-btn:hover {
            background: #1d4ed8;
        }

        @media (max-width: 600px) {
            .portfolios-container {
                padding: 12px 2px;
            }

            .portfolios-table th,
            .portfolios-table td {
                padding: 10px 6px;
                font-size: 0.95rem;
            }

            .view-btn {
                padding: 6px 10px;
                font-size: 0.95rem;
            }
        }
    </style>
    <div class="portfolios-container">
        <h2>Portfolios</h2>
        <table class="portfolios-table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>View</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($users) : ?>
                    <?php foreach ($users as $user): ?>
                        <?php $portfolio_url = home_url('/' . $user->user_nicename); ?>
                        <tr>
                            <td><?php echo ucfirst(esc_html($user->display_name)); ?></td>
                            <td><?php echo esc_html($user->user_email); ?></td>
                            <td>
                                <?php if ($portfolio_url): ?>
                                    <a href="<?php echo esc_url($portfolio_url); ?>" class="view-btn" target="_blank" rel="noopener">View</a>
                                <?php else: ?>
                                    <span style="color:#9ca3af;font-style:italic;">No link</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr class="no-portfolios-row">
                        <td colspan="3">No portfolios found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
<?php } ?>