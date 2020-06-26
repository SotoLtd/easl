<?php
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

/**
 * Shortcode attributes
 * @var $atts
 * @var $icon_widget
 * @var $el_class
 * @var $el_id
 * @var $css
 * Shortcode class EASL_VC_Icon_Widget
 * @var $this EASL_VC_Icon_Widget
 */
$el_class    = $el_id = $css_animation = '';
$icon_widget = '';
$atts        = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts );

$icon_widget      = absint( $icon_widget );
$icon_post        = get_post( $icon_widget );

$icon             = '';
$hover_state_icon = '';
$link_title       = '';
$link_new_tab     = '';
if ( $icon_post ) {
	$icon             = get_field( 'icon', $icon_widget );
	$hover_state_icon = get_field( 'hover_state_icon', $icon_widget );
	$link_title       = get_field( 'link_title', $icon_widget );
	$link_url         = get_field( 'link_url', $icon_widget );
	$link_new_tab     = get_field( 'link_new_tab', $icon_widget );
}

// Can the logged in user access the URL?
$can_access_url = easl_mz_user_can_access_url( $link_url );
//
//echo '<pre>';
//echo '<br>';
//
//var_dump($link_url);
//
//foreach(easl_mz_get_restricted_urls() as $url) {
//    var_dump($url);
//    echo $url . '<br>';
//    echo $link_url . '<br>';
//    echo substr($link_url, 0, 5) . '<br>';
//    echo substr($url, 0, 5) . '<br>';
//    if (substr($link_url, 10, 5) == substr($url, 10, 5)) {
//        die('got it');
//    }
//    if (trim($link_url) == trim($url)) {
//        die('got it though');
//    }
//}
//echo '</pre>';
if ( $link_url ) {
	$link_url = $can_access_url ? esc_url( $link_url ) : '#';
}

if ( $link_new_tab ) {
	$link_new_tab = ' target="_blank"';
}else{
	$link_new_tab = '';
}

$class_to_filter = 'wpb_easl_icon_widget wpb_content_element ' . $this->getCSSAnimation( $css_animation );
$class_to_filter .= vc_shortcode_custom_css_class( $css, ' ' ) . $this->getExtraClass( $el_class );
$css_class       = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, $class_to_filter, $this->settings['base'], $atts );

if (!$can_access_url) {
    $css_class .= ' disabled';
}
if ( EASL_VC_Icon_Widget_Grid::is_grid_active() ) {
	$css_class .= ' easl-icon-widget-wrap easl-col';
}
if ( $hover_state_icon ) {
	$css_class .= ' has-hover-icon';
}

$wrapper_attributes = array();
if ( ! empty( $el_id ) ) {
	$wrapper_attributes[] = 'id="' . esc_attr( $el_id ) . '"';
}
if ( ! empty( $css_class ) ) {
	$wrapper_attributes[] = 'class="' . esc_attr( $css_class ) . '"';
}

if ( $icon ):
	?>
    <div <?php echo implode( ' ', $wrapper_attributes ); ?>>
        <div class="easl-col-inner">
            <a class="easl-icon-widget-link" href="<?php echo $link_url; ?>"<?php echo $link_new_tab; ?>>
                <span class="easl-icon-widget-icon">
                    <img class="easl-icon-widget-icon-normal" src="<?php echo esc_url( $icon ); ?>" alt="">
                    <?php if ( $hover_state_icon ): ?>
                        <img class="easl-icon-widget-icon-hover" src="<?php echo esc_url( $hover_state_icon ); ?>"
                             alt="">
                    <?php endif; ?>
                </span>
				<?php if ( $link_title ): ?>
                    <span class="easl-icon-widget-title"><?php echo $link_title; ?></span>
				<?php endif; ?>
            </a>
        </div>
    </div>
<?php endif; ?>
