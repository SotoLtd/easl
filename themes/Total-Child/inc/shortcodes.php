<?php

require_once get_stylesheet_directory() . '/inc/shortcodes/class-vc-button.php';
require_once get_stylesheet_directory() . '/inc/shortcodes/class-vc-generic-button.php';
require_once get_stylesheet_directory() . '/inc/shortcodes/class-vc-generic-button-container.php';
require_once get_stylesheet_directory() . '/inc/shortcodes/class-vc-button-grid.php';
require_once get_stylesheet_directory() . '/inc/shortcodes/class-vc-gbutton.php';
require_once get_stylesheet_directory() . '/inc/shortcodes/class-vc-events.php';
require_once get_stylesheet_directory() . '/inc/shortcodes/class-vc-events-calendar.php';
require_once get_stylesheet_directory() . '/inc/shortcodes/class-vc-news.php';
require_once get_stylesheet_directory() . '/inc/shortcodes/class-vc-scientific-publication.php';
require_once get_stylesheet_directory() . '/inc/shortcodes/class-vc-carousel.php';
require_once get_stylesheet_directory() . '/inc/shortcodes/class-vc-carousel-item.php';
require_once get_stylesheet_directory() . '/inc/shortcodes/class-vc-yifellowship.php';
//require_once get_stylesheet_directory() . '/inc/shortcodes/class-vc-mentors.php';
//require_once get_stylesheet_directory() . '/inc/shortcodes/class-vc-mentors-table.php';
require_once get_stylesheet_directory() . '/inc/shortcodes/class-vc-cag-members.php';
require_once get_stylesheet_directory() . '/inc/shortcodes/class-vc-menu-stacked-content.php';
require_once get_stylesheet_directory() . '/inc/shortcodes/class-vc-post-type-grid.php';
require_once get_stylesheet_directory() . '/inc/shortcodes/class-vc-users-grid.php';
require_once get_stylesheet_directory() . '/inc/shortcodes/class-vc-card-button.php';
//require_once get_stylesheet_directory() . '/inc/shortcodes/class-vc-associations.php';
require_once get_stylesheet_directory() . '/inc/shortcodes/class-vc-highlights.php';
require_once get_stylesheet_directory() . '/inc/shortcodes/class-vc-staffs-accordion.php';
require_once get_stylesheet_directory() . '/inc/shortcodes/class-vc-slide-decks.php';
//require_once get_stylesheet_directory() . '/inc/shortcodes/class-vc-awardees.php';
//require_once get_stylesheet_directory() . '/inc/shortcodes/class-vc-storyline-3d-slider.php';
//require_once get_stylesheet_directory() . '/inc/shortcodes/class-vc-storyline-3d-slider-item.php';
require_once get_stylesheet_directory() . '/inc/shortcodes/class-vc-3d-carousel.php';
require_once get_stylesheet_directory() . '/inc/shortcodes/class-vc-3d-carousel-item.php';
require_once get_stylesheet_directory() . '/inc/shortcodes/class-vc-news-list.php';
require_once get_stylesheet_directory() . '/inc/shortcodes/class-vc-ilc-details.php';
require_once get_stylesheet_directory() . '/inc/shortcodes/class-vc-bgtext-box.php';
require_once get_stylesheet_directory() . '/inc/shortcodes/class-vc-yearly-awardees.php';
require_once get_stylesheet_directory() . '/inc/shortcodes/class-vc-secretary-generals-carousel.php';
require_once get_stylesheet_directory() . '/inc/shortcodes/class-vc-membership-cats.php';
require_once get_stylesheet_directory() . '/inc/shortcodes/class-vc-sitemap.php';


require_once get_stylesheet_directory() . '/inc/shortcodes/utility/easl-toggle.php';

