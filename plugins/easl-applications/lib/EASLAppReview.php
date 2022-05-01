<?php
// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
    die( '-1' );
}


/**
 * Class EASLAppReview
 */
class EASLAppReview {

    const ADMIN_MENU_SLUG = 'review-applications';

    /**
     * @var bool
     */
    protected $isAdmin;

    /**
     * @var string|null
     */
    protected $error;

    public function __construct($isAdmin) {
        $this->isAdmin = $isAdmin;

        $this->handleInviteReviewerFormSubmit();
        $this->handleRemoveReviewer();
    }

    public function configureAdminPages() {
        add_submenu_page( 'edit.php?post_type=programme','Applications', 'Applications', 'edit_pages', self::ADMIN_MENU_SLUG, [$this, 'adminPages'], 27 );
    }

    const PAGE_PROGRAMME = 'programme';
    const PAGE_SUBMISSION = 'submission';
    const PAGE_INVITE_REVIEWER = 'invite_reviewer';
    const PAGE_CSV = 'export-csv';

    public function getUrl($page, $args = []) {
        if ($this->isAdmin) {
            if ($page === self::PAGE_CSV) {
                return admin_url('admin.php?csvExport=' . $args['programmeId']);
            }
            $url = admin_url('admin.php?page=' . self::ADMIN_MENU_SLUG);
        } else {
            $url = '/review-applications?';
        }
        $url  .= '&subpage=' . $page . '&';
        if ($args) {
            $queryString = http_build_query($args);
            $url .= $queryString;
        }
        return $url;
    }

    public function adminPages() {
        if (isset($_GET['submissionId'])) {
            $this->reviewSubmissionPage($_GET['submissionId']);
        } elseif (isset($_GET['programmeId'])) {
            $this->adminProgrammePage($_GET['programmeId'], isset($_GET['tab']) ? $_GET['tab'] : 'submissions');
        } else {
            $this->adminIndexPage();
        }
    }

    private function getSubmissionDetails($submission, $forCSV = false) {
        $programmeId = get_post_meta($submission->ID, 'programme_id', true);
        $submittedDate = new DateTime('@' . get_post_meta($submission->ID, 'submitted_timestamp', true));
        $memberData = get_post_meta($submission->ID, 'member_data', true);
    
        $is_combined_school = 'easl-schools-all' == get_field( 'programme-category', $programmeId );
        $out = [
            'Title' => $memberData['salutation'],
            'Name' => $submission->post_title,
            'Email' => $memberData['email1'],
            'Phone' => $memberData['phone_work'],
            'Date of birth' => $memberData['birthdate'],
            'Country' => easl_mz_get_country_name($memberData['primary_address_country']),
            'Application submitted' => $submittedDate->format('Y-m-d')
        ];
        if('masterclass' == get_field( 'programme-category', $programmeId )) {
            $out['Job function'] = 'other' != $memberData['dotb_job_function'] ? easl_mz_get_list_item_name('job_functions', $memberData[ 'dotb_job_function' ]):$memberData[ 'dotb_job_function_other' ] ;
        }
        if ($forCSV) {
            $out = ['ID' => $submission->ID] + $out;
        }

        $fieldSetContainer = EASLApplicationsPlugin::getInstance()->getProgrammeFieldSetContainer($programmeId);

        $fieldSets = $fieldSetContainer->getFieldSets();
        $fields = [];
        $acf_fields = get_field_objects($submission->ID);
        foreach ($fieldSets as $fieldSet) {
            foreach ($fieldSet->fields as $field) {
                if ($field->hideFromOutput) {
                    continue;
                }
                $fieldKey = $fieldSet->getFieldKey($field);
                if('date_of_birth' == $field->key) {
                    $out['Date of birth'] = get_field($fieldKey, $submission->ID);
                    continue;
                }
                $fields[$fieldKey] = $field;
                if(!$forCSV && array_key_exists($fieldKey, $acf_fields)) {
                    $out[$fieldKey] = $acf_fields[$fieldKey];
                }
            }
        }
        
        if ($forCSV) {
            
            $application_other_set = false;
            
            foreach($fields as $key => $field) {
                $data = get_field($key, $submission->ID);

                if($field->key == 'applicant_profile' && 'other' == $data ) {
                    $application_other_set = true;
                    continue;
                }
                if(!$application_other_set && $field->key == 'applicant_profile_other') {
                    continue;
                }
                
                if ($is_combined_school && !$data) {
                    $out[] = '';
                    continue;
                }
                
                if ($field->type === 'file') {
                    $data = $data['url'];
                }elseif ( in_array($field->type, ['select', 'radio']) && isset($field->settings['choices'][$data])){
                    $data = $field->settings['choices'][$data];
                }elseif ('checkbox' == $field->type) {
                    $fields_value = [];
                    foreach ($data as $data_key){
                        $fields_value[] = $field->settings['choices'][$data_key];
                    }
                    $data = implode(' | ', $fields_value);
                }
                $data = str_replace(';', '-', $data);

                $out[] = preg_replace('/[\s]+/',' ', $data);
            }
            
            if($is_combined_school) {
                $out = $out + easl_app_get_schools_selected($submission);
            }

        } else {
            $acfFields = array_filter(get_field_objects($submission->ID), function($field) use ($fields) {
                return in_array($field['key'], array_keys($fields));
            });

            $out = $out + $acfFields;
        }

        $out = $out + $this->getSubmissionReviewOverview($submission, null);
        return $out;
    }

