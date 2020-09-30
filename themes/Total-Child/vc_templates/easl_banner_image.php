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
 * @var $content
 * Shortcode class EASL_VC_Banner_image
 * @var $this EASL_VC_Banner_image
 */
$el_class  = $el_id = $css_animation = '';
$image     = '';
$image_alt = '';
$link      = '';
$new_tab   = '';
$title     = '';
$subtitle  = '';
$atts      = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts );

$image_src = wp_get_attachment_image_url( $image, 'full' );

$title    = $this->escape_text( $title );
$subtitle = $this->escape_text( $subtitle );
$content  = $this->escape_text( $content );

$class_to_filter = '';
$class_to_filter .= vc_shortcode_custom_css_class( $css, ' ' ) . $this->getExtraClass( $el_class );
$css_class       = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, $class_to_filter, $this->settings['base'], $atts );

$css_class .= ' easl-banner-image-wrap';


$wrapper_attributes = array();

if ( $el_id ) {
	$wrapper_attributes[] = 'id="' . esc_attr( $el_id ) . '"';
}
$wrapper_attributes[] = 'class="' . esc_attr( $css_class ) . '"';

$html_tag      = '';
$html_tag_attr = array();
if ( $link ) {
	$html_tag      = 'a';
	$html_tag_attr = array(
		'class="easl-banner-image easl-banner-image-link"',
		'href="' . esc_url( $link ) . '"'
	);
	if ( 'true' == $new_tab ) {
		$html_tag_attr[] = 'target="_blank"';
	}
} else {
	$html_tag      = 'div';
	$html_tag_attr = array(
		'class="easl-banner-image easl-banner-image-nolink"',
	);
}
$html_tag_attr = implode( ' ', $html_tag_attr );
$opening_tag   = "<{$html_tag} {$html_tag_attr}>";
$closing_tag   = "</{$html_tag}>";

if ( ! $image_alt && $title ) {
	$image_alt = trim( strip_tags( $title ) );
}

if ( $image_src ):?>
    <div <?php echo implode( ' ', $wrapper_attributes ); ?>>
		<?php echo $opening_tag; ?>
        <img class="easl-banner-image-img" src="<?php echo $image_src; ?>" alt="<?php echo esc_attr( $image_alt ); ?>">
		<?php if ( $title || $subtitle || $content ): ?>
            <div class="easl-banner-image-overlay">
                <div class="container">
                    <div class="easl-banner-image-overlay-inner easl-hps-caption-content">
						<?php if ( $title ): ?>
                            <h2 class="easl-hsc-easl-hsc-title"><?php echo $title; ?></h2>
						<?php endif; ?>
						<?php if ( $subtitle ): ?>
                            <h3 class="easl-hsc-easl-hsc-subtitle"><?php echo $subtitle; ?></h3>
						<?php endif; ?>
						<?php if ( $content ): ?>
                            <p class="easl-hsc-easl-hsc-text"><?php echo $content; ?></p>
						<?php endif; ?>
                    </div>
                </div>
            </div>
		<?php endif; ?>
		<?php echo $closing_tag; ?>
    </div>
<?php endif; ?>