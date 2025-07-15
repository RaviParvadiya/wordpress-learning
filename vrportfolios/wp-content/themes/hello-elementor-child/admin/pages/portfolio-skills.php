<?php

function portfolio_skills_page()
{
    $skills = json_decode(get_option('portfolio_skills'), true);
?>

    <style>
        .skill-link-input {
            width: 40%;
            max-width: 100%;
        }
    </style>
    <div class="wrap">
        <h2>Manage Skills</h2>

        <?php
        if (!empty($_GET['updated'])) {
            echo '<div class="notice notice-success is-dismissible"><p>Skills saved successfully!</p></div>';
        }
        ?>
        <form method="post" action="<?php echo admin_url('admin-post.php'); ?>">
            <input type="hidden" name="action" value="save_portfolio_skills_data">
            <?php wp_nonce_field('save_portfolio_skills_data'); ?>

            <h2>Skills</h2>
            <div id="skills-wrapper">
                <?php foreach ($skills as $i => $skill) : ?>
                    <div class="skill-item">
                        <input type="text" name="portfolio_skills[<?php echo $i; ?>][title]" value="<?php echo esc_attr($skill['title']); ?>" placeholder="Skill title" />
                        <input type="text" name="portfolio_skills[<?php echo $i; ?>][link]" value="<?php echo esc_url($skill['link']); ?>" class="skill-link-input" placeholder="Skill icon link" />
                    </div>
                <?php endforeach; ?>
            </div>
            <br>
            <button type="button" id="add-skill">+</button>
            <br><br>
            <button type="submit" name="save_portfolio_skills_data" class="button button-primary">Save Skills</button>
        </form>
    </div>
    <script>
        document.getElementById('add-skill').addEventListener('click', function() {
            const wrapper = document.getElementById('skills-wrapper');
            const index = wrapper.children.length;
            const div = document.createElement('div');
            div.className = 'skill-item';
            div.innerHTML = `
            <br>
            <input type="text" name="portfolio_skills[${index}][title]" placeholder="Skill title" />
            <input type="text" name="portfolio_skills[${index}][link]" class="skill-link-input" placeholder="Skill icon link" />
            `;
            wrapper.appendChild(div);
        });
    </script>

<?php } ?>