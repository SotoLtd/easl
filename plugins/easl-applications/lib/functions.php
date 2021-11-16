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

function easl_app_get_scoring_criteria($programme_id, $submission_id = false) {
    $category = get_field( 'programme-category', $programme_id );
    if('easl-schools-all' == $category) {
        $school_criteria = get_field('scoring_criteria_schools', $programme_id);
        $scoring_criteria = array();
        $scoring_criteria[] = array(
            'criteria_name' => 'Detailed CV',
            'criteria_max' => $school_criteria['detailed_cv_max_score'],
            'criteria_instructions' => '',
        );
        $scoring_criteria[] = array(
            'criteria_name' => 'Publications',
            'criteria_max' => $school_criteria['publications_max_score'],
            'criteria_instructions' => '',
        );
        $schools = ['amsterdam', 'barcelona', 'frankfurt', 'hamburg'];
        if($submission_id){
            $schools = get_field('easl-schools-all_programme_information_schools', $submission_id);
        }
        foreach ( $schools as $school ) {
            $scoring_criteria[] = array(
                'criteria_name'         => 'Motivation Letter - School ' . ucfirst($school),
                'criteria_max'          => $school_criteria['reference_letter_max_score'],
                'criteria_instructions' => '',
            );
        }
        $scoring_criteria[] = array(
            'criteria_name' => 'Appreciation by reviewer',
            'criteria_max' => $school_criteria['appreciation_by_reviewer_max_score'],
            'criteria_instructions' => '',
        );
    }else{
        $scoring_criteria = get_field('scoring_criteria', $programme_id);
    }
    
    return $scoring_criteria;
    
}