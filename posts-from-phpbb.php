<?php
/**
 * Plugin Name: New Posts from phpBB
 * Plugin URI: https://www.wordpress.org/posts-from-phpbb
 * Description: A widget plugin that grabs your recent phpBB forum posts for you to display on your WordPress site
 * Version: 1.0.0
 * Requires PHP: 7.4
 * Author: kikipress
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: posts-from-phpbb
 * Domain Path: /languages
 *
 * @package NewPostsFromPhpbb
 */

namespace NewPostsFromPhpbb;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Load text domain for translation purposes.
 */
function load_textdomain() {
	load_plugin_textdomain( 'posts-from-phpbb', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
}

add_action( 'plugins_loaded', __NAMESPACE__ . '\load_textdomain' );

// Define constants.
define( 'POSTSFROMPHPBB_PATH', plugin_dir_path( __FILE__ ) );
define( 'POSTSFROMPHPBB_URL', plugin_dir_url( __FILE__ ) );
define( 'POSTSFROMPHPBB_VERSION', '1.0.0' );

// Register the Posts_From_Phpbb_Widget widget.
add_action(
	'widgets_init',
	function () {
		require_once plugin_dir_path( __FILE__ ) . 'widget/class-posts-from-phpbb-widget.php';
		register_widget( __NAMESPACE__ . '\Posts_From_Phpbb_Widget' );
	}
);
