<?php
namespace TotalThemeCore\WPBakery\Views\Backend_Editor;

defined( 'ABSPATH' ) || exit;

/**
 * Custom view for displaying images in the WPBakery backend editor.
 *
 * @package TotalThemeCore
 * @version 1.2.8
 */
final class Image_Before_After {

	/**
	 * Our single Image_Before_After instance.
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
	 * Create or retrieve the instance of Image_Before_After.
	 */
	public static function instance() {
		if ( is_null( static::$instance ) ) {
			static::$instance = new Image_Before_After;
			static::$instance->init_hooks();
		}

		return static::$instance;
	}

	public function init_hooks() {
		add_action( 'wp_ajax_vcex_wpbakery_backend_view_image_before_after', array( $this, 'generate' ) );
	}

	public function generate() {
		if ( ! function_exists( 'vc_user_access' ) ) {
			return;
		}

		vc_user_access()->checkAdminNonce()->validateDie()->wpAny( 'edit_posts', 'edit_pages' )->validateDie();

		$html    = '';
		$post_id = (int) vc_post_param( 'post_id' );
		$source  = vc_post_param( 'source' );

		switch ( $source ) {
			case 'featured':
				$before_image = get_post_thumbnail_id( $post_id );
				$after_image = wpex_get_secondary_thumbnail( $post_id );
				break;
			case 'custom_field':
				$before_image = get_post_meta( $post_id, vc_post_param( 'beforeImageCf' ), true );
				$after_image = get_post_meta( $post_id, vc_post_param( 'afterImageCf' ), true );
				break;
			case 'media_library';
			default:
				$before_image = vc_post_param( 'beforeImage' );
				$after_image = vc_post_param( 'afterImage' );
				break;
		}

		if ( $before_image || $after_image ) {

			$html .= '<div class="vcex-backend-view-ba">';

				if ( $before_image ) {
					$html .= '<div>' . wp_get_attachment_image( $before_image, 'thumbnail', false, array() ) . '</div>';
				}

				if ( $after_image ) {
					$html .= '<div>' . wp_get_attachment_image( $after_image, 'thumbnail', false, array() ) . '</div>';
				}

			$html .= '</div>';

		}

		echo $html;

		die();

	}

}