<?php
// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
    die( '-1' );
}

class EASL_MZ_Documents {
    
    /**
     * Core singleton class
     * @var self - pattern realization
     */
    private static $_instance;
    
    
    /**
     * Get the instance of EASL_MZ_Documents
     *
     * @return self
     */
    public static function get_instance() {
        if ( ! ( self::$_instance instanceof self ) ) {
            self::$_instance = new self();
        }
        
        return self::$_instance;
    }
    
    /**
     * Constructor loads API functions, defines paths and adds required wp actions
     *
     * @since  1.0
     */
    private function __construct() {
        add_action( 'init', [ $this, 'init' ] );
        add_filter( 'acf/upload_prefilter/type=file', [ $this, 'change_doc_file_path' ], 10, 3 );
    }
    
    public function init() {
        $this->register_cpt();
        $this->register_acf_fields();
        
        if ( ! empty( $_GET['mzmd_file'] ) ) {
            $this->handle_file_access();
            die();
        }
    }
    
    public function register_cpt() {
        register_post_type( 'mz_doc', array(
            'labels'              => array(
                'name'               => __( 'MZ Documents', 'total-child' ),
                'singular_name'      => __( 'MZ Document', 'total-child' ),
                'add_new'            => __( 'Add New', 'total-child' ),
                'add_new_item'       => __( 'Add New Document', 'total-child' ),
                'edit_item'          => __( 'Edit Document', 'total-child' ),
                'new_item'           => __( 'Add New Document', 'total-child' ),
                'view_item'          => __( 'View Document', 'total-child' ),
                'search_items'       => __( 'Search Document', 'total-child' ),
                'not_found'          => __( 'No Document Found', 'total-child' ),
                'not_found_in_trash' => __( 'No Document Found In Trash', 'total-child' )
            ),
            'public'              => false,
            'hierarchical'        => false,
            'exclude_from_search' => true,
            'publicly_queryable'  => false,
            'show_ui'             => true,
            'show_in_nav_menus'   => false,
            'show_in_admin_bar'   => false,
            'show_in_rest'        => false,
            'menu_position'       => 25,
            'capability_type'     => 'post',
            'has_archive'         => false,
            'rewrite'             => false,
            'supports'            => array(
                'title',
                'thumbnail',
            ),
        ) );
        
        register_taxonomy( 'mz_doc_category', array( 'mz_doc' ), array(
            'labels'            => array(
                'name'                       => __( 'Category', 'total' ),
                'singular_name'              => __( 'Category', 'total' ),
                'menu_name'                  => __( 'Category', 'total' ),
                'search_items'               => __( 'Search', 'total' ),
                'popular_items'              => __( 'Popular', 'total' ),
                'all_items'                  => __( 'All', 'total' ),
                'parent_item'                => __( 'Parent', 'total' ),
                'parent_item_colon'          => __( 'Parent', 'total' ),
                'edit_item'                  => __( 'Edit', 'total' ),
                'update_item'                => __( 'Update', 'total' ),
                'add_new_item'               => __( 'Add New', 'total' ),
                'new_item_name'              => __( 'New', 'total' ),
                'separate_items_with_commas' => __( 'Separate with commas', 'total' ),
                'add_or_remove_items'        => __( 'Add or remove', 'total' ),
                'choose_from_most_used'      => __( 'Choose from the most used', 'total' ),
            ),
            'public'            => false,
            'show_in_nav_menus' => false,
            'show_ui'           => true,
            'show_admin_column' => true,
            'show_tagcloud'     => false,
            'hierarchical'      => false,
            'rewrite'           => false,
            'query_var'         => false,
        ) );
        
        
        register_taxonomy( 'mz_doc_group', array( 'mz_doc' ), array(
            'labels'            => array(
                'name'                       => __( 'Group', 'total' ),
                'singular_name'              => __( 'Group', 'total' ),
                'menu_name'                  => __( 'Group', 'total' ),
                'search_items'               => __( 'Search', 'total' ),
                'popular_items'              => __( 'Popular', 'total' ),
                'all_items'                  => __( 'All', 'total' ),
                'parent_item'                => __( 'Parent', 'total' ),
                'parent_item_colon'          => __( 'Parent', 'total' ),
                'edit_item'                  => __( 'Edit', 'total' ),
                'update_item'                => __( 'Update', 'total' ),
                'add_new_item'               => __( 'Add New', 'total' ),
                'new_item_name'              => __( 'New', 'total' ),
                'separate_items_with_commas' => __( 'Separate with commas', 'total' ),
                'add_or_remove_items'        => __( 'Add or remove', 'total' ),
                'choose_from_most_used'      => __( 'Choose from the most used', 'total' ),
            ),
            'public'            => false,
            'show_in_nav_menus' => false,
            'show_ui'           => true,
            'show_admin_column' => true,
            'show_tagcloud'     => false,
            'hierarchical'      => false,
            'rewrite'           => false,
            'query_var'         => false,
        ) );
    }
    
