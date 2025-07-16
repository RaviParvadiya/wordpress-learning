<?php
add_action('admin_menu', function () {
    add_submenu_page(
        'repeater-fields', // Parent slug (adjust if needed)
        'Plugin Documentation',
        'Documentation',
        'manage_options',
        'repeater-plugin-docs',
        'repeater_plugin_docs_page'
    );
});

function repeater_plugin_docs_page() {
    ?>
    <div class="wrap">
        <h1>Repeater Field Builder - Documentation</h1>

        <p>This plugin allows you to create custom repeater fields for any post type and retrieve their data easily using the <code>my_get_field()</code> helper function or a frontend shortcode.</p>

        <hr>

        <h2>🔁 How to Retrieve Repeater Field Data (PHP)</h2>
        <p>Use this function to fetch saved repeater field values:</p>

<pre><code>&lt;?php
$data = my_get_field('your_repeater_slug', 'field_key', get_the_ID());
?&gt;
</code></pre>

        <ul>
            <li><strong>repeater_slug</strong>: The slug of your repeater group.</li>
            <li><strong>field_key</strong>: The key (slug) of the field inside the repeater.</li>
            <li><strong>get_the_ID()</strong>: Optional. Use your own post ID if needed.</li>
        </ul>

        <h3>Examples:</h3>

        <h4>🔹 Get All Rows</h4>
<pre><code>$data = my_get_field('team_repeater');
foreach ($data as $row) {
    echo $row['name'];
    echo $row['position'];
}</code></pre>

        <h4>🔹 Get Specific Field</h4>
<pre><code>$names = my_get_field('team_repeater', 'name');
print_r($names);</code></pre>

        <h2>🎨 Supported Field Types</h2>
        <ul style="display: grid; grid-template-columns: repeat(6, 1fr); gap: 10px;">
            <li>text</li>
            <li>textarea</li>
            <li>select</li>
            <li>checkbox</li>
            <li>radio</li>
            <li>number</li>
            <li>email</li>
            <li>url</li>
            <li>file</li>
            <li>date</li>
            <li>time</li>
            <li>datetime-local</li>
            <li>month</li>
            <li>week</li>
            <li>range</li>
            <li>color</li>
            <li>tel</li>
        </ul>

        <hr>

        <h2>📦 Shortcode: <code>[custom_repeater_table]</code></h2>

        <p>This shortcode displays a repeater field’s data in a responsive HTML table with media and color previews.</p>

        <h3>🧩 Syntax</h3>
<pre><code>[custom_repeater_table repeater_slug="your_slug" post_id="optional_post_id"]</code></pre>

        <h3>🧠 Parameters</h3>
        <ul>
            <li><strong>repeater_slug</strong> (required) – Repeater group slug</li>
            <li><strong>post_id</strong> (optional) – Defaults to current post if omitted</li>
        </ul>

        <h3>💡 Features</h3>
        <ul>
            <li>Automatically generates table headers</li>
            <li>Image previews for <code>jpg, png, webp, gif</code></li>
            <li>Video previews for <code>mp4, webm</code></li>
            <li>Audio playback for <code>mp3, wav</code></li>
            <li>PDF viewer link</li>
            <li>Color fields render as a 50×50 color box with code</li>
            <li>Other values rendered as plain text</li>
        </ul>

        <h3>🔍 Example</h3>
<pre><code>[custom_repeater_table repeater_slug="team_members"]</code></pre>

        <hr>

        <h2>🆘 Need Help?</h2>
        <p>If you face any issues, feel free to reach out to the plugin author or submit a support request.</p>
    </div>
    <?php
}
