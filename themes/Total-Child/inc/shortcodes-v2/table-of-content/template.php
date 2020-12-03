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
$el_class          = '';
$el_id             = '';
$css               = '';
$heading           = '';
$items             = '';
$display_type      = '';
$item_font_size    = '';
$item_font_weight  = '';
$item_font_style   = '';
$item_bg_color     = '';
$item_border_color = '';
$item_shape        = '';
$sticky_on_scroll  = '';
$sticky_type       = '';
try {
    $atts = vc_map_get_attributes( $this->getShortcode(), $atts );
} catch ( Exception $e ) {
    unset( $e );
}

extract( $atts );

$css_class = 'easl-table-of-content ' . $this->easlGetCssClass( $el_class, $css, $atts );

if ( ! $display_type ) {
    $display_type = 'inline';
}
$css_class .= ' easl-toc-' . $display_type;
if ( 'true' == $sticky_on_scroll ) {
    $css_class .= ' easl-toc-os-sticky';
    if ( 'left' == $sticky_type ) {
        $css_class .= ' easl-toc-os-sticky-left';
    }
}

$wrapper_attributes = array();
if ( ! empty( $atts['el_id'] ) ) {
    $wrapper_attributes[] = 'id="' . esc_attr( $atts['el_id'] ) . '"';
}
$wrapper_attributes[] = 'class="' . $css_class . '"';
$wrapper_attributes   = implode( ' ', $wrapper_attributes );

$items = $this->get_items( $items );

$item_classes = array();

$item_font_size = absint( $item_font_size );

if ( $item_font_size ) {
    $item_classes[] = 'easl-fsz-' . $item_font_size;
}
if ( $item_font_weight ) {
    $item_classes[] = 'easl-fswmw-' . $item_font_weight;
}
if ( $item_font_style ) {
    $item_classes[] = 'easl-fsl-' . $item_font_style;
}

$item_classes[] = $this->get_color_class( $item_bg_color, 'bgcolor' );
if ( $item_border_color ) {
    $item_classes[] = $this->get_color_class( $item_border_color, 'border' );
}
$item_classes[] = $this->get_shape_class( $item_shape );
$item_classes   = implode( ' ', $item_classes );

if ( count( $items ) > 0 ):
    if ( 'true' == $sticky_on_scroll ) {
        wp_enqueue_script(
            'toc-scripts',
            get_stylesheet_directory_uri() . '/assets/js/toc.js',
            array( 'jquery' ),
            time(),
            true
        );
    }
    ?>
    <div <?php echo $wrapper_attributes; ?>>
        <?php if ( $heading ): ?>
            <h2 class="vc_custom_heading"><?php echo $heading; ?></h2>
        <?php endif; ?>
        <div class="easl-toc-menu-wrap">
            <ul class="easl-toc-menu">
                <?php foreach ( $items as $item ): ?>
                    <li class="local-scroll-link" data-ls_linkto="#<?php echo $item['target']; ?>">
                        <a class="<?php echo $item_classes; ?>" href="#<?php echo $item['target']; ?>"><?php echo $item['title']; ?></a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
<?php endif; ?>