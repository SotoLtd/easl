<?php

define('EASL_THEME_VERSION', '2021.10.22.02');
//define( 'EASL_THEME_VERSION', time() );

if ( ! defined( 'EASL_INC_DIR' ) ) {
	define( 'EASL_INC_DIR', trailingslashit( get_stylesheet_directory() ) . 'inc/' );
}

if ( ! defined( 'EASL_HOME_URL' ) ) {
	define( 'EASL_HOME_URL', get_home_url() );
}

require_once EASL_INC_DIR . 'helpers.php';
require_once EASL_INC_DIR . 'custom-tax-news-source.php';
require_once EASL_INC_DIR . 'post-types/post-types.php';
require_once EASL_INC_DIR . 'rewrites.php';
require_once EASL_INC_DIR . 'customizer.php';
require_once EASL_INC_DIR . 'page-builder-extend.php';
require_once EASL_INC_DIR . 'total-extend.php';
require_once EASL_INC_DIR . 'shortcodes.php';
require_once EASL_INC_DIR . 'shortcodes-v2/shortcodes.php';
require_once EASL_INC_DIR . 'widgets.php';
require_once EASL_INC_DIR . 'settings.php';
require_once EASL_INC_DIR . 'sticky-footer.php';
require_once EASL_INC_DIR . 'wp-seo-extend.php';
require_once EASL_INC_DIR . 'easl-clock.php';
require_once EASL_INC_DIR . 'freshchat/freshchat.php';
require_once EASL_INC_DIR . 'survey-monkey.php';
require_once EASL_INC_DIR . 'table-builder/table-builder.php';

function easl_theme_setup() {
	load_theme_textdomain( 'total-child' );
	add_image_size( 'staff_grid', 254, 254, true );
	add_image_size( 'news_list', 350, 170, true );
	add_image_size( 'news_single', 1125, 9999, false );
}

add_action( 'after_setup_theme', 'easl_theme_setup' );

function easl_register_nav_menus() {
	register_nav_menu( 'member-zone-pages-menu', __( 'Member Zone Pages Menu' ) );
}

add_action( 'init', 'easl_register_nav_menus' );

function total_child_enqueue_parent_theme_style() {

	// Dynamically get version number of the parent stylesheet (lets browsers re-cache your stylesheet when you update your theme)
	$theme   = wp_get_theme( 'Total' );
	$version = $theme->get( 'Version' );

	// Load the stylesheet
	wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css', array(), $version );
	if ( defined( 'WPEX_THEME_STYLE_HANDLE' ) ) {
		wp_dequeue_style( WPEX_THEME_STYLE_HANDLE );
		wp_enqueue_style(
			WPEX_THEME_STYLE_HANDLE,
			get_stylesheet_uri(),
			array(),
			EASL_THEME_VERSION
		);
	}
    wp_register_style( 'vc_tta_style', get_stylesheet_directory_uri() . '/assets/css/js_composer_tta.css', false, EASL_THEME_VERSION );
}

add_action( 'wp_enqueue_scripts', 'total_child_enqueue_parent_theme_style' );

function easl_custom_scripts() {
	wp_enqueue_script( 'jquery' );
	if ( is_singular( 'event' ) && easl_is_future_event( get_queried_object_id() ) ) {
		wp_enqueue_script( 'atc', 'https://addevent.com/libs/atc/1.6.1/atc.min.js', array(), '1.2.1', false );
	}
	wp_enqueue_script( 'easl-custom', get_stylesheet_directory_uri() . '/assets/js/custom.js', array( 'jquery' ), EASL_THEME_VERSION, true );
	$ssl_scheme     = is_ssl() ? 'https' : 'http';
	$fornt_end_data = array(
		'ajaxUrl'     => admin_url( 'admin-ajax.php', $ssl_scheme ),
		'loaderImage' => '<img class="easl-loading-icon" src="' . get_stylesheet_directory_uri() . '/images/easl-loader.gif"/>',
        'article_id' => wpex_get_the_id()
	);
	wp_localize_script( 'easl-custom', 'EASLSETTINGS', $fornt_end_data );
}

add_action( 'wp_enqueue_scripts', 'easl_custom_scripts', 20 );

