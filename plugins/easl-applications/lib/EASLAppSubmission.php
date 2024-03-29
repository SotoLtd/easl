<?php
// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
    die( '-1' );
}


/**
 * Class EASLAppSubmission
 */
class EASLAppSubmission
{

    /**
     * @var int|null WP Post ID
     */
    protected $submissionId;

    /**
     * @var int WP Post ID
     */
    protected $programmeId;

    /**
     * @var array
     */
    protected $memberData;

    /**
     * @var int EASL Member ID of the current user
     */
    protected $memberId;

    const MEMBER_DATA_ID_FIELD = 'dotb_mb_id';

    const MEMBER_DATA_FIELDS = [
        'salutation' => 'Title',
        'first_name' => 'First name',
        'last_name' => 'Last name',
        self::MEMBER_DATA_ID_FIELD => 'Member ID',
        'birthdate' => 'Date of birth',
        'dotb_gender' => 'Gender',
        'phone_work' => 'Phone',
        'email1' => 'Email',
        'title' => 'Job title',

        'dotb_tmp_account' => 'Home institute',
        'primary_address_street' => 'Street',
        'primary_address_city' => 'City',
        'primary_address_state' => 'State',
        'primary_address_postalcode' => 'Postcode',
        'primary_address_country' => 'Country',
        
        'dotb_job_function' => 'Job function',
        'dotb_job_function_other' => 'Job function – other'
    ];
    //@todo Nationality field

    const SUBMISSION_DATE_META_KEY = 'submitted_timestamp';

    public function __construct($programmeId) {
        $this->programmeId = $programmeId;
    }

    public function getSubmissionId() {
        return $this->submissionId;
    }

    public function getExistingSubmissionForMember($memberId) {

        $submissions = get_posts([
            'post_type' => 'submission',
            'post_status' => 'any',
            'meta_query' => [
                'relation' => 'AND',
                [
                    'key' => 'programme_id',
                    'value' => $this->programmeId,
                ],
                [
                    'key' => 'member_id',
                    'value' => $memberId
                ]
            ],
        ]);
        if (count($submissions) > 0) {
            return current($submissions);
        }
        return null;
    }

    protected function cacheMemberData($memberId) {
        $memberData = easl_mz_get_member_data($memberId);
        foreach(self::MEMBER_DATA_FIELDS as $key => $label) {
            $this->memberData[$key] = $memberData[$key];
        }
    }

    protected function createSubmission() {
        $name = $this->memberData['first_name'] . ' ' . $this->memberData['last_name'];
        $this->submissionId = wp_insert_post(['post_type' => 'submission', 'post_title' => $name]);

        update_post_meta($this->submissionId, 'programme_id', $this->programmeId);

        update_post_meta($this->submissionId, 'member_data', $this->memberData);

        update_post_meta($this->submissionId, 'member_id', $this->memberId);
    }

    public function setup() {
        if (!$this->programmeId) {
            return false;
        }
        $sessionData = easl_mz_get_current_session_data();

        if (!isset($sessionData['member_id'])) {
            return false;
        }

        $start_date = get_field('start_date', $this->programmeId);
        $end_date = get_field('end_date', $this->programmeId);

        $start_date = DateTime::createFromFormat('d/m/Y', $start_date);
        $end_date = DateTime::createFromFormat('d/m/Y', $end_date);
        $now = new DateTime();

        if ($start_date > $now || $end_date < $now) {
            return false;
        }

        $this->memberId = $sessionData['member_id'];

        $this->cacheMemberData($this->memberId);

        $existingSubmission = $this->getExistingSubmissionForMember($this->memberId);

        if ($existingSubmission && $existingSubmission->post_status == 'publish' && $this->submissionHasReviews($existingSubmission->ID)) {
            $redirect = get_field( 'member_dashboard_url', 'option' );
            wp_redirect($redirect . '?application_submitted=' . $existingSubmission->ID);
            die();
        }
    
        if($existingSubmission) {
            $this->submissionId = $existingSubmission->ID;
        }else{
            $this->createSubmission();
        }

        return true;
    }

    public static function onSavePost($postId) {
        if('submission' != get_post_type($postId)) {
            return null;
        }
        if(!is_admin()) {
    
            $memberData = array();
            $member_id = get_post_meta($postId, 'member_id', true);
            $memberDataFromCRM = easl_mz_get_member_data($member_id);
            foreach(self::MEMBER_DATA_FIELDS as $key => $label) {
                $memberData[$key] = $memberDataFromCRM[$key];
            }
            update_post_meta($postId, 'member_data', $memberData);
        }else{
            $memberData = get_post_meta($postId, 'member_data', true);
        }
        
        $submittedAlready = get_post_meta($postId, self::SUBMISSION_DATE_META_KEY, true);
        $programmeId = get_post_meta($postId, 'programme_id', true);
        $programme = get_post($programmeId);

        if (!$submittedAlready) {
            $apps = EASLApplicationsPlugin::getInstance();
    
            $contactEmail = EASLApplicationsPlugin::getContactEmail($programmeId);
	        $reviewDeadline = get_field('review_deadline', $programmeId);

            $message = EASLApplicationsPlugin::renderEmail($apps->templateDir . 'email/application_confirmation.php', [
                'firstName' => $memberData['first_name'],
                'lastName' => $memberData['last_name'],
                'programmeName' => $programme->post_title,
                'contactEmail' => $contactEmail,
                'programmeId' => $programmeId,
                'reviewDeadline' => $reviewDeadline,
            ]);
            update_post_meta($postId, self::SUBMISSION_DATE_META_KEY, time());
            $email = $memberData['email1'];

            $subject = 'EASL Application Confirmation';
            EASLApplicationsPlugin::sendEmail($email, $subject, $message, $contactEmail);
        }

        wp_update_post(['ID' => $postId, 'post_status' => 'publish']);
    }
    
    public function submissionHasReviews($sub_id) {
        $review_query = new WP_Query( array(
            'post_type'   => 'submission-review',
            'post_status' => 'any',
            'meta_key'    => 'submission_id',
            'meta_value'  => $sub_id,
            'fields'      => 'ids',
        ) );
        
        return $review_query->have_posts();
    }
}