    public function exportCSV($programmeId, $fieldSetContainer) {

        //Get an array of fields that we are going to want to export
        $headerFields = ['ID', 'Title', 'Name', 'Email', 'Phone number', 'Date of Birth', 'Country', 'Application date',];
    
        if('masterclass' == get_field( 'programme-category', $programmeId )) {
            $headerFields[] = 'Job function';
        }
        $fieldSets = $fieldSetContainer->getFieldSets();
        $fields = [];

        foreach ($fieldSets as $fieldSet) {
            foreach ($fieldSet->fields as $field) {
                if ($field->hideFromOutput) {
                    continue;
                }
                if('date_of_birth' != $field->key && 'applicant_profile_other' != $field->key) {
                    $headerFields[] = $field->name;
                    $fieldKey = $fieldSet->getFieldKey($field);
                    $fields[$fieldKey] = $field;
                }
            }
        }
        $category = get_field( 'programme-category', $programmeId );
        $is_combined_school = false;
        if('easl-schools-all' == $category) {
            $is_combined_school = true;
            $headerFields[] = 'First choice';
            $headerFields[] = 'Second choice';
            $headerFields[] = 'Average score - first choice';
            $headerFields[] = 'Average score - second choice';
            $headerFields[] = 'Number of reviews';
        }else{
            $headerFields[] = 'Average score';
            $headerFields[] = 'Number of reviews';
        }

        $submissions = [];

        $reviewers = $this->getProgrammeReviewers($programmeId);

        if (!is_array($reviewers)) {
            $reviewers = [];
        }

        $scoringCriteria = easl_app_get_scoring_criteria($programmeId);
        
        if(!is_array($scoringCriteria)) {
            $scoringCriteria = [];
        }
        $reviewers_emails = wp_list_pluck($reviewers, 'email');
        $scoringCriteriaNames = wp_list_pluck($scoringCriteria, 'criteria_name');
        foreach($reviewers as $reviewer) {
            $reviewerName   = $reviewer['firstName'] . ' ' . $reviewer['lastName'];
            $headerFields[] = $reviewerName . ' Review total score - first choice';
            $headerFields[] = $reviewerName . ' Review total score - second choice';
            $headerFields[] = $reviewerName . ' Review text';
            foreach ( $scoringCriteria as $category ) {
                $criteria_label = isset($category['criteria_label']) ? $category['criteria_label'] : $category['criteria_name'];
                $headerFields[] = $reviewerName . ' ' . $criteria_label . ' score (out of ' . $category['criteria_max'] . ')';
            }
        }

        foreach($this->getSubmissions($programmeId, null) as $sub) {
            $sub_data = array_values( $this->getSubmissionDetails( $sub['submission'], true ) );
            $reviews  = $this->getSubmissionReviewsAllFields( $sub['id'], $scoringCriteriaNames, $is_combined_school );
            if ( ! $reviews ) {
                $reviews = array();
            }
            foreach ( $reviewers_emails as $reviewer_email ) {
                if($is_combined_school){
                    if ( isset( $reviews[ $reviewer_email ] ) ) {
                        $sub_data[ $reviewer_email . ' total_score_1' ] = $reviews[ $reviewer_email ]['total_scores']['choice1'];
                        $sub_data[ $reviewer_email . ' total_score_2' ] = $reviews[ $reviewer_email ]['total_scores']['choice2'];
                        $sub_data[ $reviewer_email . ' review_text' ] = preg_replace( '/[\s]+/', ' ', $data = str_replace( ';', '-', $reviews[ $reviewer_email ]['review_text'] ) );
                        $sub_data                                     = array_merge( $sub_data, array_values( $reviews[ $reviewer_email ]['scoring'] ) );
                    } else {
                        $sub_data = array_merge( $sub_data, array_fill( 0, 3 + count( $scoringCriteriaNames ), '' ) );
                    }
                }else {
                    if ( isset( $reviews[ $reviewer_email ] ) ) {
                        $sub_data[ $reviewer_email . ' total_score' ] = $reviews[ $reviewer_email ]['total_score'];
                        $sub_data[ $reviewer_email . ' review_text' ] = preg_replace( '/[\s]+/', ' ', $data = str_replace( ';', '-', $reviews[ $reviewer_email ]['review_text'] ) );
                        $sub_data                                     = array_merge( $sub_data, array_values( $reviews[ $reviewer_email ]['scoring'] ) );
                    } else {
                        $sub_data = array_merge( $sub_data, array_fill( 0, 2 + count( $scoringCriteriaNames ), '' ) );
                    }
                }
            }
            $submissions[] = $sub_data;
        }

        $programme = get_post($programmeId);
        $filename = $programme->post_name . '-export-' . date('Y-m-d-G-i-s', time()) . '.csv';
    
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Expires: 0');
        header('Cache-Control: no-cache');

        $fp = fopen('php://output', 'wb');
        fputcsv($fp, $headerFields);

        foreach($submissions as $s) {
            fputcsv($fp, $s);
        }

        fclose($fp);
        die();
    }

