<?php

// Prevent direct file access
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
if ( ! class_exists( 'EASL_Recent_Items_Widget' ) ) {
	class EASL_Recent_Items_Widget extends WP_Widget {
		function __construct() {
			// Instantiate the parent object
			parent::__construct( 'easl_recent_items', __( 'EASL - Recent Items', 'total-child' ) );
		}

		function widget( $args, $instance ) {
			$title = isset( $instance['title'] ) ? apply_filters( 'widget_title', $instance['title'] ) : '';
			$show_filter = isset( $instance['show_filter'] ) ? $instance['show_filter'] : 'no';
			$news_number = ! empty( $instance['news_number'] ) ? absint($instance['news_number']) : '';
			$publication_number = ! empty( $instance['publication_number'] ) ? absint($instance['publication_number']) : '';
			$event_number = ! empty( $instance['event_number'] ) ? absint($instance['event_number']) : '';
			$event_easl_only = isset( $instance['event_easl_only'] ) ? $instance['event_easl_only'] : 'no';
			$event_show_default_empty = isset( $instance['event_show_default_empty'] ) ? $instance['event_show_default_empty'] : 'no';
			$fellowship_number = ! empty( $instance['fellowship_number'] ) ? absint($instance['fellowship_number']) : '';
			echo $args['before_widget'];
			// Display widget title
			if ( $title ) {
				echo $args['before_title'] . $title . $args['after_title'];
			}
			if( ($show_filter == 'yes') && $event_number && $publication_number){
				include get_theme_file_path('partials/widgets/recent-items-filter.php');
			}
			include get_theme_file_path('partials/widgets/recent-items.php');
			echo $args['after_widget'];
		}

		function update( $new_instance, $old_instance ) {
			$instance          = $old_instance;
			$instance['title'] = ! empty( $new_instance['title'] ) ? strip_tags( $new_instance['title'] ) : '';
			$instance['show_filter'] = ! empty( $new_instance['show_filter'] ) ? 'yes' : 'no';
			$instance['news_number'] = ! empty( $new_instance['news_number'] ) ? absint($new_instance['news_number']) : '';
			$instance['publication_number'] = ! empty( $new_instance['publication_number'] ) ? absint($new_instance['publication_number']) : '';
			$instance['event_number'] = ! empty( $new_instance['event_number'] ) ? absint($new_instance['event_number']) : '';
			$instance['event_easl_only'] = ! empty( $new_instance['event_easl_only'] ) ? 'yes' : 'no';
			$instance['event_show_default_empty'] = ! empty( $new_instance['event_show_default_empty'] ) ? 'yes' : 'no';
			$instance['fellowship_number'] = ! empty( $new_instance['fellowship_number'] ) ? absint($new_instance['fellowship_number']) : '';

			return $instance;
		}

		function form( $instance ) {
			$show_filter = $title = $news_number = $event_number = $publication_number = $event_easl_only = $event_show_default_empty = $fellowship_number = '';
			extract( wp_parse_args( ( array ) $instance, array(
				'title' => '',
				'show_filter' => 'yes',
				'news_number' => 3,
				'publication_number' => 2,
				'event_number' => 1,
				'event_easl_only' => 'no',
				'event_show_default_empty' => 'no',
				'fellowship_number' => 0,
			) ) ); ?>

			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title', 'total-child' ); ?>
					:</label>
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"
				       name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text"
				       value="<?php echo esc_attr( $title ); ?>"/>
			</p>
			<p>
				<input id="<?php echo esc_attr( $this->get_field_id( 'show_filter' ) ); ?>"
				       name="<?php echo esc_attr( $this->get_field_name( 'show_filter' ) ); ?>" type="checkbox"
				       value="1" <?php checked('yes', $show_filter, true); ?>/>
				<label for="<?php echo esc_attr( $this->get_field_id( 'show_filter' ) ); ?>"><?php esc_html_e( 'Show Filter', 'total-child' ); ?></label>
			</p>
            <h4><?php _e('News Query', 'total-child'); ?></h4>
            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'news_number' ) ); ?>"><?php esc_html_e( 'News Number', 'total-child' ); ?>
                    :</label>
                <select id="<?php echo esc_attr( $this->get_field_id( 'news_number' ) ); ?>"
                        name="<?php echo esc_attr( $this->get_field_name( 'news_number' ) ); ?>">
                    <option value=""><?php _e('Hide', 'total-child'); ?></option>
                    <option value="1" <?php selected(1, $news_number, true); ?>>1</option>
                    <option value="2" <?php selected(2, $news_number, true); ?>>2</option>
                    <option value="3" <?php selected(3, $news_number, true); ?>>3</option>
                    <option value="4" <?php selected(4, $news_number, true); ?>>4</option>
                    <option value="5" <?php selected(5, $news_number, true); ?>>5</option>
                    <option value="6" <?php selected(6, $news_number, true); ?>>6</option>
                </select>
            </p>
            <h4><?php _e('Publication Query', 'total-child'); ?></h4>
            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'publication_number' ) ); ?>"><?php esc_html_e( 'Publication Number', 'total-child' ); ?>
                    :</label>
                <select id="<?php echo esc_attr( $this->get_field_id( 'publication_number' ) ); ?>"
                        name="<?php echo esc_attr( $this->get_field_name( 'publication_number' ) ); ?>">
                    <option value=""><?php _e('Hide', 'total-child'); ?></option>
                    <option value="1" <?php selected(1, $publication_number, true); ?>>1</option>
                    <option value="2" <?php selected(2, $publication_number, true); ?>>2</option>
                    <option value="3" <?php selected(3, $publication_number, true); ?>>3</option>
                    <option value="4" <?php selected(4, $publication_number, true); ?>>4</option>
                    <option value="5" <?php selected(5, $publication_number, true); ?>>5</option>
                    <option value="6" <?php selected(6, $publication_number, true); ?>>6</option>
                </select>
            </p>
            <h4><?php _e('Event Query', 'total-child'); ?></h4>
            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'event_number' ) ); ?>"><?php esc_html_e( 'Event Number', 'total-child' ); ?>
                    :</label>
                <select id="<?php echo esc_attr( $this->get_field_id( 'event_number' ) ); ?>"
                        name="<?php echo esc_attr( $this->get_field_name( 'event_number' ) ); ?>">
                    <option value=""><?php _e('Hide', 'total-child'); ?></option>
                    <option value="1" <?php selected(1, $event_number, true); ?>>1</option>
                    <option value="2" <?php selected(2, $event_number, true); ?>>2</option>
                    <option value="3" <?php selected(3, $event_number, true); ?>>3</option>
                    <option value="4" <?php selected(4, $event_number, true); ?>>4</option>
                    <option value="5" <?php selected(5, $event_number, true); ?>>5</option>
                    <option value="6" <?php selected(6, $event_number, true); ?>>6</option>
                </select>
            </p>
            <p>
                <input id="<?php echo esc_attr( $this->get_field_id( 'event_easl_only' ) ); ?>"
                       name="<?php echo esc_attr( $this->get_field_name( 'event_easl_only' ) ); ?>" type="checkbox"
                       value="1" <?php checked('yes', $event_easl_only, true); ?>/>
                <label for="<?php echo esc_attr( $this->get_field_id( 'event_easl_only' ) ); ?>"><?php esc_html_e( 'EASL Events Only', 'total-child' ); ?></label>
            </p>
            <p>
                <input id="<?php echo esc_attr( $this->get_field_id( 'event_show_default_empty' ) ); ?>"
                       name="<?php echo esc_attr( $this->get_field_name( 'event_show_default_empty' ) ); ?>" type="checkbox"
                       value="1" <?php checked('yes', $event_show_default_empty, true); ?>/>
                <label for="<?php echo esc_attr( $this->get_field_id( 'event_show_default_empty' ) ); ?>"><?php esc_html_e( 'Show events from General Hepatology topic if there is no event for a filtered topic.', 'total-child' ); ?></label>

            </p>
            <h4><?php _e('Fellowship Query', 'total-child'); ?></h4>
            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'fellowship_number' ) ); ?>"><?php esc_html_e( 'Fellowship Number', 'total-child' ); ?>
                    :</label>
                <select id="<?php echo esc_attr( $this->get_field_id( 'fellowship_number' ) ); ?>"
                        name="<?php echo esc_attr( $this->get_field_name( 'fellowship_number' ) ); ?>">
                    <option value=""><?php _e('Hide', 'total-child'); ?></option>
                    <option value="1" <?php selected(1, $fellowship_number, true); ?>>1</option>
                    <option value="2" <?php selected(2, $fellowship_number, true); ?>>2</option>
                    <option value="3" <?php selected(3, $fellowship_number, true); ?>>3</option>
                    <option value="4" <?php selected(4, $fellowship_number, true); ?>>4</option>
                    <option value="5" <?php selected(5, $fellowship_number, true); ?>>5</option>
                    <option value="6" <?php selected(6, $fellowship_number, true); ?>>6</option>
                </select>
            </p>
			<?php
		}
	}
}