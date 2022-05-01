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

function easl_app_get_school_label($school_key) {
    $schools_labels = [
        'amsterdam' => 'Clinical School Padua',
        'barcelona' => 'Clinical School London',
        'frankfurt' => 'Basic science school London',
        'hamburg'   => 'Clinical School Freiburg',
    ];
    if(array_key_exists($school_key, $schools_labels)) {
        return $schools_labels[$school_key];
    }
    return '';
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
            $school_label = easl_app_get_school_label($school);
            if(!$school_label) {
                $school_label = ucfirst($school);
            }
            $scoring_criteria[] = array(
                'criteria_name'         => 'Motivation Letter - School ' . ucfirst($school),
                'criteria_label'         => 'Motivation Letter - ' . $school_label,
                'criteria_max'          => $school_criteria['reference_letter_max_score'],
                'criteria_instructions' => '',
            );
        }
        foreach ( $schools as $school ) {
            $school_label = easl_app_get_school_label($school);
            if(!$school_label) {
                $school_label = ucfirst($school);
            }
            $scoring_criteria[] = array(
                'criteria_name'         => 'Recommendation Letter - School ' . ucfirst($school),
                'criteria_label'         => 'Recommendation Letter - ' . $school_label,
                'criteria_max'          => $school_criteria['recommendation_letter_max_score'],
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

function easl_app_school_exclude_list_for_review( $submission_id, $order = 0 ) {
    $schools          = [ 'amsterdam', 'barcelona', 'frankfurt', 'hamburg' ];
    $schools_selected = get_field( 'easl-schools-all_programme_information_schools', $submission_id );
    $select_school_key = !empty($schools_selected[ $order ]) ? $schools_selected[ $order ] : '';
    $review_names = [];
    foreach ( $schools as $school ) {
        if($select_school_key == $school) {
            continue;
        }
        $review_names[] = 'Motivation Letter - School ' . ucfirst($school);
        $review_names[] = 'Recommendation Letter - School ' . ucfirst( $school );
    }
    return $review_names;
}

function easl_app_get_schools_selected($submission_id) {
    $choices = [
        'first_choice' => '',
        'second_choice' => '',
    ];
    $schools_selected = get_field( 'easl-schools-all_programme_information_schools', $submission_id );
    if(isset($schools_selected[0])) {
        $choices['first_choice'] = easl_app_get_school_label($schools_selected[0]);
    }
    if(isset($schools_selected[1])) {
        $choices['second_choice'] = easl_app_get_school_label($schools_selected[1]);
    }
    return $choices;
}

function easl_app_get_schools_selected_for_scoring( $submission_id ) {
    $choices = [];
    
    $schools_selected = get_field( 'easl-schools-all_programme_information_schools', $submission_id );
    
    if ( isset( $schools_selected[0] ) ) {
        $choices['first_choice'] = 'Motivation Letter - School ' . ucfirst( $schools_selected[0] );
    }
    if ( isset( $schools_selected[1] ) ) {
        $choices['second_choice'] = 'Motivation Letter - School ' . ucfirst( $schools_selected[1] );
    }
    
    return $choices;
}

function easl_app_total_scoring_per_choices($schools_selected, $exclude_schools_fc, $exclude_school_sc, $scores) {
    $total_score_1 = 0;
    $total_score_2 = 0;
    
    foreach ($scores as $score_item_name => $score) {
        if ( isset( $schools_selected[0] ) ) {
            if ( ! in_array( $score_item_name, $exclude_schools_fc ) ) {
                $total_score_1 += $score;
            }
        }
        if ( isset( $schools_selected[1] ) ) {
            if ( ! in_array( $score_item_name, $exclude_school_sc ) ) {
                $total_score_2 += $score;
            }
        }
    }
    $total_scores = [
        'choice1' => $total_score_1 > 0 ? $total_score_1: '',
        'choice2' => $total_score_2 > 0 ? $total_score_2 : '',
    ];
    return $total_scores;
}