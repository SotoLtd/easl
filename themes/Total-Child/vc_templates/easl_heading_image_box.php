<?php
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

/**
 * Shortcode attributes
 * @var $atts
 * @var $slider_id
 * @var $el_class
 * @var $el_id
 * @var $css
 * @var $content
 * Shortcode class EASL_VC_Heading_Image_Box
 * @var $this EASL_VC_Heading_Image_Box
 */
$el_class   = $el_id = $css_animation = '';
$image      = '';
$heading    = '';
$box_ar     = '';
$popup_type = '';
$link       = '';
$new_tab    = '';
$pop_image  = '';
$pdf        = '';
$video_url  = '';
$gmap_url   = '';
$template   = '';
$sch_type   = '';
$sch_date   = '';
$sch_date2  = '';
$atts       = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts );

$image_src = wp_get_attachment_image_url( $image, 'full' );


$class_to_filter = '';
$class_to_filter .= vc_shortcode_custom_css_class( $css, ' ' ) . $this->getExtraClass( $el_class );
$css_class       = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, $class_to_filter, $this->settings['base'], $atts );

$css_class .= ' easl-heading-image-wrap';

if ( ! $box_ar ) {
	$box_ar = '5_3';
}


$wrapper_attributes       = array();
$has_popup                = false;
$popup_trigger_url        = '';
$popup_inline_content     = '';
$popup_inline_content_id  = '';
$popup_trigger_attributes = array();
$box_id                   = $this->get_box_id( $heading );

switch ( $popup_type ) {
	case 'link':
		$popup_trigger_url          = trim( $link );
		$popup_trigger_attributes[] = 'class="easl-heading-image-box-link"';
		if ( 'true' == $new_tab ) {
			$popup_trigger_attributes[] = 'target="_blank"';
		}
		break;
	case 'image':
		$popup_trigger_url = wp_get_attachment_image_url( $pop_image, 'full' );
		if ( $popup_trigger_url ) {
			$has_popup                  = true;
			$popup_trigger_attributes[] = 'class="easl-heading-image-box-image"';
			$popup_trigger_attributes[] = 'data-fancybox';
		}
		break;
	case 'pdf':
		if ( $pdf ) {
			$has_popup                  = true;
			$popup_trigger_url          = '#' . $box_id;
			$popup_trigger_attributes[] = 'class="easl-heading-image-box-pdf"';
			$popup_trigger_attributes[] = 'data-fancybox';
			$popup_trigger_attributes[] = 'data-type="iframe"';
			$popup_trigger_attributes[] = 'data-src="' . $pdf . '"';
		}
		break;
	case 'video':
		if ( $video_url ) {
			$has_popup                  = true;
			$popup_trigger_url          = $video_url;
			$popup_trigger_attributes[] = 'class="easl-heading-image-box-video"';
			$popup_trigger_attributes[] = 'data-fancybox';
		}
		break;
	case 'gmap':
		if ( $gmap_url ) {
			$has_popup                  = true;
			$popup_trigger_url          = $gmap_url;
			$popup_trigger_attributes[] = 'class="easl-heading-image-box-gmap"';
			$popup_trigger_attributes[] = 'data-fancybox';
			$popup_trigger_attributes[] = 'data-options="{&quot;iframe&quot; : {&quot;css&quot; : {&quot;width&quot; : &quot;80%&quot;, &quot;height&quot; : &quot;80%&quot;}}}"';
		}
		break;
	case 'custom':
		$popup_inline_content = wpb_js_remove_wpautop( $content );
		if ( $popup_inline_content ) {
			$has_popup                  = true;
			$popup_trigger_url          = '#' . $box_id;
			$popup_inline_content_id    = 'easl_custom_' . $box_id . '"';
			$popup_trigger_attributes[] = 'class="easl-heading-image-box-custom"';
			$popup_trigger_attributes[] = 'data-fancybox';
			$popup_trigger_attributes[] = 'data-src="#' . $popup_inline_content_id . '"';
		}
		break;
	case 'template':
		$popup_inline_content = $this->get_template_content( $template );
		if ( $popup_inline_content ) {
			$popup_trigger_url          = '#' . $box_id;
			$has_popup                  = true;
			$popup_inline_content_id    = 'easl_template_' . $template . '_' . $box_id . '"';
			$popup_trigger_attributes[] = 'class="easl-heading-image-box-template"';
			$popup_trigger_attributes[] = 'data-fancybox';
			$popup_trigger_attributes[] = 'data-src="#' . $popup_inline_content_id . '"';
		}
		break;
}

if ( empty( $el_id ) ) {
	$el_id = $box_id;
}
$wrapper_attributes[] = 'id="' . esc_attr( $el_id ) . '"';
$wrapper_attributes[] = 'class="' . esc_attr( $css_class ) . '"';

$schedule_show = easl_validate_schedule( $sch_type, $sch_date, $sch_date2 );

if ( $image_src && $schedule_show ):
	if ( $has_popup && function_exists( 'wpex_enqueue_lightbox_scripts' ) ) {
		wpex_enqueue_lightbox_scripts();
	}
	?>
    <div <?php echo implode( ' ', $wrapper_attributes ); ?>>
        <div class="easl-heading-image-box easl-heading-image-box-<?php echo $box_ar; ?>" style="background-image: url('<?php echo $image_src; ?>')">
            <div class="easl-heading-image-box-sizer"></div>
			<?php if ( $popup_trigger_url ): ?>
                <a href="<?php echo $popup_trigger_url ?>" <?php echo implode( ' ', $popup_trigger_attributes ); ?>>
                    <img class="easl-heading-image-box-image" src="<?php echo $image_src; ?>" alt="<?php echo esc_attr( $heading ); ?>">
                </a>
			<?php else: ?>
                <img class="easl-heading-image-box-image" src="<?php echo $image_src; ?>" alt="<?php echo esc_attr( $heading ); ?>">
			<?php endif; ?>
			<?php if ( $heading ): ?>
                <div class="easl-heading-image-box-heading">
					<?php if ( $popup_trigger_url ): ?>
                        <a href="<?php echo $popup_trigger_url ?>" <?php echo implode( ' ', $popup_trigger_attributes ); ?>>
                            <span><?php echo $heading; ?></span>
                        </a>
					<?php else: ?>
                        <span><?php echo $heading; ?></span>
					<?php endif; ?>
                </div>
			<?php endif; ?>
        </div>
		<?php if ( $popup_inline_content ): ?>
            <div id="<?php echo $popup_inline_content_id; ?>" class="easl-popup-inline-content-wrap container" style="display: none!important;">
                <div class="easl-popup-inline-content"><?php echo $popup_inline_content; ?></div>
            </div>
		<?php endif; ?>
    </div>
<?php endif; ?>