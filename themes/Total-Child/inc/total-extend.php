<?php
function easlenqueueTtaScript() {
	if ( ! defined( 'WPB_VC_VERSION' ) ) {
		return;
	}
	wp_register_style( 'vc_tta_style', vc_asset_url( 'css/js_composer_tta.min.css' ), false, WPB_VC_VERSION );
	wp_enqueue_style( 'vc_tta_style' );
	wp_register_script( 'vc_accordion_script', vc_asset_url( 'lib/vc_accordion/vc-accordion.min.js' ), array( 'jquery' ), WPB_VC_VERSION, true );
	wp_register_script( 'vc_tta_autoplay_script', vc_asset_url( 'lib/vc-tta-autoplay/vc-tta-autoplay.min.js' ), array( 'vc_accordion_script' ), WPB_VC_VERSION, true );

	wp_enqueue_script( 'vc_accordion_script' );
	if ( ! vc_is_page_editable() ) {
		wp_enqueue_script( 'vc_tta_autoplay_script' );
	}
}

function wpex_cac_has_header_right_social() {
	return get_theme_mod( 'header_right_social', true );
}

function easl_vc_button_icons() {
	return array(
		__( 'No Icon', 'total' )           => '',
		__( 'Download Blue', 'total' )     => 'download-blue',
		__( 'EILF Globe', 'total' )        => 'eilf-globe',
		__( 'Speech Buble Blue', 'total' ) => 'speech-bubble-blue',
	);
}

function easl_vc_button_grid_icons() {
	return array(
		__( 'No Icon', 'total' )          => '',
		__( 'Awards', 'total' )           => 'awards',
		__( 'Bursaries', 'total' )        => 'bursaries',
		__( 'Calendar', 'total' )         => 'calendar',
		__( 'Education', 'total' )        => 'education',
		__( 'Fellowship', 'total' )       => 'fellowship',
		__( 'Flask', 'total' )            => 'flask',
		__( 'Groups', 'total' )           => 'groups',
		__( 'Membership', 'total' )       => 'membership',
		__( 'Mentorship', 'total' )       => 'mentorship',
		__( 'Microb-Predict', 'total' )   => 'microb-predict',
		__( 'Liver', 'total' )            => 'liver',
		__( 'Arrows', 'total' )           => 'arrows',
		__( 'Arrows', 'total' )           => 'arrows',
		__( 'Globe', 'total' )            => 'globe',
		__( 'UEG', 'total' )              => 'ueg',
		__( 'ALEH', 'total' )             => 'aleh',
		__( 'AASLD', 'total' )            => 'aasld',
		__( 'APASL', 'total' )            => 'apasl',
		__( 'Aliver', 'total' )           => 'aliver',
		__( 'Aliver Green', 'total' )     => 'aliver-green',
		__( 'Apps', 'total' )             => 'apps',
		__( 'Report', 'total' )           => 'report',
		__( 'Twitter', 'total' )          => 'twitter',
		__( 'Video', 'total' )            => 'video',
		__( 'Litmus', 'total' )           => 'litmus',
		__( 'EU stars ', 'total' )        => 'eu-star',
		__( 'Hand shake', 'total' )       => 'handshake',
		__( 'group of people', 'total' )  => 'group',
		__( 'ID card', 'total' )          => 'id-card',
		__( 'Hepatocytes', 'total' )      => 'hepatocytes',
		__( 'Two faces', 'total' )        => 'two-faces',
		__( 'Scroll', 'total' )           => 'scroll',
		__( 'LiverTree', 'total' )        => 'livertree',
		__( 'e-Learning', 'total-child' ) => 'elearning',
		__( 'Liverscreen', 'total-child' ) => 'liverscreen',
	);
}

function easl_title_icons() {
	return array(
		''           => __( 'No Icon', 'total' ),
		'aasld'      => __( 'AASLD', 'total' ),
		'advocacy'   => __( 'Advocacy', 'total' ),
		'aleh'       => __( 'ALEH', 'total' ),
		'apasl'      => __( 'APASL', 'total' ),
		'arrows'     => __( 'Arrows', 'total' ),
		'awards'     => __( 'Awards', 'total' ),
		'bursaries'  => __( 'Bursaries', 'total' ),
		'calendar'   => __( 'Calendar', 'total' ),
		'education'  => __( 'Education', 'total' ),
		'fellowship' => __( 'Fellowship', 'total' ),
		'flask'      => __( 'Flask', 'total' ),
		'globe'      => __( 'Globe', 'total' ),
		'groups'     => __( 'Groups', 'total' ),
		'liver'      => __( 'Liver', 'total' ),
		'membership' => __( 'Membership', 'total' ),
		'mentorship' => __( 'Mentorship', 'total' ),
		'science'    => __( 'Science ', 'total' ),
		'ueg'        => __( 'UEG', 'total' ),
		'litmus'     => __( 'Litmus', 'total' ),
	);
}