function easl_admin_custom_scripts() {
	wp_enqueue_style( 'easl-admin-common', get_stylesheet_directory_uri() . '/assets/css/admin/common.css', array(), EASL_THEME_VERSION );
	//wp_enqueue_script( 'easl-admin-common', get_stylesheet_directory_uri() . '/assets/js/admin/common.js', array( 'jquery' ), EASL_THEME_VERSION, true );
}

add_action( 'admin_enqueue_scripts', 'easl_admin_custom_scripts' );

function easl_header_scripts() {
	if ( is_singular( 'event' ) && easl_is_future_event( get_queried_object_id() ) ) {
		?>
        <script type="text/javascript">
            window.addeventasync = function () {
                addeventatc.settings({
                    css: false
                });
            };
        </script>
		<?php
	}
}

add_action( 'wp_head', 'easl_header_scripts', 99 );
function easl_footer_scripts() {
	echo '<script type="text/javascript" src="' . get_stylesheet_directory_uri() . '/assets/js/custom.js' . '"></script>';
}

//add_action('wp_footer', 'easl_footer_scripts', 99);


/**
 * Make the main menu displayable for the mobile menu
 * Stripping all column alias and classes.
 */
add_filter( 'wp_nav_menu_objects', 'easl_nav_menu_objs', 11, 2 );
function easl_nav_menu_objs( $sorted_menu_items, $args ) {
	if ( empty( $args->theme_location ) ) {
		return $sorted_menu_items;
	}

	$current_col = $cols_parent = $hide_parent = false;
	foreach ( $sorted_menu_items as $k => $item ) {
		if ( ! empty( $hide_parent ) && in_array( $item->menu_item_parent, $hide_parent ) ) {
			$hide_parent[] = $item->ID;
			unset( $sorted_menu_items[ $k ] );
		}
		if ( is_array( $item->classes ) && in_array( 'ilc-hide-menu-item', $item->classes ) ) {
			$hide_parent[] = $item->ID;
			unset( $sorted_menu_items[ $k ] );
		}
		if ( 'mobile_menu_alt' == $args->theme_location ) {
			if ( ! empty( $current_col ) && ( $item->menu_item_parent == $current_col ) ) {
				$item->menu_item_parent = $cols_parent;
			}
			if ( is_array( $item->classes ) && in_array( 'megamenu', $item->classes ) ) {
				$sorted_menu_items[ $k ]->classes = array_diff( $item->classes, array(
					'megamenu',
					'col-1',
					'col-2',
					'col-3',
					'col-4'
				) );
			}
			if ( ! is_array( $item->classes ) || ! in_array( 'ilc-hide-link', $item->classes ) ) {
				continue;
			}
			$cols_parent = $item->menu_item_parent;
			$current_col = $item->ID;
			unset( $sorted_menu_items[ $k ] );
		}

	}
	if ( $current_col ) {
		$sorted_menu_items = array_values( $sorted_menu_items );
	}

	return $sorted_menu_items;
}

// Hide link text
//add_filter('walker_nav_menu_start_el', 'easl_walker_nav_menu_start_el', 11, 4);
function easl_walker_nav_menu_start_el( $item_output, $item, $depth, $args ) {
	if ( is_array( $item->classes ) && in_array( 'ilc-hide-link', $item->classes ) ) {
		return '';
	}

	return $item_output;
}

add_shortcode( 'easl_year', 'sc_easl_year' );
function sc_easl_year() {
	$year = date( 'Y' );

	return $year;
}

//remove_action( 'wpex_outer_wrap_before', 'wpex_skip_to_content_link' );

function easl_page_header_has_particle_animation() {
    return get_field('enable_header_animation', wpex_get_current_post_id());
}
function easl_page_heder_class( $classes ) {
	$post_id = wpex_get_current_post_id();
	//if(!wpex_page_header_subheading_content()){
	//	return $classes;
	//}
    if ( easl_page_header_has_particle_animation() ) {
		$classes[] = 'easl-particles-header';
	}
	$style    = wpex_page_header_style();
	$bg_img   = wpex_page_header_background_image();
	$bg_color = get_post_meta( $post_id, 'wpex_post_title_background_color', true );
	if ( 'background-image' == $style && $bg_img ) {
		$classes['easl-page-header-has-bg'] = 'easl-page-header-has-bg';
	} else {
		$classes['easl-page-header-has-bg'] = 'easl-page-header-no-bg';
	}

	return $classes;
}

