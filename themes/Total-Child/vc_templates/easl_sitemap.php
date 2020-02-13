<?php
// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

/**
 * Shortcode attributes
 * @var $atts
 * @var $content - shortcode content
 * Shortcode class
 * @var $this EASL_VC_Sitemap
 */
$el_class = $el_id = $css = $css_animation = $center_items = '';
$atts = vc_map_get_attributes($this->getShortcode(), $atts);
extract($atts);

$css_animation = $this->getCSSAnimation($css_animation);
$class_to_filter = 'wpb_easl_sitemap wpb_content_element ';
if('true' == $center_items) {
    $class_to_filter .= ' easl-sitmap-center';
}
$class_to_filter .= vc_shortcode_custom_css_class($css, ' ') . $this->getExtraClass($el_class);
$css_class = apply_filters(VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, $class_to_filter, $this->settings['base'], $atts);



$wrapper_attributes = array();
if (!empty($atts['el_id'])) {
    $wrapper_attributes[] = 'id="' . esc_attr($atts['el_id']) . '"';
}

//$main_location = wpex_header_menu_location();
$menu_locations = array('mobile_menu_alt');
//if ($main_location) {
//    $menu_locations[] = $main_location;
//}
if (count($menu_locations) > 0):
    ?>
    <div class=" <?php echo esc_attr($css_class); ?>" <?php echo implode(' ', $wrapper_attributes); ?>>
        <div class="easl-site-map-wrap">
            <div class="easl-site-map-inner">
                <?php
                $all_menu_html = '';
                foreach ($menu_locations as $menu_loc) {
                    $menu_html = wp_nav_menu(array(
                        'theme_location' => $menu_loc,
                        'menu_class' => 'easl-no-megamenu',
                        'id' => '',
                        'container' => '',
                        'container_class' => '',
                        'fallback_cb' => false,
                        'link_before' => '',
                        'link_after' => '',
                        'echo' => false,
                    ));
                    $menu_html = trim($menu_html);
                    $menu_html = preg_replace('/<\/ul>$/', '', $menu_html);
                    $menu_html = preg_replace('/^<ul[^>]+>/', '', $menu_html);
                    $all_menu_html .= $menu_html;
                }
                if ($all_menu_html) {
                    echo '<div class="easl-sitemap-menu-wrap"><ul class="easl-sitemap-menu">' . $all_menu_html . '</ul></div>';
                }
                ?>
            </div>
        </div>
    </div>

<?php endif; ?>