function easl_social_profile_options_list() {
	return array(
		'facebook'    => array(
			'label'      => 'Facebook',
			'icon_class' => 'ticon ticon-facebook',
		),
		'twitter'     => array(
			'label'      => 'Twitter',
			'icon_class' => 'ticon ticon-twitter',
		),
		'linkedin'    => array(
			'label'      => 'LinkedIn',
			'icon_class' => 'ticon ticon-linkedin',
		),
		'youtube'     => array(
			'label'      => 'Youtube',
			'icon_class' => 'ticon ticon-youtube',
		),
		'googleplus'  => array(
			'label'      => 'Google Plus',
			'icon_class' => 'ticon ticon-google-plus',
		),
		'pinterest'   => array(
			'label'      => 'Pinterest',
			'icon_class' => 'ticon ticon-pinterest',
		),
		'dribbble'    => array(
			'label'      => 'Dribbble',
			'icon_class' => 'ticon ticon-dribbble',
		),
		'etsy'        => array(
			'label'      => 'Etsy',
			'icon_class' => 'ticon ticon-etsy',
		),
		'vk'          => array(
			'label'      => 'VK',
			'icon_class' => 'ticon ticon-vk',
		),
		'instagram'   => array(
			'label'      => 'Instagram',
			'icon_class' => 'ticon ticon-instagram',
		),
		'flickr'      => array(
			'label'      => 'Flickr',
			'icon_class' => 'ticon ticon-flickr',
		),
		'skype'       => array(
			'label'      => 'Skype',
			'icon_class' => 'ticon ticon-skype',
		),
		'whatsapp'    => array(
			'label'      => 'Whatsapp',
			'icon_class' => 'ticon ticon-whatsapp',
		),
		'vimeo'       => array(
			'label'      => 'Vimeo',
			'icon_class' => 'ticon ticon-vimeo-square',
		),
		'vine'        => array(
			'label'      => 'Vine',
			'icon_class' => 'ticon ticon-vine',
		),
		'spotify'     => array(
			'label'      => 'Spotify',
			'icon_class' => 'ticon ticon-spotify',
		),
		'xing'        => array(
			'label'      => 'Xing',
			'icon_class' => 'ticon ticon-xing',
		),
		'yelp'        => array(
			'label'      => 'Yelp',
			'icon_class' => 'ticon ticon-yelp',
		),
		'tripadvisor' => array(
			'label'      => 'Tripadvisor',
			'icon_class' => 'ticon ticon-tripadvisor',
		),
		'houzz'       => array(
			'label'      => 'Houzz',
			'icon_class' => 'ticon ticon-houzz',
		),
		'twitch'      => array(
			'label'      => 'Twitch',
			'icon_class' => 'ticon ticon-twitch',
		),
		'rss'         => array(
			'label'      => __( 'RSS', 'total' ),
			'icon_class' => 'ticon ticon-rss',
		),
		'email'       => array(
			'label'      => __( 'Email', 'total' ),
			'icon_class' => 'ticon ticon-envelope',
		),
		'phone'       => array(
			'label'      => __( 'Phone', 'total' ),
			'icon_class' => 'ticon ticon-phone',
		),
	);
}

add_filter( 'wpex_social_profile_options_list', 'easl_social_profile_options_list' );

