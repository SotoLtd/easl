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
 * Shortcode class EASL_VC_Homepage_Slider
 * @var $this EASL_VC_Homepage_Slider
 */
$el_class    = $el_id = $css_animation = '';
$icon_widget = '';
$atts        = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts );

$slider_id = absint( $slider_id );

$slides = $this->get_slides( $slider_id );

$class_to_filter = '';
$class_to_filter .= vc_shortcode_custom_css_class( $css, ' ' ) . $this->getExtraClass( $el_class );
$css_class       = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, $class_to_filter, $this->settings['base'], $atts );

$css_class .= ' easl-home-page-slider-wrap';

$wrapper_attributes = array();

EASL_VC_Homepage_Slider::$slider_instance_count ++;

if ( empty( $el_id ) ) {
	$el_id = 'easl-home-page-slider-' . $slider_id . '-' . EASL_VC_Homepage_Slider::$slider_instance_count;
}
$wrapper_attributes[] = 'id="' . esc_attr( $el_id ) . '"';

$wrapper_attributes[] = 'class="' . esc_attr( $css_class ) . '"';

if ( count( $slides ) > 0 ):
	$this->enqueue_slider_assets();
	$slider_settings = $this->get_slider_settings( $slider_id );
	$navigation_dots = array();
	?>
    <div <?php echo implode( ' ', $wrapper_attributes ); ?>>
        <div class="rev_slider_wrapper fullwidthbanner-container">
            <div id="rev_slider_<?php echo $slider_id . '_' . EASL_VC_Homepage_Slider::$slider_instance_count; ?>" class="easl-homepage-slider rev_slider fullwidthabanner" data-version="5.4.8" style="display: none;" data-autoplay="<?php echo $slider_settings['autoplay']; ?>" data-delay="<?php echo $slider_settings['autoplay_duration']; ?>">
                <ul>
					<?php
					foreach ( $slides as $slide ):
						$navigation_dots[] = '<a class="easl-hps-nav-dot-item" href="#"></a>';
						?>
                        <li <?php echo $slide['data_atts']; ?>>
                            <img src="<?php echo esc_url( $slide['image'] ); ?>" alt="<?php echo esc_attr( $slide['image_alt'] ); ?>"
                                    class="rev-slidebg"
                                    data-bgposition="<?php echo $slide['image_pos']; ?>"
                                    data-bgfit="cover"
                                    data-bgrepeat="no-repeat"
                            />
                            <div
                                    data-frames='[{"delay": 0,"speed": 300,"from": "opacity: 0","to": "opacity: 1"},{"delay": "wait","speed": 300,"to": "opacity: 0"}]'
                                    data-visibility="['on', 'on', 'on', 'on']"
                                    data-x="left"
                                    data-y="bottom"
                                    data-width="100%"
                                    class="tp-caption easl-hps-caption"
                            >
								<?php
								if ( $slide['link_html'] ) {
									echo $slide['link_html'];
								}
								?>
                                <div class="easl-hps-caption-inner container"><?php
	                                if ( $slide['link_html'] ) {
		                                echo $slide['link_html'];
	                                }
	                                ?>
                                    <div class="easl-hps-caption-content">
										<?php
										echo $slide['title'];
										echo $slide['subtitle'];
										echo $slide['text'];
										if ( $slide['cta1'] || $slide['cta2'] ) {
											echo '<div class="easl-hps-caption-cta-wrap">';
										}
										if ( $slide['cta1'] ) {
											echo $slide['cta1'];
										}
										if ( $slide['cta2'] ) {
											echo $slide['cta2'];
										}
										if ( $slide['cta1'] || $slide['cta2'] ) {
											echo '</div>';
										}
										?>
                                    </div>
                                </div>
                            </div>
                        </li>
					<?php endforeach; ?>
                </ul>
            </div>
        </div>
	    <?php if ( count( $navigation_dots ) > 1 ): ?>
            <div class="easl-hps-nav-wrap">
                <div class="easl-hps-nav-arrows-wrap">
                    <a href="#" class="easl-hps-nav-arrow-left"><span class="ticon ticon-chevron-left"></span></a>
                    <a href="#" class="easl-hps-nav-arrow-right"><span class="ticon ticon-chevron-right"></span></a>
                </div>
            </div>
	    <?php endif; ?>
    </div>
<?php endif; ?>