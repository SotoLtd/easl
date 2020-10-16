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
        add_menu_page( 'Applications', 'Applications', 'edit_pages', self::ADMIN_MENU_SLUG, [$this, 'adminPages'], 'dashicons-admin-page', 27 );
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
            'Application submitted' => $submittedDate->format('Y-m-d')
        ];
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
                $fields[$fieldKey] = $field;
            }
        }
        if ($forCSV) {
            foreach($fields as $key => $field) {
                $data = get_field($key, $submission->ID);

                if ($field->type === 'file') {
                    $data = $data['url'];
                }

                $out[$field->name] = $data;
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
        $headerFields = ['ID', 'Title', 'Name', 'Email', 'Phone number', 'Date of Birth', 'Application date'];
        $fieldSets = $fieldSetContainer->getFieldSets();
        $fields = [];

        foreach ($fieldSets as $fieldSet) {
            foreach ($fieldSet->fields as $field) {
                if ($field->hideFromOutput) {
                    continue;
                }
                $headerFields[] = $field->name;
                $fieldKey = $fieldSet->getFieldKey($field);
                $fields[$fieldKey] = $field;
            }
        }

        $headerFields[] = 'Average score';
        $headerFields[] = 'Number of reviews';

        $submissions = [];

        $reviewers = $this->getProgrammeReviewers($programmeId);

        if (!is_array($reviewers)) {
            $reviewers = [];
        }

        $scoringCriteria = get_field('scoring_criteria', $programmeId);

        foreach($this->getSubmissions($programmeId, null) as $sub) {
            $submissions[] = $this->getSubmissionDetails($sub['submission'], true);
        }

        foreach($reviewers as $reviewer) {
            $reviewerName = $reviewer['firstName'] . ' ' . $reviewer['lastName'];
            $headerFields[] = $reviewerName . ' Review total score';
            $headerFields[] = $reviewerName . ' Review text';

            foreach($scoringCriteria as $category) {
                $headerFields[] = $reviewerName . ' ' . $category['criteria_name'] . ' score (out of ' . $category['criteria_max'] . ')';
            }

            foreach($submissions as &$submission) {
                $reviews = $this->getSubmissionReviews($submission['ID']);
                foreach($reviews as $review) {
                    if ($review['reviewer_email'] === $reviewer['email']) {
                        $submission[] = $review['total_score'];
                        $submission[] = $review['review_text'];
                        foreach($review['scoring'] as $category) {
                            $submission[] = $category['score'];
                        }
                    }
                }
            }
        }

        $programme = get_post($programmeId);
        $filename = $programme->post_title . ' export.csv';

        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');

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

    protected function saveReviewDetails($loggedInUserData, $submission, $formData) {
        $submissionId = $submission->ID;

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

        if (isset($formData['review_id'])) {
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

        $programmeId = get_post_meta($submissionId, 'programme_id', true);
        wp_redirect($this->getUrl(self::PAGE_PROGRAMME, ['programmeId' => $programmeId]) . '&review_submitted=1');
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
            'scoringCriteria' => get_field('scoring_criteria', $programmeId),
            'reviews' => $reviews,
            'myReview' => $myReview,
            'saveReviewErrors' => $saveReviewErrors,
            'isAdmin' => $this->isAdmin
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
        $from = EASLApplicationsPlugin::getContactEmail($programmeId);

        $message = EASLApplicationsPlugin::renderEmail($apps->templateDir . 'email/invite_reviewer.php', [
            'firstName' => $memberData['first_name'],
            'lastName' => $memberData['last_name'],
            'programmeName' => $programme->post_title,
            'guidelinesLink' => $guidelinesLink,
            'reviewDeadline' => $reviewDeadline,
            'from' => $from
        ]);

        $subject = 'Invitation to Review EASL Applications';

        EASLApplicationsPlugin::sendEmail($email, $subject, $message, $from);
    }
}