    private function adminIndexPage() {
        $programmes = array_map(
            function($p) {
                $p->start_date = get_field('start_date', $p->ID);
                $p->end_date = get_field('end_date', $p->ID);
                $p->submissions_count = $this->getNumberSubmissions($p->ID);
                return $p;
            },
            get_posts(['post_type' => 'programme', 'numberposts' => -1])
        );
        $this->renderTemplate('admin/submissions.php', ['programmes' => $programmes]);
    }

    protected function saveReviewDetails($loggedInUserData, $submission, $formData, $redirect = true) {
	    if ( $submission instanceof WP_Post ) {
		    $submissionId = $submission->ID;
	    }else{
		
		    $submissionId = $submission;
	    }

        $errors = [];
        foreach($formData['category'] as $i => $cat) {
            if (!$cat['score']) {
                $errors['categories'][] = $i;
            }
        }
        if (!$formData['review_text']) {
            $errors[] = 'review_text';
        }

        if ($errors) {
            return $errors;
        }

        if (!empty($formData['review_id'])) {
            $reviewId = $formData['review_id'];
            $reviewerEmail = get_post_meta($reviewId, 'reviewer_email', true);
            if ($reviewerEmail !== $loggedInUserData['email1']) {
                //Error - the user is not the author of this review
                return false;
            }
        } else {
            $reviewerName = $loggedInUserData['first_name'] . ' ' . $loggedInUserData['last_name'];
            $reviewTitle = 'Review by ' . $reviewerName . ' of submission from ' . $submission->post_title;
            $reviewId = wp_insert_post(['post_type' => 'submission-review', 'post_title' => $reviewTitle]);
            update_post_meta($reviewId, 'reviewer_name', $reviewerName);
            update_post_meta($reviewId, 'reviewer_email', $loggedInUserData['email1']);
            update_post_meta($reviewId, 'submission_id', $submissionId);
        }

        update_post_meta($reviewId, 'review_text', $formData['review_text']);

        $totalScore = 0;
        foreach($formData['category'] as $cat) {
            $totalScore += $cat['score'];
        }
        update_post_meta($reviewId, 'total_score', $totalScore);

        update_post_meta($reviewId, 'scoring', $formData['category']);
        if($redirect){
	        $programmeId = get_post_meta($submissionId, 'programme_id', true);
            $redirect_params = [
                'programmeId' => $programmeId
            ];
    
            $category = get_field( 'programme-category', $programmeId );
            $schools = get_post_meta($submissionId, 'easl-schools-all_programme_information_schools', true);
            if(('easl-schools-all' == $category) && $schools) {
                $redirect_params['school'] = $schools[0];
            }
	        wp_redirect($this->getUrl(self::PAGE_PROGRAMME, $redirect_params) . '&review_submitted=1');
        }
        return true;
    }
    
