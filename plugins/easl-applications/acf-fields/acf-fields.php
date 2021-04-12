<?php
// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
    die( '-1' );
}

class EASL_APP_ACF_Custom_Fields {
    
    
    /**
     * @var self
     */
    protected static $_instance;
    
    protected $settings;
    protected $types = array();
    
    public static function getInstance() {
        if ( ! self::$_instance ) {
            self::$_instance = new self();
        }
        
        return self::$_instance;
    }
    
    private function __construct() {
        $this->settings = [
            'version' => '1.0.0',
            'url'     => plugin_dir_url( __FILE__ ),
            'path'    => plugin_dir_path( __FILE__ )
        ];
        add_action( 'acf/include_field_types', [ $this, 'register_field_types' ] );
    }
    
    public function register_field_types() {
        $fields = array(
            'application_file'
        );
        foreach ( $fields as $field ) {
            $name_chunks = explode( '_', $field );
            include_once $this->settings['path'] . '/fields/' . implode( '-', $name_chunks ) . '.php';
            $field_class_name = 'EASL_APP_ACF_Field_' . implode( '_', array_map( 'ucfirst', $name_chunks ) );
            if ( class_exists( $field_class_name ) ) {
                $this->types[ $field ] = new $field_class_name( $this->settings );
            }
        }
    }
    
    public function get_field_instance( $field ) {
        if ( isset( $this->fields_instances[ $field ] ) ) {
            return $this->fields_instances[ $field ];
        }
        
        return null;
    }
}

EASL_APP_ACF_Custom_Fields::getInstance();