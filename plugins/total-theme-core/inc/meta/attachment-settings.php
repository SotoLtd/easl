<?php
/**
 * Adds new fields for the media items.
 *
 * @package Total WordPress Theme
 * @subpackage Framework
 * @version 5.1
 */

namespace TotalThemeCore\Meta;

defined( 'ABSPATH' ) || exit;

final class Attachment_Settings {

	/**
	 * Our single Attachment_Settings instance.
	 */
	private static $instance;

	/**
	 * Disable instantiation.
	 */
	private function __construct() {
		// Private to disabled instantiation.
	}

	/**
	 * Disable the cloning of this class.
	 *
	 * @return void
	 */
	final public function __clone() {
		throw new Exception( 'You\'re doing things wrong.' );
	}

	/**
	 * Disable the wakeup of this class.
	 */
	final public function __wakeup() {
		throw new Exception( 'You\'re doing things wrong.' );
	}

	/**
	 * Create or retrieve the instance of Attachment_Settings.
	 */
	public static function instance() {
		if ( is_null( static::$instance ) ) {
			static::$instance = new Attachment_Settings;
			static::$instance->init_hooks();
		}

		return static::$instance;
	}

	/**
	 * Hook into actions and filters.
	 */
	public function init_hooks() {
		add_filter( 'attachment_fields_to_edit', array( $this, 'edit_fields' ), null, 2 );
		add_filter( 'attachment_fields_to_save', array( $this, 'save_fields' ), null , 2 );
	}

	/**
	 * Adds new edit attachment fields
	 *
	 * @since 2.0.0
	 */
	public function edit_fields( $form_fields, $post ) {
		$form_fields['wpex_video_url'] = array(
			'label'	=> esc_html__( 'Video URL', 'total' ),
			'input'	=> 'text',
			'value'	=> get_post_meta( $post->ID, '_video_url', true ),
		);
	   return $form_fields;
	}

	/**
	 * Save new attachment fields
	 *
	 * @since 2.0.0
	 */
	public function save_fields( $post, $attachment ) {
		if ( isset( $attachment['wpex_video_url'] ) ) {
			update_post_meta( $post['ID'], '_video_url', wp_strip_all_tags( $attachment['wpex_video_url'] ) );
		}
		return $post;
	}

}