    protected function getSubmissionReviewsAllFields( $submissionID, $scoringCriteriaNames, $is_combined_school=false ) {
        global $wpdb;
        $sql = "SELECT p.ID, rt.meta_value AS review_text, re.meta_value AS reviewer_email, ts.meta_value AS total_score, sc.meta_value AS scoring  FROM {$wpdb->posts} AS p";
        $sql .= " INNER JOIN {$wpdb->postmeta} ON ( p.ID = {$wpdb->postmeta}.post_id )";
        $sql .= " LEFT JOIN {$wpdb->postmeta} AS rt ON ( p.ID = rt.post_id ) AND ( rt.meta_key = 'review_text' )";
        $sql .= " LEFT JOIN {$wpdb->postmeta} AS re ON ( p.ID = re.post_id ) AND ( re.meta_key = 'reviewer_email' )";
        $sql .= " LEFT JOIN {$wpdb->postmeta} AS ts ON ( p.ID = ts.post_id ) AND ( ts.meta_key = 'total_score' )";
        $sql .= " LEFT JOIN {$wpdb->postmeta} AS sc ON ( p.ID = sc.post_id ) AND ( sc.meta_key = 'scoring' )";
        
        $sql .= " WHERE (1=1)";
        $sql .= " AND ( {$wpdb->postmeta}.meta_key = 'submission_id')";
        $sql .= $wpdb->prepare( " AND ( {$wpdb->postmeta}.meta_value = %d)", $submissionID );
        $sql .= " AND (p.post_type = 'submission-review')";
        $sql .= " AND (p.post_status NOT IN('trash', 'auto-draft'))";
        $sql .= " GROUP BY p.ID ORDER BY p.post_date DESC LIMIT 0, 99999";
        
        $result = $wpdb->get_results( $sql );
        if ( ! $result ) {
            return array();
        }
        $schools_selected = [];
        $exclude_schools_fc = [];
        $exclude_school_sc = [];
        if($is_combined_school){
            $schools_selected = get_field( 'easl-schools-all_programme_information_schools', $submissionID );
            if ( isset( $schools_selected[0] ) ) {
                $exclude_schools_fc = easl_app_school_exclude_list_for_review($submissionID, 0);
            }
            if ( isset( $schools_selected[1] ) ) {
                $exclude_school_sc = easl_app_school_exclude_list_for_review($submissionID, 1);
            }
        }
        
        $data = array();
        foreach ( $result as $row ) {
            $scores = maybe_unserialize( $row->scoring );
            if ( ! $scores ) {
                $scores = array();
            }
            $score_data = array();
            foreach ( $scores as $score ) {
                $score_data[ $score['name'] ] = $score['score'];
            }
            $scores = array();
            foreach ( $scoringCriteriaNames as $scoring_criteria_name ) {
                $scores[ $scoring_criteria_name ] = isset( $score_data[ $scoring_criteria_name ] ) ? $score_data[ $scoring_criteria_name ] : '';
            }
            $reviewer_data = array(
                'review_text'    => $row->review_text,
                'reviewer_email' => $row->reviewer_email,
                'total_score'    => $row->total_score,
                'scoring'        => $scores,
            );
            if($is_combined_school){
                $reviewer_data['total_scores'] = easl_app_total_scoring_per_choices($schools_selected, $exclude_schools_fc, $exclude_school_sc, $scores);
            }
            $data[ strtolower($row->reviewer_email) ] = $reviewer_data;
        }
        
        return $data;
    }