function easl_extended_meta( $array, $post ) {
	$array['main']['settings']['easl_extra_body_class'] = array(
		'title'       => esc_html__( 'Extra body class', 'total-child' ),
		'type'        => 'text',
		'id'          => 'easl_extra_body_class',
		'description' => esc_html__( 'Add an extra body class for ths page.', 'total-child' ),
	);
	$array['title']['settings']['title_icon']           = array(
		'title'       => esc_html__( 'Title icon', 'total' ),
		'type'        => 'select',
		'id'          => 'ese_post_title_icon',
		'description' => esc_html__( 'Select a icon to display on left of the title for this page or post.', 'total' ),
		'options'     => easl_title_icons(),
	);
	$array['title']['settings']['page_title_color']     = array(
		'title'       => esc_html__( 'Title Text Color', 'total-child' ),
		'id'          => 'easl_page_title_color',
		'type'        => 'select',
		'description' => esc_html__( 'Select a page title color.', 'total-child' ),
		'options'     => array(
			''           => esc_html__( 'Default', 'total-child' ),
			'black'      => esc_html__( 'Black', 'total-child' ),
			'blue'       => esc_html__( 'Blue', 'total-child' ),
			'light-blue' => esc_html__( 'Light Blue', 'total-child' ),
			'white'      => esc_html__( 'White', 'total-child' ),
		),
	);
	$array['title']['settings']['allow_shortcode_html'] = array(
		'title'       => esc_html__( 'HTML & Shortcode in Custom Title', 'total-child' ),
		'id'          => 'easl_title_allow_shortcode_html',
		'type'        => 'select',
		'description' => esc_html__( 'Allow HTML tags and Shortcodes in custom title.', 'total-child' ),
		'options'     => array(
			''        => esc_html__( 'Default', 'total-child' ),
			'enable'  => esc_html__( 'Enable', 'total-child' ),
			'disable' => esc_html__( 'Disable', 'total-child' ),
		),
	);

	return $array;
}

add_filter( 'wpex_metabox_array', 'easl_extended_meta', 10, 2 );

function easl_remove_parents_action() {
	// Page Header
	remove_action( 'wpex_hook_page_header_inner', 'wpex_display_breadcrumbs' );
	add_action( 'wpex_hook_page_header_inner', 'wpex_display_breadcrumbs', 9 );

	remove_action( 'wpex_hook_page_header_inner', 'wpex_page_header_title_table_wrap_open', 0 );
	remove_action( 'wpex_hook_page_header_inner', 'wpex_page_header_title_table_wrap_close', 9999 );

	remove_action( 'wpex_hook_footer_after', 'wpex_footer_bottom' );
	//add_action( 'wpex_hook_footer_after', 'wpex_footer_bottom' );
	remove_action( 'wpex_hook_footer_bottom_inner', 'wpex_footer_bottom_copyright' );
	remove_action( 'wpex_hook_footer_bottom_inner', 'wpex_footer_bottom_menu' );

	add_action( 'wpex_hook_footer_bottom', 'wpex_footer_bottom_copyright' );
	add_action( 'wpex_hook_footer_bottom', 'wpex_footer_bottom_menu' );
}

add_action( 'template_redirect', 'easl_remove_parents_action' );


function easl_template_parts( $parts ) {
	$parts['topbar_countdown'] = 'partials/topbar/topbar-countdown';

	return $parts;
}

add_filter( 'wpex_template_parts', 'easl_template_parts' );

function easl_vc_add_params() {
	vc_add_params( 'vc_single_image', array(

		array(
			'type'        => 'textfield',
			'heading'     => __( 'Over Image Link Text', 'total' ),
			'param_name'  => 'img_over_link_text',
			'description' => __( 'Use this field to add a overlay caption with link', 'total' ),
			'group'       => __( 'Over Image Link', 'total' ),
		),
		array(
			'type'        => 'textfield',
			'heading'     => __( 'Over Image Link', 'total' ),
			'param_name'  => 'img_over_link',
			'description' => __( 'Use this field to add a overlay caption with link', 'total' ),
			'group'       => __( 'Over Image Link', 'total' ),
		),
		array(
			'type'       => 'dropdown',
			'heading'    => __( 'Over ImageLink Target', 'js_composer' ),
			'param_name' => 'img_over_linktarget',
			'value'      => vc_target_param_list(),
			'group'      => __( 'Over Image Link', 'total' ),
		),
	) );
	$tour_styles                                     = WPBMap::getParam( 'vc_tta_tour', 'style' );
	$tour_styles                                     = $tour_styles['value'];
	$tour_styles[ __( 'EASL Flat', 'total-child' ) ] = 'easl-flat';
	vc_update_shortcode_param( 'vc_tta_tour', array(
		'param_name' => 'style',
		'value'      => $tour_styles
	) );

	$toggle_styles                                       = WPBMap::getParam( 'vc_toggle', 'style' );
	$toggle_styles                                       = $toggle_styles['value'];
	$toggle_styles[ __( 'EASL Toggle', 'total-child' ) ] = 'easl-toggle';
	vc_update_shortcode_param( 'vc_toggle', array(
		'param_name' => 'style',
		'value'      => $toggle_styles
	) );
}

