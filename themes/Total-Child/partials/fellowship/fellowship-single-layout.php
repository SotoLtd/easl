<?php
/**
 * Single fellowship layout
 *
 * @package Total WordPress theme
 * @subpackage Partials
 * @version 4.4.1
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$fellowship_term                = get_field( 'fellowship-term' );
$first_aplication_period_start  = get_field( 'aplication_period_start' );
$first_aplication_period_start  = $first_aplication_period_start ? date( "d-M", strtotime( $first_aplication_period_start ) ) : '';
$first_aplication_period_finish = get_field( 'aplication_period_finish' );
$first_aplication_period_finish = $first_aplication_period_finish ? date( "d-M", strtotime( $first_aplication_period_finish ) ) : '';

$second_aplication_period_start  = get_field( 'second_aplication_period_start' );
$second_aplication_period_start  = $second_aplication_period_start ? date( "d-M", strtotime( $second_aplication_period_start ) ) : '';
$second_aplication_period_finish = get_field( 'second_aplication_period_finish' );
$second_aplication_period_finish = $second_aplication_period_finish ? date( "d-M", strtotime( $second_aplication_period_finish ) ) : '';


$apply_url = get_field( 'apply_url' );

$application_guidelines      = get_field( 'application_guidelines' );
$read_application_guidelines = get_field( 'read_application_guidelines' );
if ( ! $read_application_guidelines ) {
	$read_application_guidelines = $application_guidelines;
}

$appliciation_periods_parts = array();
if ( $first_aplication_period_start ) {
	$appliciation_periods_parts[] = $first_aplication_period_start;
}
if ( $first_aplication_period_finish ) {
	$appliciation_periods_parts[] = $first_aplication_period_finish;
}
$first_application_period = implode( ' - ', $appliciation_periods_parts );

$appliciation_periods_parts = array();
if ( $second_aplication_period_start ) {
	$appliciation_periods_parts[] = $second_aplication_period_start;
}
if ( $second_aplication_period_finish ) {
	$appliciation_periods_parts[] = $second_aplication_period_finish;
}
$second_application_period = implode( ' - ', $appliciation_periods_parts );

$application_period_formatted = implode( '<br/>', array( $first_application_period, $second_application_period ) );
unset( $appliciation_periods_parts, $first_application_period, $second_application_period );
if ( $application_period_formatted ) {
	$application_period_formatted = '<span>' . $application_period_formatted . '</span>';
}
?>
<div class="fellowship-topbar">
    <h2 class="app-period-title">
        <span><?php _e( 'Application Period: ', 'total-child' ); ?></span><?php echo $application_period_formatted; ?>
    </h2>
	<?php if ( $application_guidelines ): ?>
        <div class="fellowship-application-guidelines">
            <a class="application-guidelines-link"
               href="<?php echo esc_url( $application_guidelines ); ?>"><?php _e( 'Application Guidelines', 'total-child' ); ?></a>
        </div>
	<?php endif; ?>
	<?php if ( $apply_url ): ?>
        <div class="fellowship-apply-button">
            <a class="easl-generic-button easl-color-lightblue easl-size-small"
               href="<?php echo esc_url( $apply_url ); ?>" target="_blank"><?php _e( 'Apply Here', 'total-child' ); ?>
                <span class="easl-generic-button-icon"><span class="ticon ticon-chevron-right"></span></span></a>
        </div>
	<?php endif; ?>
</div>
<div class="fellowship-details-image-title">
	<?php if ( has_post_thumbnail() ): ?>
        <div class="fellowship-details-image">
			<?php the_post_thumbnail( 'single-post-thumbnail' ); ?>
        </div>
	<?php endif; ?>
    <div class="fellowship-detials-title-term">
        <h1 class="fellowship-detials-title"><?php the_title(); ?></h1>
		<?php if ( $fellowship_term ): ?>
            <div class="fellowship-detials-terms">
                <span><?php echo $fellowship_term; ?></span>
            </div>
		<?php endif; ?>
    </div>
</div>
<div class="fellowship-details-content"><?php the_content(); ?></div>
<div class="fellowhip-buttons-wrap easl-generic-buttons-wrap easl-align-left">
	<?php if ( $read_application_guidelines ): ?>
        <a class="easl-generic-button easl-color-blue easl-size-medium"
           href="<?php echo esc_url( $read_application_guidelines ); ?>"><?php _e( 'Read the application guidelines before applying', 'total-child' ) ?>
            <span class="easl-generic-button-icon"><span class="ticon ticon-chevron-right"></span></span></a>
	<?php endif; ?>
    <a class="easl-generic-button easl-color-lightblue easl-size-medium"
       href="/join-the-community/"><?php _e( 'Join the Community', 'total-child' ) ?><span
                class="easl-generic-button-icon"><span class="ticon ticon-chevron-right"></span></span></a>
</div>
<?php
// List Past fellowship awardees
$award_type = get_field( 'past_awardees' );
$award_type = absint( $award_type );
// get years name for last 2 available years
$avaiable_years = EASL_Award_Config::get_years( $award_type, 2, false );
$do_auery       = true;
$query_args     = array(
	'post_type'      => EASL_Award_Config::get_slug(),
	'posts_per_page' => - 1,
	'order'          => 'DESC',
	'oderby'         => 'meta_value_num',
	'meta_key'       => 'award_year'
);

if ( count( $avaiable_years ) > 0 ) {
	$query_args['meta_query'] = array(
		'relation' => 'AND',
		array(
			'key'     => 'award_year',
			'value'   => $avaiable_years,
			'compare' => 'IN',
		)
	);
} else {
	$do_auery = false;
}
if ( $award_type ) {
	$query_args['tax_query'] = array(
		'relation' => 'AND',
		array(
			'taxonomy' => 'award_group',
			'field'    => 'id',
			'terms'    => array( $award_type ),
			'operator' => 'IN',
		)
	);
} else {
	$do_auery = false;
}

$award_query = false;
if ( $do_auery ) {
	$award_query = new WP_Query( $query_args );
}
$people_col_width = 'vc_col-sm-4';
if ( $award_query && $award_query->have_posts() ):
	?>
    <div class="fellowship-past-awardees">
        <h2 class="past-fellows"><?php _e( 'Past Fellows', 'total-child' ); ?></h2>
		<?php
		while ( $award_query->have_posts() ) {
			$award_query->the_post();
			include locate_template( 'partials/award/year-row.php' );
		}
		?>
    </div>
	<?php
	wp_reset_query();
	$show_more_link = get_field( 'show_more_link' );
	$show_more_link = wp_parse_args( $show_more_link, array(
		'title'  => '',
		'url'    => '',
		'target' => '',
	) );
	if ( $show_more_link['title'] && $show_more_link['url'] ):
		?>
        <div class="fellowship-show-more">
            <a class="easl-generic-button easl-color-blue easl-size-medium"
               href="<?php echo esc_url( $show_more_link['url'] ); ?>"<?php if ( $show_more_link['target'] == '_blank' ) {
				echo ' target="_blank"';
			} ?>><?php echo esc_html( $show_more_link['title'] ); ?><span class="easl-generic-button-icon"><span
                            class="ticon ticon-chevron-right"></span></span></a>
        </div>
	<?php
	endif;
endif;
?>