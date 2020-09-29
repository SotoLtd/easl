<?php
// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}
$publication_date = get_field('publication_raw_date');
$publication_date_format = get_field('publication_date_format');
$custom_date_text = get_field('custom_date_text');
if(!$publication_date_format){
	$publication_date_format = 'Y';
}
if($publication_date_format == 'custom'){
	$publication_date = $custom_date_text;
}elseif($publication_date){
	$publication_date = DateTime::createFromFormat('d/m/Y', $publication_date);
	$publication_date = $publication_date->format($publication_date_format);
}
$image = has_post_thumbnail( get_the_ID() ) ? wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ), 'single-post-thumbnail' ) : '';

$topic_color = easl_get_publication_topic_color(get_the_ID());

$read_more_to_permalink = true;
$target = '';

$read_more_link = get_field('link_to_journal_hepatology');
$target = ' target="_blank"';
if(!$read_more_link){
	$read_more_link =  get_permalink();
	$target = '';
}

?>
<div class="easl-highlights-publications-item-inner easl-color-<?php echo $topic_color; ?><?php if(!has_post_thumbnail()){echo ' ehpi-nothumb';} ?>">
	<?php if(has_post_thumbnail()): ?>
        <figure><a href="<?php echo $read_more_link; ?>"<?php echo $target; ?>><?php echo wpex_get_post_thumbnail(array('width' => 80, 'height' => 107, 'crop' => true, 'attachment' => get_post_thumbnail_id())); ?></a></figure>
	<?php endif; ?>
    <div class="easl-highlights-publications-date-title">
		<?php if($publication_date):     ?>
            <h4><?php echo $publication_date;?></h4>
		<?php endif; ?>
        <h2><a href="<?php echo $read_more_link; ?>"<?php echo $target; ?>><?php the_title(); ?></a></h2>
        <a class="easl-generic-button easl-color-lightblue" href="<?php echo $read_more_link; ?>"<?php echo $target; ?>><?php _e('Read More', 'total-child'); ?><span class="easl-generic-button-icon"><span class="ticon ticon-chevron-right"></span></span></a>
    </div>
</div>