add_action( 'vc_after_init', 'easl_vc_add_params', 40 );


add_filter( 'wpex_main_metaboxes_post_types', 'easl_total_post_types' );
function easl_total_post_types( $post_types ) {
	$post_types['event'] = 'event';

	return $post_types;
}


function easl_page_header_title_table_wrap_open() {
	if ( is_singular( 'event' ) && 'background-image' != wpex_page_header_style() ) {
		echo '<div class="page-header-table clr"><div class="page-header-table-cell">';
	}
}

function easl_page_header_title_table_wrap_close() {
	if ( is_singular( 'event' ) && 'background-image' != wpex_page_header_style() ) {
		echo '</div></div>';
	}
}

add_action( 'wpex_hook_page_header_inner', 'easl_page_header_title_table_wrap_open', 0 );
add_action( 'wpex_hook_page_header_inner', 'easl_page_header_title_table_wrap_close', 9999 );


function easl_get_news_page_header_style() {
	$page_id = wpex_get_mod( 'blog_page', 5626 );
	$style   = get_post_meta( $page_id, 'wpex_post_title_style', true );
	$style   = ( 'default' == $style ) ? '' : $style;

	return $style;
}

function easl_get_news_page_header_height() {
	$page_id      = wpex_get_mod( 'blog_page', 5626 );
	$title_height = get_post_meta( $page_id, 'wpex_post_title_height', true );
	$title_height = $title_height ? $title_height : wpex_get_mod( 'page_header_table_height' );

	return $title_height;
}

function easl_get_news_page_header_overlay_style() {
	$page_id = wpex_get_mod( 'blog_page', 5626 );
	$style   = get_post_meta( $page_id, 'wpex_post_title_background_overlay', true );
	$style   = $style == 'none' ? '' : $style;

	return $style;
}

function easl_get_news_page_header_bg( $post_id ) {
	$page_id  = wpex_get_mod( 'blog_page', 5626 );
	$new_meta = get_post_meta( $page_id, 'wpex_post_title_background_redux', true );
	$image    = '';
	if ( is_array( $new_meta ) && ! empty( $new_meta['url'] ) ) {
		$image = isset( $new_meta['url'] ) ? $new_meta['url'] : $image;
	} else {
		$image = $new_meta ? $new_meta : $image;
	}

	return $image;
}

function easl_get_events_page_header_bg( $event_id ) {
	$term_id = easl_meeting_type_id( $event_id );
	if ( ! $term_id ) {
		return '';
	}
	$bg = '';
	if ( function_exists( 'get_field' ) ) {
		$bg = get_field( 'event_header_image', 'event_type_' . $term_id );
	}
	if ( ! $bg ) {
		return '';
	}
	$bg = wp_get_attachment_image_src( $bg, 'full' );
	if ( ! $bg ) {
		return '';
	}

	return $bg[0];
}

function easl_get_publication_page_header_bg( $pub_id ) {
	$term_id = easl_publication_topic_id( $pub_id );
	if ( ! $term_id ) {
		return '';
	}
	$bg = '';
	if ( function_exists( 'get_field' ) ) {
		$bg = get_field( 'event_header_image', 'event_type_' . $term_id );
	}
	if ( ! $bg ) {
		return '';
	}
	$bg = wp_get_attachment_image_src( $bg, 'full' );
	if ( ! $bg ) {
		return '';
	}

	return $bg[0];
}

function easl_page_header_style( $style ) {
	if ( is_single() ) {
		return easl_get_news_page_header_style();
	}
	if ( is_search() ) {
		return 'background-image';
	}
	if ( ! is_singular( 'event', Publication_Config::get_publication_slug() ) || 'background-image' == $style ) {
		return $style;
	}
	$term_id = easl_meeting_type_id( get_queried_object_id() );
	if ( ! $term_id ) {
		return $style;
	}
	$bg = '';
	if ( function_exists( 'get_field' ) ) {
		$bg = get_field( 'event_header_image', 'event_type_' . $term_id );
	}
	if ( ! $bg ) {
		return $style;
	}

	return 'background-image';

}

add_filter( 'wpex_page_header_style', 'easl_page_header_style', 20 );

