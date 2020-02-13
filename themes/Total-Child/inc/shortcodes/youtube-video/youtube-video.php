<?php
if (!defined('ABSPATH')) die('-1');
class EASL_VC_Youtube_Player extends WPBakeryShortCode {
	public static $script_loaded = false;
	public function get_attachment_url($attachment_id) {
		$attachment_url = wp_get_attachment_image_src($attachment_id, 'full');
		if($attachment_url){
			$attachment_url = $attachment_url[0];
		}
		return $attachment_url;
	}
	public function load_scripts(){
		if(!self::$script_loaded){
			self::$script_loaded = true;
			wp_enqueue_script('ilc-youtube-player-scripts', get_stylesheet_directory_uri() . '/assets/js/youtube-player.js', array('jquery'), null, true);
		}
	}
}

vc_lean_map('easl_yt_player', null, get_theme_file_path('inc/shortcodes/youtube-video/map.php'));
