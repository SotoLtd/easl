<?php
if ( !defined( 'ABSPATH' ) ) {
	die( '-1' );
}

$post = get_post( get_the_ID() );
$easl_position = get_field('easl_position');

if($easl_position){
	$easl_position = wp_kses($easl_position, array(
	        'span' => array(),
	        'strong' => array(),
	        'em' => array(),
	        'b' => array(),
	        'i' => array(),
    ));
}

$img_path = '';
if ( has_post_thumbnail( $post->ID ) ) {
	$image = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ), 'staff_grid' );
	if ( $image ) {
		$img_path = $image[ 0 ];
	}
}
if(!$img_path){
    $img_path = get_stylesheet_directory_uri() . '/images/default-avatar.png';
}
$terms = get_the_terms( $post->ID, 'staff_category' );
?>
<div class="profile-block-wrapper">
    <div class="easl-staff-details-top">
        <div class="easl-staff-details-thumb">
            <img src="<?php echo $img_path; ?>" alt="">
        </div>
        <div class="easl-staff-details-title-contact">
            <h2><?php the_title(); ?></h2>
	        <?php if ( $easl_position ): ?>
                <h4 class="easl-staff-details-position"><?php echo $easl_position; ?></h4>
	        <?php endif; ?>
            <?php if(has_excerpt()): ?>
            <div class="easl-staff-details-excerpt"><?php the_excerpt(); ?></div>
            <?php endif; ?>
        </div>
    </div>
    <div class="easl-staff-details-bio">
        <?php the_content(); ?>
    </div>
</div>