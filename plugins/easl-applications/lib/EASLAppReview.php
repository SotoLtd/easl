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
        
        foreach ($fieldSets as $fieldSet) {
            foreach ($fieldSet->fields as $field) {
                if ($field->hideFromOutput) {
                    continue;
                }
                $fieldKey = $fieldSet->getFieldKey($field);
                if('date_of_birth' == $field->key) {
                    $out['Date of birth'] = get_field($fieldKey, $submission->ID);
                }else{
                    $fields[$fieldKey] = $field;
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

        } else {
            $acfFields = array_filter(get_field_objects($submission->ID), function($field) use ($fields) {
                return in_array($field['key'], array_keys($fields));
            });

            $out = $out + $acfFields;
        }

        $out = $out + $this->getSubmissionReviewOverview($submission);
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

        $headerFields[] = 'Average score';
        $headerFields[] = 'Number of reviews';

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
            $headerFields[] = $reviewerName . ' Review total score';
            $headerFields[] = $reviewerName . ' Review text';
            foreach ( $scoringCriteria as $category ) {
                $headerFields[] = $reviewerName . ' ' . $category['criteria_name'] . ' score (out of ' . $category['criteria_max'] . ')';
            }
        }

        foreach($this->getSubmissions($programmeId, null) as $sub) {
            $sub_data = array_values( $this->getSubmissionDetails( $sub['submission'], true ) );
            $reviews  = $this->getSubmissionReviewsAllFields( $sub['id'], $scoringCriteriaNames );
            if ( ! $reviews ) {
                $reviews = array();
            }
            foreach ( $reviewers_emails as $reviewer_email ) {
                if ( isset( $reviews[ $reviewer_email ] ) ) {
                    $sub_data[ $reviewer_email . ' total_score' ] = $reviews[ $reviewer_email ]['total_score'];
                    $sub_data[ $reviewer_email . ' review_text' ] = preg_replace('/[\s]+/',' ',$data = str_replace(';', '-', $reviews[ $reviewer_email ]['review_text']));
                    $sub_data                                     = array_merge( $sub_data, array_values( $reviews[ $reviewer_email ]['scoring'] ) );
                } else {
                    $sub_data = array_merge( $sub_data, array_fill( 0, 2 + count( $scoringCriteriaNames ), '' ) );
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
	        wp_redirect($this->getUrl(self::PAGE_PROGRAMME, ['programmeId' => $programmeId]) . '&review_submitted=1');
        }
        return true;
    }
    
    protected function getSubmissionReviewsAllFields( $submissionID, $scoringCriteriaNames ) {
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
            $data[ $row->reviewer_email ] = array(
                'review_text'    => $row->review_text,
                'reviewer_email' => $row->reviewer_email,
                'total_score'    => $row->total_score,
                'scoring'        => $scores,
            );
        }
        
        return $data;
    }

    protected function getSubmissionReviews($submissionId) {
        $reviews = get_posts([
            'post_type' => 'submission-review',
            'post_status' => 'any',
            'meta_key' => 'submission_id',
            'meta_value' => $submissionId
        ]);
        $reviewData = array_map(function($review) {
            $meta = get_post_meta($review->ID);

            $keys = ['reviewer_name', 'review_text', 'reviewer_email', 'total_score', 'scoring'];

            $out = ['id' => $review->ID];

            foreach($keys as $key) {
                if (isset($meta[$key]) && isset($meta[$key][0])) {
                    $out[$key] = $meta[$key][0];
                } else {
                    return null;
                }
            }
            $out['scoring'] = unserialize($out['scoring']);
            return $out;
        }, $reviews);

        return array_filter($reviewData);
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
    protected function getSubmissionPosts($programmeId) {
        return get_posts([
            'post_type' => 'submission',
            'post_status' => 'any',
            'posts_per_page' => -1,
            'meta_query' => [
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
        ]);
    }

    /**
     * @param $programmeId
     * @param null $reviewerEmail
     * @return array
     */
    protected function getSubmissions($programmeId, $reviewerEmail = null) {
        $submission_posts = $this->getSubmissionPosts($programmeId);

        return array_map(function($submission) use ($reviewerEmail, $programmeId) {

            $meta = get_post_meta($submission->ID);

            $sub = [
                    'id' => $submission->ID,
                    'name' => $submission->post_title,
                    'date' => new DateTime('@' . $meta['submitted_timestamp'][0]),
                    'submission' => $submission
                ] + $this->getSubmissionReviewOverview($submission, $reviewerEmail);

            return $sub;
        }, $submission_posts);
    }

    private function getSubmissionReviewOverview($submission, $reviewerEmail = null) {

        $reviewedByMe = false;

        $reviews = $this->getSubmissionReviews($submission->ID);
        if (count($reviews)) {
            $totalScores = array_map(function($review) {
                return $review['total_score'];
            }, $reviews);
            $sumTotalScore = array_sum($totalScores);
            $averageScore = $sumTotalScore / count($reviews);

            if ($reviewerEmail) {
                $myReview = $this->findReviewByUser($reviews, $reviewerEmail);
                $reviewedByMe = !!$myReview;
            }
        }
        if ($this->isAdmin) {
            $out = [
                'averageScore' => isset($averageScore) ? $averageScore : null,
                'numberReviews' => count($reviews)
            ];
        } else {
            $out = [];
        }
        if ($reviewerEmail) {
            $out['reviewedByMe'] = $reviewedByMe;
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
            'reviewManager' => $this
        ]);
    }

    public function getProgrammesUserCanReview($email) {

        $programmes = get_posts(['post_type' => 'programme', 'numberposts' => -1]);
        return array_values(array_filter($programmes, function($p) use ($email) {
            $reviewers = $this->getProgrammeReviewers($p->ID);
            if (!$reviewers) return false;

            $userIsReviewer = array_filter($reviewers, function($reviewer) use($email) {
                return $reviewer['email'] == $email;
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

        if (count($validProgrammes) === 1) {
            $programmeId = $validProgrammes[0]->ID;
        }

        if ($programmeId) {
            $programme = get_post($programmeId);
            $programmeIsValid = array_filter($validProgrammes, function($p) use ($programmeId) {
                return $p->ID == $programmeId;
            });
            if (!$programmeIsValid) {
                $error = 'You have not been invited as a reviewer for this programme.';
            }
        } else {
            $programme = null;
        }

        $submissions = $this->getSubmissions($programmeId, $loggedInUserData['email1']);
        $this->renderTemplate('submissions.php', [
            'submissions' => $submissions,
            'programme' => $programme,
            'reviewManager' => $this,
            'validProgrammes' => $validProgrammes
        ]);
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

    protected function getNumberSubmissions($programmeId) {
        return count($this->getSubmissionPosts($programmeId));
//        global $wpdb;
//        return $wpdb->get_var("
//          SELECT COUNT(DISTINCT(post_id))
//          FROM $wpdb->postmeta
//          WHERE meta_key = 'programme_id'
//          AND meta_value = $programmeId
//        ");
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
                return $r['email'] == $email;
            })) {

                //The reviewer already exists for this programme
                $this->error = 'That email address has already been invited as a reviewer for this programme';
                return;
            }

            $reviewers[] = [
                'email' => $email,
                'firstName' => $member['first_name'],
                'lastName' => $member['last_name'],
                'name' => $member['first_name'] . ' ' . $member['last_name']
            ];

            update_post_meta($programmeId, 'reviewers', $reviewers);

            //Invite the reviewer
            $this->sendReviewerInviteEmail($email, $member, $programmeId);
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
            return $reviewer['email'] != $email;
        });

        update_post_meta($programmeId, 'reviewers', $reviewers);
        wp_redirect($this->getUrl(self::PAGE_PROGRAMME, ['programmeId' => $programmeId, 'tab' => 'reviewers']));
    }

    protected function sendReviewerInviteEmail($email, $memberData, $programmeId) {

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
            'contactEmail' => $contactEmail
        ]);

        $subject = 'Invitation to Review EASL Applications';

        EASLApplicationsPlugin::sendEmail($email, $subject, $message, $contactEmail);
    }
}