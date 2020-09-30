<?php
// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

return array(
	'name'           => __( 'EASL MZ Member Benefits', 'easl-member-zone' ),
	'base'           => 'easl_mz_member_benefits',
	'category'       => __( 'EASL MZ', 'easl-member-zone' ),
	'description'    => __( 'EASL MZ Member Benefits', 'easl-member-zone' ),
	'icon'           => 'vcex-icon ticon ticon-book',
	'php_class_name' => 'EASL_VC_MZ_Member_Benefits',
	'params'         => array(
		array(
			'type' => 'textarea_html',
			'heading' => __( 'Content', 'easl-member-zone' ),
			'param_name' => 'content',
			'description' => __( 'Enter the content for this element.', 'easl-member-zone' ),
		)
	)
);