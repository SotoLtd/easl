<?php
// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

class EASL_VC_MZ_Applications extends EASL_MZ_Shortcode {

    public function get_categories() {
        return get_terms(['taxonomy' => 'programme-category', 'hide_empty' => false]);
    }

    public function get_programmes_for_term($term_id) {
        $programmes = get_posts(['post_type' => 'programme', 'numberposts' => -1, 'tax_query' => [
            [
                'taxonomy' => 'programme-category',
                'field' => 'term_id',
                'terms' => $term_id
            ]
        ]]);

        return array_filter($programmes, function($programme) {
            return !get_field('hide', $programme->ID);
        });
    }

    public function applications_open($programme_id) {
        $start_date = get_field('start_date', $programme_id);
        $end_date = get_field('end_date', $programme_id);

        $start = DateTime::createFromFormat('d/m/Y', $start_date);
        $end = DateTime::createFromFormat('d/m/Y', $end_date);
        $end->add(new DateInterval('P1D'));

        $now = time();

        return $start->getTimestamp() < $now && $end->getTimestamp() > $now;
    }

    public function memberHasAlreadyApplied($programme_id) {

        $sessionData = easl_mz_get_current_session_data();

        if (!isset($sessionData['member_id'])) {
            return false;
        }

        $submission = new EASLAppSubmission($programme_id);
        return !!$submission->getExistingSubmissionForMember($sessionData['member_id']);
    }
    
    public function get_apply_link($programme_id) {
        $applyPage = get_field('apply_page', 'option');
        return $applyPage . '?programme_id=' . $programme_id;
    }
}