    protected function getSubmissionReviews($submissionId) {
        $args = [
            'post_type' => 'submission-review',
            'post_status' => 'any',
            'meta_query' => [
                'relation' => 'AND',
                [
                    'key' => 'submission_id',
                    'value' => $submissionId,
                    'compare' => '='
                ]
            ],
        ];
        $reviewers = $this->getProgrammeReviewers(get_post_meta($submissionId, 'programme_id', true));
        $reviewers_emails = false;
        if (is_array($reviewers)) {
            $reviewers_emails = wp_list_pluck($reviewers, 'email');
        }
        if($reviewers_emails) {
            $args['meta_query'][] = [
                'key' => 'reviewer_email',
                'value' => $reviewers_emails,
                'compare' => 'IN'
            ];
        }
    
        $reviews = get_posts($args);
        $reviewData = [];
        $reviewers_emails_found = [];
        foreach ( $reviews as $review ) {
            $meta = get_post_meta($review->ID);
            if(
                empty($meta['reviewer_name'][0]) ||
                empty($meta['review_text'][0]) ||
                empty($meta['reviewer_email'][0]) ||
                empty($meta['total_score'][0]) ||
                empty($meta['scoring'][0])
            ) {
                continue;
            }
            if(in_array($meta['reviewer_email'][0], $reviewers_emails_found)) {
                continue;
            }
            $reviewers_emails_found[] = $meta['reviewer_email'][0];
    
            $out = ['id' => $review->ID];
            $out['reviewer_name'] = $meta['reviewer_name'][0];
            $out['review_text'] = $meta['review_text'][0];
            $out['reviewer_email'] = $meta['reviewer_email'][0];
            $out['total_score'] = $meta['total_score'][0];
            $out['scoring'] = maybe_unserialize($meta['scoring'][0]);
            $reviewData[] = $out;
        }
        return $reviewData;
    }

    protected function findReviewByUser($reviews, $reviewerEmail) {
        $matchingReviews = array_filter($reviews, function($review) use ($reviewerEmail) {
            return $review['reviewer_email'] === $reviewerEmail;
        });
        return count($matchingReviews) > 0 ? current($matchingReviews) : null;
    }

    public function reviewSubmissionPage($submissionId) {

        $submission = get_post($submissionId);
        $fields = $this->getSubmissionDetails($submission);

        $myReview = $saveReviewErrors = null;

        $loggedInUserData = easl_mz_get_logged_in_member_data();

        if (!$this->isAdmin) {
            if (isset($_POST['review_submitted'])) {
                if(!empty($_POST['easl_app_other_schools_sub'])) {
                	foreach ($_POST['easl_app_other_schools_sub'] as $other_schools_sub_id) {
                		$reviewData = $_POST;
		                $reviewData['review_id'] = easl_app_get_my_review_id($other_schools_sub_id, $loggedInUserData['email1']);
		                $sss = $this->saveReviewDetails($loggedInUserData, $other_schools_sub_id, $reviewData, false);
	                }
                }
	            $saveReviewErrors = $this->saveReviewDetails($loggedInUserData, $submission, $_POST);
            }
        }

        $reviews = $this->getSubmissionReviews($submissionId);

        if (!$this->isAdmin) {
            $myReview = $this->findReviewByUser($reviews, $loggedInUserData['email1']);
        }

        $programmeId = get_post_meta($submissionId, 'programme_id', true);
        $programme = get_post($programmeId);

        $this->renderTemplate('review.php', [
            'submission' => $submission,
            'programme' => $programme,
            'fields' => $fields,
            'reviewManager' => $this,
            'scoringCriteria' => easl_app_get_scoring_criteria($programmeId, $submissionId),
            'reviews' => $reviews,
            'myReview' => $myReview,
            'saveReviewErrors' => $saveReviewErrors,
            'isAdmin' => $this->isAdmin,
        ]);
    }

    /**
     * @param $programmeId
     * @return int[]|WP_Post[]
     */
    protected function getSubmissionPosts($programmeId, $status = 'any' , $schools = false, $ids_only = false) {
        $args = [
            'post_type' => 'submission',
            'post_status' => $status,
            'posts_per_page' => -1,
            'meta_query' => [
                'relation' => 'AND',
                [
                    'key' => 'programme_id',
                    'value' => $programmeId,
                    'compare' => '='
                ],
                [
                    'key' => 'submitted_timestamp',
                    'compare' => 'EXISTS'
                ]
            ]
        ];
        if($schools) {
            $school_meta = [
                'relation' => 'OR',
            ];
            foreach ($schools as $school) {
                $school_meta[] = [
                    'key' => 'easl-schools-all_programme_information_schools',
                    'compare' => 'RLIKE',
                    'value' => '^[^"]+"' . $school .'"',
                ];
            }
            $args['meta_query'][] = $school_meta;
        }
        if($ids_only) {
            $args['fields'] = 'ids';
        }
        return get_posts($args);
    }