    public function register_acf_fields() {
        if ( function_exists( 'acf_add_local_field_group' ) ):
            acf_add_local_field_group( array(
                'key'                   => 'group_my_documents_access',
                'title'                 => 'My documents access',
                'fields'                => array(
                    array(
                        'key'               => 'field_mz_md_access_type',
                        'label'             => 'Access type',
                        'name'              => 'mz_md_access_type',
                        'type'              => 'radio',
                        'instructions'      => '',
                        'required'          => 0,
                        'conditional_logic' => 0,
                        'wrapper'           => array(
                            'width' => '',
                            'class' => '',
                            'id'    => '',
                        ),
                        'choices'           => array(
                            'all'     => 'Any member',
                            'include' => 'Anyone including',
                            'exclude' => 'Anyone excluding',
                        ),
                        'allow_null'        => 0,
                        'other_choice'      => 0,
                        'default_value'     => 'all',
                        'layout'            => 'vertical',
                        'return_format'     => 'value',
                        'save_other_choice' => 0,
                    ),
                    array(
                        'key'               => 'field_mz_md_member_emails',
                        'label'             => 'Member emails',
                        'name'              => 'mz_md_member_emails',
                        'type'              => 'repeater',
                        'instructions'      => '',
                        'required'          => 0,
                        'conditional_logic' => array(
                            array(
                                array(
                                    'field'    => 'field_mz_md_access_type',
                                    'operator' => '!=',
                                    'value'    => 'all',
                                ),
                            ),
                        ),
                        'wrapper'           => array(
                            'width' => '',
                            'class' => '',
                            'id'    => '',
                        ),
                        'collapsed'         => '',
                        'min'               => 0,
                        'max'               => 0,
                        'layout'            => 'table',
                        'button_label'      => '',
                        'sub_fields'        => array(
                            array(
                                'key'               => 'field_mz_md_email',
                                'label'             => 'email',
                                'name'              => 'email',
                                'type'              => 'email',
                                'instructions'      => '',
                                'required'          => 1,
                                'conditional_logic' => 0,
                                'wrapper'           => array(
                                    'width' => '',
                                    'class' => '',
                                    'id'    => '',
                                ),
                                'default_value'     => '',
                                'placeholder'       => '',
                                'prepend'           => '',
                                'append'            => '',
                            ),
                        ),
                    ),
                ),
                'location'              => array(
                    array(
                        array(
                            'param'    => 'taxonomy',
                            'operator' => '==',
                            'value'    => 'mz_doc_group',
                        ),
                    ),
                ),
                'menu_order'            => 0,
                'position'              => 'normal',
                'style'                 => 'default',
                'label_placement'       => 'top',
                'instruction_placement' => 'label',
                'hide_on_screen'        => '',
                'active'                => true,
                'description'           => '',
                'show_in_rest'          => 0,
            ) );
            
            acf_add_local_field_group( array(
                'key'                   => 'group_mz_md_access_groups',
                'title'                 => 'My documents access groups',
                'fields'                => array(
                    array(
                        'key'               => 'field_mz_md_group',
                        'label'             => 'Groups',
                        'name'              => 'mz_md_groups',
                        'type'              => 'taxonomy',
                        'instructions'      => '',
                        'required'          => 0,
                        'conditional_logic' => 0,
                        'wrapper'           => array(
                            'width' => '',
                            'class' => '',
                            'id'    => '',
                        ),
                        'taxonomy'          => 'mz_doc_group',
                        'field_type'        => 'multi_select',
                        'allow_null'        => 0,
                        'add_term'          => 0,
                        'save_terms'        => 1,
                        'load_terms'        => 1,
                        'return_format'     => 'id',
                        'multiple'          => 0,
                    ),
                    array(
                        'key'               => 'field_mz_md_cat',
                        'label'             => 'Category',
                        'name'              => 'mz_md_cat',
                        'type'              => 'taxonomy',
                        'instructions'      => '',
                        'required'          => 0,
                        'conditional_logic' => 0,
                        'wrapper'           => array(
                            'width' => '',
                            'class' => '',
                            'id'    => '',
                        ),
                        'taxonomy'          => 'mz_doc_category',
                        'field_type'        => 'multi_select',
                        'allow_null'        => 0,
                        'add_term'          => 0,
                        'save_terms'        => 1,
                        'load_terms'        => 1,
                        'return_format'     => 'id',
                        'multiple'          => 0,
                    ),
                    array(
                        'key'               => 'field_mz_md_file',
                        'label'             => 'File',
                        'name'              => 'mz_md_file',
                        'type'              => 'file',
                        'instructions'      => '',
                        'required'          => 1,
                        'conditional_logic' => 0,
                        'wrapper'           => array(
                            'width' => '',
                            'class' => '',
                            'id'    => '',
                        ),
                        'return_format'     => 'url',
                        'library'           => 'all',
                        'min_size'          => '',
                        'max_size'          => '',
                        'mime_types'        => '',
                        '_easl_mz_doc_file' => true,
                    ),
                ),
                'location'              => array(
                    array(
                        array(
                            'param'    => 'post_type',
                            'operator' => '==',
                            'value'    => 'mz_doc',
                        ),
                    ),
                ),
                'menu_order'            => 0,
                'position'              => 'normal',
                'style'                 => 'default',
                'label_placement'       => 'top',
                'instruction_placement' => 'label',
                'hide_on_screen'        => '',
                'active'                => true,
                'description'           => '',
                'show_in_rest'          => 0,
            ) );
        
        endif;
    }
    
