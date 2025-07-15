<?php

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;

if (! defined('ABSPATH')) exit;

class ECW_Breadcrumbs_Widget extends Widget_Base
{

    public function get_name()
    {
        return 'ecw_breadcrumbs';
    }

    public function get_title()
    {
        return __('Breadcrumbs', 'ecw');
    }

    public function get_icon()
    {
        return 'eicon-posts-group';
    }

    public function get_categories()
    {
        return ['general'];
    }

    public function _register_controls()
    {
        $this->start_controls_section(
            'section_content',
            [
                'label' => __('Content', 'ecw'),
            ]
        );

        $this->add_control(
            'use_yoast',
            [
                'label' => __('Use Yoast Breadcrumbs', 'ecw'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'ecw'),
                'label_off' => __('No', 'ecw'),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->end_controls_section();

        // Style Section
        $this->start_controls_section(
            'section_style',
            [
                'label' => __('Style', 'ecw'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'typography',
                'selector' => '{{WRAPPER}} .ecw-breadcrumbs',
            ]
        );

        $this->end_controls_section();
    }

    public function render()
    {
        $settings = $this->get_settings_for_display();

        echo '<nav class="ecw-breadcrumbs">';

        if ($settings['use_yoast'] === 'yes' && function_exists('yoast_breadcrumb')) {
            yoast_breadcrumb('<p id="breadcrumbs">', '</p>');
        } else {
            // fallback breadcrumbs
            echo '<a href="' . esc_url(home_url()) . '">Home</a> &raquo; ';
            echo '<span>' . get_the_title() . '</span>';
        }

        echo '</nav>';
    }
}