    /**
     * @param $programmeId
     * @param null $reviewerEmail
     * @return array
     */
    protected function getSubmissions($programmeId, $reviewerEmail = null, $status = 'any', $schools=false) {
        $submission_posts = $this->getSubmissionPosts($programmeId, $status, $schools);

        return array_map(function($submission) use ($reviewerEmail, $programmeId) {

            $meta = get_post_meta($submission->ID);

            $sub = [
                    'id' => $submission->ID,
                    'name' => $submission->post_title,
                    'date' => new DateTime('@' . $meta['submitted_timestamp'][0]),
                    'submission' => $submission
                ] + $this->getSubmissionReviewOverview($submission, $reviewerEmail, );

            return $sub;
        }, $submission_posts);
    }

    private function getSubmissionReviewOverview($submission, $reviewerEmail = null) {
        $reviewedByMe = false;
        $reviews = $this->getSubmissionReviews($submission->ID);
        $programmeId = get_post_meta($submission->ID, 'programme_id', true);
        $is_combined_school = 'easl-schools-all' == get_field( 'programme-category', $programmeId );
        if(!$reviews) {
            $reviews = [];
        }
        $total_score_1 = 0;
        $total_score_2 = 0;
        $exclude_school_scores_1 = [];
        $exclude_school_scores_2 = [];
        $school_choices = [];
        if($is_combined_school) {
            $school_choices = easl_app_get_schools_selected($submission->ID);
            $exclude_school_scores_1 = easl_app_school_exclude_list_for_review($submission->ID, 0);
            if($school_choices['second_choice']){
                $exclude_school_scores_2 = easl_app_school_exclude_list_for_review($submission->ID, 1);
            }
        }
        foreach ($reviews as $review) {
            if(!$is_combined_school) {
                $total_score_1 += $review['total_score'];
                continue;
            }
            $rts1 = $review['total_score'];
            if(!empty($school_choices['second_choice'])) {
                $rts2 = $review['total_score'];
                foreach ( $review['scoring'] as $review_score ) {
                    if(in_array($review_score['name'], $exclude_school_scores_1)) {
                        $rts1 -= $review_score['score'];
                    }
                    if(in_array($review_score['name'], $exclude_school_scores_2)) {
                        $rts2 -= $review_score['score'];
                    }
                }
                $total_score_2 += $rts2;
            }
            $total_score_1 += $rts1;
        }
        $total_reviews_count = count($reviews);
        if($total_reviews_count) {
            $average_score_1 = (float) $total_score_1/$total_reviews_count;
            $average_score_2 = (float) $total_score_2/$total_reviews_count;
            $average_score_1 = number_format($average_score_1, 2, '.', '');
            $average_score_2 = number_format($average_score_2, 2, '.', '');
            
            if ($reviewerEmail) {
                $myReview = $this->findReviewByUser($reviews, $reviewerEmail);
                $reviewedByMe = !!$myReview;
            }
        }
        if ($this->isAdmin) {
            if($is_combined_school){
                $out = [
                    'averageScore1'  => isset( $average_score_1 ) ? $average_score_1 : null,
                    'averageScore2'  => isset( $average_score_2 ) ? $average_score_2 : null,
                    'numberReviews' => count( $reviews )
                ];
            }else {
                $out = [
                    'averageScore'  => isset( $average_score_1 ) ? $average_score_1 : null,
                    'numberReviews' => count( $reviews )
                ];
            }
        } else {
            $out = [];
        }
        if ($reviewerEmail) {
            $out['reviewedByMe'] = $reviewedByMe;
            $out['myReview'] = $reviewedByMe ? $myReview : false;
            $out['totalScoreByMe'] = $reviewedByMe? $myReview['total_score'] : '';
        }

        return $out;
    }

    private function adminProgrammePage($programmeId, $tab) {
        $programme = get_post($programmeId);

        $submissions = $this->getSubmissions($programmeId);

        $reviewers = $this->getProgrammeReviewers($programmeId);
        $this->renderTemplate('admin/programme.php', [
            'reviewers' => $reviewers,
            'submissions' => $submissions,
            'programme' => $programme,
            'tab' => $tab,
            'reviewManager' => $this,
        ]);
    }