    public function get_docs_by_email( $email ) {
        $terms_args = [
            'fields'     => 'ids',
            'taxonomy'   => 'mz_doc_group',
            'hide_empty' => false,
            'meta_query' => [
                'relation' => 'OR',
                [
                    'key'     => 'mz_md_access_type',
                    'value'   => 'all',
                    'compare' => '='
                ],
                [
                    'relation' => 'AND',
                    [
                        'key'     => 'mz_md_access_type',
                        'value'   => 'include',
                        'compare' => '='
                    ],
                    [
                        'key'     => 'mz_md_member_emails_$_email',
                        'value'   => $email,
                        'compare' => '='
                    ]
                ],
                [
                    'relation' => 'AND',
                    [
                        'key'     => 'mz_md_access_type',
                        'value'   => 'exclude',
                        'compare' => '='
                    ],
                    [
                        'key'     => 'mz_md_member_emails_$_email',
                        'value'   => $email,
                        'compare' => '!='
                    ]
                ]
            ]
        ];
        add_filter( 'terms_clauses', [ $this, 'acf_terms_query_where_filter' ] );
        $groups_inc = get_terms( $terms_args );
        remove_filter( 'terms_clauses', [ $this, 'acf_terms_query_where_filter' ] );
        
        if ( ! $groups_inc ) {
            return false;
        }
        $query_args = [
            'post_type'      => 'mz_doc',
            'posts_per_page' => - 1,
            'post_status'    => 'publish',
            'order'          => 'DESC',
            'orderby'        => 'date',
            'tax_query'      => [
                [
                    'taxonomy' => 'mz_doc_group',
                    'field'    => 'term_id',
                    'operator' => 'IN',
                    'terms'    => $groups_inc
                ]
            ]
        ];
        $file_data  = [];
        $doc_query  = new WP_Query( $query_args );
        while ( $doc_query->have_posts() ) {
            $doc_query->the_post();
            $file_data[] = [
                'title'    => get_the_title(),
                'file'     => get_field( 'mz_md_file' ),
                'category' => $this->get_doc_category( get_the_ID() )
            ];
        }
        
        return $file_data;
    }
    
