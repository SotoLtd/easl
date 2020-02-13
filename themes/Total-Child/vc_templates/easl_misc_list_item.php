<?php
// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

/**
 * Shortcode attributes
 * @var $atts
 * @var $el_class
 * @var $el_id
 * @var $content
 * Shortcode class EASL_VC_Misc_List_Item
 * @var $this EASL_VC_Misc_List_Item
 */

$el_class      = '';
$el_id         = '';
$css_animation = '';
$type          = '';
$image         = '';
$title         = '';
$excerpt       = '';
$link_text     = '';
$link_url      = '';
$link_target   = '';

$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts );

$image     = absint( trim( $image ) );
$title     = trim( $title );
$excerpt   = trim( $excerpt );
$link_text = trim( $link_text );
$link_url  = trim( $link_url );

$has_valid_data = true;
if ( ! in_array( $type, array( 'downloadable', 'image_content', 'title_excerpt' ) ) ) {
	$has_valid_data = false;
}

$css_class .= 'easl-misc-list-item' . $this->getCSSAnimation( $css_animation ) . ' easl-misc-list-item-' . str_replace( '_', '-', $type );

if($link_target == 'true') {
	$link_target = 'target="_blank"';
}else{
	$link_target = '';
}

if ( 'downloadable' == $type ) {
	if ( ! $title || ! $link_url ) {
		$has_valid_data = false;
	}
	$link_target = 'target="_blank"';
}

if ( 'image_content' == $type ) {
	$image = wp_get_attachment_image_url( $image, 'full' );
	if ( ! $image || ! $excerpt ) {
		$has_valid_data = false;
	}
}
if ( 'title_excerpt' == $type ) {
	if ( ! $title || ! $excerpt ) {
		$has_valid_data = false;
	}
}

$wrapper_attributes = array();
if ( ! empty( $el_id ) ) {
	$wrapper_attributes[] = 'id="' . esc_attr( $el_id ) . '"';
}
if ( ! empty( $css_class ) ) {
	$wrapper_attributes[] = 'class="' . esc_attr( $css_class ) . '"';
}
if ( $has_valid_data ):
	EASL_VC_Misc_List::add_list_items();
	?>
    <li <?php echo implode( ' ', $wrapper_attributes ); ?>>
		<?php if ( 'downloadable' == $type ): ?>
            <a class="easl-misc-list-item-url" <?php echo $link_target; ?> href="<?php echo esc_url( $link_url ); ?>"><?php echo $title; ?>
            <span
                    class="easl-mli-icon"><i class="ticon ticon-chevron-right"></i></span></a>
		<?php elseif ( 'image_content' == $type ): ?>
            <div class="easl-misc-list-item-img">
                <img src="<?php echo esc_url( $image ); ?>" alt="">
            </div>
            <div class="easl-misc-list-item-excerpt"><?php echo $excerpt ?></div>
			<?php if ( $link_text && $link_url ): ?>
                <a class="easl-misc-list-item-url" <?php echo $link_target; ?> href="<?php echo esc_url( $link_url ); ?>"><?php echo $link_text; ?>
                    <span class="easl-mli-icon"><i class="ticon ticon-chevron-right"></i></span></a>
			<?php endif; ?>
		<?php elseif ( 'title_excerpt' == $type ): ?>
            <h5><?php echo $title; ?></h5>
            <div class="easl-misc-list-item-excerpt"><?php echo $excerpt ?></div>
			<?php if ( $link_text && $link_url ): ?>
                <a class="easl-misc-list-item-url" <?php echo $link_target; ?> href="<?php echo esc_url( $link_url ); ?>"><?php echo $link_text; ?>
                    <span class="easl-mli-icon"><i class="ticon ticon-chevron-right"></i></span></a>
			<?php endif; ?>
		<?php endif; ?>
    </li>
<?php endif; ?>