function easl_page_header_title_height( $height ) {
	if ( is_single() ) {
		return easl_get_news_page_header_height();
	}
	if ( is_search() || is_singular( 'event', Publication_Config::get_publication_slug() ) ) {
		return 220;
	}

	return $height;

}

add_filter( 'wpex_post_title_height', 'easl_page_header_title_height', 20 );

function easl_page_header_overlay_style( $style ) {
	if ( is_single() ) {
		return easl_get_news_page_header_overlay_style();
	}
	if ( is_search() || is_singular( 'event', Publication_Config::get_publication_slug() ) ) {
		return '';
	}

	return $style;

}

add_filter( 'wpex_page_header_overlay_style', 'easl_page_header_overlay_style', 20 );

function easl_page_header_bg( $image, $post_id ) {
	$cusotm_bg = '';
	if ( is_search() ) {
		$cusotm_bg = get_stylesheet_directory_uri() . '/images/header-blue-pattern.jpg';
	}
	if ( is_single() ) {
		$cusotm_bg = easl_get_news_page_header_bg( $post_id );
	}
	if ( is_singular( 'event' ) ) {
		$cusotm_bg = easl_get_events_page_header_bg( $post_id );
	}
	if ( is_singular( Publication_Config::get_publication_slug() ) ) {
		$cusotm_bg = easl_get_publication_page_header_bg( $post_id );
	}
	if ( $cusotm_bg ) {
		return $cusotm_bg;
	}

	return $image;
}

add_filter( 'wpex_page_header_background_image', 'easl_page_header_bg', 20, 2 );

function easl_page_header_for_event( $args, $instance ) {
	if ( 'singular_event' == $instance ) {
		$args['string'] = single_post_title( '', false );
	}

	return $args;
}

add_filter( 'wpex_page_header_title_args', 'easl_page_header_for_event', 20, 2 );

function easl_util_sc_title_icon( $atts, $content = null ) {
	$title = $icon = $inline = '';
	$atts  = shortcode_atts( array(
		'icon'   => '',
		'title'  => '',
		'inline' => 'true',
	), $atts );
	extract( $atts );
	if ( ! $title ) {
		return '';
	}
	$icon_class = 'easl-sc-title-icon';
	if ( $icon ) {
		$icon_class .= ' easl-sc-title-icon-hasicon easl-sc-title-icon-' . $icon;
	}
	if ( 'true' != $inline ) {
		$icon_class .= ' easl-sc-title-icon-block';
	}

	return '<div class="' . $icon_class . '"><span>' . $title . '</span></div>';
}

add_shortcode( 'easl_title_icon', 'easl_util_sc_title_icon' );

function easl_vcex_change_shortcode_parameter() {
	vc_add_param( 'vcex_post_type_carousel', array(
		'type'       => 'vcex_ofswitch',
		'std'        => 'true',
		'heading'    => __( 'Enable Posts Link', 'total' ),
		'param_name' => 'easl_title_link',
		'group'      => __( 'Title', 'total' ),
	) );
}

add_action( 'vc_after_init', 'easl_vcex_change_shortcode_parameter', 50 );

function easl_vcex_post_type_carousel_title( $title_output, $atts ) {
	if ( empty( $atts['easl_title_link'] ) || ( 'true' == $atts['easl_title_link'] ) ) {
		return $title_output;
	}
	$heading_style = vcex_inline_style( array(
		'margin'         => $atts['content_heading_margin'],
		'text_transform' => $atts['content_heading_transform'],
		'font_size'      => $atts['content_heading_size'],
		'font_weight'    => $atts['content_heading_weight'],
		'line_height'    => $atts['content_heading_line_height'],
	) );

	$content_heading_color = vcex_inline_style( array(
		'color' => $atts['content_heading_color'],
	) );

	$title_output = '<div class="wpex-carousel-entry-title entry-title"' . $heading_style . '>';
	$title_output .= '<span ' . $content_heading_color . '>';
	$title_output .= esc_html( $atts['post_title'] );
	$title_output .= '</span>';

	$title_output .= '</div>';

	return $title_output;
}

add_filter( 'vcex_post_type_carousel_title', 'easl_vcex_post_type_carousel_title', 10, 2 );

function easl_wpb_toggle_heading( $heading, $data ) {
	return '<h4>' . $data['title'] . '</h4>';
}

add_filter( 'wpb_toggle_heading', 'easl_wpb_toggle_heading', 20, 2 );