    public function acf_terms_query_where_filter( $clauses ) {
        if ( empty( $clauses['where'] ) ) {
            return $clauses;
        }
        $clauses['where'] = str_replace( "meta_key = 'mz_md_member_emails_$", "meta_key LIKE 'mz_md_member_emails_%", $clauses['where'] );
        
        return $clauses;
    }
    
    public function get_doc_category( $doc_id ) {
        $cats = get_the_terms( $doc_id, 'mz_doc_category' );
        
        return $cats ? $cats[0]->name : '';
    }
    
    
    public function change_doc_file_path( $errors, $file, $field ) {
        if ( empty( $field['_easl_mz_doc_file'] ) ) {
            return $errors;
        }
        add_filter( 'upload_dir', [ $this, 'files_upload_dir' ], 20 );
        add_filter( 'intermediate_image_sizes_advanced', [ $this, 'prevent_intermediate_sizes_creation' ] );
        add_action( 'wp_create_file_in_uploads', [ $this, 'restore_image_sizes' ] );
        add_filter( 'wp_handle_upload', [ $this, 'restore_files_upload_dir' ] );
        
        return $errors;
    }
    
    public function files_upload_dir( $uploads ) {
        if ( is_admin() ) {
            $doc_id = $_POST['post_id'];
        } else {
            $doc_id = $_POST['_acf_post_id'];
        }
        $replace_base       = '/wp-content/uploads/mz-docs/doc-' . $doc_id;
        $uploads['path']    = str_replace( '/wp-content/uploads', $replace_base, $uploads['path'] );
        $uploads['url']     = str_replace( '/wp-content/uploads', $replace_base, $uploads['url'] );
        $uploads['basedir'] = str_replace( '/wp-content/uploads', $replace_base, $uploads['basedir'] );
        $uploads['baseurl'] = str_replace( '/wp-content/uploads', $replace_base, $uploads['baseurl'] );
        
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
    
    protected function handle_file_access() {
        if ( 0 !== strpos( $_GET['mzmd_file'], 'doc-' ) ) {
            return false;
        }
        $file_name_parts = explode( '/', $_GET['mzmd_file'] );
        
        if ( count( $file_name_parts ) < 2 ) {
            return false;
        }
        $doc_id = str_replace( 'doc-', '', $file_name_parts[0] );
        if ( ! $this->current_member_can_access_doc_file( $doc_id ) ) {
            die( "You don't have sufficient permission to access this file" );
        }
        
        $upload_dir     = wp_get_upload_dir();
        $file_full_path = $upload_dir['basedir'] . '/mz-docs/' . $_GET['mzmd_file'];
        if ( ! file_exists( $file_full_path ) ) {
            return false;
        }
        $fp = fopen( $file_full_path, 'rb' );
        header( 'Content-Type: ' . mime_content_type( $file_full_path ) );
        header( 'Content-Length: ' . filesize( $file_full_path ) );
        header( "X-Robots-Tag: noindex, nofollow", true );
        fpassthru( $fp );
        fclose( $fp );
        
        return true;
    }
    
    public function current_member_can_access_doc_file( $doc_id ) {
        if ( current_user_can( 'manage_options' ) ) {
            return true;
        }
        if ( ! easl_mz_is_member_logged_in() ) {
            return false;
        }
        $member_email = easl_mz_get_manager()->getSession()->get_current_members_login();
        if ( ! $member_email ) {
            return false;
        }
        $docs_access_groups = get_the_terms( $doc_id, 'mz_doc_group' );
        
        $allowed = false;
        foreach ( $docs_access_groups as $access_group ) {
            $access_type   = get_field( 'mz_md_access_type', 'mz_doc_group_' . $access_group->term_id );
            $access_emails = get_field( 'mz_md_member_emails', 'mz_doc_group_' . $access_group->term_id );
            $access_emails = is_array( $access_emails ) ? wp_list_pluck( $access_emails, 'email' ) : [];

            if ( 'all' == $access_type ) {
                return true;
            }
            if ( 'include' == $access_type && is_array( $access_emails ) && in_array( $member_email, $access_emails ) ) {
                $allowed = true;
                break;
            }
            if ( 'exclude' == $access_emails && is_array( $access_emails ) && ! in_array( $member_email, $access_emails ) ) {
                $allowed = true;
                break;
            }
        }
        
        return $allowed;
    }
}
