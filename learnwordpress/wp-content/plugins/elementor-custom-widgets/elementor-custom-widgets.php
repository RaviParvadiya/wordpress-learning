<?php

/**
 * 
 * Plugin Name: Elementor Custom Widgets
 * Description: Custom widgets for Elementor.
 * 
 */

 require_once __DIR__ . '/functions.php';
 
function ecw_register_custom_widgets( $widgets_manager ) {
    require_once(__DIR__ . '/widgets/contact-header.php');
    require_once(__DIR__ . '/widgets/breadcrumbs-widget.php');
    $widgets_manager->register( new \Elementor_Contact_Header_Widget() );
    $widgets_manager->register( new \ECW_Breadcrumbs_Widget() );
}

add_action('elementor/widgets/register', 'ecw_register_custom_widgets');

add_action('init', function() {
    if ( ! did_action( 'elementor/loaded' ) ) {
        add_action('admin_notices', function() {
            echo '<div class="notice notice-warning"><p>Elementor must be installed and activated to use this plugin.</p></div>';
        });
    }
});