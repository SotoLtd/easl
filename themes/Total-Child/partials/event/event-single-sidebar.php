<?php
// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

$key_dates                 = get_field( 'event_key_deadline_row' );
$event_poster_image        = get_field( 'event_poster_image' );
$poster_download_link      = get_field( 'poster_download_link' );
$event_poster_text_source  = get_field( 'event_poster_text_source' );
$event_poster_custom_text  = get_field( 'event_poster_custom_text' );
$event_google_map_iframe   = get_field( 'event_google_map_iframe' );
$event_sidebar_top_widgets = get_field( 'sidebar_top_widgets' );

if ( ! in_array( $event_poster_text_source, array( 'default', 'no', 'custom' ) ) ) {
	$event_poster_text_source = 'default';
}
$event_poster_text = '';
if ( $event_poster_text_source == 'no' ) {
	$event_poster_text = '';
} elseif ( $event_poster_text_source == 'custom' ) {
	$event_poster_text = trim( $event_poster_custom_text );
} else {
	$event_poster_text = trim( wpex_get_mod( 'event_poster_text' ) );
}

?>
<div class="wpb_column vc_column_container vc_col-sm-4">
    <div class="vc_column-inner event-main-sidebar">
        <div class="wpb_wrapper">
            <?php if(12947 == get_the_ID()) { get_template_part( 'partials/event/widgets/countdown' );} ?>
			<?php
			if ( have_rows( 'sidebar_top_widgets' ) ) {
				while ( have_rows( 'sidebar_top_widgets' ) ) {
					the_row();
					$widget_type = get_sub_field( 'type' );
					if ( $widget_type ) {
						$widget_type = str_replace( '_', '-', $widget_type );
						get_template_part( 'partials/event/widgets/' . $widget_type );
					}
				}
			}
			?>
			<?php
			if ( $key_dates ):
				?>
                <div class="event-sidebar-item event-key-deadlines">
                    <div class="event-sidebar-item-inner app-process">

                        <h3 class="event-sidebar-item-title">Key Deadlines</h3>
                        <ul>
							<?php
							if ( ! $key_dates ) {
								$key_dates = array();
							}
							$counter = 0;
							foreach ( $key_dates as $date ):
								switch ( $counter ):
									case 0:
										$addon_class = 'active';
										break;
									case 1:
										$addon_class = 'next-key';
										break;
									default:
										$addon_class = '';
								endswitch;
								$kd_start_date = ! empty( $date['event_key_start_date'] ) ? trim( $date['event_key_start_date'] ) : '';
								$kd_end_date = ! empty( $date['event_key_end_date'] ) ? trim( $date['event_key_end_date'] ) : '';

								$kd_start_date = DateTime::createFromFormat( 'd/m/Y', $kd_start_date );
								$kd_end_date   = DateTime::createFromFormat( 'd/m/Y', $kd_end_date );
								if ( false === $kd_start_date ) {
									continue;
								}

								$date_parts        = array();
								$date_parts['d'][] = $kd_start_date->format( 'd' );
								$date_parts['m'][] = $kd_start_date->format( 'M' );
								$date_parts['y'][] = $kd_start_date->format( 'Y' );
								if ( false !== $kd_end_date ) {
									$date_parts['d'][] = $kd_end_date->format( 'd' );
									$date_parts['m'][] = $kd_end_date->format( 'M' );
									$date_parts['y'][] = $kd_end_date->format( 'Y' );
								}
								$date_parts['y'] = array_unique( $date_parts['y'] );
								$formatted_date  = '';
								if ( count( $date_parts['y'] ) > 1 ) {
									$formatted_date = "{$date_parts['d'][0]} {$date_parts['m'][0]}, {$date_parts['y'][0]} - {$date_parts['d'][1]} {$date_parts['m'][1]}, {$date_parts['y'][1]}";
								} else {
									$date_parts['m'] = array_unique( $date_parts['m'] );
									if ( count( $date_parts['m'] ) > 1 ) {
										$formatted_date = "{$date_parts['d'][0]} {$date_parts['m'][0]} - {$date_parts['d'][1]} {$date_parts['m'][1]}, {$date_parts['y'][0]}";
									} else {
										$date_parts['d'] = array_unique( $date_parts['d'] );
										$formatted_date = implode( ' - ', $date_parts['d'] ) . " {$date_parts['m'][0]}, {$date_parts['y'][0]}";
									}
								}

								?>
                                <li class="app-process-key <?php echo $addon_class; ?>">
                                    <p class="event-kd-date"><?php echo $formatted_date; ?></p>
                                    <h4 class="event-kd-title"><?php echo strip_tags( $date['event_key_deadline_description'], '<br>' ); ?></h4>
                                </li>
								<?php $counter ++; ?>
							<?php endforeach; ?>
                        </ul>
                    </div>
                </div>
			<?php endif; ?>
			<?php if ( $event_poster_image || $poster_download_link ): ?>
                <div class="event-poster-image-box event-sidebar-item event-image-box-bg">
					<?php if ( $event_poster_image ): ?>
                        <div class="eib-image">
                            <img alt="" src="<?php echo $event_poster_image; ?>"/>
                        </div>
					<?php endif; ?>
					<?php if ( $event_poster_text ): ?>
                        <p><?php echo wp_kses( $event_poster_text, array(
								'br'   => array(),
								'span' => array(
									'style' => array(),
									'class' => array(),
								)
							) ); ?></p>
					<?php endif; ?>
					<?php if ( $poster_download_link && ! empty( $poster_download_link['url'] ) ): ?>
                        <a class="event-button event-button-icon event-button-no-arrow event-button-icon-download event-image-box-full-button" href="<?php echo esc_url( $poster_download_link['url'] ); ?>" <?php if ( $poster_download_link['target'] ) {
							echo 'target="' . esc_attr( $poster_download_link['target'] ) . '"';
						} ?> download>
							<?php if ( ! empty( $poster_download_link['title'] ) ) {
								echo esc_html( $poster_download_link['title'] );
							}
							{
								_e( 'Download Poster', 'total-child' );
							} ?>
                        </a>
					<?php endif; ?>
                </div>
			<?php endif; ?>
			<?php if ( $event_google_map_iframe ): ?>
                <div class="event-google-map-box event-sidebar-item event-image-box-bg">
                    <div class="eib-image">
						<?php echo $event_google_map_iframe; ?>
                    </div>
                    <div class="eib-text">
						<?php if ( get_field( 'event_address' ) ): ?>
                            <p><?php echo get_field( 'event_address' ); ?></p>
						<?php endif; ?>
						<?php if ( get_field( 'event_google_map_view_on_map' ) ): ?>
                            <p>
                                <a class="event-button event-button-icon event-button-no-arrow event-button-icon-marker event-image-box-full-button"
                                        href="<?php echo get_field( 'event_google_map_view_on_map' ); ?>" target="_blank"
                                >View on Map</a>
                            </p>
						<?php endif; ?>
                    </div>
                </div>
			<?php endif; ?>
			<?php
			if ( have_rows( 'sidebar_bottom_widgets' ) ) {
				while ( have_rows( 'sidebar_bottom_widgets' ) ) {
					the_row();
					$widget_type = get_sub_field( 'type' );
					if ( $widget_type ) {
						$widget_type = str_replace( '_', '-', $widget_type );
						get_template_part( 'partials/event/widgets/' . $widget_type );
					}
				}
			}
			?>
        </div>
    </div>
</div>
