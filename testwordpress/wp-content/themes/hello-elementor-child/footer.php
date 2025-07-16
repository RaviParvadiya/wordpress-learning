<?php

/**
 * The footer.
 * 
 * This is the template that displays all of the <footer> section and everything up until main.
 */
?>

<footer class="footer_sec">
    <div class="top_sec_footer">
        <?php
        $data = get_option('custom_options_8');
        if (is_string($data)) {
            $data = json_decode($data, true);
        }
        ?>
        <div class="top_sec_dls">
            <div class="top_sec_dls_title">
                <h2><?php echo esc_html($data['footer_title']); ?></h2>
                <p><?php echo esc_html($data['footer_description']); ?></p>
            </div>
            <div class="send_msg_foo">
                <a href="<?php echo !empty($data['footer_msg_btn_link']) ? esc_url($data['footer_msg_btn_link']) : '#'; ?>"><?php echo esc_html($data['footer_msg_btn_txt']); ?></a>
            </div>
        </div>
        <div class="bottom_sec_dls">
            <div class="bottom_first_sec">
                <?php
                wp_nav_menu([
                    'theme_location' => 'footer-menu',
                    'container'      => false,
                    'menu_class'     => '',
                    'items_wrap'     => '<ul>%3$s</ul>',
                    'fallback_cb'    => false,
                ]);
                ?>
            </div>
            <div class="bottom_last_sec">
                <div class="bottom_last_one">
                    <div class="signup_foo">
                        <div class="signup_dls">
                            <h6>Sign up</h6>
                            <a href="#"><?php echo esc_html($data['footer_phone']); ?></a>
                        </div>
                        <div class="signup_dls">
                            <h6>Available hours </h6>
                            <a href="#"><?php echo esc_html($data['footer_hours']); ?></a>
                        </div>
                    </div>
                    <div class="signup_dls">
                        <h6>Email</h6>
                        <a href="#"><?php echo esc_html($data['footer_email']); ?></a>
                    </div>
                </div>
                <div class="bottom_last_two">
                    <h2>Cleveland <br> Quick Sale</h2>
                    <p>© 2024 — Copyright</p>
                </div>
            </div>
        </div>
    </div>
</footer>
<?php wp_footer(); ?>