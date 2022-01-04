<?php
if (!defined('ABSPATH')) die('-1');
class EASL_VC_Youtube_Player extends EASL_ShortCode {
	public static $script_loaded = false;
	public function get_attachment_url($attachment_id) {
		$attachment_url = wp_get_attachment_image_src($attachment_id, 'full');
		if($attachment_url){
			$attachment_url = $attachment_url[0];
		}
		return $attachment_url;
	}
    
    public function get_video_id_from_input( $input ) {
        $input = trim( $input );
        if ( strpos( $input, 'http') === false ) {
            return $input;
        }
        preg_match( '%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $input, $match );
        
        return $match[1];
    }
	public function load_scripts(){
		if(!self::$script_loaded){
			self::$script_loaded = true;
			wp_enqueue_script('easl-youtube-player-scripts', get_stylesheet_directory_uri() . '/assets/js/youtube-player.js', array('jquery'), null, true);
		}
	}
}
