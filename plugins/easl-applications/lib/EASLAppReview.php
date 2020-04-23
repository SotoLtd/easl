<?php

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
    }

    public function configureAdminPages() {
        add_menu_page( 'Review applications', 'Review applications', 'edit_pages', self::ADMIN_MENU_SLUG, [$this, 'adminPages'], 'dashicons-admin-page', 23 );
    }

    const PAGE_PROGRAMME = 'programme';
    const PAGE_SUBMISSION = 'submission';
    const PAGE_INVITE_REVIEWER = 'invite_reviewer';

    public function getUrl($page, $args) {
        if ($this->isAdmin) {
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

    public function adminIndexPage() {
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

        if ($formData['review_id']) {
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
            update_post_meta($reviewId, 'submission_id', $submissionId);
            update_post_meta($reviewId, 'review_text', $formData['review_text']);
            update_post_meta($reviewId, 'reviewer_name', $reviewerName);
            update_post_meta($reviewId, 'reviewer_email', $loggedInUserData['email1']);
        }
        $totalScore = 0;
        foreach($formData['category'] as $cat) {
            $totalScore += $cat['score'];
        }
        update_post_meta($reviewId, 'total_score', $totalScore);

        update_post_meta($reviewId, 'scoring', $formData['category']);
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
        return count($matchingReviews) > 0 ? $matchingReviews[0] : null;
    }

    public function reviewSubmissionPage($submissionId) {

        $submission = get_post($submissionId);
        $reviews = $this->getSubmissionReviews($submissionId);

        $myReview = null;

        if (!$this->isAdmin) {
            $loggedInUserData = easl_mz_get_logged_in_member_data();
            if (isset($_POST['review_submitted'])) {
                $this->saveReviewDetails($loggedInUserData, $submission, $_POST);
            }

            $myReview = $this->findReviewByUser($reviews, $loggedInUserData['email1']);
        }

        $programmeId = get_post_meta($submissionId, 'programme_id', true);
        $programme = get_post($programmeId);
        $fields = get_field_objects($submissionId);

        $confirmationFieldsetId = EASLApplicationsPlugin::getInstance()->submissionFieldSets['fellowship']['confirmation'];

        $this->renderTemplate('review.php', [
            'submission' => $submission,
            'programme' => $programme,
            'fields' => $fields,
            'confirmationFieldsetId' => $confirmationFieldsetId,
            'reviewManager' => $this,
            'scoringCriteria' => get_field('scoring_criteria', $programmeId),
            'reviews' => $reviews,
            'myReview' => $myReview,
            'isAdmin' => $this->isAdmin
        ]);
    }

    protected function getSubmissions($programmeId, $reviewerEmail = null) {
        $submission_posts = get_posts([
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

        return array_map(function($submission) use ($reviewerEmail) {
            $meta = get_post_meta($submission->ID);

            $reviews = $this->getSubmissionReviews($submission->ID);
            $reviewedByMe = false;
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

            return [
                'id' => $submission->ID,
                'name' => $submission->post_title,
                'date' => new DateTime('@' . $meta['submitted_timestamp'][0]),
                'averageScore' => isset($averageScore) ? $averageScore : null,
                'numberReviews' => count($reviews),
                'reviewedByMe' => $reviewerEmail ? $reviewedByMe : null
            ];
        }, $submission_posts);
    }

    public function adminProgrammePage($programmeId, $tab) {
        $programme = get_post($programmeId);

        $submissions = $this->getSubmissions($programmeId);

        $reviewers = $this->getProgrammeReviewers($programmeId);
        $this->renderTemplate('admin/programme.php', [
            'reviewers' => $reviewers,
            'submissions' => $submissions,
            'programme' => $programme,
            'tab' => $tab,
            'reviewManager' => $this]);
    }

    public function programmePage($programmeId = null) {
        $programmes = get_posts(['post_type' => 'programme']);
        $loggedInUserData = easl_mz_get_logged_in_member_data();

        $validProgrammes = array_filter($programmes, function($p) use ($loggedInUserData) {
            $reviewers = $this->getProgrammeReviewers($p->ID);
            if (!$reviewers) return false;

            $userIsReviewer = array_filter($reviewers, function($reviewer) use($loggedInUserData) {
                return $reviewer['email'] == $loggedInUserData['email1'];
            });
            if (count($userIsReviewer) === 0) {
                return false;
            }
            return true;
        });

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
        return get_post_meta($programmeId, 'reviewers', true);
    }

    protected function renderTemplate($file, $vars) {
        extract($vars);
        $error = $this->error;
        require_once(EASLApplicationsPlugin::rootDir() . 'templates/' . $file);
    }

    protected function getNumberSubmissions($programmeId) {
        global $wpdb;
        return $wpdb->get_var("
          SELECT COUNT(DISTINCT(post_id))
          FROM $wpdb->postmeta
          WHERE meta_key = 'programme_id'
          AND meta_value = $programmeId
        ");
    }

    public function handleInviteReviewerFormSubmit() {
        if (isset($_POST['reviewer_invitation_email']) && isset($_GET['programmeId'])) {
            $email = $_POST['reviewer_invitation_email'];
            $this->inviteReviewer($email, $_GET['programmeId']);
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
            $this->sendReviewerInviteEmail($email, $programmeId);
        } else {
            $this->error = 'No MyEASL account was found for ' . $email;
        }
    }

    protected function sendReviewerInviteEmail($email, $programmeId) {
        $subject = get_field('reviewer_email_subject', $programmeId);
        $content = get_field('reviewer_email', $programmeId);
        $email = 'will@willevans.tech';
        $headers = ['Content-Type: text/html; charset=UTF-8'];
        wp_mail($email, $subject, $content. $headers);
    }
}