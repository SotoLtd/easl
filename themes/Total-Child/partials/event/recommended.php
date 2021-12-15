<?php
// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
    die( '-1' );
}
$now_time = time();

$event_args      = array(
    'post_type'      => EASL_Event_Config::get_event_slug(),
    'post_status'    => 'publish',
    'posts_per_page' => 3,
    'order'          => 'ASC',
    'orderby'        => 'meta_value_num',
    'meta_key'       => 'event_start_date',
    'meta_query'     =>  array(
        'relation' => 'AND',
        array(
            'key'     => 'event_organisation',
            'value'   => array( 1, 3 ),
            'compare' => 'IN',
            'type'    => 'NUMERIC',
        ),
        array(
            'relation' => 'OR',
            array(
                'key'     => 'event_start_date',
                'value'   => $now_time - 86399,
                'compare' => '>=',
                'type'    => 'NUMERIC',
            ),
            array(
                'key'     => 'event_end_date',
                'value'   => $now_time - 86399,
                'compare' => '>=',
                'type'    => 'NUMERIC',
            ),
        )
    ),
);

$event_query       = new WP_Query( $event_args );
if ( $event_query->have_posts() ):
    ?>
    <div class="wpb_easl_events easl-recommended-events">
        <?php echo wpb_widget_title( array( 'title' => 'Recommended events', 'extraclass' => 'wpb_easl_events_heading' ) );  ?>
        <div class="easl-events-list-wrap">
            <ul class="easl-events-grid-3">
                <?php
                while ( $event_query->have_posts() ) {
                    $event_query->the_post();
                    get_template_part( 'partials/event/event-loop' );
                }
                wp_reset_query();
                ?>
            </ul>
        </div>
    </div>
<?php endif; ?>
