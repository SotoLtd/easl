<?php
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

/**
 * Shortcode attributes
 * @var $atts
 * @var $el_class
 * @var $el_id
 * @var $css
 * Shortcode class EASL_VC_Annual_Reports
 * @var $this EASL_VC_Annual_Reports
 */

$el_class = $el_id = $css = $css_animation = '';
$atts     = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts );

$css_animation   = $this->getCSSAnimation( $css_animation );
$class_to_filter = 'wpb_easl_annual_reports wpb_content_element ';
$class_to_filter .= vc_shortcode_custom_css_class( $css, ' ' ) . $this->getExtraClass( $el_class );
$css_class       = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, $class_to_filter, $this->settings['base'], $atts );

$wrapper_attributes = array();
if ( ! empty( $atts['el_id'] ) ) {
	$wrapper_attributes[] = 'id="' . esc_attr( $atts['el_id'] ) . '"';
}

$top_rows  = '';
$arch_rows = '';

$annual_reports = new WP_Query( array(
	'posts_per_page' => - 1,
	'post_type'      => 'annual_reports',
	'post_status'    => 'publish',
	'order'          => 'DESC',
	'orderby'        => 'meta_value_num',
	'meta_key'       => 'annual_reports_year',
) );

if ( $annual_reports->have_posts() ) {
	$counter = 0;
	while ( $annual_reports->have_posts() ) {

		$annual_reports->the_post();
		$download_link = get_field( 'annual_reports_pdf_file' );
		$online_link = $download_link;
            //'download="' . basename( parse_url( $url, PHP_URL_PATH ) ) . '"'
		$image = has_post_thumbnail( get_the_ID() ) ?
			wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ), 'single-post-thumbnail' ) : '';
		if ( $counter < 3 ):
			$top_rows .= '<div class="wpb_column vc_column_container vc_col-sm-4">' .
			             '<div class="vc_column-inner ">' .
			             '<div class="wpb_wrapper">' .
			             '<div class="vc_row wpb_row vc_inner vc_row-fluid">' .
			             '<div class="wpb_column vc_column_container vc_col-sm-6">' .
			             '<div class="vc_column-inner ">' .
			             '<div class="wpb_wrapper">' .
			             '<div  class="wpb_single_image wpb_content_element annual-report-thumb">' .
			             '<figure class="wpb_wrapper vc_figure">' .
			             '<div class="vc_single_image-wrapper   vc_box_border_grey">';
			if ( $image ):
				$top_rows .= '<img width="159" height="233" src="' . $image[0] . '" 
                                        class="vc_single_image-img attachment-full" alt="' . get_the_title() . '" />';
			endif;
			$top_rows .= '</div>' .
			             '</figure>' .
			             '</div>' .
			             '</div>' .
			             '</div>' .
			             '</div>' .
			             '<div class="wpb_column vc_column_container vc_col-sm-6">' .
			             '<div class="vc_column-inner ">' .
			             '<div class="wpb_wrapper">' .
			             '<h2 style="font-size: 20px;text-align: left" class="vc_custom_heading" >' . get_the_title() . '</h2>' .
			             '<div class="vc_custom_1535647240328 wpex-clr" style="margin-top: 20px; margin-bottom: 5px;">' .
			             '<div class="theme-button-block-wrap theme-button-wrap clr">' .
			             '<a href="' . $download_link . '" target="_blank"' .
			             'class="vcex-button theme-button block animate-on-hover" ' .
			             'style="color:#ffffff;width:100%;font-family:KnockoutHTF51Middleweight;"' .
			             '>' .
			             '<span class="theme-button-inner">' .
			             '</span>View and download report</span>' .
			             '<span class="vcex-icon-wrap theme-button-icon-right">' .
			             '<span class="ticon ticon-angle-right"></span>' .
			             '</a>' .
			             '</div>' .
			             '</div>' .
			             '<div class="vc_custom_1535647249115 wpex-clr" style="margin-top: 20px; margin-bottom: 5px;">' .
			             '</div>' .
			             '</div>' .
			             '</div>' .
			             '</div>' .
			             '</div>' .
			             '</div>' .
			             '</div>' .
			             '</div>';
		else:
			$arch_rows .= '<div class="wpb_column vc_column_container vc_col-sm-2">' .
			              '<div class="vc_column-inner ">' .
			              '<div class="wpb_wrapper">' .
			              '<a href="' . get_field( 'annual_reports_pdf_file' ) . '" ' .
			              'class="vcex-button theme-txt-link inline animate-on-hover" ' .
			              'target="_blank" style="color:#114f85;">' .
			              '<span class="theme-button-inner">' . get_the_title() . '<span class="vcex-icon-wrap theme-button-icon-right">' .
			              '<span class="ticon ticon-angle-right"></span></span></span></a>' .
			              '</div>' .
			              '</div>' .
			              '</div>';
		endif;
		$counter ++;
	}
	wp_reset_query();
}
?>
<div class=" <?php echo esc_attr( $css_class ); ?>" <?php echo implode( ' ', $wrapper_attributes ); ?>>
	<?php if ( $top_rows ): ?>
        <div class="vc_row wpb_row vc_row-fluid">
			<?php echo $top_rows; ?>
        </div>
	<?php endif; ?>
	<?php if ( $arch_rows ): ?>
        <div class="vc_row wpb_row vc_row-fluid">
            <div class="wpb_column vc_column_container vc_col-sm-12">
                <div class="vc_column-inner ">
                    <div class="wpb_wrapper">
                        <h2 style="font-size: 30px;color: #78cdf2;text-align: left" class="vc_custom_heading">Archived
                            Reports</h2>
                        <div class="vc_row wpb_row vc_inner vc_row-fluid" style="margin-top: 20px; margin-bottom: 5px;">
							<?php echo $arch_rows; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
	<?php endif; ?>
</div>
