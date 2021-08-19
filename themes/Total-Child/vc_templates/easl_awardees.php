<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
$el_class = $el_id = $css_animation = $css = '';
$limit = $include_tags = $exclude_tags = $order = $orderby = $orderby_meta_key = '';

$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts );

$limit  = !empty($limit) ? absint($limit) : -1;

if(!in_array($order, array('ASC', 'DESC'))) {
	$order = 'DESC';
}

if(!$orderby){
	$orderby = 'ID';
}

$include_tags = $this->string_to_array($include_tags);
$exclude_tags = $this->string_to_array($exclude_tags);

$query_args = array(
	'posts_per_page' => $limit,
	'post_type' => 'staff',
	'post_status'=> 'publish',
	'tax_query' => array(
		array(
			'taxonomy' => 'staff_tag',
			'field' => 'term_id',
			'terms' => explode(',',$atts['include_tags']),
			'operator' => 'AND',
		)
	),
	'orderby'=> $orderby,
	'order' => $order,
);

if(in_array($orderby, array('meta_value_num', 'meta_value' ))){
	$query_args['meta_key'] = $orderby_meta_key;
}

$tax_query = array();
if($include_tags && count($include_tags) > 0){
	$tax_query[] = array(
		'taxonomy' => 'staff_tag',
		'field' => 'term_id',
		'terms' => $include_tags,
		'operator' => 'AND',
	);
}if($exclude_tags && count($exclude_tags) > 0){
	$tax_query[] = array(
		'taxonomy' => 'staff_tag',
		'field' => 'term_id',
		'terms' => $exclude_tags,
		'operator' => 'NOT IN',
	);
}

if(count($tax_query) > 1){
	$tax_query['relation'] = 'AND';
}
if(count($tax_query) > 0){
	$query_args['tax_query'] = $tax_query;
}

$awardees = new WP_Query($query_args);



$staff_col_width = $atts['staff_col_width'];
switch ($staff_col_width){
    case '1':
        $vc_col_width = 'vc_col-sm-12';
        break;
    case '2':
        $vc_col_width = 'vc_col-sm-6';
        break;
    case '3':
        $vc_col_width = 'vc_col-sm-4';
        break;
    case '4':
        $vc_col_width = 'vc_col-sm-3';
        break;
    default:
        $vc_col_width = 'vc_col-sm-4';
}

$class_to_filter = 'wpb_easl_ilc_details wpb_content_element';
$class_to_filter .= vc_shortcode_custom_css_class( $css, ' ' ) . $this->getExtraClass( $el_class ) . $this->getCSSAnimation( $css_animation );
$css_class		 = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, $class_to_filter, $this->settings[ 'base' ], $atts );
$wrapper_attributes = array();
if (!empty($el_id)) {
	$wrapper_attributes[] = 'id="' . esc_attr($el_id) . '"';
}
if ( $css_class ) {
	$wrapper_attributes[] = 'class="' . esc_attr( $css_class ) . '"';
}


if ( $awardees->have_posts() ):
    $counter = 0;
?>
<div <?php echo implode( ' ', $wrapper_attributes ); ?>>
    <div class="vc_row wpb_row vc_inner vc_row-fluid">
        <?php
        while ($awardees->have_posts()):
            $awardees->the_post();
            $image = has_post_thumbnail( get_the_ID() ) ?
                wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ), 'single-post-thumbnail' ) : '';
            $avatar_src = $image ? $image[0] : '/wp-content/uploads/2018/10/default-avatar.png';

            $awardee_profile_link = get_field('recognition_awardee_profile_link');

            ?>

            <div class="wpb_column vc_column_container <?php echo $vc_col_width;?>">
                <div class="vc_column-inner ">
                    <div class="wpb_wrapper">
                        <div class="wpb_single_image wpb_content_element vc_align_ ">
                            <figure class="wpb_wrapper vc_figure">
                                <div class="vc_single_image-wrapper   vc_box_border_grey">
                                    <?php if($awardee_profile_link && trim($awardee_profile_link['url'])): ?>
                                    <a href="<?php echo esc_url(trim($awardee_profile_link['url'])); ?>" <?php if($awardee_profile_link['target']){ echo 'target="'. esc_attr($awardee_profile_link['target']) .'"';} ?>>
                                    <?php endif; ?>
                                    <img width="254" height="254" src="<?php echo $avatar_src;?>"
                                         class="vc_single_image-img attachment-full" alt=""
                                         sizes="(max-width: 254px) 100vw, 254px">
                                    <?php if($awardee_profile_link): ?></a><?php endif; ?>
                                </div>
                            </figure>
                        </div>

                        <div style="color:#104e85;font-family:Helvetica Neue;font-size:19px;"
                             class="wpb_text_column has-custom-color wpb_content_element  recognition-link">
                            <div class="wpb_wrapper">
                                <p><?php echo the_title();?></p>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
</div>
<?php endif;?>
