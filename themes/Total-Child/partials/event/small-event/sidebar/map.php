<?php
// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}
$event_google_map_iframe      = get_field( 'se_google_map_iframe' );
$event_address                = get_field( 'se_address' );
$event_google_map_view_on_map = get_field( 'se_google_map_view_on_map' );
if ( $event_google_map_iframe ):
	?>
    <div class="easl-small-event-sbitem easl-small-event-sbitem-map">
        <div class="easl-small-event-sbitem-inner">
            <div class="easl-event-map">
                <div class="eib-image">
					<?php echo $event_google_map_iframe; ?>
                </div>
                <div class="eib-text">
					<?php if ( $event_address ): ?>
                        <p><?php echo $event_address; ?></p>
					<?php endif; ?>
					<?php if ( $event_google_map_view_on_map ): ?>
                        <p>
                            <a class="event-button event-button-light-blue event-button-icon event-button-icon-marker event-image-box-full-button"
                                    href="<?php echo $event_google_map_view_on_map; ?>" target="_blank"
                            >View on Map</a>
                        </p>
					<?php endif; ?>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>