<?php
/**
 * Plugin Name:   WP Pending Posts
 * Plugin URI:    https://github.com/MITLibraries/wp-pending-posts
 * Description:   Displays a "Pending posts" dashboard widget for admin-level users.
 * Version:       1.0.1
 * Author:        MIT Libraries
 * Author URI:    https://github.com/MITLibraries
 * Licence:       GPL3
 *
 * @package WP Pending Posts
 * @since 0.1.0
 * @author MIT Libraries
 * @link https://github.com/MITLibraries/wp-pending-posts
 */

namespace mitlib;

// Don't call the file directly!
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Include the necessary classes.
include_once( 'class-pending-posts-widget.php' );

// Load the widget styles.
add_action( 'admin_enqueue_scripts', array( 'mitlib\Pending_Posts_Widget', 'styles' ) );

// Call the class' init method as part of dashboard setup.
add_action( 'wp_dashboard_setup', array( 'mitlib\Pending_Posts_Widget', 'init' ) );
