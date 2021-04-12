<?php
// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}
class EASL_APP_ACF_Field_Application_File extends acf_field_file {
    /*
	*  __construct
	*
	*  This function will setup the field type data
	*
	*  @type	function
	*  @date	5/03/2014
	*  @since	5.0.0
	*
	*  @param	n/a
	*  @return	n/a
	*/
    
    function initialize() {
        
        // vars
        $this->name = 'application_file';
        $this->label = __("Application File",'acf');
        $this->category = 'content';
        $this->defaults = array(
            'return_format'	=> 'array',
            'library' 		=> 'all',
            'min_size'		=> 0,
            'max_size'		=> 0,
            'mime_types'	=> ''
        );
        
        // filters
        add_filter('get_media_item_args', array($this, 'get_media_item_args'));
    }
}