function add_easl_particles_header_scripts() {
    if ( easl_page_header_has_particle_animation() ) {
        wp_enqueue_script( 'particles-scripts', get_stylesheet_directory_uri() . '/assets/js/particles.min.js', array( 'jquery' ), true );
        wp_enqueue_script( 'easl-particles-scripts', get_stylesheet_directory_uri() . '/assets/js/easl-particles-header.js', array(
            'jquery',
            'particles-scripts'
        ), true );
    };
}
add_action( 'wp_footer', 'add_easl_particles_header_scripts' );

function easl_header_inline_scripts() {
    $post_id = wpex_get_current_post_id();
    $scripts = trim( get_field( 'easl_header_scripts', $post_id ) );
    if ( $scripts ) {
        echo "\n" . $scripts . "\n";
    }
}

add_action( 'wp_head', 'easl_header_inline_scripts', 50 );
function easl_footer_inline_scripts() {
    $post_id = wpex_get_current_post_id();
    $scripts = trim( get_field( 'easl_footer_scripts', $post_id ) );
    if ( $scripts ) {
        echo "\n" . $scripts . "\n";
    }
}

add_action( 'wp_footer', 'easl_footer_inline_scripts', 50 );



add_filter( 'wpex_page_header_classes', 'easl_page_heder_class' );

// Add custom font to font settings
function wpex_add_custom_fonts() {
	return array(
		'Helvetica Neue',
	);
}

