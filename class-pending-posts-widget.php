<?php
/**
 * Class that defines a "pending posts" dashboard widget.
 *
 * @package WP Pending Posts
 * @since 1.0.0
 */

namespace mitlib;

/**
 * Defines base widget
 */
class Pending_Posts_Widget {

	/**
	 * The id of this widget.
	 */
	const WID = 'pending_posts';

	/**
	 * Hook to wp_dashboard_setup to add the widget.
	 */
	public static function init() {
		wp_add_dashboard_widget(
			self::WID, // A unique slug/ID
			'Urgent and/or pending posts', // Visible name for the widget.
			array( 'mitlib\Pending_Posts_Widget', 'widget' )  // Callback for the main widget content.
		);
	}

	/**
	 * Loads custom stylesheet.
	 */
	public static function styles() {
		wp_register_style( 'pending_styles', plugin_dir_url( __FILE__ ) . 'wp-pending-posts.css', false, '1.0.0' );
		wp_enqueue_style( 'pending_styles' );
	}

	/**
	 * Load the widget code.
	 *
	 * The widget runs two queries in order to avoid a performance penalty using complex sorts.
	 * The first query looks for pending posts that are _also_ marked "urgent". The second query
	 * looks for pending posts that are _not_ marked "urgent".
	 *
	 * In an ideal world this query would be written along the lines of:
	 * SELECT posts
	 * WHERE post_status = 'pending'
	 * ORDER BY meta_value DESC, title ASC
	 */
	public static function widget() {
		// Define the basic query arguments array. This will then be slightly modified ahead of each query.
		$args = array(
			'post_type' => 'any',
			'orderby' => 'title',
			'order' => 'ASC',
			'post_status' => 'pending',
			'posts_per_page' => 10,
			'meta_key' => 'urgent',
		);

		// The first query looks for pending posts _with_ the urgent flag.
		$args['meta_value'] = 1;
		$urgent = new \WP_Query( $args );

		// The second query looks for pending posts _without_ the urgent flag.
		$args['meta_value'] = 0;
		$pending = new \WP_Query( $args );

		// Use the template to render widget output.
		require_once( 'widget.php' );

		// Reset post data.
		$urgent = null;
		$pending = null;
		wp_reset_postdata();

	}
}
