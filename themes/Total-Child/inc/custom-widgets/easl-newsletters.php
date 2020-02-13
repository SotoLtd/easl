<?php

// Prevent direct file access
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
if ( ! class_exists( 'EASL_Newsletters_Widget' ) ) {
	class EASL_Newsletters_Widget extends WP_Widget {
		function __construct() {
			// Instantiate the parent object
			parent::__construct( 'easl_newsletters', __( 'EASL - Newsletters', 'total-child' ) );
		}

		function widget( $args, $instance ) {
			$title = isset( $instance['title'] ) ? apply_filters( 'widget_title', $instance['title'] ) : '';
			$year = isset( $instance['year'] ) ? absint($instance['year']): '';
			$limit = !empty( $instance['limit'] ) ? absint($instance['limit']): -1;
			echo $args['before_widget'];
			// Display widget title
			if ( $title ) {
				echo $args['before_title'] . $title . $args['after_title'];
			}
			$query_args = array(
				'post_type'      => EASL_Newsletter_Config::get_slug(),
				'post_status'    => 'publish',
				'orderby'        => 'date',
				'order'          => 'DESC',
				'posts_per_page' => $limit,
			);
			if($year) {
			    $query_args['year'] = $year;
            }
			$newsletter_query = new WP_Query($query_args);
			if($newsletter_query->have_posts()){
			    echo '<div class="easl-newsletters-list"><ul>';
			    while ($newsletter_query->have_posts()){
				    $newsletter_query->the_post();
				    get_template_part('partials/newsletter/list');
                }
                echo '</div></ul>';
			    wp_reset_query();
            }else{
			    echo '<p class="easl-not-found">'.  __('No newsletter found', 'total-child') .'</p>';
            }

			echo $args['after_widget'];
		}

		function update( $new_instance, $old_instance ) {
			$instance          = $old_instance;
			$instance['title'] = ! empty( $new_instance['title'] ) ? strip_tags( $new_instance['title'] ) : '';
			$instance['year'] = ! empty( $new_instance['year'] ) ? absint( $new_instance['year'] ) : '';
			$instance['limit'] = ! empty( $new_instance['limit'] ) ? absint( $new_instance['limit'] ) : '';

			return $instance;
		}

		function form( $instance ) {
			$year = $title = $limit = '';
			extract( wp_parse_args( ( array ) $instance, array(
				'title' => '',
                'year' => '',
				'limit' => '',
			) ) );
			$years = EASL_Newsletter_Config::get_years();

			?>

            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title', 'total-child' ); ?>
                    :</label>
                <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"
                       name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text"
                       value="<?php echo esc_attr( $title ); ?>"/>
            </p>
            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'year' ) ); ?>"><?php esc_html_e( 'Year', 'total-child' ); ?>
                    :</label>
                <select id="<?php echo esc_attr( $this->get_field_id( 'year' ) ); ?>"
                       name="<?php echo esc_attr( $this->get_field_name( 'year' ) ); ?>"
                        value="<?php echo esc_attr( $title ); ?>">
                    <option value="">All Year</option>
                    <?php foreach ($years as $y): ?>
                        <option value="<?php echo $y; ?>" <?php selected($year, $y, true) ?>><?php echo $y; ?></option>
                    <?php endforeach; ?>
                </select>
            </p>

            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'limit' ) ); ?>"><?php esc_html_e( 'Limit', 'total-child' ); ?>
                    :</label>
                <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'limit' ) ); ?>"
                       name="<?php echo esc_attr( $this->get_field_name( 'limit' ) ); ?>" type="text"
                       value="<?php echo esc_attr( $limit ); ?>"/>
                <smll><?php _e('Leave empty to display all', 'total-child') ?></smll>
            </p>
			<?php
		}
	}
}