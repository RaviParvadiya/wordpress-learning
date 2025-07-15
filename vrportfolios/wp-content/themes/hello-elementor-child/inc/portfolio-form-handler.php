<?php

// Handle Form Submission
add_action('admin_init', 'save_portfolio_form_data');
function save_portfolio_form_data()
{
    if (!isset($_POST['save_portfolio_data'])) return;
    if (!wp_verify_nonce($_POST['_wpnonce'], 'save_portfolio_data')) return;

    $user_id = get_current_user_id();

    update_user_meta($user_id, 'portfolio_fname', sanitize_text_field($_POST['portfolio_fname']));
    update_user_meta($user_id, 'portfolio_lname', sanitize_text_field($_POST['portfolio_lname']));
    update_user_meta($user_id, 'portfolio_role', sanitize_text_field($_POST['portfolio_role']));
    update_user_meta($user_id, 'portfolio_description', sanitize_textarea_field($_POST['portfolio_description']));
    update_user_meta($user_id, 'portfolio_about', sanitize_textarea_field($_POST['portfolio_about']));
    update_user_meta($user_id, 'portfolio_work_description', sanitize_textarea_field($_POST['portfolio_work_description']));

    update_user_meta($user_id, 'portfolio_github', esc_url_raw($_POST['portfolio_github']));
    update_user_meta($user_id, 'portfolio_twitter', esc_url_raw($_POST['portfolio_twitter']));
    update_user_meta($user_id, 'portfolio_linkedin', esc_url_raw($_POST['portfolio_linkedin']));

    update_user_meta($user_id, 'portfolio_template', sanitize_text_field($_POST['portfolio_template']));

    // Save skills as JSON
    $skills = array_map('sanitize_text_field', $_POST['selected_skills'] ?? []);
    update_user_meta($user_id, 'portfolio_skills', json_encode($skills));

    // Save other (user-defined) skills as JSON
    $other_skills = array();
    if (!empty($_POST['other_skills']) && is_array($_POST['other_skills'])) {
        foreach ($_POST['other_skills'] as $skill) {
            $title = isset($skill['title']) ? sanitize_text_field($skill['title']) : '';
            $link = isset($skill['link']) ? esc_url_raw($skill['link']) : '';
            if ($title) {
                $other_skills[] = [
                    'title' => $title,
                    'link' => $link
                ];
            }
        }
    }
    update_user_meta($user_id, 'portfolio_other_skills', json_encode($other_skills));

    // Save projects as JSON
    /* $projects = array_map(function ($proj) {
        return [
            'subtitle' => sanitize_text_field($proj['subtitle']),
            'title' => sanitize_text_field($proj['title']),
            'description' => sanitize_textarea_field($proj['description']),
        ];
    }, $_POST['portfolio_projects'] ?? []); */
    $projects = $_POST['portfolio_projects'] ?? [];
    $uploaded_files = $_FILES['portfolio_projects_screenshot'] ?? [];

    foreach ($projects as $i => $project) {
        $subtitle = sanitize_text_field($project['subtitle']);
        $title = sanitize_text_field($project['title']);
        $description = sanitize_textarea_field($project['description']);

        if (!$subtitle || !$title || !$description) return;

        $project_data = [
            'subtitle' => $subtitle,
            'title' => $title,
            'description' => $description,
        ];

        if (isset($uploaded_files['tmp_name'][$i]) && $uploaded_files['error'][$i] === UPLOAD_ERR_OK && $uploaded_files['size'][$i] <= 512000) {
            $file = [
                'name' => $uploaded_files['name'][$i],
                'type' => $uploaded_files['type'][$i],
                'tmp_name' => $uploaded_files['tmp_name'][$i],
                'error' => $uploaded_files['error'][$i],
                'size' => $uploaded_files['size'][$i]
            ];

            if (! function_exists('wp_handle_upload')) {
                require_once ABSPATH . 'wp-admin/includes/file.php';
            }

            $allowed_formats = ['jpg', 'png', 'jpeg', 'webp'];
            $file_name = basename($file['name']);
            $file_type = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mime_type = finfo_file($finfo, $file['tmp_name']);
            finfo_close($finfo);
            $allowed_mimes = ['image/jpg', 'image/jpeg', 'image/png', 'image/webp'];

            if (!in_array($mime_type, $allowed_mimes) || !in_array($file_type, $allowed_formats)) return;

            $upload_dir = wp_upload_dir();

            $image_subdir = '/portfolio-projects/';
            $image_path = wp_normalize_path($upload_dir['basedir']) . $image_subdir;

            if (! file_exists($image_path)) {
                wp_mkdir_p($image_path);
            }

            $overrides = [
                'test_form' => false,
                'upload_error_handler' => false,
                'unique_filename_callback' => function ($dir, $name, $ext) use ($user_id, $i) {
                    return 'project-image-' . $user_id . '-' . $i . $ext;
                }
            ];

            $custom_upload_dir_filter = function ($dirs) use ($image_subdir, $upload_dir) {
                $dirs['subdir'] = $image_subdir;
                $dirs['path'] = wp_normalize_path($upload_dir['basedir']) . $image_subdir;
                $dirs['url'] = $upload_dir['baseurl'] . $image_subdir;
                return $dirs;
            };
            add_filter('upload_dir', $custom_upload_dir_filter);

            $upload = wp_handle_upload($file, $overrides);

            remove_filter('upload_dir', $custom_upload_dir_filter);

            if (!isset($upload['error'])) {
                $project_data['screenshot'] = esc_url_raw($upload['url']);
            }
        } elseif (!empty($project['screenshot'])) {
            // Preserve existing image if no new upload
            $project_data['screenshot'] = esc_url_raw($project['screenshot']);
        }

        $projects[$i] = $project_data;
    }
    update_user_meta($user_id, 'portfolio_projects', json_encode($projects));

    // Handle avatar file upload
    if (isset($_FILES['portfolio_avatar']) && $_FILES['portfolio_avatar']['error'] === UPLOAD_ERR_OK && $_FILES['portfolio_avatar']['size'] <= 512000) {
        $allowed_formats = ['jpg', 'png', 'jpeg', 'webp'];
        $file_name = basename($_FILES['portfolio_avatar']['name']);
        $file_type = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime_type = finfo_file($finfo, $_FILES['portfolio_avatar']['tmp_name']);
        finfo_close($finfo);
        $allowed_mimes = ['image/jpg', 'image/jpeg', 'image/png', 'image/webp'];
        if (!in_array($mime_type, $allowed_mimes)) {
            // Invalid file type, do not proceed
            return;
        }

        if (! function_exists('wp_handle_upload')) {
            require_once ABSPATH . 'wp-admin/includes/file.php';
        }

        if (in_array($file_type, $allowed_formats)) {
            $upload_dir = wp_upload_dir();

            $avatar_url = get_user_meta($user_id, 'portfolio_avatar', true);
            if ($avatar_url) {
                // Strip base URL to get relative path
                $relative_path = str_replace($upload_dir['baseurl'], '', $avatar_url);

                // Combine with base directory to get absolute file path
                $avatar_path = wp_normalize_path($upload_dir['basedir']) . $relative_path;

                if (strpos($avatar_path, '/portfolio-avatars/') !== false && file_exists($avatar_path)) {
                    // Delete the file
                    unlink($avatar_path);

                    // Optional: remove the stored URL in user meta
                    delete_user_meta($user_id, 'portfolio_avatar');
                }
            }

            $avatar_subdir = '/portfolio-avatars/';
            $avatar_path = wp_normalize_path($upload_dir['basedir']) . $avatar_subdir;

            // Create directory if it does not exist
            if (!file_exists($avatar_path)) {
                wp_mkdir_p($avatar_path);
            }

            // Set upload overrides
            $overrides = [
                'test_form' => false, // Disable form field check
                'upload_error_handler' => null, // No custom error handler
                'unique_filename_callback' => function ($dir, $name, $ext) use ($user_id) {
                    return 'avatar-user-' . $user_id . $ext; // Always use the same filename for this user
                }
            ];

            // Temporarily override upload directory for avatar uploads
            $custom_upload_dir_filter = function ($dirs) use ($avatar_subdir, $upload_dir) {
                $dirs['subdir'] = $avatar_subdir; // Set subdirectory for avatars
                $dirs['path'] = wp_normalize_path($upload_dir['basedir']) . $avatar_subdir; // Set full path
                $dirs['url'] = $upload_dir['baseurl'] . $avatar_subdir; // Set URL
                return $dirs;
            };
            add_filter('upload_dir', $custom_upload_dir_filter);

            // Do the actual upload
            $upload = wp_handle_upload($_FILES['portfolio_avatar'], $overrides);

            // Remove the filter to not affect other uploads
            remove_filter('upload_dir', $custom_upload_dir_filter);
        }

        if (!isset($upload['error'])) {
            $file_path = $upload['file'];

            // Double check actual file size on disk
            if (filesize($file_path) <= 512000) {
                update_user_meta($user_id, 'portfolio_avatar', esc_url_raw($upload['url']));
            } else {
                // Optionally delete the file if it exceeds size
                unlink($file_path);
            }
        }
    }
}

