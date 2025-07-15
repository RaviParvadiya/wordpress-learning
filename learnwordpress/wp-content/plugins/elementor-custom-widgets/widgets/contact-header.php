<?php

use Elementor\Widget_Base;

class Elementor_Contact_Header_Widget extends Widget_Base {

    public function get_name() {
        return 'contact_header';
    }

    public function get_title() {
        return 'Contact Header';
    }

    public function get_icon() {
        return 'eicon-header';
    }

    public function get_categories() {
        return [ 'general' ];
    }

    protected function render() {
        ?>
        <section style="background:#f9f9f9; padding: 20px;">
            <div style="max-width:1200px; margin:auto;">
                <div style="margin-bottom: 10px;">Home » Contact Us</div>
                <div style="display:flex; gap: 40px;">
                    <div><strong>Phone:</strong> +91-1234567890</div>
                    <div><strong>Email:</strong> info@domain.com</div>
                    <div><strong>WhatsApp:</strong> +91-9876543210</div>
                </div>
            </div>
        </section>
        <?php
    }
}
