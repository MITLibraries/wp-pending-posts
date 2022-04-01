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
	 * The basic query for pending posts.
	 */
	const QUERY = array(
		'post_type' => 'any',
		'orderby' => 'title',
		'order' => 'ASC',
		'post_status' => 'pending',
		'posts_per_page' => 10,
	);

	/**
	 * The id of this widget.
	 */
	const WID = 'pending_posts';

	/**
	 * Check for the context of the plugin. Is ACF active? Is there an Urgent flag defined?
	 *
	 * This influences how pending posts are queried, and the language around the widget.
	 *
	 * Returns true if the ACF plugin is present, and the Urgent flag is present.
	 * Returns false otherwise.
	 */
	public static function context() {
		$result = false;
		// group_54dd062c31627 is the value for the 'Urgent for post' field group. Sigh...
		if ( is_plugin_active( 'advanced-custom-fields-pro/acf.php' ) && acf_get_field_group( 'group_54dd062c31627' ) ) {
			$result = true;
		}
		return $result;
	}

	/**
	 * Hook to wp_dashboard_setup to add the widget.
	 */
	public static function init() {
		wp_add_dashboard_widget(
			self::WID, // A unique slug/ID.
			( self::context() ? 'Pending posts and their urgency' : 'Pending posts' ), // Visible name for the widget.
			array( 'mitlib\Pending_Posts_Widget', 'widget' )  // Callback for the main widget content.
		);
	}

	/**
	 * Query for pending posts with the urgent flag set.
	 *
	 * @SuppressWarnings(PHPMD.MissingImport)
	 */
	public static function query_pending() {
		$args = self::QUERY;
		if ( true == self::context() ) {
			$args['meta_key'] = 'urgent';
			$args['meta_value'] = 0;
		}
		return new \WP_Query( $args );
	}

	/**
	 * Query for pending posts with the urgent flag set.
	 *
	 * @SuppressWarnings(PHPMD.MissingImport)
	 */
	public static function query_urgent() {
		// Guard clause to return null if the context isn't correct.
		if ( false == self::context() ) {
			return null;
		}
		$args = self::QUERY;
		$args['meta_key'] = 'urgent';
		$args['meta_value'] = 1;
		return new \WP_Query( $args );
	}

	/**
	 * Loads custom stylesheet.
	 */
	public static function styles() {
		wp_register_style( 'pending_styles', plugin_dir_url( __FILE__ ) . 'wp-pending-posts.css', false, self::version() );
		wp_enqueue_style( 'pending_styles' );
	}

	/**
	 * The current plugin version, read from WordPress.
	 */
	public static function version() {
		$result = get_plugin_data( plugin_dir_path( __FILE__ ) . 'wp-pending-posts.php' );
		return $result['Version'];
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
	 *
	 * @SuppressWarnings(PHPMD.UnusedLocalVariable)
	 */
	public static function widget() {
		// The first query looks for pending posts with the urgent flag set, assuming ACF and the Urgent flag exist.
		$urgent = self::query_urgent();

		// The second query returns all pending posts if the context is false, or posts without the urgent flag set if
		// the context is true.
		$pending = self::query_pending();

		// Use the template to render widget output.
		require_once( 'widget.php' );

		// Reset post data.
		$urgent = null;
		$pending = null;
		wp_reset_postdata();

	}
}
