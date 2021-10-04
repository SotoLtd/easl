<?php
// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

add_action('wpex_outer_wrap_after', 'easl_sticky_footer', 20);

add_action( 'wp_ajax_easl_save_closed_footer_message', 'easl_save_closed_footer_message' );
add_action( 'wp_ajax_nopriv_easl_save_closed_footer_message', 'easl_save_closed_footer_message' );

function easl_sticky_footer() {
	$enabled        = get_field( 'footer_sticky_msg_enable', 'option' );
	$sticky_message = get_field( 'footer_stikcy_msg', 'option' );
	$enabled_for = 'global';
	if(is_singular() && get_field( 'footer_sticky_msg_enable', wpex_get_the_id() )){
		$enabled = true;
		$sticky_message = get_field( 'footer_stikcy_msg', wpex_get_the_id() );
		$enabled_for = wpex_get_the_id();
	}
	
	$closed_for_IP  = easl_footer_message_is_closed($enabled_for);
	if ( ! $enabled || ! $sticky_message || $closed_for_IP ) {
		return '';
	}
	$template = locate_template('partials/footer/sticky-message.php');

	if(!$template){
		return '';
	}
	include $template;
}

function easl_footer_message_is_closed($for = 'global') {
	if('global' == $for) {
		$ips = get_option( 'easl_footer_message_closed_ip' );
	}else{
		$ips = get_post_meta($for, 'easl_footer_message_closed_ip', true);
	}
	
	if ( ! is_array( $ips ) ) {
		return false;
	}
	$current_ip = easl_get_visitorIP();
	if ( ! $current_ip ) {
		return false;
	}
	if ( in_array( $current_ip, $ips ) ) {
		return true;
	}

	return false;
}

function easl_footer_message_save_closed($for = 'global') {
	if('global' == $for) {
		$ips = get_option( 'easl_footer_message_closed_ip' );
	}else{
		$ips = get_post_meta($for, 'easl_footer_message_closed_ip', true);
	}
	if ( ! is_array( $ips ) ) {
		$ips = array();
	}
	$current_ip = easl_get_visitorIP();
	if ( ! $current_ip ) {
		return false;
	}
	if ( in_array( $current_ip, $ips ) ) {
		return true;
	}
	$ips[] = $current_ip;
	update_option( 'easl_footer_message_closed_ip', $ips );
	if('global' == $for) {
		update_option( 'easl_footer_message_closed_ip', $ips );
	}else{
		update_post_meta($for, 'easl_footer_message_closed_ip', $ips);
	}

	return true;
}

function easl_get_visitorIP() {
	$ip = '';
	if ( array_key_exists( 'HTTP_X_FORWARDED_FOR', $_SERVER ) && ! empty( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) {
		if ( strpos( $_SERVER['HTTP_X_FORWARDED_FOR'], ',' ) > 0 ) {
			$ip = explode( ",", $_SERVER['HTTP_X_FORWARDED_FOR'] );
			$ip = trim( $ip[0] );
		} else {
			$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
		}
	} else {
		$ip = $_SERVER['REMOTE_ADDR'];
	}
	if ( filter_var( $ip, FILTER_VALIDATE_IP ) ) {
		return $ip;
	}

	return false;
}

function easl_save_closed_footer_message() {
	$page = !empty($_POST['page']) ? $_POST['page'] : 'global';
	$result = easl_footer_message_save_closed($page);
	if ( $result ) {
		wp_send_json( array( 'status' => 'OK' ) );
	}
	wp_send_json( array( 'status' => 'NO' ) );
}
