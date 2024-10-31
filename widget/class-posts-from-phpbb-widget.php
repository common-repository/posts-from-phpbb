<?php
/**
 * Add the Posts_From_Phpbb_Widget widget.
 *
 * @package NewPostsFromPhpbb
 */

namespace NewPostsFromPhpbb;

/**
 * Create the Posts_From_Phpbb_Widget class.
 */
class Posts_From_Phpbb_Widget extends \WP_Widget {

	/**
	 * Set up the class.
	 */
	public function __construct() {

		$widget_options = array(
			'description' => __( 'Display new posts from your phpbb forum', 'posts-from-phpbb' ),
		);

		parent::__construct(
			'posts-from-phpbb',
			__( 'New Posts from Your phpBB Forum', 'posts-from-phpbb' ),
			$widget_options
		);
	}

	/**
	 * Front-end display of the widget.
	 *
	 * @param array $args     Widget arguments.
	 * @param array $instance Saved values from the database.
	 *
	 * @see WP_Widget::widget()
	 */
	public function widget( $args, $instance ) {
		echo $args['before_widget'];
		ob_start();
		$default_title = 'New Posts from phpBB';
		$title         = ! empty( $instance['title'] ) ? $instance['title'] : $default_title;
		$number        = ! empty( $instance['number'] ) ? $instance['number'] : 5;
		$url           = isset( $instance['url'] ) ? $instance['url'] : '';
		$mydb          = new \wpdb( $instance['dbuser'], $instance['dbpass'], $instance['dbname'], $instance['dbhost'] );

		$query = $mydb->prepare(
			'SELECT DISTINCT phpbb_forums.forum_id, forum_last_post_subject, 
        forum_last_post_id, forum_last_poster_name, forum_last_post_time, phpbb_posts.topic_id FROM phpbb_forums INNER JOIN phpbb_posts 
        ON phpbb_forums.forum_last_post_subject=phpbb_posts.post_subject ORDER BY forum_last_post_id DESC LIMIT %d',
			$number
		);

		echo $args['before_title'] . esc_html( $title ) . $args['after_title'];

		$rows = $mydb->get_results( $query );

		if ( $rows ) {
			echo '<ul>';
			foreach ( $rows as $row ) {
				echo '<li><a href=' . esc_html( $url ) . '/viewtopic.php?f=' . esc_html( $row->forum_id ) . '&amp;t=' . esc_html( $row->topic_id ) . '&amp;p=' . esc_html( $row->forum_last_post_id ) . '&amp;#p' . esc_html( $row->forum_last_post_id ) . '>' .
					esc_html( $row->forum_last_post_subject ) . '</a><br/>' .
					esc_html( __( 'by', 'posts-from-phpbb' ) ) . ' ' . esc_html( $row->forum_last_poster_name ) . ' ' . esc_html( __( 'on', 'posts-from-phpbb' ) ) . ' ' .
					esc_html( wp_date( get_option( 'date_format' ), esc_html( $row->forum_last_post_time ) ) );
				'</li>';
			}
			echo '</ul>';
		} else {
			esc_html_e( 'No posts available.', 'posts-from-phpbb' );
		}
		echo $args['after_widget'];
		ob_end_flush();
	}


	/**
	 * Back-end widget form.
	 *
	 * @param array $instance Previously saved values from the database.
	 *
	 * @see WP_Widget::form()
	 */
	public function form( $instance ) {
		$url    = isset( $instance['url'] ) ? $instance['url'] : '';
		$dbuser = isset( $instance['dbuser'] ) ? $instance['dbuser'] : '';
		$dbpass = isset( $instance['dbpass'] ) ? $instance['dbpass'] : '';
		$dbname = isset( $instance['dbname'] ) ? $instance['dbname'] : '';
		$dbhost = isset( $instance['dbhost'] ) ? $instance['dbhost'] : '';
		$title  = isset( $instance['title'] ) ? $instance['title'] : '';
		$number = isset( $instance['number'] ) ? (int) $instance['number'] : 5;
		ob_start();
		?>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'url' ) ); ?>"><?php esc_html_e( 'Forum URL', 'posts-from-phpbb' ); ?>*</label>
				<input type="url" class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'url' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'url' ) ); ?>" required value="<?php echo esc_attr( $url ); ?>">
			</p>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'dbuser' ) ); ?>"><?php esc_html_e( 'Database User', 'posts-from-phpbb' ); ?>*</label>
				<input type="text" class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'dbuser' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'dbuser' ) ); ?>" required value="<?php echo esc_attr( $dbuser ); ?>">
			</p>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'dbpass' ) ); ?>"><?php esc_html_e( 'Database Password', 'posts-from-phpbb' ); ?>*</label>
				<input type="password" class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'dbpass' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'dbpass' ) ); ?>" required value="<?php echo esc_attr( $dbpass ); ?>">
			</p>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'dbname' ) ); ?>"><?php esc_html_e( 'Database Name', 'posts-from-phpbb' ); ?>*</label>
				<input type="text" class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'dbname' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'dbname' ) ); ?>" required value="<?php echo esc_attr( $dbname ); ?>">
			</p>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'dbhost' ) ); ?>"><?php esc_html_e( 'Database Host', 'posts-from-phpbb' ); ?>*</label>
				<input type="text" class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'dbhost' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'dbhost' ) ); ?>" required value="<?php echo esc_attr( $dbhost ); ?>">
			</p>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title', 'posts-from-phpbb' ); ?>:</label>
				<input type="text" class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" value="<?php echo esc_attr( $title ); ?>">
			</p>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'number' ) ); ?>"><?php esc_html_e( 'Number of new forum posts to show', 'posts-from-phpbb' ); ?>:</label>
				<input type="number" class="tiny-text" id="<?php echo esc_attr( $this->get_field_id( 'number' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'number' ) ); ?>" step="1" min="1" size="3" value="<?php echo esc_attr( $number ); ?>">
			</p>  
		<?php
			echo esc_html_e( 'Fields marked with an asterisk (*) are required', 'posts-from-phpbb' );
			ob_end_flush();
	}
	/**
	 * Sanitize widget form values as they are saved.
	 *
	 * @param array $new_instance Values just sent to be saved.
	 * @param array $old_instance Previously saved values from the database.
	 *
	 * @return array Updated safe values to be saved.
	 * @see    WP_Widget::update()
	 */
	public function update( $new_instance, $old_instance ) {

		$instance = $old_instance;

		$instance['url']    = esc_url_raw( $new_instance['url'] );
		$instance['title']  = sanitize_text_field( $new_instance['title'] );
		$instance['number'] = (int) $new_instance['number'];
		$instance['dbuser'] = sanitize_text_field( $new_instance['dbuser'] );
		$instance['dbpass'] = $new_instance['dbpass'];
		$instance['dbname'] = sanitize_text_field( $new_instance['dbname'] );
		$instance['dbhost'] = sanitize_text_field( $new_instance['dbhost'] );
		return $instance;
	}
}