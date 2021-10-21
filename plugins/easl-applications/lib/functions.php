<?php
// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
    die( '-1' );
}
/**
 * @param $field acf_field
 */
function easl_app_show_personal_info( $field ) {
    if ( is_admin() ) {
        return;
    }
    if ( 'masterclass_confirmation_personal_information' != $field['key'] ) {
        return;
    }
    $sessionData = easl_mz_get_current_session_data();
    $memberData  = easl_mz_get_member_data( $sessionData['member_id'] );
    
    $member_profile_url = get_field( 'member_profile_url', 'option' );
    echo '<table>';
    foreach ( EASLAppSubmission::MEMBER_DATA_FIELDS as $key => $label ) {
        if('dotb_job_function' == $key) {
            $value = easl_mz_get_list_item_name('job_functions', $memberData[ $key ]);
        }elseif('primary_address_country' == $key){
            $value = easl_mz_get_country_name( $memberData[ $key ]);
        }else{
            $value = $memberData[ $key ];
        }
        echo '<tr>';
        echo '<th>' . $label . '</th>';
        echo '<td>' . $value . '</td>';
        echo '</tr>';
    }
    echo '</table>';
    echo '<p>If you want to update your personal details, please go <a href="'. $member_profile_url .'" target="_blank">My Profile</a> page.</p>';
}

function easl_app_users_other_schools_app_for_this_reviewer($submissionId, $reviewerEmail) {
	$programmeId = get_post_meta($submissionId, 'programme_id', true);
	if('easl-schools' != get_field( 'programme-category', $programmeId )) {
		return false;
	}
	$thisYear = date('Y');
	$otherProgrammes = get_posts( [
		'post_type' => 'programme',
		'numberposts' => - 1,
		'fields' => 'ids',
		'meta_query' => [
			[
				'key' => 'programme-category',
				'value' => 'easl-schools',
				'compare' => '='
			],
			[
				'key'     => 'end_date',
				'compare' => '>',
				'value'   => $thisYear . '0000',
			],
			[
				'key'     => 'end_date',
				'compare' => '<=',
				'value'   => $thisYear . '1231',
			],
			[
				'key'     => 'reviewers',
				'compare' => 'LIKE',
				'value'   => $reviewerEmail,
			],
		]
	] );
	if(!$otherProgrammes) {
		return false;
	}
	$appMemberId = get_post_meta($submissionId, 'member_id', true);
	
	$submissions = get_posts([
		'post_type' => 'submission',
		'post_status' => 'any',
		'posts_per_page' => -1,
		'post__not_in' => [$submissionId],
		'fields' => 'ids',
		'meta_query' => [
			[
				'key' => 'member_id',
				'value' => $appMemberId,
				'compare' => '='
			],
			[
				'key' => 'programme_id',
				'value' => $otherProgrammes,
				'compare' => 'IN'
			],
			[
				'key' => 'submitted_timestamp',
				'compare' => 'EXISTS'
			]
		]
	]);
	return $submissions;
}

function easl_app_get_my_review_id($submissionId, $reviewer_email) {
	$reviews = get_posts([
		'post_type' => 'submission-review',
		'post_status' => 'any',
		'fields' => 'ids',
		'meta_query' => [
			[
				'key' => 'submission_id',
				'value' => $submissionId,
				'compare' => '='
			],
			[
				'key'     => 'reviewer_email',
				'compare' => '=',
				'value'   => $reviewer_email,
			],
		],
	]);
	if(!$reviews) {
		return false;
	}
	return $reviews[0];
}

function easl_app_email_content_type_html($content_type = ''){
	return 'text/html';
}
function easl_app_email_form_email($form_email){
	return 'no-reply@easl.eu';
}
function easl_app_email_form_name($form_name){
	return 'EASL Applications';
}