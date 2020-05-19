<?php
require_once(EASLApplicationsPlugin::rootDir() . 'lib/EASLAppSubmission.php');
require_once(EASLApplicationsPlugin::rootDir() . 'lib/EASLAppReview.php');
/*
Plugin Name: EASL Applications
Description: The plugin contains the functionality for EASL Programmes - Fellowships, Masterclass etc.
Version: 0.1
Author: Soto
Author URI: http://www.gosoto.co/
*/
class EASLApplicationsPlugin {

    const OPTIONS_PAGE_SLUG = 'application-system-options';

    const SUBMISSION_FIELD_SETS = [
        'fellowship' => 'Fellowship',
        'masterclass' => 'EASL Masterclass',
        'easl-schools' => 'EASL Schools',
        'sponsorship' => 'Sponsorship',
        'mentorship' => 'Mentorship',
        'registry-grant' => 'Registry Grant'
    ];

    /**
     * @var self
     */
    static $_instance;

    /**
     * @var string
     */
    public $templateDir;

    /**
     * @var string
     */
    public $dir;

    /**
     * @var array
     */
    private $submissionFieldSets = [];

    public function __construct(){
        //ACF custom fields
        $this->createFields();

        add_action('init', [$this, 'init']);
        add_action('easl_applications_page_content', [$this, 'pageContent']);
        add_action('admin_menu', [$this, 'adminMenu']);
        add_action('acf/save_post', [EASLAppSubmission::class, 'onSavePost'], 10, 1);
        add_action('acf/init', [$this, 'addOptionsFields']);

        $this->handleCSVExportRequest();

        $this->dir =  dirname( __FILE__ ) . '/';
        $this->templateDir =$this->dir . 'templates/';
    }

    public static function getInstance() {
        if ( ! ( self::$_instance instanceof self ) ) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }

    public static function rootDir() {
        return self::getInstance()->dir;
    }


    private function addOptionsPage() {
        if ( function_exists( 'acf_add_options_page' ) ) {
            acf_add_options_page( array(
                'page_title' => 'Application system options',
                'menu_slug'  => self::OPTIONS_PAGE_SLUG,
                'capability' => 'manage_options',
                'redirect'   => false,
            ) );
        }
    }

    public function addOptionsFields() {
        acf_add_local_field_group([
            'key' => 'key_pages',
            'title' => 'Key pages',
            'fields' => [
                [
                    'key' => 'apply_page',
                    'label' => 'Apply page',
                    'name' => 'apply_page',
                    'type' => 'page_link'
                ]
            ],
            'location' => [
                [
                    [
                        'param' => 'options_page',
                        'operator' => '==',
                        'value' => self::OPTIONS_PAGE_SLUG
                    ]
                ]
            ]
        ]);
    }

    private function registerPostTypes()
    {
        register_post_type('programme', [
            'labels' => [
                'name' => 'Programmes',
                'singular_name' => 'Programme'
            ],
            'supports' => ['title', 'editor', 'excerpt'],
            'public' => true,
            'publicly_queryable' => true
        ]);

        register_post_type('submission-review', [
            'labels' => [
                'name' => 'Reviews',
                'singular_name' => 'Review'
            ]
        ]);

        register_post_type('submission', [
            'labels' => [
                'name' => 'Submissions',
                'singular_name' => 'Submission'
            ],
            'publicly_queryable' => true
        ]);
    }

    private function registerTaxonomies() {
        register_taxonomy( 'programme-category', 'programme', [
                'labels' => [
                    'name' => 'Programme category'
                ],
                'hierarchical' => true
            ]
        );
    }

    public static function acfPostTypeLocation($postType) {
        return [
            [
                [
                    'param' => 'post_type',
                    'operator' => '==',
                    'value' => $postType
                ]
            ]
        ];
    }

    private function createFields() {
        require_once($this->dir . 'fields/EASLApplicationField.php');
        require_once($this->dir . 'fields/EASLApplicationFieldSet.php');

        require_once($this->dir . 'fields/fieldsets/AbstractFieldContainer.php');

        $fieldsets = [
            'fellowship' => 'FellowshipFieldContainer',
            'mentorship' => 'MentorshipFieldContainer',
            'registry-grant' => 'RegistryGrantFieldContainer',
            'sponsorship' => 'EndorsementFieldContainer',
            'schools' => 'EASLSchoolsFieldContainer',
            'masterclass' => 'MasterclassFieldContainer'
        ];
        foreach($fieldsets as $key => $className) {
            require_once($this->dir . 'fields/fieldsets/' . $className . '.php');
            $fieldset = new $className($key);
            $this->submissionFieldSets[$key] = $fieldset;
        }

        require_once($this->dir . 'fields/define_fields.php');
    }

    /**
     * @param $programmeId
     * @return AbstractFieldContainer
     */
    public function getProgrammeFieldSetContainer($programmeId) {
        $programmeCategory = get_field('programme-category', $programmeId);
        return $this->submissionFieldSets[$programmeCategory];
    }

    public function init() {
        $this->addOptionsPage();
        $this->registerPostTypes();
        $this->registerTaxonomies();

    }

    private function handleCSVExportRequest() {
        if (isset($_GET['csvExport'])) {
            require_once($this->dir . 'lib/EASLAppReview.php');
            $review = new EASLAppReview(true);
            $programmeId = $_GET['csvExport'];
            $review->exportCSV($programmeId, $this->getProgrammeFieldSetContainer($programmeId));
        }
    }

    /**
     * Admin pages
     */
    public function adminMenu() {

        require_once($this->dir . 'lib/EASLAppReview.php');
        $review = new EASLAppReview(true);
        $review->configureAdminPages();
    }

    public function pageContent() {
        if (is_page('review-applications')) {
            $review = new EASLAppReview(false);
            if (isset($_GET['submissionId'])) {
                $review->reviewSubmissionPage($_GET['submissionId']);
            } else {
                $review->programmePage($_GET['programmeId']);
            }
        } else {
            require_once($this->templateDir . 'apply.php');
        }
    }

    public static function getSlug($string) {
        return strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $string), '-'));
    }

    public static function makeOptionsList($options) {
        $out = [];

        foreach($options as $option) {
            $out[self::getSlug($option)] = $option;
        }
        return $out;
    }

    public static function renderEmail($templatePath, $vars = []) {
        ob_start();
        extract($vars);
        include($templatePath);
        $output = ob_get_contents();
        ob_end_clean();
        return $output;
    }
}

$instance = EASLApplicationsPlugin::getInstance();