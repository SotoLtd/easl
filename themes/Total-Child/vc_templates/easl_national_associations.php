<?php
/**
 * EASL_VC_Staffs
 */
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}
/**
 * Shortcode attributes
 * @var $atts
 * @var $content - shortcode content
 * @var $el_class
 * @var $el_id
 * @var $this EASL_VC_National_Associations
 */

$el_class              = '';
$css                   = '';
$css_animation         = '';
$contribute_enable     = '';
$contribute_title      = '';
$contribute_subtitle   = '';
$contribute_btitle     = '';
$contribute_bcolor     = '';
$contribute_form       = '';
$contribute_form_title = '';

EASL_VC_National_Associations::reset_nas_data();
EASL_VC_National_Associations::update_nas_count();

$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts );
$class_to_filter = 'wpb_content_element easl-nas-wrap clr';
$class_to_filter .= vc_shortcode_custom_css_class( $css, ' ' ) . $this->getExtraClass( $el_class ) . $this->getCSSAnimation( $css_animation );
$css_class       = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, $class_to_filter, $this->settings( 'base' ), $atts );

$wrapper_attributes = array();

if ( $widget_title && empty( $atts['el_id'] ) ) {
	$atts['el_id'] = sanitize_title_with_dashes( $widget_title );
}

if ( ! empty( $atts['el_id'] ) ) {
	$wrapper_attributes[] = 'id="' . esc_attr( $atts['el_id'] ) . '"';
}
if ( $css_class ) {
	$wrapper_attributes[] = 'class="' . esc_attr( $css_class ) . '"';
}

$contribute_title    = trim( $contribute_title );
$contribute_subtitle = trim( $contribute_subtitle );
$contribute_btitle   = trim( $contribute_btitle );

$page_link = remove_query_arg( 'nas_id' );

$current_nas_id = ! empty( $_GET['nas_id'] ) ? absint( $_GET['nas_id'] ) : '';

$current_nas_post = false;
if ( $current_nas_id ) {
	$current_nas_post = get_post( $current_nas_id );
}
EASL_VC_National_Associations::set_contribute_data( array(
	'enabled' => $contribute_enable == 'true',
    'title' => $contribute_title,
    'subtitle' => $contribute_subtitle,
    'button_title' => $contribute_btitle,
    'button_color' => $contribute_bcolor,
    'form_id' => $contribute_form,
    'form_title' => $contribute_form_title,
) );
EASL_VC_National_Associations::form_overlay_at_footer();
?>
<div <?php echo implode( ' ', $wrapper_attributes ); ?>>
    <div class="eals-nas-filter">
        <?php get_template_part( 'partials/national-association/filter' ); ?>
    </div>
	<?php
    if ( 'true' == $contribute_enable ){
		get_template_part( 'partials/national-association/contribute' );
    }
    ?>
    <div class="easl-nas-details-wrap<?php if ( $current_nas_post ) {
		echo ' easl-active';
	} ?>">
        <div class="easl-nas-details">
			<?php
			if ( $current_nas_post ) {
				global $post;
				$post = $current_nas_post;
				setup_postdata( $post );
				get_template_part( 'partials/national-association/details' );
				wp_reset_postdata();
			}
			?>
        </div>
        <div class="easl-sd-load-icon"><img class="easl-loading-icon" src="<?php echo get_stylesheet_directory_uri(); ?>/images/easl-loader.gif"/></div>
    </div>
</div>