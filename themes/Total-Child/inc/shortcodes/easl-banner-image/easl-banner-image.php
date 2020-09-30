<?php
// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

class EASL_VC_Banner_image extends WPBakeryShortCode {

	public function escape_text( $text ) {
		$text = wp_kses( $text, array(
			'br'     => array(),
			'a'      => array(
				'style'  => array(),
				'class'  => array(),
				'id'     => array(),
				'href'   => array(),
				'target' => array(),
			),
			'span'   => array(
				'style' => array(),
				'class' => array(),
				'id'    => array(),
			),
			'strong' => array(
				'style' => array(),
				'class' => array(),
				'id'    => array(),
			),
			'em'     => array(
				'style' => array(),
				'class' => array(),
				'id'    => array(),
			),
			'sup'    => array(
				'style' => array(),
				'class' => array(),
				'id'    => array(),
			),
			'sub'    => array(
				'style' => array(),
				'class' => array(),
				'id'    => array(),
			),
		) );
		$text = str_replace( array( "<br>\n", "<br/>\n", "--NL--", "\n", "\r" ), array( '<br/>', '<br/>', '<br/>', '<br/>', '' ), $text );

		return $text;
	}
}

vc_lean_map( 'easl_banner_image', null, get_theme_file_path( 'inc/shortcodes/easl-banner-image/map.php' ) );