/**
 * Display Social Share Icons
 */
function easl_social_share_icons() {
	include get_theme_file_path( 'partials/social-shares-icons.php' );
}

function easl_social_share_icons_row() {
	if (is_singular(Publication_Config::get_publication_slug())) {
		return;
	}
	include get_theme_file_path( 'partials/social-shares-icons.php' );
}
add_action( 'wpex_hook_content_bottom', 'easl_social_share_icons_row' );

function easl_before_headder() {
	echo '<div id="header-top-line"></div>';
}

add_action( 'wpex_hook_header_before', 'easl_before_headder' );
function easl_mobile_top_buttons() {
	get_template_part( 'partials/header/mobile-top-buttons', 100 );
}

add_action( 'wpex_hook_header_top', 'easl_mobile_top_buttons', 1 );

function easl_header_follow_icons() {
	get_template_part( 'partials/follow-icons' );
}

add_action( 'wpex_hook_header_top', 'easl_header_follow_icons', 2 );

function easl_page_prelaoder_template() {
	get_template_part( 'partials/header/preloader' );
}

add_action( 'wpex_hook_after_body_tag', 'easl_page_prelaoder_template' );


function easl_page_header_background_image() {

	// Get current post ID
	$post_id = wpex_get_current_post_id();

	// Get default Customizer value
	$image = wpex_get_mod( 'page_header_background_img' );

	// Fetch from featured image
	if ( $image
	     && $post_id
	     && $fetch_thumbnail_types = wpex_get_mod( 'page_header_background_fetch_thumbnail', null )
	) {
		if ( ! is_array( $fetch_thumbnail_types ) ) {
			$fetch_thumbnail_types = explode( ',', $fetch_thumbnail_types );
		}
		if ( in_array( get_post_type( $post_id ), $fetch_thumbnail_types ) ) {
			$thumbnail = get_post_thumbnail_id( $post_id );
			if ( $thumbnail ) {
				$image = $thumbnail;
			}
		}
	}

	// Apply filters before meta checks => meta should always override
	$image = apply_filters( 'wpex_page_header_background_img', $image ); // @todo remove this deprecated filter
	$image = apply_filters( 'wpex_page_header_background_image', $image, $post_id );

	// Check meta for bg image
	if ( $post_id ) {

		// Get page header background from meta
		if ( $post_id && 'background-image' == get_post_meta( $post_id, 'wpex_post_title_style', true ) ) {

			if ( $new_meta = get_post_meta( $post_id, 'wpex_post_title_background_redux', true ) ) {
				if ( is_array( $new_meta ) && ! empty( $new_meta['url'] ) ) {
					$image = isset( $new_meta['url'] ) ? $new_meta['url'] : $image;
				} else {
					$image = $new_meta ? $new_meta : $image;
				}
			} else {
				$meta  = get_post_meta( $post_id, 'wpex_post_title_background', true ); // Fallback
				$image = $meta ? $meta : $image;
			}

		}

	}
	if ( ! $image ) {
		return '';
	}
	// Generate image URL if using ID
	$image_url = '';
	if ( $image && is_numeric( $image ) ) {
		$image_url = wp_get_attachment_image_url( $image, 'full' );
	}
	if ( ! $image_url ) {
		return '';
	}

	$img_alt = trim( strip_tags( get_post_meta( $image, '_wp_attachment_image_alt', true ) ) );

	// Return image
	return '<img class="easl-page-header-bg-img" alt="' . $img_alt . '" src="' . $image_url . '"/>';
}

function easl_singular_default_header_background_image() {
	if ( ! is_singular( array( 'event', Publication_Config::get_publication_slug() ) ) ) {
		return '';
	}
	$post_id = get_queried_object_id();
	$color   = '';
	if ( is_singular( 'event' ) ) {
		$color = easl_get_events_topic_color( $post_id );
	}
	if ( is_singular( Publication_Config::get_publication_slug() ) ) {
		$color = easl_get_publication_topic_color( $post_id );
	}
	$image_url = get_stylesheet_directory_uri() . '/images/';
	if ( ! $color || ( $color == 'gray' ) ) {
		$image_url .= 'events-header.jpg';
	} else {
		$image_url .= 'events-header-' . $color . '.jpg';
	}
	$img_alt = get_the_title( $post_id );

	return '<img class="easl-page-header-bg-img" alt="' . $img_alt . '" src="' . $image_url . '"/>';
}