function register_custom_sidebar() {
	register_sidebar( array(
		'name'          => ( 'Social Buttons' ),
		'id'            => 'social_buttons',
		'description'   => ( 'Widgets in this area will be shown on all posts and pages.' ),
		'before_widget' => '<div id="%1$s" class="sidebar-box widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );

	register_sidebar( array(
		'name'          => ( 'Archive Reports' ),
		'id'            => 'archive-reports',
		'description'   => ( 'Widgets in this area will be shown on all posts and pages.' ),
		'before_widget' => '<div id="%1$s" class="sidebar-box widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );

	register_sidebar( array(
		'name'          => ( 'Fellowship Detail Sidebar' ),
		'id'            => 'fellowship-detail-sidebar',
		'description'   => ( 'Widgets in this area will be shown on fellowship post type detail page.' ),
		'before_widget' => '<div id="%1$s" class="sidebar-box widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );

	register_sidebar( array(
		'name'          => ( 'Blog Sidebar' ),
		'id'            => 'blog-sidebar',
		'description'   => ( 'Widgets in this area will be shown on blog pages.' ),
		'before_widget' => '<div id="%1$s" class="sidebar-box widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );
}

add_action( 'widgets_init', 'register_custom_sidebar' );

add_shortcode( 'timeline_slide', 'timefunc' );
// [timeline_slide category_name="history"]
function timefunc( $atts ) {

	$the_query     = new WP_Query( array(
		'post_type'      => EASL_History_Config::get_slug(),
		'posts_per_page' => - 1,
		'meta_key'       => 'history_year',
		'orderby'        => 'meta_value',
		'order'          => 'DESC',

	) );
	$dates         = '';
	$issues        = '';
	$image         = [];
	$year_list     = [];
	$year_selected = 0;

	if ( $the_query->have_posts() ) {
		while ( $the_query->have_posts() ) {
			$the_query->the_post();

			$year = get_post_meta( get_the_ID(), 'history_year', true );

			if ( has_post_thumbnail( get_the_ID() ) ) {
				$image = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ), 'single-post-thumbnail' );
			}

			if ( $year ) {
				$year_list[] = $year;
				$selected    = $year_selected < 1 ? 'class="selected"' : '';
				$dates       .= '<li class="slider-frame-points" data-year="' . $year . '"><a href="#' . $year . '" ' . $selected . ' >' . $year . '</a></li>';

				$issues .= '<li id="' . $year . '"><div style="float: left; width: 50%;"><img src="' . $image[0] . '" width="256" height="256" /></div>' .
				           '<div style="float: left;width: 50%">' .
				           '<h1>' . $year . '</h1>' .
				           '<h2>' . get_the_title() . '</h2>' . get_the_content() .
				           '</div></li>';

				$year_selected ++;
			}
		}
	}
	ob_start();
	?>
    <div id="timeline">
        <div class="history-slider-logo">
            <style>
                .history-slider-logo {
                    width: 100%;
                    height: 100px;
                    border-bottom: 2px solid #80c4e5;
                    background-image: url("<?php echo get_stylesheet_directory_uri();?>/images/title-icons/history-slider-logo.png");
                    background-position: top left;
                    background-repeat: no-repeat;
                    background-size: auto;
                }
            </style>
        </div>
        <ul id="issues">
			<?php echo $issues; ?>
        </ul>
        <ul id="dates">
			<?php echo $dates; ?>
        </ul>
        <a href="#" id="next"><i class="ticon ticon-angle-right" aria-hidden="true"></i></a>
        <a href="#" id="prev"><i class="ticon ticon-angle-left" aria-hidden="true"></i></a>
        <div class="slider-block">
            <div class="timeline-value"><?php echo max( $year_list ); ?></div>
            <div class="slider-wrapper-block">
                <div id='slider'>
                    <div id="custom-handle" class="ui-slider-handle"></div>
                </div>
            </div>
            <div class="timeline-value"><?php echo min( $year_list ); ?></div>
        </div>
    </div>
	<?php
	$html = ob_get_contents();
	ob_end_clean();

	return $html;
}

add_filter( 'get_archives_link',
	function ( $link_html, $url, $text, $format, $before, $after ) {
		if ( 'archived_mentors' == $format ) {
			$link_html = $before . "<a href='$url'>Archived Mentors - Mentees</a>" . $after;
		}

		return $link_html;
	}, 10, 6 );

add_action( 'wp_ajax_get_staff_profile', 'get_staff_profile' );
add_action( 'wp_ajax_nopriv_get_staff_profile', 'get_staff_profile' );
function get_staff_profile() {
	$staff_id = ! empty( $_POST['staff_id'] ) ? absint( $_POST['staff_id'] ) : false;
	if ( ! $staff_id ) {
		echo '';
		die();
	}
	global $post;
	$post = get_post( $staff_id );
	if ( ! $post ) {
		echo '';
		die();
	}
	setup_postdata( $post );
	ob_start();
	get_template_part( 'partials/staff/details' );
	wp_reset_postdata();
	$html = ob_get_clean();
	echo $html;
	die();
}

add_filter( 'nav_menu_link_attributes', 'wpse_100726_extra_atts', 10, 3 );
function wpse_100726_extra_atts( $atts, $item, $args ) {
	$atts['data-item'] = $item->title;

	return $atts;
}

function easl_vc_tab_list_newline( $html ) {
	return str_replace( '--NL--', '<br/>', $html );
}

add_filter( 'vc-tta-get-params-tabs-list', 'easl_vc_tab_list_newline', 10 );

function easl_posts_pagination_display( $display, $post_type ) {
	$post_types_to_hide = array(
		'publication',
		'annual_reports',
		'slide_decks',
		'associations',
		Fellowship_Config::get_fellowship_slug(),
	);
	if ( in_array( $post_type, $post_types_to_hide ) ) {
		return false;
	}

	return $display;
}

add_filter( 'wpex_has_next_prev', 'easl_posts_pagination_display', 10, 2 );

function easl_staffs_types_args( $args ) {
	$args['public']  = false;
	$args['show_ui'] = true;

	return $args;
}

add_filter( 'wpex_staff_args', 'easl_staffs_types_args' );

function easl_body_classes( $classes ) {
	$post_id = get_queried_object_id();
	if ( is_singular( 'event' ) ) {
		$classes[] = 'event-color-' . easl_get_events_topic_color( $post_id );
		$event_subpage_id = easl_get_the_event_subpage_id();
		if($event_subpage_id) {
			$classes[] = 'event-subpage-' . $event_subpage_id;
        }
	}
	if ( is_singular( 'blog' ) ) {
		$classes[] = 'single-post';
	}
	if ( is_tax( 'blog_category' ) ) {
		$classes[] = 'tag';
	}
	if ( is_singular( Publication_Config::get_publication_slug() ) ) {
		$classes[] = 'publication-color-' . easl_get_publication_topic_color( $post_id );
	}
	$page_title_color = get_post_meta( $post_id, 'easl_page_title_color', true );
    if ( (!$page_title_color && is_singular('blog')) || (is_tax('blog_category') || is_tax('blog_tag')) ) {
        $page_title_color = get_post_meta( wpex_get_mod( 'easl_blog_page', 22015 ), 'easl_page_title_color', true );
    }
	if ( $page_title_color ) {
		$classes[] = 'easl-page-title-color-' . $page_title_color;
	}
	$extra_body_class = get_post_meta( $post_id, 'easl_extra_body_class', true );
	if ( $extra_body_class ) {
		$classes[] = $extra_body_class;
	}

	return $classes;
}

add_filter( 'body_class', 'easl_body_classes' );

function easl_wtp_button() {
	get_template_part( 'partials/wtp-close-button' );
}

add_action( 'ttb_wtp_before_buttons_inside_form', 'easl_wtp_button' );

function easl_exclude_posts_from_search( $query ) {
	if ( is_admin() || ( defined( 'DOING_AJAX' ) && DOING_AJAX ) || ! $query->is_search() ) {
		return;
	}
	$excludes = array( 8, 12, 6922 );
	$query->set( 'post__not_in', $excludes );
}

add_action( 'pre_get_posts', 'easl_exclude_posts_from_search' );

add_filter( 'wpb_widget_title', 'easl_override_widget_title', 10, 2 );
function easl_override_widget_title( $output = '', $params = array( '' ) ) {
	$extraclass = ( isset( $params['extraclass'] ) ) ? " " . $params['extraclass'] : "";

	return '<h1 class="entry-title' . $extraclass . '">' . $params['title'] . '</h1>';
}

/**
 * @param $query  WP_Query
 */
function exclude_pages_from_search( $query ) {
	if ( is_admin() || ! $query->is_main_query() || ! $query->is_search() ) {
		return;
	}
	$post_not_in = $query->get( 'post__not_in' );
	if ( $post_not_in && is_string( $post_not_in ) ) {
		$post_not_in = implode( ',', $post_not_in );
	}
	if ( ! $post_not_in ) {
		$post_not_in = array();
	}
	$post_not_in[] = 10219;
	$post_not_in[] = 10232;
	$query->set( 'post__not_in', $post_not_in );
}

if ( isset( $_GET['s'] ) ) {
	add_action( 'pre_get_posts', 'exclude_pages_from_search' );
}

add_action('wp_ajax_easl_love_the_article', 'easl_love_the_article');
add_action('wp_ajax_nopriv_easl_love_the_article', 'easl_love_the_article');

function easl_love_the_article() {
    if(empty($_POST['article_id'])) {
        wp_send_json(array(
                'Status' => 'FAILED'
        ));
    }
    $post_id = $_POST['article_id'];
    $love_count = get_post_meta($post_id, 'easl_love_count', true);
    $love_count = absint($love_count);
    $love_count++;
    update_post_meta($post_id, 'easl_love_count', $love_count);
    wp_send_json(array(
        'Status' => 'SUCCESS',
        'Count' => $love_count
    ));
}
add_action('wp_ajax_easl_article_hit_count', 'easl_article_hit_count');
add_action('wp_ajax_nopriv_easl_article_hit_count', 'easl_article_hit_count');

function easl_article_hit_count() {
    if(empty($_POST['article_id'])) {
        wp_send_json(array(
                'Status' => 'FAILED'
        ));
    }
    $post_id = $_POST['article_id'];
    $hit_count = get_post_meta($post_id, 'easl_hit_count', true);
    $hit_count = absint($hit_count);
    $hit_count++;
    update_post_meta($post_id, 'easl_hit_count', $hit_count);
    wp_send_json(array(
        'Status' => 'SUCCESS',
        'Count' => $hit_count
    ));
}