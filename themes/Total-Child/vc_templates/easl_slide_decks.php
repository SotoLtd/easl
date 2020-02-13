<?php
if (!defined('ABSPATH')) {
	die('-1');
}

/**
 * Shortcode attributes
 * @var $atts
 * @var $title
 * @var $el_class
 * @var $el_id
 * @var $css
 * Shortcode class EASL_VC_Slide_Decks
 * @var $this EASL_VC_Slide_Decks
 */

$el_class = $el_id = $css_animation = $css = '';
$limit= '';

$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts );


$class_to_filter = 'wpb_easl_slide_decks wpb_content_element';
$class_to_filter .= vc_shortcode_custom_css_class( $css, ' ' ) . $this->getExtraClass( $el_class ) . $this->getCSSAnimation( $css_animation );
$css_class		 = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, $class_to_filter, $this->settings[ 'base' ], $atts );
$wrapper_attributes = array();
if (!empty($el_id)) {
	$wrapper_attributes[] = 'id="' . esc_attr($el_id) . '"';
}
if ( $css_class ) {
	$wrapper_attributes[] = 'class="' . esc_attr( $css_class ) . '"';
}

$has_filter = false;
$filter_sd_topics = [];
$filter_sd_search = '';
$filter_sd_cat = '';
$filter_sd_year = '';

if(!empty($_REQUEST['sd_topics'])){
	$filter_sd_topics = $_REQUEST['sd_topics'];
	$has_filter = true;
}
if( !empty($_REQUEST['sd_search']) ){
	$filter_sd_search = $_REQUEST['sd_search'];
	$has_filter = true;
}
if(!empty($_REQUEST['sd_cat'])){
	$filter_sd_cat = $_REQUEST['sd_cat'];
	$has_filter = true;
}
if(!empty($_REQUEST['sd_year'])){
	$filter_sd_year = $_REQUEST['sd_year'];
	$has_filter = true;
}

if(empty($filter_sd_year)){
    $has_filter = false;
}

$filter_by_topic = '';
$taxonomy_string = '';
$is_custom_topic = false;

$taxonomies = get_terms( array(
    'taxonomy'   => Slide_Decks_Config::get_topic_slug(),
    'hide_empty' => true,
    'orderby'    => 'name',
    'order'      => 'ASC',
    'fields'     => 'id=>name',
) );
if($taxonomies){
    foreach ($taxonomies as $term_id => $term_name){
        $bg_color ='';
        $topic_color = get_term_meta($term_id, 'easl_tax_color', true);
        if(!$topic_color) {
            $bg_color = 'blue';
        } else {
            $bg_color = $topic_color;
        }
        $filter_topic_checked = '';
        if(in_array($term_id, $filter_sd_topics)){
            $filter_topic_checked = 'checked';
            $is_custom_topic = true;
        }
        $taxonomy_string .= '<li>'.
                                '<label class="easl-custom-checkbox csic-'.$bg_color.'">'.
                                    '<input type="checkbox" name="sd_topics[]" value="'.$term_id.'" data-countries="" '.$filter_topic_checked.'> <span>'.$term_name.'</span>'.
                                '</label>'.
                            '</li>';
    }
}


$br = '';
$no_bottom_margins = '';


