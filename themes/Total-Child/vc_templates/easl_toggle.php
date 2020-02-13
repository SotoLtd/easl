<?php
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}
/**
 * Shortcode attributes
 * @var $atts
 * @var $title
 * @var $el_class
 * @var $style
 * @var $color
 * @var $size
 * @var $open
 * @var $css_animation
 * @var $el_id
 * @var $content - shortcode content
 * @var $css
 */
$title = '';
extract( $atts );


?>
<div class="vc_toggle vc_toggle_easl-toggle">
	<div class="vc_toggle_title">
		<h4><?php echo $data['title']; ?></h4>
		<i class="vc_toggle_icon"></i></div>
	<div class="vc_toggle_content"><?php echo wpb_js_remove_wpautop( apply_filters( 'the_content', $content ), true ); ?></div>
</div>
