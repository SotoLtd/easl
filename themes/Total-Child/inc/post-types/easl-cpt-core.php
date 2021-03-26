<?php
// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
    die( '-1' );
}

class EASL_CPT {
    
    protected $slugs;
    
    protected $cpt_args;
    
    protected $tax_args;
    
    /**
     * Core singleton class
     * @var self - pattern realization
     */
    private static $_instance;
    
    /**
     * Get the instance of EASL_CPT
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
    protected function __construct() {
        $this->load_data();
        add_action( 'init', array( $this, 'init' ), 0 );
    }
    
    protected function load_data() {
        $this->slugs    = array();
        $this->cpt_args = array();
        $this->tax_args = array();
    }
    
    public function get_slug( $type = 'cpt' ) {
        return ! empty( $this->slugs[ $type ] ) ? $this->slugs[ $type ] : '';
    }
    
    protected function init() {
    
    }
}