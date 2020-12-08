<?php
// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
    die( '-1' );
}


$publication_link_to_file = get_field('publication_link_to_file');

$topic_str = '';
$topics = wp_get_post_terms(get_the_ID(), 'publication_topic' );
if($topics){
	foreach ($topics as $topic){
		$topic_str .= $topic->name.' ';
		
	}
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
?>
<div class="pub-content">
    <p class="sp-meta">
        <?php if ( $publication_date ): ?>
            <span class="sp-meta-date"><?php echo $publication_date; ?></span>
        <?php endif; ?>
        <span class=sp-meta-sep"> | </span>
        <span class="sp-meta-type">Topic:</span>
        <span class="sp-meta-value"><?php echo $topic_str; ?></span>
    </p>
    <h3 class="single-publication-title"><?php echo get_the_title(); ?></h3>
    <div class="pub-description">
        <?php the_content(); ?>
    </div>
    <?php if ( $publication_link_to_file ): ?>
        <div class="vc_row wpb_row vc_row-fluid">
            <div class="wpb_column vc_column_container vc_col-sm-12">
                <div class="vc_column-inner " style="margin-bottom: 40px;">
                    <div class="wpb_wrapper">
                        <a href="<?php echo $publication_link_to_file['url'] ?>" target="_blank" class="easl-button easl-button-wide-short"><?php echo $publication_link_to_file['title'] ?></a>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
    <?php
    $tags = get_the_term_list( get_the_ID(), Publication_Config::get_tag_slug() );
    if ( $tags ):
        ?>
        <div class="easl-tags publication-tags">
            <?php echo $tags; ?>
        </div>
    <?php endif; ?>
    <?php easl_social_share_icons(); ?>
</div>