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
            $url = admin_url('admin.php?page=' . self::ADMIN_MENU_SLUG . '&subpage=' . $page . '&');
        } else {
            $url = '/review-applications?';
        }
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

    public function reviewSubmissionPage($submissionId) {
        $submission = get_post($submissionId);
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
            'scoringCriteria' => get_field('scoring_criteria', $programmeId)
        ]);
    }

    protected function getSubmissions($programmeId) {
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
        return array_map(function($submission){
            $meta = get_post_meta($submission->ID);

            return [
                'id' => $submission->ID,
                'name' => $submission->post_title,
                'date' => new DateTime('@' . $meta['submitted_timestamp'][0])
            ];
        }, $submission_posts);
    }

    public function adminProgrammePage($programmeId, $tab) {
        $programme = get_post($programmeId);

        $submissions = $this->getSubmissions($programmeId);

        $reviewers = get_post_meta($programmeId, 'reviewers', true);
        $this->renderTemplate('admin/programme.php', ['reviewers' => $reviewers, 'submissions' => $submissions, 'programme' => $programme, 'tab' => $tab, 'reviewManager' => $this]);
    }

    public function programmePage($programmeId) {
        $programme = get_post($programmeId);
        $submissions = $this->getSubmissions($programmeId);
        $this->renderTemplate('submissions.php', ['submissions' => $submissions, 'programme' => $programme, 'reviewManager' => $this]);
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
            $reviewers = get_post_meta($programmeId, 'reviewers', true);

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