    public function getProgrammesUserCanReview($email) {

        $programmes = get_posts(['post_type' => 'programme', 'numberposts' => -1]);
        return array_values(array_filter($programmes, function($p) use ($email) {
            $reviewers = $this->getProgrammeReviewers($p->ID);
            if (!$reviewers) return false;

            $userIsReviewer = array_filter($reviewers, function($reviewer) use($email) {
                return strtolower($reviewer['email']) == strtolower($email);
            });
            if (count($userIsReviewer) === 0) {
                return false;
            }
            return true;
        }));
    }

    public function programmePage($programmeId = null) {
        $loggedInUserData = easl_mz_get_logged_in_member_data();

        $validProgrammes = $this->getProgrammesUserCanReview($loggedInUserData['email1']);
    
        if(!$programmeId && count($validProgrammes) !== 1) {
            EASLApplicationsPlugin::load_template('review/programme-list.php', [
                'reviewManager' => $this,
                'validProgrammes' => $validProgrammes
            ]);
            return;
        }
        
        if(!$programmeId && (count($validProgrammes) === 1) ) {
            $programmeId = $validProgrammes[0]->ID;
        }
        if(!$programmeId) {
            return;
        }
        $programme = get_post($programmeId);
        $programmeIsValid = array_filter($validProgrammes, function($p) use ($programmeId) {
            return $p->ID == $programmeId;
        });
        if (!$programmeIsValid) {
            echo '<h2>You have not been invited as a reviewer for this programme.</h2>';
            return;
        }
    
        $category = get_field( 'programme-category', $programmeId );
        $current_school = !empty($_GET['school']) ? $_GET['school'] : '';
        $schools = false;
        $programme_schools_applied = [];
        if('easl-schools-all' == $category) {
            $programme_schools_applied = $this->get_programmes_school_applied($programmeId, $loggedInUserData['email1']);
            if(!$programme_schools_applied) {
                echo '<h2>No application in any of the schools.</h2>';
                return;
            }
            $schools = array_keys($programme_schools_applied);
        }
        if($current_school) {
            $schools = [$current_school];
        }
        $submissions = $this->getSubmissions($programmeId, $loggedInUserData['email1'], 'publish', $schools);
        EASLApplicationsPlugin::load_template('review/submissions.php', [
            'submissions' => $submissions,
            'programme' => $programme,
            'schools' => $programme_schools_applied,
            'current_school' => $current_school,
            'total_applications' => $this->getNumberSubmissions($programmeId, array_keys($programme_schools_applied)),
            'reviewManager' => $this,
            'validProgrammes' => $validProgrammes
        ]);
        return;
    }
    
    public function get_reviewer_schools( $programme_id, $reviewer_email ) {
        $schools                  = [
            'amsterdam' => 'Clinical School Padua',
            'barcelona' => 'Clinical School London',
            'frankfurt' => 'Basic science school London',
            'hamburg'   => 'Clinical School Freiburg',
        ];
        $current_reviewer_schools = false;
        if ( $reviewer_email ) {
            $programme_reviewers = $this->getProgrammeReviewers( $programme_id );
            foreach ( $programme_reviewers as $pr ) {
                if ( empty( $pr['email'] ) || empty( $pr['schools'] ) ) {
                    continue;
                }
                if ( strtolower($pr['email']) == strtolower($reviewer_email) ) {
                    $current_reviewer_schools = $pr['schools'];
                    break;
                }
            }
        }
        if ( ! $current_reviewer_schools ) {
            return $schools;
        }
        $schools_to_return = [];
        foreach ( $schools as $school_key => $school_name ) {
            if ( in_array( $school_key, $current_reviewer_schools ) ) {
                $schools_to_return[ $school_key ] = $school_name;
            }
        }
        
        return $schools_to_return;
    }
    public function get_programmes_school_applied($programme_id, $reviewer_email = '') {
        if($reviewer_email) {
            $schools = $this->get_reviewer_schools($programme_id, $reviewer_email);
        }else{
            $schools = [
                'amsterdam' => 'Clinical School Padua',
                'barcelona' => 'Clinical School London',
                'frankfurt' => 'Basic science school London',
                'hamburg'   => 'Clinical School Freiburg',
            ];
        }
        $available_schools = [];
        foreach ($schools as $school_key => $school_name) {
            $program_school_submissions = $this->getSubmissionPosts($programme_id, 'publish', [$school_key], true);
            if($program_school_submissions) {
                $available_schools[$school_key] = [
                    'school_name' => $school_name,
                    'count' => count($program_school_submissions)
                ];
            }
        }
        
        return $available_schools;
    }

