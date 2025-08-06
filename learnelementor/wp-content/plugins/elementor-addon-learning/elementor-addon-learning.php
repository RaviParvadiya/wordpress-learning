<?php

/**
 * Plugin Name: Elementor Addon Learning
 * Description: Custom Elementor Addon.
 * Version:     1.0.0
 * Author:      Ravi Parvadiya
 * Text-domain: elementor-addon-learning
 * 
 * Requires Plugin: elementor
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

function elementor_addon_learning() {
    // Load plugin file
    require_once __DIR__ . '/includes/plugin.php';

    // Run the plugin
    \Elementor_Addon_Learning\Plugin::instance();

}
add_action( 'plugins_loaded', 'elementor_addon_learning' );

