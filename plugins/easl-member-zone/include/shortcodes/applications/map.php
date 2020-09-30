<?php
// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

return array(
	'name'           => __( 'EASL MZ Applications', 'easl-member-zone' ),
	'base'           => 'easl_mz_applications',
	'category'       => __( 'EASL MZ', 'easl-member-zone' ),
	'description'    => __( 'EASL MZ Applications', 'easl-member-zone' ),
	'icon'           => 'vcex-icon ticon ticon-book',
	'php_class_name' => 'EASL_VC_MZ_Applications',
	'params'         => array(
		array(
			'type' => 'textfield',
			'heading' => __( 'Widget title', 'easl-member-zone' ),
			'param_name' => 'title',
			'description' => __( 'Enter text used as widget title (Note: located above content element).', 'easl-member-zone' ),
		)
	)
);