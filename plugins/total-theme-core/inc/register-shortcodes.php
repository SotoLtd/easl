<?php
namespace TotalThemeCore;

defined( 'ABSPATH' ) || exit;

final class Register_Shortcodes {

	/**
	 * Our single Register_Shortcodes instance.
	 */
	private static $instance;

	/**
	 * Disable instantiation.
	 */
	private function __construct() {}

	/**
	 * Disable the cloning of this class.
	 *
	 * @return void
	 */
	final public function __clone() {}

	/**
	 * Disable the wakeup of this class.
	 */
	final public function __wakeup() {}

	/**
	 * Create or retrieve the instance of Register_Shortcodes.
	 */
	public static function instance() {
		if ( is_null( static::$instance ) ) {
			static::$instance = new Register_Shortcodes;
			static::$instance->register();
			static::$instance->add_filters();
		}

		return static::$instance;
	}

	public function register() {

		new Shortcodes\Shortcode_Span;
		new Shortcodes\Shortcode_Site_URL;
		new Shortcodes\Shortcode_Menu_Site_URL;
		new Shortcodes\Shortcode_Highlight;
		new Shortcodes\Shortcode_Line_Break;
		new Shortcodes\Shortcode_WP_Login_Link;
		new Shortcodes\Shortcode_Username;
		new Shortcodes\Shortcode_Select_menu;
		new Shortcodes\Shortcode_Ticon;
		new Shortcodes\Shortcode_Date;
		new Shortcodes\Shortcode_Staff_Social;
		new Shortcodes\Shortcode_Searchform;
		new Shortcodes\Shortcode_Current_Year;
		new Shortcodes\Shortcode_Cf_Value;

		new Shortcodes\Shortcode_Post_Title;
		new Shortcodes\Shortcode_Post_Permalink;
		new Shortcodes\Shortcode_Post_Permalink;
		new Shortcodes\Shortcode_Post_Date;
		new Shortcodes\Shortcode_Post_Date_Modified;
		new Shortcodes\Shortcode_Post_Author;

		new Shortcodes\Shortcode_Polylang_Switcher;
		new Shortcodes\Shortcode_Wpml_Translate;
		new Shortcodes\Shortcode_Wpml_Language_Selector;

		new Shortcodes\Shortcode_Enqueue_Imagesloaded;
		new Shortcodes\Shortcode_Enqueue_Lightbox;

	}

	public function add_filters() {

		// Cleanup shortcodes.
		add_filter( 'the_content', array( $this, 'cleanup' ) );

		// Add shortcode support to various filters.
		add_filter( 'wp_nav_menu_items', 'do_shortcode' );
		add_filter( 'the_excerpt', 'shortcode_unautop' );
		add_filter( 'the_excerpt', 'do_shortcode' );
		add_filter( 'widget_text', 'do_shortcode' );

	}

	/**
	 * Cleanup shortcodes.
	 *
	 * Filters the_content to make sure shortcodes aren't being wrapped in p tags or have br tags after them.
	 *
	 * @since 1.2.8
	 * @access public
	 */
	public function cleanup( $content ) {

		$content = strtr( $content, array(
			'<p>['    => '[',
			']</p>'   => ']',
			']<br />' => ']',
		) ) ;

		return $content;

	}

}