    protected function getProgrammeReviewers($programmeId) {
        $reviewers = get_post_meta($programmeId, 'reviewers', true);
        if(!$reviewers) {
            $reviewers = [];
        }
        return $reviewers;
    }

    protected function renderTemplate($file, $vars) {
        extract($vars);
        $error = $this->error;
        require_once(EASLApplicationsPlugin::rootDir() . 'templates/' . $file);
    }

    protected function getNumberSubmissions($programmeId, $schools=false) {
        return count($this->getSubmissionPosts($programmeId, 'publish', $schools, true));
    }

    public function handleInviteReviewerFormSubmit() {
        if (isset($_POST['reviewer_invitation_email']) && isset($_GET['programmeId'])) {
            $email = $_POST['reviewer_invitation_email'];
            $this->inviteReviewer($email, $_GET['programmeId']);
        }
    }

    public function handleRemoveReviewer() {
        if (isset($_GET['remove_reviewer_email']) && isset($_GET['programmeId'])) {
            $email = $_GET['remove_reviewer_email'];
            $this->removeReviewer($email, $_GET['programmeId']);
        }
    }

    protected function inviteReviewer($email, $programmeId) {
        $api = easl_mz_get_manager()->getApi();
        $filterArgs = [
            'max_num' => 1,
            'filter' => [
                [
                    'email1' => [
                        '$equals' => $email
                    ]
                ]
            ]
        ];
        $memberData = $api->get_members($filterArgs);
        if ($memberData) {
            $member = current($memberData);

            //Get existing reviewers
            $reviewers = $this->getProgrammeReviewers($programmeId);

            if (array_filter($reviewers, function($r) use ($email) {
                return strtolower($r['email']) == strtolower($email);
            })) {

                //The reviewer already exists for this programme
                $this->error = 'That email address has already been invited as a reviewer for this programme';
                return;
            }
            $reviewers_data = [
                'email' => $email,
                'firstName' => $member['first_name'],
                'lastName' => $member['last_name'],
                'name' => $member['first_name'] . ' ' . $member['last_name']
            ];
            
            if(!empty($_POST['reviewer_schools'])) {
                $reviewers_data['schools'] = $_POST['reviewer_schools'];
            }
    
            $reviewers[] = $reviewers_data;
            update_post_meta($programmeId, 'reviewers', $reviewers);

            //Invite the reviewer
            $this->sendReviewerInviteEmail($email, $member, $programmeId, $reviewers_data);
        } else {
            $this->error = 'No MyEASL account was found for ' . $email;
        }
    }

    protected function removeReviewer($email, $programmeId) {
        $reviewers = array_filter($this->getProgrammeReviewers($programmeId), function($reviewer) use ($email) {
//            echo $email . '<br>';
//            echo $reviewer['email'] . '<br>';
//            print_r($reviewer);
//            die();
            return strtolower($reviewer['email']) != strtolower($email);
        });

        update_post_meta($programmeId, 'reviewers', $reviewers);
        wp_redirect($this->getUrl(self::PAGE_PROGRAMME, ['programmeId' => $programmeId, 'tab' => 'reviewers']));
    }

    protected function sendReviewerInviteEmail($email, $memberData, $programmeId, $reviewers_data = []) {

        $programme = get_post($programmeId);
        $apps = EASLApplicationsPlugin::getInstance();

        $guidelinesLink = get_field('guidelines_link', $programmeId);
        $reviewDeadline = get_field('review_deadline', $programmeId);
        $contactEmail = EASLApplicationsPlugin::getContactEmail($programmeId);

        $message = EASLApplicationsPlugin::renderEmail($apps->templateDir . 'email/invite_reviewer.php', [
            'firstName' => $memberData['first_name'],
            'lastName' => $memberData['last_name'],
            'programmeName' => $programme->post_title,
            'guidelinesLink' => $guidelinesLink,
            'reviewDeadline' => $reviewDeadline,
            'contactEmail' => $contactEmail,
            'reviewers_data' => $reviewers_data,
        ]);

        $subject = 'Invitation to Review EASL Applications';

        EASLApplicationsPlugin::sendEmail($email, $subject, $message, $contactEmail);
    }
}