$sd_cats_options = '';
$slide_decks_categories = $this->get_categories_heirarchi();
$child_cats_drobdowns = array();
if($slide_decks_categories){
    foreach ($slide_decks_categories['parents'] as  $sd_cat){
        $cc_cat_dd = '';
	    $sd_cats_options .= '<option value="'. $sd_cat['term_id'] .'" '. selected($sd_cat['term_id'], $filter_sd_cat, false) .'>'. $sd_cat['term_name'] .'</option>';

	    if(isset($slide_decks_categories['childs'][$sd_cat['term_id']])){
	        foreach ($slide_decks_categories['childs'][$sd_cat['term_id']] as $cterm){
		        $cc_cat_dd .= '<option value="'. $cterm['term_id'] .'"'. selected($cterm['term_id'], $filter_sd_year, false) .'>'. $cterm['term_name'] .'</option>';
	        }
        }
	    if($cc_cat_dd){
		    $child_cats_drobdowns[] = '<select class="sd-cat-childs-'.$sd_cat['term_id'].'"><option value="">Select Year</option>'. $cc_cat_dd .'</select>';
        }
    }
}
if(count($child_cats_drobdowns) > 0){
	$child_cats_drobdowns = implode('', $child_cats_drobdowns);
}else{
	$child_cats_drobdowns = '';
}
?>
<div <?php echo implode( ' ', $wrapper_attributes ); ?>>
    <div class="easl-slide-decks-wrap">
        <form class="slide-deck-filter" action="" method="get" style="background: #fff; border: 3px solid #004b87; padding: 30px 15px; margin-bottom: 30px;">
            <div class="easl-row easl-slide-decks-container">
                <div class="easl-col easl-col-3">
                    <div class="easl-col-inner">
                        <h4 style="font-size: 21px;border-bottom: 1px solid #d7d7d7;">Show me:</h4>
                        <ul class="ec-filter-topics">
                            <li>
                                <label class="easl-custom-checkbox easl-cb-all csic-light-blue easl-active">
                                    <input type="checkbox" name="sd_topics[]" value="" <?php if(!$is_custom_topic){ echo 'checked="checked"';}?>> <span>All topics</span>
                                </label>
                            </li>
                            <?php echo $taxonomy_string;?>
                        </ul>
                    </div>
                </div>
                <div class="easl-col easl-col-2-3">
                    <div class="easl-col-inner">
                        <div class="ec-filter-search">
                            <input type="text" name="sd_search" value="<?php if($filter_sd_search) {echo $filter_sd_search;}?>" placeholder="Search for slide decks"/>
                            <span class="ecs-icon"><i class="ticon ticon-search" aria-hidden="true"></i></span>
                        </div>
                        <div class="ec-filter-cat">
                            <h4 style="font-size: 21px">Filter Slide Decks:</h4>
                            <div class="easl-custom-select" style="margin-bottom: 15px;">
                                <span class="ec-cs-label">Select an option</span>
                                <select name="sd_cat" placeholder="Select an option">
                                    <option value="">Select an option</option>
                                    <?php echo $sd_cats_options; ?>
                                </select>
                            </div>
                            <div style="display: none;">
                                <?php echo $child_cats_drobdowns; ?>
                            </div>
                        </div>
                        <div class="ec-filter-year">
                            <div class="easl-custom-select" style="margin-bottom: 0px;">
                                <span class="ec-cs-label">Select year</span>
                                <select name="sd_year" placeholder="Select year">
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
        <?php
        if($has_filter):
	        $limit = absint($limit);
	        if(!$limit){
		        $limit = -1;
	        }
	        $paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
	        $query_args = array(
		        'post_type'      => Slide_Decks_Config::get_slug(),
		        'post_status'    => 'publish',
		        'posts_per_page' => $limit,
		        'paged'          => $paged,
	        );

	        if($filter_sd_search){
		        $query_args['s'] = $filter_sd_search;
	        }

	        $tax_query = array();
	        if( is_array($filter_sd_topics) && count($filter_sd_topics) > 0 && $filter_sd_topics[0] != '') {
		        $tax_query[] =  array(
			        'taxonomy' => Slide_Decks_Config::get_topic_slug(),
			        'field' => 'id',
			        'terms' => $filter_sd_topics,
			        'operator' => 'IN',
		        );
	        }
	        if(!empty($filter_sd_year)) {
		        $tax_query[] =  array(
			        'taxonomy' => Slide_Decks_Config::get_category_slug(),
			        'field' => 'id',
			        'terms' => array($filter_sd_year),
			        'operator' => 'IN',
		        );
	        }
	        if(count($tax_query) > 0){
		        $query_args['tax_query'] = array('relation' => 'AND');
		        $query_args['tax_query'][] = $tax_query;
	        }

	        $easl_query = new WP_Query( $query_args );

	        $paginatio_html =  paginate_links( array(
		        'total'     => $easl_query->max_num_pages,
		        'current'   => $paged,
		        'end_size'  => 3,
		        'mid_size'  => 5,
		        'prev_next' => true,
		        'prev_text' => '<span class="ticon ticon-angle-left" aria-hidden="true"></span>',
		        'next_text' => '<span class="ticon ticon-angle-right" aria-hidden="true"></span>',
		        'type'      => 'list',
	        ) );

	        $pagination = '<div class="easl-list-pagination" >' . $paginatio_html . '</div>';

	        $not_found_text = __('Content is coming soon', 'total-child');
	        $sponsors_text = get_field('sponsors_text', get_term($filter_sd_year, Slide_Decks_Config::get_category_slug()));
	        $sponsors_text_position = get_field('sponsors_text_position', get_term($filter_sd_year, Slide_Decks_Config::get_category_slug()));
	        if(!in_array($sponsors_text_position, array('top', 'bottom'))) {
		        $sponsors_text_position = 'top';
            }
        ?>
        <?php if($sponsors_text && $sponsors_text_position == 'top'): ?>
        <div class="easl-sd-sponsors">
            <?php echo do_shortcode($sponsors_text); ?>
        </div>
        <?php endif; ?>
        <div class="easl-slide-decks-top-pagination easl-list-pagination" ><?php echo $paginatio_html; ?></div>
        <div class="easl-slide-decks-inner">
			<?php
			if ( $easl_query->have_posts() ){
				while ( $easl_query->have_posts() ):
					$easl_query->the_post();
					$topic_str = '';
					$topics = wp_get_post_terms(get_the_ID(), Slide_Decks_Config::get_topic_slug() );
					if($topics){
						foreach ($topics as $topic){
							$topic_str .= $topic->name.' ';

						}
					}
					$image = has_post_thumbnail( get_the_ID() ) ?
						wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ), 'single-post-thumbnail' ) : '';
					$image_src = $image ? $image[0] : '';

					$has_landing_page =  get_field('sd_has_landing_page');
					$landing_page_link =  get_field('sd_landing_page_url');
					$download_link =  get_field('slide-decks-file');
					$landing_page_url = '';
					$landing_page_title = '';
					$landing_page_target = '';
					if($landing_page_link){
						$landing_page_url = $landing_page_link['url'];
						$landing_page_title = $landing_page_link['title'] ? $landing_page_link['title'] : __('Download', 'total-child');
						$landing_page_target = $landing_page_link['target'] ? $landing_page_link['target'] : '_self';
                    }

					$img = '';
					$title = '';
					$button_link = '';
					if($has_landing_page){
                        if($landing_page_url){
	                        if($image_src){
		                        $img = '<a href="'. esc_url($landing_page_url) .'" title="" target="'. $landing_page_target .'"><img src="'. $image_src .'" alt=""></a>';
	                        }
	                        $title = '<a href="'. esc_url($landing_page_url) .'" title="" target="'. $landing_page_target .'">' . get_the_title() . '</a>';
	                        $button_link = '<a class="easl-generic-button easl-size-medium easl-color-light-blue" href="'. esc_url($landing_page_url) .'" title="" target="'. $landing_page_target .'">'. $landing_page_title .'<span class="easl-generic-button-icon"><span class="ticon ticon-chevron-right"></span></span></a>';
                        }else{
	                        if($image_src){
		                        $img = '<img src="'. $image_src .'" alt="">';
	                        }
	                        $title = get_the_title();
                        }
                    }elseif($download_link){
					    if($image_src){
						    $img = '<a href="'. esc_url($download_link) .'" title="" target="_blank" download="'. basename( parse_url( $download_link, PHP_URL_PATH ) ) .'"><img src="'. $image_src .'" alt=""></a>';
                        }
						$title = '<a href="'. esc_url($download_link) .'" title="" target="_blank" download="'. basename( parse_url( $download_link, PHP_URL_PATH ) ) .'">' . get_the_title() . '</a>';
						$button_link = '<a class="easl-generic-button easl-size-medium easl-color-light-blue" href="'. esc_url($download_link) .'" title="" target="_blank" download="'. basename( parse_url( $download_link, PHP_URL_PATH ) ) .'">'. __('Download', 'total-child') .'<span class="easl-generic-button-icon"><span class="ticon ticon-chevron-right"></span></span></a>';
                    }else{
						if($image_src){
							$img = '<img src="'. $image_src .'" alt="">';
						}
						$title = get_the_title();
                    }
					?>
                    <article class="easl-slide-deck-item <?php if($image_src){echo 'easl-slide-deck-item-has-thumb';} ?> easl-sdrow-color-<?php echo easl_get_slide_decks_topic_color(); ?> clr">
						<?php if($img): ?>
                            <div class="easl-slide-deck-item-thumb">
                                <?php echo $img; ?>
                            </div>
						<?php endif; ?>
                        <div class="easl-slide-deck-item-content">
                            <div class="easl-slide-deck-item-meta-title">
	                            <?php if($topic_str): ?>
                                <p class="sp-meta">
                                    <span class="sp-meta-type"><?php _e('Topic:', 'total-child'); ?></span>
                                    <span class="sp-meta-value"><?php echo $topic_str; ?></span>
                                </p>
	                            <?php endif; ?>
                                <h3><?php echo $title ?></h3>
                            </div>
                            <?php
                            if($button_link){
                                echo $button_link;
                            }
                            ?>
                        </div>
                    </article>
				<?php
				endwhile;
				wp_reset_query();
			}else{
				echo '<div class="easl-not-found"><p>'. $not_found_text .'</p></div>';
            }
			?>
        </div>
        <?php if($sponsors_text && $sponsors_text_position == 'bottom'): ?>
            <div class="easl-sd-sponsors">
		        <?php echo do_shortcode($sponsors_text); ?>
            </div>
        <?php endif; ?>
        <div class="easl-slide-decks-bottom-pagination easl-list-pagination" ><?php echo $paginatio_html; ?></div>
        <?php endif; ?>
    </div>
</div>
