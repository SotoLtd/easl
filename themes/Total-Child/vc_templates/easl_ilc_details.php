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
 * Shortcode class EASL_VC_ILC_Details
 * @var $this EASL_VC_ILC_Details
 */

$el_class = $el_id = $css_animation = $css = '';
$ilc_id = $display_selector = $selector_title = '';

$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts );


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

$ilc_id = absint($ilc_id);
if(!empty($_GET['ilc'])){
	$ilc_object = $this->get_ilc_by_name($_GET['ilc']);
}elseif($ilc_id){
	$ilc_object = get_post($ilc_id);
}else{
	$ilc_object = $this->get_latest_ilc();
}

if($ilc_object):
	$current_page_url = remove_query_arg('ilc');
	if(function_exists('get_field')){
		$congress_material_url = get_field('congress_materials', $ilc_object->ID);
		$congress_report_pdf = get_field('congress_report_pdf', $ilc_object->ID);
		$downloadable_congress_report = get_field('downloadable_congress_report', $ilc_object->ID);
		$congress_report_new_tab = get_field('congress_report_new_tab', $ilc_object->ID);
		$daily_news = get_field('daily_news', $ilc_object->ID);
		$debriefs = get_field('debriefs', $ilc_object->ID);
	}else{
		$congress_material_url = get_post_meta($ilc_object->ID, 'congress_materials', true);
		$congress_report_pdf = get_post_meta($ilc_object->ID, 'congress_report_pdf', true);
		$downloadable_congress_report = get_post_meta($ilc_object->ID, 'downloadable_congress_report', true);
		$congress_report_new_tab = get_post_meta($ilc_object->ID, 'congress_report_new_tab', true);
		$daily_news = get_post_meta($ilc_object->ID, 'daily_news', true);
		$debriefs = get_post_meta($ilc_object->ID, 'debriefs', true);
	}
	$debriefs = $this->get_debrief_data($debriefs);
	if($debriefs){
		wp_enqueue_script('easl-yt-playlist', get_theme_file_uri('assets/js/yt-playlist.js'), array('jquery'), EASL_THEME_VERSION, true);
	}
	if($downloadable_congress_report) {
		$downloadable_congress_report = ' download="' . basename( parse_url( $congress_report_pdf, PHP_URL_PATH ) ) . '"';
    }
	if($congress_report_new_tab) {
		$congress_report_new_tab = ' target="_blank"';
    }
?>
<div <?php echo implode( ' ', $wrapper_attributes ); ?>>
	<div class="easl-ilc-details">
		<?php if($display_selector == 'true' && $ilcs = EASL_ILC_Config::get_ilcs()): ?>
		<div class="easl-ilc-details-filter">
			<?php if($selector_title): ?>
			<h4><?php echo esc_html($selector_title); ?></h4>
			<?php endif; ?>
			<div class="easl-dropdown">
				<span class="easl-dropdown-label"><?php echo get_the_title($ilc_object); ?></span>
				<ul>
					<?php foreach ($ilcs as $ilc): ?>
					<li><a href="<?php echo add_query_arg(array('ilc' => $ilc['value']), $current_page_url); ?>"><span><?php echo $ilc['label']; ?></span></a></li>
					<?php endforeach; ?>
				</ul>
			</div>
		</div>
		<?php endif; ?>
		<?php if($congress_material_url || $congress_report_pdf || $daily_news) ?>
		<div class="easl-ilc-details-buttons easl-row">
			<?php if($daily_news): ?>
			<div class="easl-col easl-col-3">
				<div class="easl-col-inner">
					<a class="easl-ilc-details-button" target="_blank" href="<?php echo esc_url($congress_material_url); ?>"><?php _e('<span>Congress</span> Material', 'total-child'); ?></a>
				</div>
			</div>
			<?php endif;  ?>
			<?php if($congress_report_pdf): ?>
			<div class="easl-col easl-col-3">
				<div class="easl-col-inner">
					<a class="easl-ilc-details-button" href="<?php echo esc_url($congress_report_pdf); ?>"<?php echo $downloadable_congress_report.$congress_report_new_tab; ?>><?php _e('<span>Congress</span> Report', 'total-child'); ?></a>
				</div>
			</div>
			<?php endif;  ?>
			<?php if($daily_news): ?>
			<div class="easl-col easl-col-3">
				<div class="easl-col-inner">
					<a class="easl-ilc-details-button" target="_blank" href="<?php echo esc_url($daily_news); ?>"><?php _e('<span>Congress</span> News', 'total-child'); ?></a>
				</div>
			</div>
			<?php endif;  ?>
		</div>
		<?php if($debriefs): ?>
		<div class="easl-ilc-details-debriefs">
            <h3><?php _e('Debriefs'); ?></h3>
			<div class="easl-yt-playlist" data-source="<?php echo $debriefs['source']; ?>" data-id="<?php echo $debriefs['id']; ?>"></div>
		</div>
		<?php endif; ?>
	</div>
</div>
<?php endif; ?>