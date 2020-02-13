<?php
// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}
if(!isset($peoples_args)) {
	$peoples_args = array(
		'post_type'      => 'staff',
		'posts_per_page' => - 1,
		'order'          => 'DESC',
		'orderby' => 'post__in'
	);
}
if(!isset($award_title_type)){
	$award_title_type = 'year';
}
if(!isset($people_col_width)) {
	$people_col_width = 'vc_col-sm-3';
}
if(!isset($display_thumb)) {
	$display_thumb = 'false';
}

$award_thumb = '';
if ( 'true' == $display_thumb && has_post_thumbnail() ) {
$award_thumb = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ), 'single-post-thumbnail' );
$award_thumb = $award_thumb ? $award_thumb[0] : '';
}
?>
<div class="easl-yearly-awardees-row">
	<div class="easl-yearly-awardees-year"><span><?php if($award_title_type == 'year'){echo get_field( 'award_year' );}else{the_title();}?></span></div>
	<?php
	$awardees         = get_field( 'awardees' );

	if ( $awardees && count( $awardees ) > 0 ):
		$peoples_args['post__in'] = $awardees;
		$people_query = new WP_Query( $peoples_args );
		if ( $people_query->have_posts() ):
			?>
			<div class="easl-yearly-awardees-peoples vc_row wpb_row vc_inner vc_row-fluid">
				<?php
				while ( $people_query->have_posts() ):
					$people_query->the_post();
					$image      = has_post_thumbnail( get_the_ID() ) ? wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ), 'single-post-thumbnail' ) : '';
					$avatar_src = $image ? $image[0] : get_stylesheet_directory_uri() . '/images/default-avatar.png';

					$awardee_profile_link = get_field( 'recognition_awardee_profile_link' );
					?>
					<div class="wpb_column vc_column_container <?php echo $people_col_width; ?>">
						<div class="vc_column-inner ">
							<div class="wpb_wrapper">
								<div class="easl-yearly-awardee-image">
									<?php if ( $awardee_profile_link && trim( $awardee_profile_link['url'] ) ): ?>
									<a href="<?php echo esc_url( trim( $awardee_profile_link['url'] ) ); ?>" <?php if ( $awardee_profile_link['target'] ) {
										echo 'target="' . esc_attr( $awardee_profile_link['target'] ) . '"';
									} ?>>
										<?php endif; ?>
										<img src="<?php echo $avatar_src; ?>" alt=""/>
										<?php if ( $awardee_profile_link ): ?></a><?php endif; ?>
								</div>

								<h5 class="easl-yearly-awardee-title">
									<?php if ( $awardee_profile_link && trim( $awardee_profile_link['url'] ) ): ?>
									<a href="<?php echo esc_url( trim( $awardee_profile_link['url'] ) ); ?>" <?php if ( $awardee_profile_link['target'] ) {
										echo 'target="' . esc_attr( $awardee_profile_link['target'] ) . '"';
									} ?>>
										<?php endif; ?>
										<?php echo the_title(); ?>
										<?php if ( $awardee_profile_link ): ?></a><?php endif; ?>
								</h5>
							</div>
						</div>
					</div>
				<?php endwhile; ?>
				<?php if($award_thumb): ?>
					<div class="wpb_column vc_column_container vc_col-sm-6">
						<div class="vc_column-inner ">
							<div class="wpb_wrapper">
								<div class="easl-yearly-award-thumb">
									<img src="<?php echo $award_thumb; ?>" alt="">
								</div>
							</div>
						</div>
					</div>
				<?php endif; ?>
			</div>
			<?php
			wp_reset_query();
		endif;
		?>
	<?php endif; ?>
</div>
