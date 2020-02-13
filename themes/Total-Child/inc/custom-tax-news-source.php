<?php
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

class EASL_News_Source_Tax {
	private static $slug = 'news_source';

	public function __construct() {
		add_action( 'init', array( $this, 'register' ), 0 );
		//add_filter( 'manage_edit-post_columns', array( $this, 'edit_columns' ) );
		//add_action( 'manage_post_posts_custom_column', array( $this, 'column_display' ), 10, 2 );
		add_action( 'restrict_manage_posts', array( $this, 'tax_filters' ) );
	}

	static public function get_slug(){
		return self::$slug;
	}

	public function register() {
		// Apply filters
		$args = array(
			'labels'            => array(
				'name'                       => 'News Source',
				'singular_name'              => 'News Source',
				'menu_name'                  => 'News Source',
				'search_items'               => __( 'Search', 'total-child' ),
				'popular_items'              => __( 'Popular', 'total-child' ),
				'all_items'                  => __( 'All', 'total-child' ),
				'parent_item'                => __( 'Parent', 'total-child' ),
				'parent_item_colon'          => __( 'Parent', 'total-child' ),
				'edit_item'                  => __( 'Edit', 'total-child' ),
				'update_item'                => __( 'Update', 'total-child' ),
				'add_new_item'               => __( 'Add New', 'total-child' ),
				'new_item_name'              => __( 'New', 'total-child' ),
				'separate_items_with_commas' => __( 'Separate with commas', 'total-child' ),
				'add_or_remove_items'        => __( 'Add or remove', 'total-child' ),
				'choose_from_most_used'      => __( 'Choose from the most used', 'total-child' ),
			),
			'public'            => true,
			'show_in_nav_menus' => true,
			'show_ui'           => true,
			'show_tagcloud'     => false,
			'hierarchical'      => true,
			'show_admin_column' => true,
			'rewrite'           => array(
				'slug' => 'news_source',
			),
			'query_var'         => true
		);

		// Register the taxonomy
		register_taxonomy( self::get_slug(), array( 'post' ), $args );
	}


	public function edit_columns( $columns ) {
		$columns['easl_news_source'] = __( 'News Source', 'total-child' );

		return $columns;
	}

	public function column_display( $column, $post_id ) {
		switch ( $column ) {
			case "easl_news_source":
				if ( $news_source_list = get_the_term_list( $post_id, 'news_source', '', ', ', '' ) ) {
					echo $news_source_list;
				} else {
					echo '&mdash;';
				}
				break;
		}
	}

	public function tax_filters() {
		global $typenow;
		if ( 'post' == $typenow ) {
			$tax_slug         = 'news_source';
			$current_tax_slug = isset( $_GET[ $tax_slug ] ) ? esc_html( $_GET[ $tax_slug ] ) : false;
			$tax_obj          = get_taxonomy( $tax_slug );
			$tax_name         = $tax_obj->labels->name;
			$terms            = get_terms( $tax_slug );
			if ( count( $terms ) > 0 ) {
				echo "<select name='$tax_slug' id='$tax_slug' class='postform'>";
				echo "<option value=''>$tax_name</option>";
				foreach ( $terms as $term ) {
					echo '<option value=' . $term->slug, $current_tax_slug == $term->slug ? ' selected="selected"' : '', '>' . $term->name . ' (' . $term->count . ')</option>';
				}
				echo "</select>";
			}
		}
	}
}

new EASL_News_Source_Tax();