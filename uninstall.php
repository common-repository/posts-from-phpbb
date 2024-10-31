<?php
/**
 * Clean-up on uninstalling.
 *
 * @package NewPostsFromPhpbb
 */

namespace NewPostsFromPhpbb;

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

unregister_widget( 'Posts_From_Phpbb_Widget' );
delete_option( 'widget_posts-from-phpbb' );
