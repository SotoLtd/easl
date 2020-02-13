<?php
// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

class EASL_VC_National_Associations extends WPBakeryShortCode {
	static private $footer_action_queued = false;
	static private $overlay_forms = array();
	static private $nas_instance_count = 0;
	static private $contribute_data;

	static public function reset_nas_data() {
		self::$contribute_data = array(
			'enabled'      => false,
			'title'        => '',
			'subtitle'     => '',
			'button_title' => '',
			'button_color' => '',
			'form_id'      => '',
			'form_title'   => '',
		);
	}

	static public function get_overlay_forms(){
		return self::$overlay_forms;
	}

	static public function update_nas_count(){
		self::$nas_instance_count++;
	}

	static public function get_nas_count(){
		return self::$nas_instance_count;
	}

	static public function set_contribute_data( $data ) {
		$data                  = wp_parse_args( $data, array(
			'enabled'      => false,
			'title'        => '',
			'subtitle'     => '',
			'button_title' => '',
			'button_color' => '',
			'form_id'      => '',
			'form_title'   => '',
		) );
		self::$contribute_data = $data;
		self::$overlay_forms[] = array(
			'id' => $data['form_id'],
			'title' => $data['form_title'],
			'uid' => $data['form_id'] . '-' . self::get_nas_count(),
		);
	}

	static public function get_contribute_data($key = '') {
		if(!$key){
			return self::$contribute_data;
		}
		if(isset(self::$contribute_data[$key])){
			return self::$contribute_data[$key];
		}
		return '';
	}

	static public function form_overlay_at_footer(){
		if(self::$footer_action_queued){
			return false;
		}
		self::$footer_action_queued = true;
		add_action('wp_footer', array('EASL_VC_National_Associations', 'output_footer_html'));
	}

	static public function output_footer_html(){
		get_template_part('partials/national-association/contribute-forms');
	}

	static public function get_forms_dropdown() {
		$gravity_forms_array[ __( 'No Gravity forms found.', 'crvc_extension' ) ] = '';
		if ( class_exists( 'RGFormsModel' ) ) {
			$gravity_forms = RGFormsModel::get_forms( 1, 'title' );
			if ( $gravity_forms ) {
				$gravity_forms_array = array( __( 'Select a form to display.', 'crvc_extension' ) => '' );
				foreach ( $gravity_forms as $gravity_form ) {
					$gravity_forms_array[ $gravity_form->title ] = $gravity_form->id;
				}
			}
		}

		return $gravity_forms_array;
	}

	static public function ajax_nas_details() {
		$nas_id   = ! empty( $_POST['nas_id'] ) ? absint( $_POST['nas_id'] ) : '';
		$nas_post = false;
		if ( $nas_id ) {
			$nas_post = get_post( $nas_id );
		}
		if ( $nas_post ) {
			global $post;
			$post = $nas_post;
			setup_postdata( $post );
			get_template_part( 'partials/national-association/details' );
			wp_reset_postdata();
		}
		die();
	}
}

add_action( 'wp_ajax_get_nas_details', array( 'EASL_VC_National_Associations', 'ajax_nas_details' ) );
add_action( 'wp_ajax_nopriv_get_nas_details', array( 'EASL_VC_National_Associations', 'ajax_nas_details' ) );


vc_lean_map( 'easl_national_associations', null, get_theme_file_path( 'inc/shortcodes/easl-national-associations/map.php' ) );