<?php

function render_portfolio_form_page_backup()
{
    $user_id = get_current_user_id();
    $fname = get_user_meta($user_id, 'portfolio_fname', true);
    $lname = get_user_meta($user_id, 'portfolio_lname', true);
    $role = get_user_meta($user_id, 'portfolio_role', true);
    $description = get_user_meta($user_id, 'portfolio_description', true);
    $about = get_user_meta($user_id, 'portfolio_about', true);
    $skills = json_decode(get_option('portfolio_skills'), true) ?: [];
    $projects = json_decode(get_user_meta($user_id, 'portfolio_projects', true), true) ?: [];
    $github_url = get_user_meta($user_id, 'portfolio_github', true);
    $twitter_url = get_user_meta($user_id, 'portfolio_twitter', true);
    $linkedin_url = get_user_meta($user_id, 'portfolio_linkedin', true);
    $selected_template = get_user_meta($user_id, 'portfolio_template', true);

?>
    <div class="portfolio-admin-container">
        <h1>My Portfolio</h1>
        <form method="post">
            <?php wp_nonce_field('save_portfolio_data'); ?>

            <h2>First Name</h2>
            <input type="text" name="portfolio_fname" id="portfolio_fname" value="<?php echo esc_attr($fname); ?>"></input>

            <h2>Last Name</h2>
            <input type="text" name="portfolio_lname" value="<?php echo esc_attr($lname); ?>"></input>

            <h2>Role</h2>
            <input type="text" name="portfolio_role" value="<?php echo esc_attr($role); ?>"></input>

            <h2>Description</h2>
            <textarea name="portfolio_description" rows="5" cols="60"><?php echo esc_textarea($description); ?></textarea>

            <h2>About</h2>
            <textarea name="portfolio_about" rows="5" cols="60"><?php echo esc_textarea($about); ?></textarea>

            <h2>Skills</h2>
            <div id="skills-wrapper">
                <?php foreach ($skills as $i => $skill) : ?>
                    <div class="skill-item">
                        <input type="text" name="portfolio_skills[<?php echo $i; ?>][title]" value="<?php echo esc_attr($skill['title']); ?>" placeholder="Skill name" />
                    </div>
                <?php endforeach; ?>
            </div>
            <button type="button" id="add-skill">Add Skill</button>

            <h2>Projects</h2>
            <div id="projects-wrapper">
                <?php foreach ($projects as $i => $project) : ?>
                    <div class="project-item">
                        <input type="text" name="portfolio_projects[<?php echo $i; ?>][title]" value="<?php echo esc_attr($project['title']); ?>" placeholder="Project title" />
                        <br>
                        <textarea name="portfolio_projects[<?php echo $i; ?>][description]" rows="5" cols="60"><?php echo esc_textarea($project['description']); ?></textarea>
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
                ?>
                    <option value="<?php echo esc_attr($template_key) ?>" <?php selected($selected_template, $template_key); ?>><?php echo esc_html($template_label) ?></option>
                <?php
                }
                ?>
            </select>

            <h2>Social accounts</h2>
            <label for="portfolio_github">Github:</label>
            <input type="text" name="portfolio_github" id="portfolio_github" value="<?php echo esc_attr($github_url); ?>" placeholder="Your github porfile link">
            <br>
            <br>
            <label for="portfolio_twitter">Twitter:</label>
            <input type="text" name="portfolio_twitter" id="portfolio_twitter" value="<?php echo esc_attr($twitter_url); ?>" placeholder="Your twitter porfile link">
            <br>
            <br>
            <label for="portfolio_linkedin">LinkedIn:</label>
            <input type="text" name="portfolio_linkedin" id="portfolio_linkedin" value="<?php echo esc_attr($linkedin_url); ?>" placeholder="Your linkedin porfile link">

            <p><input type="submit" name="save_portfolio_data" class="button button-primary" value="Save Portfolio" /></p>
        </form>
    </div>
<?php
}

/**
 * 
 * Custom Skills
 */

 /* <div class="custom-skills">
                    <h3>Add custom skills:</h3>
                    <div id="custom-skills-wrapper">
                        <?php 
                        // Show custom skills that are not in predefined list
                        foreach ($user_skills as $i => $skill) : 
                            if (!in_array($skill['name'], array_column($predefined_skills, 'title'))) :
                        ?>
                            <div class="skill-item">
                                <input type="text" name="custom_skills[<?php echo $i; ?>][title]" value="<?php echo esc_attr($skill['title']); ?>" placeholder="Skill name" />
                                <input type="text" name="custom_skills[<?php echo $i; ?>][link]" value="<?php echo esc_attr($skill['link']); ?>" placeholder="Skill level (e.g., Beginner, Intermediate, Expert)" />
                                <button type="button" class="remove-skill" onclick="removeSkill(this)">×</button>
                            </div>
                        <?php 
                            endif;
                        endforeach; 
                        ?>
                    </div>
                    <button type="button" id="add-custom-skill">+ Add Custom Skill</button>
                </div> 

                    <script>
        document.getElementById('add-custom-skill').addEventListener('click', function() {
            const wrapper = document.getElementById('custom-skills-wrapper');
            const index = wrapper.children.length;
            const div = document.createElement('div');
            div.className = 'skill-item';
            div.innerHTML = `
                <input type="text" name="custom_skills[${index}][title]" placeholder="Skill title" />
                <input type="text" name="custom_skills[${index}][link]" placeholder="Skill icon link" />
                <button type="button" class="remove-skill" onclick="removeSkill(this)">×</button>
            `;
            wrapper.appendChild(div);
        });

        function removeSkill(button) {
            const skillItem = button.parentElement;
            skillItem.remove();
        }
    </script>
                
*/