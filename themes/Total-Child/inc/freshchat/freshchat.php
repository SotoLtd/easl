<?php
// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

add_action( 'template_redirect', 'easl_alter_fresh_chat_hooks' );

function easl_feshchat_is_enabled() {
	if($event_subpage_id = easl_get_the_event_subpage_id()) {
		$the_id = $event_subpage_id;
	}else{
		$the_id = wpex_get_current_post_id();
	}
	if ( ! $the_id ) {
		return false;
	}
	
	return get_field( 'easl_freshchat_enable', $the_id );
}

function easl_alter_fresh_chat_hooks() {
	remove_action( 'wp_footer', 'add_fc' );
	if(! easl_feshchat_is_enabled()) {
		return false;
	}
	add_action( 'wp_footer', 'add_fc' );
}