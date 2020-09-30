<?php
// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

/**
 * Shortcode attributes
 * @var $atts
 * @var $content - shortcode content
 * Shortcode class EASL_VC_Separator
 * @var $this EASL_VC_Table_Of_Content
 */
$el_class     = '';
$el_id        = '';
$css          = '';
$heading      = '';
$items        = '';
$display_type = '';

$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts );

$class_to_filter = '';
$class_to_filter .= vc_shortcode_custom_css_class( $css, ' ' ) . $this->getExtraClass( $el_class );
$css_class       = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, $class_to_filter, $this->settings['base'], $atts );

$css_class = 'easl-table-of-content ' . $css_class;

if(!$display_type) {
	$display_type = 'inline';
}
$css_class .= ' easl-toc-' . $display_type;

$wrapper_attributes = array();
if ( ! empty( $atts['el_id'] ) ) {
	$wrapper_attributes[] = 'id="' . esc_attr( $atts['el_id'] ) . '"';
}
$wrapper_attributes[] = 'class="' . $css_class . '"';
$wrapper_attributes   = implode( ' ', $wrapper_attributes );

$items = $this->get_items( $items );

if ( count( $items ) > 0 ):
	?>
    <div <?php echo $wrapper_attributes; ?>>
        <?php if($heading): ?>
        <h2 class="vc_custom_heading"><?php echo $heading; ?></h2>
        <?php endif; ?>
        <ul>
			<?php foreach ( $items as $item ): ?>
                <li class="local-scroll-link" data-ls_linkto="#<?php echo $item['target']; ?>"><a href="#<?php echo $item['target']; ?>"><?php echo $item['title']; ?></a></li>
			<?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>