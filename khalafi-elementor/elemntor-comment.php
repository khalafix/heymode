<?php
/**
 * Plugin Name: Elementor Comment Modal Widget
 * Description: Adds a modal comment form widget to Elementor.
 * Version: 1.1.0
 * Author: You
 */

if ( ! defined( 'ABSPATH' ) ) exit;

// Check if Elementor is loaded
function cmw_elementor_is_loaded() {
    return did_action( 'elementor/loaded' );
}

// Register widget
add_action( 'elementor/widgets/register', function( $widgets_manager ) {

    if ( ! cmw_elementor_is_loaded() ) return;

    require_once __DIR__ . '/widget-comment-modal.php';

    $widgets_manager->register( new \Comment_Modal_Widget() );
});
