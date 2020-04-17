<?php
require_once(EASLApplicationsPlugin::rootDir() . 'lib/EASLAppSubmission.php');
/*
Plugin Name: EASL Applications
Description: The plugin contains the functionality for EASL Programmes - Fellowships, Masterclass etc.
Version: 0.1
Author: Soto
Author URI: http://www.gosoto.co/
*/
class EASLApplicationsPlugin {

    const OPTIONS_PAGE_SLUG = 'application-system-options';

    const SUBMISSION_FIELD_SET_KEYS = [
        'fellowship',
        'masterclass',
        'easl-schools',
        'sponsorship',
        'fellowship',
        'mentorship',
        'registry-grant'
    ];

    /**
     * @var self
     */
    static $_instance;

    /**
     * @var string
     */
    protected $templateDir;

    /**
     * @var string
     */
    public $dir;

    /**
     * @var array
     */
    public $submissionFieldSets = [];

    public function __construct(){
        add_action('init', [$this, 'init']);
        add_action('easl_applications_page_content', [$this, 'pageContent']);
        add_action('admin_menu', [$this, 'adminMenu']);
        add_action('acf/save_post', [EASLAppSubmission::class, 'onSavePost'], 10, 1);

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

        register_post_type('application-review', [
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
            'public' => true,
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
        require_once($this->dir . 'fields/fieldsets/fellowship.php');
        $this->submissionFieldSets['fellowship'] = $fieldsets;
        require_once($this->dir . 'fields/define_fields.php');
    }

    public function init() {
        $this->addOptionsPage();
        $this->registerPostTypes();
        $this->registerTaxonomies();

        //ACF custom fields
        $this->createFields();

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
        require_once($this->templateDir . 'apply.php');
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
}

$instance = EASLApplicationsPlugin::getInstance();