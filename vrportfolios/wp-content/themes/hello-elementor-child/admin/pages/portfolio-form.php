<?php

function render_portfolio_form_page()
{
    $user_id = get_current_user_id();
    $fname = get_user_meta($user_id, 'portfolio_fname', true);
    $lname = get_user_meta($user_id, 'portfolio_lname', true);
    $avatar = get_user_meta($user_id, 'portfolio_avatar', true);
    $role = get_user_meta($user_id, 'portfolio_role', true);
    $description = get_user_meta($user_id, 'portfolio_description', true);
    $about = get_user_meta($user_id, 'portfolio_about', true);
    $work_description = get_user_meta($user_id, 'portfolio_work_description', true);
    $projects = json_decode(get_user_meta($user_id, 'portfolio_projects', true), true) ?: [];
    $github_url = get_user_meta($user_id, 'portfolio_github', true);
    $twitter_url = get_user_meta($user_id, 'portfolio_twitter', true);
    $linkedin_url = get_user_meta($user_id, 'portfolio_linkedin', true);
    $selected_template = get_user_meta($user_id, 'portfolio_template', true);

?>
    <div class="portfolio-admin-container">
        <h1>My Portfolio</h1>
        <form method="post" enctype="multipart/form-data">
            <?php wp_nonce_field('save_portfolio_data'); ?>

            <h2>First Name</h2>
            <input type="text" name="portfolio_fname" id="portfolio_fname" value="<?php echo esc_attr($fname); ?>" required></input>

            <h2>Last Name</h2>
            <input type="text" name="portfolio_lname" value="<?php echo esc_attr($lname); ?>" required></input>

            <h2>Upload your profile picture</h2>
            <input type="file" name="portfolio_avatar" id="portfolio_avatar" accept="image/*">
            <br>
            <small>This image will be used in your portfolio and as your avatar icon.</small>

            <h2>Role</h2>
            <input type="text" name="portfolio_role" value="<?php echo esc_attr($role); ?>" required></input>

            <h2>Description</h2>
            <textarea name="portfolio_description" rows="5" cols="60" required><?php echo esc_textarea($description); ?></textarea>

            <h2>About</h2>
            <textarea name="portfolio_about" rows="5" cols="60" required><?php echo esc_textarea($about); ?></textarea>

            <h2>Skills</h2>
            <div id="skills-wrapper">
                <?php
                // Get predefined skills from the skills page
                $predefined_skills = json_decode(get_option('portfolio_skills'), true) ?: [];
                $user_skills = json_decode(get_user_meta($user_id, 'portfolio_skills', true), true) ?: [];
                ?>

                <div class="skills-selection">
                    <h3>Select from available skills:</h3>
                    <div class="skills-grid">
                        <?php foreach ($predefined_skills as $i => $skill) : ?>
                            <label class="skill-checkbox">
                                <input type="checkbox"
                                    name="selected_skills[]"
                                    value="<?php echo esc_attr($skill['title']); ?>"
                                    <?php echo in_array($skill['title'], $user_skills) ? 'checked' : ''; ?>>
                                <span class="skill-label">
                                    <?php echo esc_html($skill['title']); ?>
                                </span>
                            </label>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Other Skills Section -->
                <h2>Other Skills</h2>
                <div id="other-skills-wrapper">
                    <?php
                    $other_skills = json_decode(get_user_meta($user_id, 'portfolio_other_skills', true), true) ?: [];
                    if (!empty($other_skills)) :
                        foreach ($other_skills as $i => $skill) : ?>
                            <div class="other-skill-item">
                                <input type="text" name="other_skills[<?php echo $i; ?>][title]" value="<?php echo esc_attr($skill['title']); ?>" placeholder="Skill name (required)" required />
                                <input type="text" name="other_skills[<?php echo $i; ?>][link]" value="<?php echo esc_url($skill['link'] ?? ''); ?>" placeholder="Skill image URL (optional)" />
                                <button type="button" class="remove-other-skill">Remove</button>
                            </div>
                    <?php endforeach;
                    endif; ?>
                </div>
                <button type="button" id="add-other-skill">Add Other Skill</button>

            </div>

            <h2>Work experience</h2>
            <textarea name="portfolio_work_description" rows="5" cols="60" placeholder="Tell something about experience."><?php echo esc_textarea($work_description); ?></textarea>

            <!-- FIXME: the get value if doesn't exists like the user's first time -->
            <h2>Projects</h2>
            <div id="projects-wrapper">
                <?php foreach ($projects as $i => $project) : ?>
                    <div class="project-item">
                        <input type="text" name="portfolio_projects[<?php echo $i; ?>][subtitle]" value="<?php echo esc_attr($project['subtitle']); ?>" placeholder="Project subtitle" required />
                        <input type="text" name="portfolio_projects[<?php echo $i; ?>][title]" value="<?php echo esc_attr($project['title']); ?>" placeholder="Project title" required />
                        <br>
                        <textarea name="portfolio_projects[<?php echo $i; ?>][description]" rows="5" cols="60" required><?php echo esc_textarea($project['description']); ?></textarea>
                        <label for="portfolio_projects_screenshot[<?php echo $i; ?>]">Project Image:</label>
                        <input type="file" name="portfolio_projects_screenshot[<?php echo $i; ?>]" id="portfolio_projects_screenshot[<?php echo $i; ?>]" accept="image/*">
                        <br>
                        <button type="button" class="remove-other-skill">Remove</button>

                        <!-- Hidden input to preserve existing screenshot URL -->
                        <input type="hidden" name="portfolio_projects[<?php echo $i; ?>][screenshot]" value="<?php echo esc_url($project['screenshot'] ?? ''); ?>" />
                    </div>
                <?php endforeach; ?>
            </div>
            <button type="button" id="add-project">Add Project</button>

            <h2>Choose template</h2>
            <select name="portfolio_template" id="portfolio_template">
                <?php
                foreach (glob(get_stylesheet_directory() . '/templates/*.php') as $file) {
                    $file_name = basename($file); // e.g., template-1.php
                    $template_key = basename($file, '.php'); // e.g., template-1
                    $template_label = ucwords(str_replace('-', ' ', $template_key)); // e.g., Template 1

                    // Skip default template from dropdown
                    if ($template_key === 'template-default') continue;
                ?>
                    <option value="<?php echo esc_attr($template_key) ?>" <?php selected($selected_template, $template_key); ?>><?php echo esc_html($template_label) ?></option>
                <?php
                }
                ?>
            </select>

            <h2>Social accounts</h2>
            <label for="portfolio_github">Github:</label>
            <input type="text" name="portfolio_github" id="portfolio_github" value="<?php echo esc_url($github_url); ?>" placeholder="Your github porfile link">
            <br>
            <br>
            <label for="portfolio_twitter">Twitter:</label>
            <input type="text" name="portfolio_twitter" id="portfolio_twitter" value="<?php echo esc_url($twitter_url); ?>" placeholder="Your twitter porfile link">
            <br>
            <br>
            <label for="portfolio_linkedin">LinkedIn:</label>
            <input type="text" name="portfolio_linkedin" id="portfolio_linkedin" value="<?php echo esc_url($linkedin_url); ?>" placeholder="Your linkedin porfile link">

            <p><input type="submit" name="save_portfolio_data" class="button button-primary" value="Save Portfolio" /></p>
        </form>
    </div>
<?php
}