function easl_vc_shortcodes_lean_maps(){
	vc_lean_map( 'easl_button', null, get_theme_file_path('/inc/shortcodes/config/easl-button.php') );
	vc_lean_map( 'easl_generic_button', null, get_theme_file_path('/inc/shortcodes/config/easl-generic-button.php') );
	vc_lean_map( 'easl_generic_button_container', null, get_theme_file_path('/inc/shortcodes/config/easl-generic-button-container.php') );
	vc_lean_map( 'easl_button_grid', null, get_theme_file_path('/inc/shortcodes/config/easl-button-grid.php'));
	vc_lean_map( 'easl_gbutton', null, get_theme_file_path('/inc/shortcodes/config/easl-gbutton.php') );
	vc_lean_map( 'easl_events', null, get_theme_file_path() . '/inc/shortcodes/config/easl-events.php' );
	vc_lean_map( 'easl_events_calendar', null, get_theme_file_path('/inc/shortcodes/config/easl-events-calendar.php') );
	vc_lean_map( 'easl_news', null, get_theme_file_path('/inc/shortcodes/config/easl-news.php') );
	vc_lean_map( 'easl_carousel', null, get_theme_file_path('/inc/shortcodes/config/easl-carousel.php') );
	vc_lean_map( 'easl_carousel_item', null, get_theme_file_path('/inc/shortcodes/config/easl-carousel-item.php' ));
	vc_lean_map( 'easl_yi_fellowship', null, get_theme_file_path('/inc/shortcodes/config/easl-yifellowship.php') );
	//vc_lean_map( 'easl_mentors', null, get_theme_file_path('/inc/shortcodes/config/easl-mentors.php') );
	//vc_lean_map( 'easl_mentors_table', null, get_theme_file_path('/inc/shortcodes/config/easl-mentors-table.php' ));
	vc_lean_map( 'easl_cag_members', null, get_theme_file_path('/inc/shortcodes/config/easl-cag-members.php') );
	vc_lean_map( 'easl_menu_stacked_content', null, get_theme_file_path('/inc/shortcodes/config/easl-menu-stacked-content.php' ));
	vc_lean_map( 'easl_card_button', null, get_theme_file_path('/inc/shortcodes/config/easl-card-button.php' ));
	vc_lean_map( 'easl_staffs_accordion', null, get_theme_file_path('/inc/shortcodes/config/easl-staffs-accordion.php') );
	vc_lean_map( 'easl_scientific_publication', null, get_theme_file_path('/inc/shortcodes/config/easl-scientific-publication.php') );
	//vc_lean_map( 'easl_awardees', null, get_theme_file_path('/inc/shortcodes/config/easl-awardees.php') );
	//vc_lean_map( 'easl_s3d_slider', null, get_theme_file_path('/inc/shortcodes/config/easl-storyline-3d-slider.php') );
	//vc_lean_map( 'easl_s3d_slider_item', null, get_theme_file_path('/inc/shortcodes/config/easl-storyline-3d-slider-item.php') );
	vc_lean_map( 'easl_3d_carousel', null, get_theme_file_path('/inc/shortcodes/config/easl-3d-carousel.php') );
	vc_lean_map( 'easl_3d_carousel_item', null, get_theme_file_path('/inc/shortcodes/config/easl-3d-carousel-item.php') );
	vc_lean_map( 'easl_news_list', null, get_theme_file_path('/inc/shortcodes/config/easl-news-list.php') );
	vc_lean_map( 'easl_slide_decks', null, get_theme_file_path('/inc/shortcodes/config/easl-slide-decks.php') );
	vc_lean_map( 'easl_ilc_details', null, get_theme_file_path('/inc/shortcodes/config/easl-ilc-details.php') );
	//vc_lean_map( 'easl_national_associations', null, get_theme_file_path('/inc/shortcodes/config/easl-national-associations.php') );
	vc_lean_map( 'easl_bgtext_box', null, get_theme_file_path('/inc/shortcodes/config/easl-bgtext-box.php') );
	vc_lean_map( 'easl_yearly_awardees', null, get_theme_file_path('/inc/shortcodes/config/easl-yearly-awardees.php') );
	vc_lean_map( 'easl_secretary_general_carousel', null, get_theme_file_path('/inc/shortcodes/config/easl-secretary-general-carousel.php') );
	vc_lean_map( 'easl_membership_cats', null, get_theme_file_path('/inc/shortcodes/config/easl-membership-cats.php') );
	vc_lean_map( 'easl_highlights', null, get_theme_file_path('/inc/shortcodes/config/easl-highlights.php') );
	vc_lean_map( 'easl_sitemap', null, get_theme_file_path('/inc/shortcodes/config/easl-sitemap.php') );
}
add_action( 'vc_after_init', 'easl_vc_shortcodes_lean_maps', 40 );

function easl_map_vc_shortcodes() {
	require_once get_theme_file_path('inc/shortcodes/youtube-video/youtube-video.php');
	require_once get_theme_file_path('inc/shortcodes/easl-staffs/easl-staffs.php');
	require_once get_theme_file_path('inc/shortcodes/easl-icon-widget-grid/easl-icon-widget-grid.php');
	require_once get_theme_file_path('inc/shortcodes/easl-icon-widget/easl-icon-widget.php');
	require_once get_theme_file_path('inc/shortcodes/easl-annual-reports/easl-annual-reports.php');
	require_once get_theme_file_path('inc/shortcodes/easl-history-slide/easl-history-slide.php');
	require_once get_theme_file_path('inc/shortcodes/easl-national-associations/easl-national-associations.php');
	require_once get_theme_file_path('inc/shortcodes/easl-misc-list/easl-misc-list.php');
	require_once get_theme_file_path('inc/shortcodes/easl-misc-list-item/easl-misc-list-item.php');
	require_once get_theme_file_path('inc/shortcodes/easl-homepage-slider/easl-homepage-slider.php');
	require_once get_theme_file_path('inc/shortcodes/easl-heading/easl-heading.php');
	require_once get_theme_file_path('inc/shortcodes/easl-separator/easl-separator.php');
	require_once get_theme_file_path('inc/shortcodes/easl-heading-image-box/easl-heading-image-box.php');
	require_once get_theme_file_path('inc/shortcodes/easl-callout/easl-callout.php');
	require_once get_theme_file_path('inc/shortcodes/easl-logo-grid/easl-logo-grid.php');
	require_once get_theme_file_path('inc/shortcodes/easl-banner-image/easl-banner-image.php');
	require_once get_theme_file_path('inc/shortcodes/easl-events-key-dates/easl-events-key-dates.php');
}
add_action('vc_after_mapping', 'easl_map_vc_shortcodes');
