<?php

/**
 * Template Name: Dynamic ACF Template
 */

get_header();
?>

<main id="primary" class="site-main">
    <h1><?php the_title(); ?></h1>

    <?php
    // Get the ACF field value
    $dynamic_text = get_field('dynamic_text');
    ?>

    <p>Field value: <strong><?php echo esc_html($dynamic_text); ?></strong></p>

    <?php
    // Example form to update the field
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['new_value'])) {
        $new_value = sanitize_text_field($_POST['new_value']);
        update_field('dynamic_text', $new_value, get_the_ID());
        echo '<p>Updated!</p>';
    }
    ?>

    <form method="post">
        <input type="text" name="new_value" value="<?php echo esc_attr($dynamic_text); ?>">
        <button type="submit">Update Field</button>
    </form>

    <!-- <img src="<?php // the_field('dynamic_image'); 
                    ?>" /> -->

    <?php
    $image = get_field('dynamic_image');

    if ($image || is_array($image)) {
        echo '<div class="acf-image-preview">';
        echo '<img src="' . esc_url($image) . '" alt="' . esc_attr($image) . '" width="300">';
        echo '</div>';
    } else {
        echo '<p>No image uploaded.</p>';
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['new_image'])) {
        $file = $_FILES['new_image'];

        // Handle upload using WordPress API
        require_once(ABSPATH . 'wp-admin/includes/file.php');
        require_once(ABSPATH . 'wp-admin/includes/image.php');

        $uploaded = media_handle_upload('new_image', get_the_ID());

        if (is_wp_error($uploaded)) {
            echo '<p style="color:red;">Image upload failed.</p>';
        } else {
            update_field('dynamic_image', $uploaded, get_the_ID());
            echo '<p style="color:green;">Image updated!</p>';
        }
    }
    ?>

    <form method="post" enctype="multipart/form-data">
        <p><label>Upload Image: <input type="file" name="new_image"></label></p>
        <button type="submit">Upload Image</button>
    </form>

</main>

<?php get_footer(); ?>