<?php

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
        'primary_address_country' => 'Country'
    ];
    //@todo Nationality field

    const SUBMISSION_DATE_META_KEY = 'submitted_timestamp';

    public function __construct($programmeId) {
        $this->programmeId = $programmeId;
    }

    public function getSubmissionId() {
        return $this->submissionId;
    }

    protected function getExistingSubmissionForMember($memberId) {

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

        $this->memberId = $sessionData['member_id'];

        $this->cacheMemberData($this->memberId);

        $existingSubmission = $this->getExistingSubmissionForMember($this->memberId);

        if ($existingSubmission) {
            $this->submissionId = $existingSubmission->ID;
        } else {
            $this->createSubmission();
        }

        return true;
    }

    public static function onSavePost($postId) {
        update_post_meta($postId, self::SUBMISSION_DATE_META_KEY, time());
        wp_update_post(['ID' => $postId, 'post_status' => 'publish']);
    }
}