function handle_portfolio_image_upload($user_id, $file, $subdir, $filename_prefix, $index = null)
{
    if (! function_exists('wp_handle_upload')) {
        require_once ABSPATH . 'wp-admin/includes/file.php';
    }

    if (
        $file['error'] !== UPLOAD_ERR_OK ||
        $file['size'] > 512000
    ) {
        return false;
    }

    $allowed_formats = ['jpg', 'jpeg', 'png', 'webp'];
    $allowed_mimes = ['image/jpg', 'image/jpeg', 'image/png', 'image/webp'];

    $file_name = basename($file['name']);
    $file_type = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime_type = finfo_file($finfo, $file['tmp_name']);
    finfo_close($finfo);

    if (! in_array($file_type, $allowed_formats) || ! in_array($mime_type, $allowed_mimes)) {
        // Invalid file type, do not proceed
        return false;
    }

    $upload_dir = wp_upload_dir();
    $upload_path = wp_normalize_path($upload_dir['basedir']) . $subdir;

    // Create directory if it does not exist
    if (!file_exists($upload_path)) {
        wp_mkdir_p($upload_path);
    }

    // Set upload overrides
    $overrides = [
        'test_form' => false, // Disable form field check
        'upload_error_handler' => null, // No custom error handler
        'unique_filename_callback' => function ($dir, $name, $ext) use ($user_id, $filename_prefix, $index) {
            return $filename_prefix . '-' . $user_id . (isset($index) ? '-' . $index : '') . $ext; // Always use the same filename for this user
        }
    ];

    // Temporarily override upload directory for file uploads
    $custom_upload_dir_filter = function ($dirs) use ($subdir, $upload_dir) {
        $dirs['subdir'] = $subdir; // Set subdirectory
        $dirs['path'] = wp_normalize_path($upload_dir['basedir']) . $subdir; // Set full path
        $dirs['url'] = $upload_dir['baseurl'] . $subdir; // Set URL
        return $dirs;
    };
    add_filter('upload_dir', $custom_upload_dir_filter);

    // Do the actual upload
    $upload = wp_handle_upload($file, $overrides);

    // Remove the filter to not affect other uploads
    remove_filter('upload_dir', $custom_upload_dir_filter);

    return isset($upload['error']) ? false : esc_url_raw($upload['url']);
}
