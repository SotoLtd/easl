<?php
// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

class EASL_VC_ILC_Details extends WPBakeryShortCode {
	public function get_latest_ilc() {
		$ilcs = get_posts( array(
			'post_type'      => EASL_ILC_Config::get_slug(),
			'post_status'    => 'publish',
			'posts_per_page' => 1,
			'order' => 'DESC',
			'orderby' => 'title',
		));
		if(!$ilcs){
			return false;
		}
		return array_shift($ilcs);
	}
	public function get_ilc_by_name($name) {
		$ilcs = get_posts( array(
			'post_type'      => EASL_ILC_Config::get_slug(),
			'post_status'    => 'publish',
			'posts_per_page' => 1,
			'order' => 'DESC',
			'orderby' => 'title',
			'name' => $name
		));
		if(!$ilcs){
			return false;
		}
		return array_shift($ilcs);
	}

	public function get_debrief_data($debrief) {
		$data = array();
		if(empty($debrief['source'])){
			return false;
		}
		if('yt_playlist' == $debrief['source']){
			$data['source'] = 'playlist';
			$data['id'] = !empty($debrief['youtube_playlist_id']) ? $debrief['youtube_playlist_id'] : '';
		}else{
			$data['source'] = 'ids';
			$data['id'] = !empty($debrief['youtube_video_ids']) ? $debrief['youtube_video_ids'] : '';
		}
		if(!$data['id']){
			return false;
		}
		return $data;
	}
}