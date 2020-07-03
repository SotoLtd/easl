<?php
// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

function easl_mz_get_manager() {
	return EASL_MZ_Manager::get_instance();
}


function easl_mz_get_asset_url( $filename = '' ) {
	return easl_mz_get_manager()->asset_url( $filename );
}

function easl_mz_is_member_logged_in() {
	return EASL_MZ_Manager::get_instance()->getSession()->has_member_active_session();
}

function easl_mz_get_member_data($member_id) {
//    $member_id = EASL_MZ_Manager::get_instance()->getSession()->ge_current_member_id();
//    print_r($member_id);
//    if ($member_id) {
        $api = easl_mz_get_manager()->getApi();
        return $api->get_member_details($member_id);
//    }
//    return null;
}

function easl_member_logout_url() {
	return add_query_arg( 'mz_logout', 1, get_site_url() );
}

function easl_member_new_membership_form_url( $renew = false ) {
	$url = get_field( 'membership_plan_url', 'option' );
	if ( $renew ) {
		$url = add_query_arg( 'mz_renew', 1, $url );
	} else {
		$url = add_query_arg( 'mz_add', 1, $url );
	}


	return $url;
}

function easl_membership_page_url() {
	return get_field( 'member_profile_url', 'option' );
}

function easl_membership_thanks_page_url() {
	return get_field( 'membership_confirm_page_url', 'option' );
}

function easl_membership_checkout_url() {
	$checkout_page = get_field( 'membership_checkout_url', 'option' );

	return $checkout_page;
}

function easl_mz_get_current_session_data() {
	return EASL_MZ_Manager::get_instance()->getSession()->get_current_session_data();
}

function easl_mz_get_logged_in_member_data() {
    $session_data = easl_mz_get_current_session_data();
    if ($session_data['member_id']) {
        if (empty($session_data['member_data']) ) {
            return easl_mz_refresh_logged_in_member_data();
        }
        return $session_data['member_data'];
    }
    return null;
}

function easl_mz_refresh_logged_in_member_data() {
    $session_data = easl_mz_get_current_session_data();
    if (empty($session_data['member_id'])) {
	    return null;
    }
    $member_id = $session_data['member_id'];
    $api = EASL_MZ_API::get_instance();
    $member_data = $api->get_member_details($member_id);

	if (!$member_data) {
		return null;
	}
    $membership_data = $api->get_members_latest_membership($member_id);

    if($membership_data) {
	    $member_data['membership'] = $membership_data;
    }
    $session = EASL_MZ_Manager::get_instance()->getSession();
    $session->add_data('member_data', $member_data);
    $session->save_session_data();

    return $member_data;
}

function easl_mz_user_is_member() {
    $member = easl_mz_get_logged_in_member_data();

    if ($member) {
        return !!$member['dotb_mb_id'] && $member['dotb_mb_current_status'] === 'active';
    }
    return false;
}

function easl_mz_setcookie( $name, $value, $expire = 0, $secure = false, $httponly = false ) {
	if ( ! headers_sent() ) {
		setcookie( $name, $value, $expire, COOKIEPATH ? COOKIEPATH : '/', COOKIE_DOMAIN, $secure, $httponly );
	} elseif ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
		headers_sent( $file, $line );
		trigger_error( "{$name} cookie cannot be set - headers already sent by {$file} on line {$line}", E_USER_NOTICE );
	}
}

function easl_mz_enqueue_select_assets() {
	wp_enqueue_style( 'select2', easl_mz_get_asset_url( 'lib/select2/css/select2.min.css' ), array(), '4.0.10' );
	wp_enqueue_script( 'select2', easl_mz_get_asset_url( 'lib/select2/js/select2.min.js' ), array(), '4.0.10', true );
}

function easl_mz_enqueue_datepicker_assets() {
	wp_enqueue_style( 'jquery-ui', easl_mz_get_asset_url( 'lib/jquery-ui-1.11.4/jquery-ui.min.css' ), array(), '1.11.4' );
	wp_enqueue_script( 'jquery-ui-datepicker' );
}

function easl_mz_mail_form_name( $from_name ) {
	$from_name = 'EASL Memberzone';

	return $from_name;
}

function easl_mz_get_member_image_src( $member_id, $member_picture ) {
	$member_image = easl_mz_get_asset_url( 'images/default-avatar.jpg' );;
	if ( $member_picture ) {
		$member_image = add_query_arg( array(
			'mz_action' => 'member_image',
			'member_id' => $member_id
		), get_site_url() );
	}

	return $member_image;
}

function easl_mz_get_note_download_url( $note ) {
	$ssl_scheme = is_ssl() ? 'https' : 'http';
	$url        = add_query_arg( array(
		'mz_action' => 'membership_note',
		'note_id'   => $note['id'],
	), get_site_url() );

	return $url;
}

function easl_mz_menu_attrs( $atts ) {

	if ( empty( $atts['href'] ) ) {
		return $atts;
	}

    $has_membership = easl_mz_user_is_member();

    $restricted_urls = easl_mz_get_restricted_urls();

    if ( in_array($atts['href'], $restricted_urls) && !$has_membership ) {
        $atts['href'] = '#';
        $atts['class'] = 'disabled';
    }
	if ( $atts['href'] == '#jhep_link' ) {
	    $atts['href'] = easl_mz_get_jhep_link();
	}
	if ( $atts['href'] == '#jhep_test_link' ) {
		$atts['href'] = easl_mz_get_jhep_link( true );
	}

	return $atts;
}

function easl_mz_country_short( $c1, $c2 ) {
	if ( $c1['member_count'] == $c2['member_count'] ) {
		return 0;
	}

	return ( $c1['member_count'] > $c2['member_count'] ) ? - 1 : 1;
}

function easl_mz_get_restricted_urls() {
    $restricted = get_field('restricted_pages', 'option');
    if (!$restricted) $restricted = [];

    $urls = array_map(function($link) {
            return $link['link'];
        }, $restricted
    );

    //Also restrict the link to JHEP
    $urls[] = '#jhep_link';
    return $urls;
}

function easl_mz_user_can_access_memberzone_page($page_id) {
    $link = get_permalink($page_id);
    return easl_mz_user_can_access_url($link);
}

function easl_mz_user_can_access_url($url) {
    if (!easl_mz_is_member_logged_in()) {
        return false;
    }
    if (easl_mz_user_is_member()) {
        return true;
    }

    foreach(easl_mz_get_restricted_urls() as $restricted_url) {
        if (parse_url($restricted_url, PHP_URL_PATH) == parse_url($url, PHP_URL_PATH)) {
            return false;
        }
    }
    return true;
}

function easl_mz_redirect($url) {
	wp_redirect($url);
	exit();
}