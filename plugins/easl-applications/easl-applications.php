<?php
// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
    die( '-1' );
}
define('EASL_APPLICATION_VERSION', '2.0.3');
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
        'easl-schools-all' => 'EASL Schools Combined',
        'sponsorship' => 'Sponsorship',
        'mentorship' => 'Mentorship',
        'registry-grant' => 'Registry Grant',
        'daniel-alagille-award' => 'Daniel Alagille Award',
        'emerging-leader-award' => 'Emerging Leader Award',
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
        $this->dir         = dirname( __FILE__ ) . '/';
        $this->templateDir = $this->dir . 'templates/';
        require_once $this->dir . 'lib/functions.php';
        //ACF custom fields
        $this->createFields();

        add_action('init', [$this, 'init']);
        add_action('add_meta_boxes', [$this, 'registerSubmissionMetaBox']);
        add_action('easl_application_page_start', [$this, 'application_assets']);
        add_action('easl_applications_page_content', [$this, 'pageContent']);
        add_action('admin_menu', [$this, 'adminMenu']);
        add_action('acf/save_post', [EASLAppSubmission::class, 'onSavePost'], 10, 1);
        add_action('acf/init', [$this, 'addOptionsFields']);
        
        add_filter('acf/validate_value/name=easl-schools-all_programme_information_schools', [$this, 'restrict_combine_schools_selection'], 20, 4);
        add_filter('acf/prepare_field/name=easl-schools-all_programme_information_schools', [$this, 'display_sorted_combine_schools_selection']);
        
        add_filter('wp_nav_menu_items', [$this, 'addReviewMenuItem'], 10, 2);
        add_action( 'admin_enqueue_scripts', [$this, 'admin_scripts'] );
        
        add_action( 'wp_ajax_sync_submission_member_data', [ $this, 'sync_submission_member_data' ] );
        add_action( 'wp_ajax_nopriv_sync_submission_member_data', [ $this, 'sync_submission_member_data' ] );
        
        add_filter( 'acf/upload_prefilter/type=file', [ $this, 'change_application_files_path' ], 10, 3 );
        
        add_action('deleted_post', [$this, 'clean_submissions_after_post_delete'], 10, 2);
    }
    
    public function change_application_files_path( $errors, $file, $field ) {
        if ( empty( $field['_easl_app_file'] ) ) {
            return $errors;
        }
        add_filter( 'upload_dir', [ $this, 'files_upload_dir' ], 20 );
        add_filter( 'intermediate_image_sizes_advanced', [ $this, 'prevent_intermediate_sizes_creation' ] );
        add_action( 'wp_create_file_in_uploads', [ $this, 'restore_image_sizes' ] );
        add_filter( 'wp_handle_upload', [ $this, 'restore_files_upload_dir' ] );
    }
    
    public function files_upload_dir( $uploads ) {
        if ( is_admin() ) {
            $submission_id = $_POST['post_id'];
        } else {
            $submission_id = $_POST['_acf_post_id'];
        }
        $replace_base       = '/wp-content/uploads/applications/app-' . $submission_id;
        $uploads['path']    = str_replace( '/wp-content/uploads', $replace_base, $uploads['path'] );
        $uploads['url']     = str_replace( '/wp-content/uploads', $replace_base, $uploads['url'] );
        $uploads['basedir'] = str_replace( '/wp-content/uploads', $replace_base, $uploads['basedir'] );
        $uploads['baseurl'] = str_replace( '/wp-content/uploads', $replace_base, $uploads['baseurl'] );
        
        //subdir  $subdir = "/$y/$m";
        
        return $uploads;
    }
    
    public function prevent_intermediate_sizes_creation( $sizes ) {
        return array();
    }
    
    public function restore_image_sizes( $file ) {
        remove_filter( 'intermediate_image_sizes_advanced', [ $this, 'prevent_intermediate_sizes_creation' ] );
    }
    
    public function restore_files_upload_dir( $upload ) {
        remove_filter( 'upload_dir', [ $this, 'files_upload_dir' ], 20 );
        
        return $upload;
    }
    
    public static function getInstance() {
        if ( ! self::$_instance ) {
            self::$_instance = new self();
        }
        
        return self::$_instance;
    }
    
    public static function rootDir() {
        return self::getInstance()->dir;
    }
    
    public function addReviewMenuItem( $items, $args ) {
        if ( $args->theme_location === 'member-zone-pages-menu' ) {
            $review           = new EASLAppReview( false );
            $loggedInUserData = easl_mz_get_logged_in_member_data();
            $validProgrammes  = $review->getProgrammesUserCanReview( $loggedInUserData['email1'] );
            if ( count( $validProgrammes ) > 0 ) {
                $items .= '<li><a href="' . $review->getUrl( EASLAppReview::PAGE_PROGRAMME ) . '">Review applications</a></li>';
            }
        }
        
        return $items;
    }
    
    
    private function addOptionsPage() {
        if ( function_exists( 'acf_add_options_page' ) ) {
            acf_add_options_page( array(
                'page_title'  => 'Application system options',
                'menu_slug'   => self::OPTIONS_PAGE_SLUG,
                'parent_slug' => 'edit.php?post_type=programme',
                'capability'  => 'manage_options',
                'redirect'    => false,
            ) );
        }
    }
    
    public function restrict_combine_schools_selection($valid, $value, $field, $input) {
        if (count($value) > 2) {
            $valid = 'Max 2 schools are allowed.';
        }
        return $valid;
    }
    
    public function display_sorted_combine_schools_selection($field) {
        if(!$field['value']) {
            return $field;
        }
        $choices = [];
        $org_choices = $field['choices'];
        foreach ($field['value'] as $value) {
            if(array_key_exists($value, $org_choices)) {
                $choices[$value] = $org_choices[$value];
                unset($org_choices[$value]);
            }
        }
        $field['choices'] =  array_merge($choices, $org_choices);
        return $field;
    }
    public function addOptionsFields() {
        acf_add_local_field_group( [
            'key'      => 'key_pages',
            'title'    => 'Key pages',
            'fields'   => [
                [
                    'key'   => 'apply_page',
                    'label' => 'Apply page',
                    'name'  => 'apply_page',
                    'type'  => 'page_link'
                ]
            ],
            'location' => [
                [
                    [
                        'param'    => 'options_page',
                        'operator' => '==',
                        'value'    => self::OPTIONS_PAGE_SLUG
                    ]
                ]
            ]
        ] );
    }
    
    private function registerPostTypes() {
        register_post_type( 'programme', [
            'labels'       => [
                'name'          => 'Programmes',
                'singular_name' => 'Programme',
                'add_new'       => 'Add New Programme',
            ],
            'supports'     => [ 'title', 'editor', 'excerpt' ],
            'public'       => false,
            'show_ui'      => true,
            'show_in_menu' => true,
            'rewrite'      => false,
            'menu_icon'    => 'dashicons-calendar',
        ] );
        
        register_post_type( 'submission-review', [
            'labels'       => [
                'name'          => 'Reviews',
                'singular_name' => 'Review'
            ],
            'public'       => false,
            'show_ui'      => false,
            'show_in_menu' => false,
            'rewrite'      => false,
        ] );
        
        register_post_type( 'submission', [
            'labels'       => [
                'name'          => 'Submissions',
                'singular_name' => 'Submission'
            ],
            'public'       => false,
            'show_ui'      => true,
            'show_in_menu' => 'edit.php?post_type=programme',
            'rewrite'      => false,
        ] );
    }
    
    private function registerTaxonomies() {
        register_taxonomy( 'programme-category', 'programme', [
                'labels'             => [
                    'name' => 'Programme category'
                ],
                'hierarchical'       => true,
                'publicly_queryable' => false,
                'show_ui'            => true,
                'show_in_menu'       => true,
                'show_in_nav_menus'  => false,
                'show_admin_column'  => true,
                'rewrite'            => false,
            ]
        );
    }
    
    public static function acfPostTypeLocation( $postType ) {
        return [
            [
                [
                    'param'    => 'post_type',
                    'operator' => '==',
                    'value'    => $postType
                ]
            ]
        ];
    }
    
    private function createFields() {
        require_once( $this->dir . 'fields/EASLApplicationField.php' );
        require_once( $this->dir . 'fields/EASLApplicationFieldSet.php' );
        
        require_once( $this->dir . 'fields/fieldsets/AbstractFieldContainer.php' );
        
        $fieldsets = [
            'fellowship'            => 'FellowshipFieldContainer',
            'mentorship'            => 'MentorshipFieldContainer',
            'registry-grant'        => 'RegistryGrantFieldContainer',
            'sponsorship'           => 'EndorsementFieldContainer',
            'easl-schools'          => 'EASLSchoolsFieldContainer',
            'easl-schools-all'      => 'EASLSchoolsCombinedFieldContainer',
            'masterclass'           => 'MasterclassFieldContainer',
            'daniel-alagille-award' => 'DanielAlagilleAwardFieldContainer',
            'emerging-leader-award' => 'EmergingLeaderAwardFieldContainer',
        ];
        foreach ( $fieldsets as $key => $className ) {
            require_once( $this->dir . 'fields/fieldsets/' . $className . '.php' );
            $fieldset                          = new $className( $key );
            $this->submissionFieldSets[ $key ] = $fieldset;
        }
        
        require_once( $this->dir . 'fields/define_fields.php' );
    }
    
    /**
     * @param $programmeId
     *
     * @return AbstractFieldContainer
     */
    public function getProgrammeFieldSetContainer( $programmeId ) {
        $programmeCategory = get_field( 'programme-category', $programmeId );
        
        return $this->submissionFieldSets[ $programmeCategory ];
    }
    
    public function init() {
        $this->addOptionsPage();
        $this->registerPostTypes();
        $this->registerTaxonomies();
        
        $this->handleCSVExportRequest();
        if ( ! empty( $_GET['easl_app_priv_file'] ) ) {
            $this->handle_file_access();
            die();
        }
        if(isset($_GET['mhm_fix_school_choices'])) {
            //$this->fix_school_choices();
        }
    }
    
    /**
     * @param int $postid
     * @param WP_Post $post
     */
    public function clean_submissions_after_post_delete($postid, $post) {
        WP_Filesystem();
        /**
         * @var WP_Filesystem_Direct $wp_filesystem
         */
        global $wp_filesystem;
        if ( 'submission' == $post->post_type ) {
            $dir = WP_CONTENT_DIR . '/uploads/applications/app-' . $postid;
            $wp_filesystem->rmdir( $dir, true );
        } elseif ( 'programme' == $post->post_type ) {
            $submissions = get_posts( [
                'post_type'      => 'submission',
                'post_status'    => 'publish',
                'posts_per_page' => - 1,
                'fields'         => 'ids',
                'meta_query'     => [
                    [
                        'key'     => 'programme_id',
                        'value'   => $postid,
                        'compare' => '='
                    ],
                ]
            ] );
            if ( $submissions ) {
                foreach ( $submissions as $submission_id ) {
                    wp_delete_post($submission_id, true);
                }
            }
        }
    }
    protected function fix_school_choices() {
        $file_handle = fopen( self::rootDir() . 'school-choices.csv', "r" );
        if ( ! $file_handle ) {
            die( 'File not found' );
        }
        $csv_data   = [];
        $school_map = [
            'Padua'        => 'amsterdam',
            'London'       => 'barcelona',
            'Basic London' => 'frankfurt',
            'Freiburg'     => 'hamburg',
        ];
        while ( ( $data = fgetcsv( $file_handle, 1000, "," ) ) !== false ) {
            $csv_data[ $data[0] ] = [ $school_map[ $data[1] ], $school_map[ $data[2] ] ];
        }
        fclose( $file_handle );
        foreach($csv_data as $email => $application) {
            $submission = get_posts([
                'post_type' => 'submission',
                'post_status' => 'publish',
                'posts_per_page' => -1,
                'fields' => 'ids',
                'meta_query' => [
                    'relation' => 'AND',
                    [
                        'key' => 'programme_id',
                        'value' => 27839,
                        'compare' => '='
                    ],
                    [
                        'key' => 'member_data',
                        'value' => $email,
                        'compare' => 'LIKE'
                    ],
                    [
                        'key' => 'submitted_timestamp',
                        'compare' => 'EXISTS'
                    ]
                ]
            ]);
            if(!$submission){
                continue;
            }
            update_post_meta($submission[0], 'easl-schools-all_programme_information_schools', $application);
        }
        die();
    }
    
    protected function handle_file_access() {
        if ( 0 !== strpos( $_GET['easl_app_priv_file'], 'app-' ) ) {
            return false;
        }
        $file_name_parts = explode( '/', $_GET['easl_app_priv_file'] );
        if ( count( $file_name_parts ) < 2 ) {
            return false;
        }
        $app_id = str_replace( 'app-', '', $file_name_parts[0] );
        
        if ( ! $this->current_user_can_access_app_file( $app_id ) ) {
            die( "You don't have sufficient permission to access this file" );
        }
        
        $upload_dir     = wp_get_upload_dir();
        $file_full_path = $upload_dir['basedir'] . '/applications/' . $_GET['easl_app_priv_file'];
        if ( ! file_exists( $file_full_path ) ) {
            return false;
        }
        $fp = fopen( $file_full_path, 'rb' );
        header( 'Content-Type: ' . mime_content_type( $file_full_path ) );
        header( 'Content-Length: ' . filesize( $file_full_path ) );
        header("X-Robots-Tag: noindex, nofollow", true);
        fpassthru( $fp );
        fclose( $fp );
        
        return true;
    }
    
    public function current_user_can_access_app_file( $app_id ) {
        if ( current_user_can( 'manage_options' ) ) {
            return true;
        }
        if ( ! easl_mz_is_member_logged_in() ) {
            return false;
        }
        $sessionData = easl_mz_get_current_session_data();
        
        if ( ! empty( $sessionData['member_id'] ) && ( $sessionData['member_id'] == get_post_meta( $app_id, 'member_id', true ) ) ) {
            return true;
        }
        
        $reviewers = get_post_meta( get_post_meta( $app_id, 'programme_id', true ), 'reviewers', true );
        if ( $reviewers ) {
            $reviewers = wp_list_pluck( $reviewers, 'email' );
        }
        if ( $reviewers && is_array( $reviewers ) && ! empty( $sessionData['member_id'] ) && in_array( $sessionData['member_data']['email1'], $reviewers ) ) {
            return true;
        }
        return false;
    }
    
    private function handleCSVExportRequest() {
        if ( isset( $_GET['csvExport'] ) ) {
            require_once( $this->dir . 'lib/EASLAppReview.php' );
            $review      = new EASLAppReview( true );
            $programmeId = $_GET['csvExport'];
            $review->exportCSV( $programmeId, $this->getProgrammeFieldSetContainer( $programmeId ) );
        }
    }
    
    /**
     * Admin pages
     */
    public function adminMenu() {
        
        require_once( $this->dir . 'lib/EASLAppReview.php' );
        $review = new EASLAppReview( true );
        $review->configureAdminPages();
    }
    public function application_assets() {
        wp_enqueue_style('easl-application-style', plugin_dir_url( __FILE__ ) . 'assets/css/application.css', [], EASL_APPLICATION_VERSION);
        if ( !is_page( 'review-applications' ) ) {
            wp_enqueue_style('dragula', plugin_dir_url( __FILE__ ) . 'assets/lib/dragula/dragula.min.css');
            wp_enqueue_script('dragula', plugin_dir_url( __FILE__ ) . 'assets/lib/dragula/dragula.min.js');
            wp_enqueue_script('application-js', plugin_dir_url( __FILE__ ) . 'assets/js/application.js', ['dragula', 'jquery'], EASL_APPLICATION_VERSION, true);
        }else{
            wp_enqueue_script('review-js', plugin_dir_url( __FILE__ ) . 'assets/js/review.js', [ 'jquery'], EASL_APPLICATION_VERSION, true);
        }
    }
    public function pageContent() {
        if ( is_page( 'review-applications' ) ) {
            $review = new EASLAppReview( false );
            if ( isset( $_GET['submissionId'] ) ) {
                $review->reviewSubmissionPage( $_GET['submissionId'] );
            } else {
                $review->programmePage( isset( $_GET['programmeId'] ) ? $_GET['programmeId'] : null );
            }
        } else {
            require_once( $this->templateDir . 'apply.php' );
        }
    }
    
    public static function getSlug( $string ) {
        return strtolower( trim( preg_replace( '/[^A-Za-z0-9-]+/', '-', $string ), '-' ) );
    }
    
    public static function makeOptionsList( $options ) {
        $out = [];
        
        foreach ( $options as $option ) {
            $out[ self::getSlug( $option ) ] = $option;
        }
        
        return $out;
    }
    
    public static function getContactEmail( $programmeId ) {
        $categories = wp_get_post_terms( $programmeId, 'programme-category' );
        
        $from = 'fellowships@easloffice.eu';
        foreach ( $categories as $category ) {
            if ( $category->slug == 'schools-masterclasses' ) {
                $from = 'schools@easloffice.eu';
            }
        }
        
        return $from;
    }
    
    public static function renderEmail( $templatePath, $vars = [] ) {
        ob_start();
        extract( $vars );
        include( $templatePath );
        $output = ob_get_contents();
        ob_end_clean();
        
        return $output;
    }
    
    public static function sendEmail( $to, $subject, $message, $bcc = 'fellowships@easloffice.eu' ) {
        $headers = [
            'Bcc: ' . $bcc,
        ];
        add_filter('wp_mail_from', 'easl_app_email_form_email', 100);
        add_filter('wp_mail_from_name', 'easl_app_email_form_name', 100);
        add_filter('wp_mail_content_type', 'easl_app_email_content_type_html', 100);
        
        wp_mail( $to, $subject, $message, $headers );
        
	    remove_filter('wp_mail_content_type', 'easl_app_email_content_type_html', 100);
	    remove_filter('wp_mail_from', 'easl_app_email_form_email', 100);
	    remove_filter('wp_mail_from_name', 'easl_app_email_form_name', 100);
    }
    
    public function registerSubmissionMetaBox() {
        add_meta_box( 'sbmb_member_details', 'Member Details', [
            $this,
            'submissionMemberDetailsMetaBox'
        ], [ 'submission' ], 'advanced', 'high' );
    }
    
    public function submissionMemberDetailsMetaBox( $post ) {
        $member_data = get_post_meta( $post->ID, 'member_data', true );
        echo '<div class="sub-member-details-wrap">';
        echo '<table id="sub-member-details-table" class="widefat" style="margin-bottom: 20px">';
        foreach ( EASLAppSubmission::MEMBER_DATA_FIELDS as $key => $label ) {
            $value = '';
            if ( ! empty( $member_data[ $key ] ) ) {
                $value = $member_data[ $key ];
            }
            if('dotb_job_function' == $key) {
                $value = easl_mz_get_list_item_name('job_functions', $member_data[ $key ]);
            }elseif('primary_address_country' == $key){
                $value = easl_mz_get_country_name( $member_data[ $key ]);
            }
            
            echo '<tr>';
            echo '<td>' . $label . '</td>';
            echo '<td>' . $value . '</td>';
            echo '</tr>';
        }
        echo '</table>';
        echo '<a href="#" id="sync-member-data-button" class="button" data-id="' . $post->ID . '" data-nonce="' . wp_create_nonce( 'sync_member_data_for_sub_' . $post->ID ) . '">Sync member data from CRM</a>';
        echo '</div>';
    }
    
    public function admin_scripts() {
        $screen = get_current_screen();
        if ( ! empty( $screen->post_type ) && 'submission' == $screen->post_type ) {
            wp_enqueue_script( 'submission-admin', plugin_dir_url( __FILE__ ) . '/assets/js/admin/submissions.js', [ 'jquery' ], time(), true );
        }
    }
    
    public function sync_submission_member_data() {
        if ( empty( $_POST['_wpnonce'] ) || empty( $_POST['sub_id'] ) ) {
            die( '' );
        }
        if ( ! wp_verify_nonce( $_POST['_wpnonce'], 'sync_member_data_for_sub_' . $_POST['sub_id'] ) ) {
            die( '' );
        }
        $sub_id    = $_POST['sub_id'];
        $member_id = get_post_meta( $sub_id, 'member_id', true );
        if ( ! $member_id ) {
            die( '' );
        }
        $member_data = easl_mz_get_member_data( $member_id );
        if ( ! $member_data ) {
            die( '' );
        }
        $member_data_to_save = array();
        $output              = '';
        foreach ( EASLAppSubmission::MEMBER_DATA_FIELDS as $key => $label ) {
            $value = '';
            if ( ! empty( $member_data[ $key ] ) ) {
                $value = $member_data[ $key ];
            }
            $member_data_to_save[ $key ] = $value;
            $output                      .= '<tr>';
            $output                      .= '<td>' . $label . '</td>';
            $output                      .= '<td>' . $value . '</td>';
            $output                      .= '</tr>';
        }
        update_post_meta( $sub_id, 'member_data', $member_data_to_save );
        wp_update_post( [
            'ID'         => $sub_id,
            'post_title' => $member_data_to_save['first_name'] . ' ' . $member_data_to_save['last_name']
        ] );
        echo $output;
        die();
    }
    
    public static function findInArray( $array, callable $callable ) {
        $matches = array_filter( $array, $callable );
        if ( $matches ) {
            return end( $matches );
        }
        
        return null;
    }
    
    /**
     * @param string $template template php file wiht .php extension
     * @param array $data
     *
     * @return bool
     */
    public static function load_template( $template, $data = [] ) {
        $template_path = EASLApplicationsPlugin::getInstance()->templateDir . $template;
        if ( ! file_exists( $template_path ) ) {
            return false;
        }
        extract( $data );
        include( $template_path );
        return true;
    }
}

$instance = EASLApplicationsPlugin::getInstance();