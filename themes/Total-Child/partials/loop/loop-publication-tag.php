<?php
// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}
$topics = '';
$topics = easl_publications_topics_name(get_the_ID(), false, ' - ');
if($hide_topic === "true"){
	$topics = '';
	$topic_label = '';
	$topic_delimiter = '';
}
$image = has_post_thumbnail( get_the_ID() ) ?
	wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ), 'single-post-thumbnail' ) : '';
$image_src = $image ? $image[0] : '';


$excerpt = $hide_excerpt === "true" ? '' : get_the_excerpt();
$read_more_link =  $deny_detail_page === "true" ? get_field('link_to_journal_hepatology') : get_permalink();
$target = $deny_detail_page === "true" ? 'target="_blank"' : '';
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
?>

<article class="scientific-publication <?php if(!$image_src){echo 'sp-has-no-thumb';} ?> easl-sprow-color-<?php echo easl_get_publication_topic_color(); ?> clr">
	<?php if($image_src): ?>
		<div class="sp-thumb">
			<a href="<?php echo $read_more_link;?>" title="" <?php $target ?>>
				<img alt="" src="<?php echo $image_src; ?>"/>
			</a>
		</div>
	<?php endif; ?>
	<div class="scientific-publication-content  sp-content">
		<div class="sp-item-meta-title">
			<p class="sp-meta">
				<?php if($publication_date): ?>
					<span class="sp-meta-date"><?php echo $publication_date; ?></span>
				<?php endif; ?>
				<?php if($topic_delimiter): ?>
					<span class=sp-meta-sep"><?php echo $topic_delimiter; ?></span>
				<?php endif; ?>
				<?php if($topics): ?>
					<span class="sp-meta-type"><?php echo $topic_label; ?></span>
					<span class="sp-meta-value"><?php echo $topics; ?></span>
				<?php endif; ?>
			</p>
			<h3><a href="<?php echo $read_more_link; ?>" <?php echo $target; ?>><?php the_title(); ?></a></h3>
		</div>
		<p class="sp-excerpt"><?php echo $excerpt; ?></p>
		<a class="easl-button" href="<?php echo $read_more_link; ?>" <?php echo $target; ?>><?php _e('Read More', 'total-child'); ?> <span class="easl-generic-button-icon"><span class="ticon ticon-chevron-right"></span></span></a>
	</div>
</article>