<?php
// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}
class EASL_Award_Config {
	protected static $slugs = array(
		'type' => 'award',
		'award_group' => 'award_group',
		'award_year' => 'award_year'
	);
	public function __construct() {
		add_action( 'init', array( 'EASL_Award_Config', 'register_post_type' ), 0 );
		add_action( 'init', array( 'EASL_Award_Config', 'register_tax' ), 0 );
		add_filter('acf/get_field_group_style', array('EASL_Award_Config', 'hide_on_edit_screen'));
	}
	/**
	 * Get post type slug
	 * @return string
	 */
	public static function get_slug(){
		return self::$slugs['type'];
	}
	/**
	 * Get award group slug
	 * @return string
	 */
	public static function get_award_group_slug (){
		return self::$slugs['award_group'];
	}
	/**
	 * Get award year slug
	 * @return string
	 */
	public static function get_award_year_slug (){
		return self::$slugs['award_year'];
	}
	/**
	 * Register post type.
	 */
	public static function register_post_type() {
		register_post_type( self::get_slug(), array(
			'labels' => array(
				'name' => __( 'Awards', 'total-child' ),
				'singular_name' => __( 'Award', 'total-child' ),
				'add_new' => __( 'Add New', 'total-child' ),
				'add_new_item' => __( 'Add New Award', 'total-child' ),
				'edit_item' => __( 'Edit Award', 'total-child' ),
				'new_item' => __( 'Add New Award', 'total-child' ),
				'view_item' => __( 'View Award', 'total-child' ),
				'search_items' => __( 'Search Awards', 'total-child' ),
				'not_found' => __( 'No Awards Found', 'total-child' ),
				'not_found_in_trash' => __( 'No Awards Found In Trash', 'total-child' )
			),
			'public' => false,
			'show_ui' => true,
			'capability_type' => 'post',
			'has_archive' => false,
			'menu_position' => 25,
			'rewrite' => false,
			'supports' => array(
				'title',
				'thumbnail',
			),
		) );
	}

	/**
	 * Register Award Group
	 *
	 * @since 2.0.0
	 */
	public static function register_award_group() {
		// Define args and apply filters for child theming
		$args = array(
			'labels' => array(
				'name' => __( 'Award Type', 'total-child' ),
				'singular_name' => __( 'Award Type', 'total-child' ),
				'menu_name' => __( 'Award Type', 'total' ),
				'search_items' => __( 'Search Award Types', 'total-child' ),
				'popular_items' => __( 'Popular Award Types', 'total-child' ),
				'all_items' => __( 'All Award Types', 'total-child' ),
				'parent_item' => __( 'Parent Award Type', 'total-child' ),
				'parent_item_colon' => __( 'Parent Award Type:', 'total-child' ),
				'edit_item' => __( 'Edit Award Type', 'total-child' ),
				'update_item' => __( 'Update Award Type', 'total-child' ),
				'add_new_item' => __( 'Add New Award Type', 'total-child' ),
				'new_item_name' => __( 'New Award Type Name', 'total-child' ),
				'separate_items_with_commas' => __( 'Separate award types with commas', 'total-child' ),
				'add_or_remove_items' => __( 'Add or remove award type', 'total-child' ),
				'choose_from_most_used' => __( 'Choose from the most used award types', 'total-child' ),
			),
			'public' => false,
			'show_in_nav_menus' => false,
			'show_ui' => true,
			'show_tagcloud' => false,
			'hierarchical' => true,
			'show_admin_column' => true,
			'show_in_quick_edit' => true,
			'rewrite' => false,
			'query_var' => false
		);

		// Register the staff tag taxonomy
		register_taxonomy( self::get_award_group_slug(), array( self::get_slug() ), $args );
	}
	/**
	 * Register Award Year.
	 *
	 * @since 2.0.0
	 */
	public static function register_award_year() {
		// Define args and apply filters for child theming
		$args = array(
			'labels' => array(
				'name' => __( 'Award Year', 'total-child' ),
				'singular_name' => __( 'Award Year', 'total-child' ),
				'menu_name' => __( 'Award Year', 'total' ),
				'search_items' => __( 'Search Award Years', 'total-child' ),
				'popular_items' => __( 'Popular Award Years', 'total-child' ),
				'all_items' => __( 'All Award Years', 'total-child' ),
				'parent_item' => __( 'Parent Award Year', 'total-child' ),
				'parent_item_colon' => __( 'Parent Award Year:', 'total-child' ),
				'edit_item' => __( 'Edit Award Year', 'total-child' ),
				'update_item' => __( 'Update Award Year', 'total-child' ),
				'add_new_item' => __( 'Add New Award Year', 'total-child' ),
				'new_item_name' => __( 'New Award Year Name', 'total-child' ),
				'separate_items_with_commas' => __( 'Separate award year with commas', 'total-child' ),
				'add_or_remove_items' => __( 'Add or remove award years', 'total-child' ),
				'choose_from_most_used' => __( 'Choose from the most used award years', 'total-child' ),
			),
			'public' => false,
			'show_in_nav_menus' => false,
			'show_ui' => true,
			'show_tagcloud' => false,
			'hierarchical' => false,
			'show_admin_column' => true,
			'show_in_quick_edit' => true,
			'rewrite' => false,
			'query_var' => false
		);

		// Register the staff tag taxonomy
		register_taxonomy( self::get_award_year_slug(), array( self::get_slug() ), $args );
	}
	public static function register_tax(){
		self::register_award_group();
		//self::register_award_year();
	}

	public static function hide_on_edit_screen($style) {
		$hide = array(
			'#award_groupdiv',
			'#screen-meta label[for=award_groupdiv-hide]',
			'#tagsdiv-award_year',
			'#screen-meta label[for=tagsdiv-award_year-hide]',
			'.post-type-award #mymetabox_revslider_0',
		);
		if(1) {
			$hide[] = '#mymetabox_revslider_0';
		}
		$style .= implode(', ', $hide) . ' {display: none;}';

		return $style;
	}
	public static function get_years($type = false, $limit = -1, $past_only = false) {
		if(!$type) {
			return array();
		}
		global $wpdb;
		$post_type = EASL_Award_Config::get_slug();

		$sql  = "SELECT DISTINCT ym.meta_value AS year FROM {$wpdb->posts}";
		$sql .= " INNER JOIN {$wpdb->postmeta} ON ( {$wpdb->posts}.ID = {$wpdb->postmeta}.post_id ) ";
		$sql .= " INNER JOIN {$wpdb->postmeta} AS ym ON ( {$wpdb->posts}.ID = ym.post_id ) ";
		$sql .= " WHERE (1=1)";
		$sql .= $wpdb->prepare(" AND (({$wpdb->postmeta}.meta_key = 'award_type') AND ({$wpdb->postmeta}.meta_value = %d))", $type);
		if($past_only){
			$current_year = (int)date("Y");
			$sql .= " AND ((ym.meta_key = 'award_year') AND (ym.meta_value < {$current_year})) ";
		}else{
			$sql .= " AND (ym.meta_key = 'award_year') ";
		}
		$sql .= " AND ({$wpdb->posts}.post_type = '{$post_type}') AND ({$wpdb->posts}.post_status = 'publish') ORDER BY ym.meta_value DESC";
		if($limit > 0) {
			$sql .= $wpdb->prepare(" LIMIT %d", $limit);
		}

		$years = $wpdb->get_col($sql);

		if(!$years || !is_array($years)){
			return array();
		}
		return $years;
	}
}
new EASL_Award_Config();