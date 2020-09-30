<?php
// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

/**
 * Shortcode attributes
 * @var $atts
 * @var $content - shortcode content
 * Shortcode class EASL_VC_Logo_Grid
 * @var $this EASL_VC_Logo_Grid
 */
$el_class = '';
$el_id    = '';
$css      = '';

$heading = '';
$logos   = '';
$border  = '';

$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts );

$class_to_filter = '';
$class_to_filter .= vc_shortcode_custom_css_class( $css, ' ' ) . $this->getExtraClass( $el_class );
$css_class       = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, $class_to_filter, $this->settings['base'], $atts );

$css_class = 'easl-logo-grid-wrap ' . $css_class;

if ( 'true' == $border ) {
	$css_class .= ' easl-logo-grid-has-border';
}


$wrapper_attributes = array();
if ( ! empty( $atts['el_id'] ) ) {
	$wrapper_attributes[] = 'id="' . esc_attr( $atts['el_id'] ) . '"';
}
$wrapper_attributes[] = 'class="' . $css_class . '"';
$wrapper_attributes   = implode( ' ', $wrapper_attributes );

if ( $logos ) {
	$logos = explode( ',', $logos );
} else {
	$logos = array();
}
$logos_data = array();
foreach ( $logos as $logo_id ) {
	$logo_id = absint( trim( $logo_id ) );
	if ( $logo_id <= 0 ) {
		continue;
	}
	$src = wp_get_attachment_image_url( $logo_id, 'full' );
	if(!$src){
		continue;
	}
	$logos_data[] = array(
		'src' => $src,
		'alt' => trim( wp_strip_all_tags( get_post_meta( $logo_id, '_wp_attachment_image_alt', true ) ) ),
	);
}
if ( count( $logos_data ) > 0 ):
	?>
    <div <?php echo $wrapper_attributes; ?>>
		<?php if ( $heading ): ?>
            <h3 class="easl-logo-grid-title"><?php echo $heading; ?></h3>
		<?php endif; ?>
        <div class="easl-logo-grid">
			<?php foreach ( $logos_data as $logo_data ): ?>
			<div class="easl-logo-grid-item">
				<div class="easl-logo-grid-item-inner">
					<span><img src="<?php echo $logo_data['src']; ?>" alt="<?php echo $logo_data['alt']; ?>"></span>
				</div>
			</div>
			<?php endforeach; ?>
        </div>
    </div>
<?php endif; ?>