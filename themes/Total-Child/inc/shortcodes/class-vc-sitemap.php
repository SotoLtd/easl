<?php
// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

if( class_exists('WPBakeryShortCode') ){
	class EASL_VC_Sitemap extends WPBakeryShortCode{
	}
}