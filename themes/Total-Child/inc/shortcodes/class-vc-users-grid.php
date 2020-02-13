<?php
/**
 * EASL Users Grid
 *
 */

if ( ! class_exists( 'EASL_Users_Grid_Shortcode' ) ) {

	class EASL_Users_Grid_Shortcode {

		/**
		 * Main constructor
		 *
		 * @since 4.0
		 */
		public function __construct() {
			add_shortcode( 'easl_users_grid', array( $this, 'output' ) );
			vc_lean_map( 'easl_users_grid', array( $this, 'map' ) );
			add_filter( 'vc_autocomplete_easl_users_grid_role__in_callback', 'vcex_suggest_user_roles', 10, 1 );
			add_filter( 'vc_autocomplete_easl_users_grid_role__in_render', 'vcex_render_user_roles', 10, 1 );
			add_filter( 'vc_edit_form_fields_attributes_easl_users_grid', array( $this, 'edit_fields' ), 10 );
		}

		/**
		 * Shortcode output => Get template file and display shortcode
		 *
		 * @since 4.0
		 */
		public function output( $atts, $content = null ) {
			ob_start();
			include( get_stylesheet_directory() . '/vc_templates/easl_users_grid.php' );
			return ob_get_clean();
		}

		/**
		 * Map shortcode to VC
		 *
		 * @since 4.0
		 */
		public function map() {
			return array(
				'name' => __( 'EASL Users Grid', 'total' ),
				'description' => __( 'Displays a grid of users', 'total' ),
				'base' => 'easl_users_grid',
				'category' => __( 'EASL', 'total' ),
				'icon' => 'vcex-users-grid vcex-icon ticon ticon-users',
				'params' => array(
					// General
					array(
						'type' => 'textfield',
						'heading' => __( 'Unique Id', 'total' ),
						'param_name' => 'unique_id',
						'admin_label' => true,
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Custom Classes', 'total' ),
						'param_name' => 'classes',
						'admin_label' => true,
					),
					vcex_vc_map_add_css_animation(),
					array(
						'type' => 'vcex_visibility',
						'heading' => __( 'Visibility', 'total' ),
						'param_name' => 'visibility',
					),
					array(
						'type' => 'dropdown',
						'heading' => __( 'Grid Style', 'total' ),
						'param_name' => 'grid_style',
						'std' => 'fit_columns',
						'value' => array(
							__( 'Fit Columns', 'total' ) => 'fit_columns',
							__( 'Masonry', 'total' ) => 'masonry',
						),
						'edit_field_class' => 'vc_col-sm-3 vc_column clear',
					),
					array(
						'type' => 'vcex_grid_columns',
						'heading' => __( 'Columns', 'total' ),
						'param_name' => 'columns',
						'std' => '5',
						'edit_field_class' => 'vc_col-sm-3 vc_column',
					),
					array(
						'type' => 'vcex_column_gaps',
						'heading' => __( 'Gap', 'total' ),
						'param_name' => 'columns_gap',
						'edit_field_class' => 'vc_col-sm-3 vc_column',
					),
					array(
						'type' => 'dropdown',
						'heading' => __( 'Responsive', 'total' ),
						'param_name' => 'columns_responsive',
						'value' => array( __( 'Yes', 'total' ) => 'true', __( 'No', 'false' ) => 'false' ),
						'edit_field_class' => 'vc_col-sm-3 vc_column',
						'dependency' => array( 'element' => 'columns', 'value' => array( '2', '3', '4', '5', '6', '7', '8', '9', '10' ) ),
					),
					array(
						'type' => 'vcex_grid_columns_responsive',
						'heading' => __( 'Responsive Settings', 'total' ),
						'param_name' => 'columns_responsive_settings',
						'dependency' => array( 'element' => 'columns_responsive', 'value' => 'true' ),
					),
					array(
						'type' => 'dropdown',
						'std' => 'author_page',
						'heading' => __( 'On click action', 'total' ),
						'param_name' => 'onclick',
						'value' => array(
							__( 'Open author page', 'total' ) => 'author_page',
							__( 'Open user website', 'total' ) => 'user_website',
							__( 'Disable', 'total' ) => 'disable',
						),
					),
					// Head
					array(
						'type'       => 'vcex_ofswitch',
						'std'        => 'false',
						'heading'    => __( 'Enable', 'total' ),
						'param_name' => 'entry_head',
						'group'      => __( 'Head', 'total' ),
					),
					array(
						'type'        => 'textfield',
						'heading'     => __( 'Widget title', 'total' ),
						'param_name'  => 'widget_title',
						'group'       => __( 'Head', 'total' ),
						'description' => __( 'Enter text used as widget title (Note: located above content element).', 'total' ),
						'dependency'  => array( 'element' => 'entry_head', 'value' => 'true' ),
					),
					array(
						'type'        => 'textfield',
						'heading'     => __( 'Widget subtitle', 'total' ),
						'param_name'  => 'widget_subtitle',
						'group'       => __( 'Head', 'total' ),
						'description' => __( 'Enter text used as widget subtitle (Note: located above content element).', 'total' ),
						'dependency'  => array( 'element' => 'entry_head', 'value' => 'true' ),
					),
					array(
						'type'        => 'checkbox',
						'param_name'  => 'view_all_link',
						'group'       => __( 'Head', 'total' ),
						'heading'     => __( 'Show view all link?', 'total' ),
						'description' => __( 'Enable view all links beside widget title.', 'total' ),
						'dependency'  => array( 'element' => 'entry_head', 'value' => 'true' ),
					),
					array(
						'type'        => 'textfield',
						'heading'     => __( 'View All links Text', 'total' ),
						'param_name'  => 'view_all_text',
						'group'       => __( 'Head', 'total' ),
						'description' => __( 'Enter text used as view all events link.', 'total' ),
						'dependency'  => array( 'element' => 'entry_head', 'value' => 'true' ),
					),
					array(
						'type'        => 'textfield',
						'heading'     => __( 'View All links URL', 'total' ),
						'param_name'  => 'view_all_url',
						'group'       => __( 'Head', 'total' ),
						'description' => __( 'Enter URL used as view all events link.', 'total' ),
						'dependency'  => array( 'element' => 'entry_head', 'value' => 'true' ),
					),
					// Query
					array(
						'type' => 'autocomplete',
						'heading' => __( 'User Roles', 'total' ),
						'param_name' => 'role__in',
						'admin_label' => true,
						'std' => '',
						'settings' => array(
							'multiple' => true,
							'min_length' => 1,
							'groups' => false,
							'unique_values' => true,
							'display_inline' => true,
							'delay' => 0,
							'auto_focus' => true,
						),
						'group' => __( 'Query', 'total' ),
					),
					array(
						'type' => 'dropdown',
						'heading' => __( 'Order', 'total' ),
						'param_name' => 'order',
						'group' => __( 'Query', 'total' ),
						'value' => array(
							__( 'ASC', 'total' ) => 'ASC',
							__( 'DESC', 'total' ) => 'DESC',
						),
					),
					array(
						'type' => 'dropdown',
						'heading' => __( 'Order By', 'total' ),
						'param_name' => 'orderby',
						'value' => array(
							__( 'Display Name', 'total' ) => 'display_name',
							__( 'Nicename', 'total' ) => 'nicename',
							__( 'Login', 'total' ) => 'login',
							__( 'Registered', 'total' ) => 'registered',
							'ID' => 'ID',
							__( 'Email', 'total' ) => 'email',
						),
						'group' => __( 'Query', 'total' ),
					),
					array(
						'type'        => 'textfield',
						'heading'     => __( 'Number of Users', 'total' ),
						'param_name'  => 'number',
						'std'         => '4',
						'group'       => __( 'Query', 'total' ),
						'description' => __( 'You can leave empty to display all users.', 'total' ),
					),
					// Image
					array(
						'type' => 'vcex_ofswitch',
						'std' => 'true',
						'heading' => __( 'Enable', 'total' ),
						'param_name' => 'avatar',
						'group' => __( 'Avatar', 'total' ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Size', 'total' ),
						'param_name' => 'avatar_size',
						'std' => '150',
						'group' => __( 'Avatar', 'total' ),
						'dependency' => array( 'element' => 'avatar', 'value' => 'true' ),
						'description' => __( 'Size of Gravatar to return (max is 512 for standard Gravatars)', 'total' ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Meta Field', 'total' ),
						'param_name' => 'avatar_meta_field',
						'std' => '',
						'group' => __( 'Avatar', 'total' ),
						'dependency' => array( 'element' => 'avatar', 'value' => 'true' ),
						'description' => __( 'Enter the "ID" of a custom user meta field to pull the avatar from there instead of searching for the user\'s Gravatar', 'total' ),
					),
					array(
						'type' => 'vcex_image_hovers',
						'heading' => __( 'CSS3 Image Hover', 'total' ),
						'param_name' => 'avatar_hover_style',
						'group' => __( 'Avatar', 'total' ),
					),
					// Name
					array(
						'type' => 'vcex_ofswitch',
						'std' => 'true',
						'heading' => __( 'Enable', 'total' ),
						'param_name' => 'name',
						'group' => __( 'Name', 'total' ),
					),
					array(
						'type' => 'vcex_select_buttons',
						'heading' => __( 'Tag', 'total' ),
						'param_name' => 'name_heading_tag',
						'choices' => 'html_tag',
						'std' => 'div',
						'group' => __( 'Name', 'total' ),
					),
					array(
						'type' => 'colorpicker',
						'heading' => __( 'Color', 'total' ),
						'param_name' => 'name_color',
						'group' => __( 'Name', 'total' ),
						'std' => '',
						'dependency' => array( 'element' => 'name', 'value' => 'true' ),
					),
					array(
						'type'  => 'vcex_font_family_select',
						'heading' => __( 'Font Family', 'total' ),
						'param_name' => 'name_font_family',
						'group' => __( 'Name', 'total' ),
						'dependency' => array( 'element' => 'name', 'value' => 'true' ),
					),
					array(
						'type' => 'vcex_font_weight',
						'heading' => __( 'Font Weight', 'total' ),
						'param_name' => 'name_font_weight',
						'group' => __( 'Name', 'total' ),
						'dependency' => array( 'element' => 'name', 'value' => 'true' ),
					),
					array(
						'type'  => 'textfield',
						'heading' => __( 'Font Size', 'total' ),
						'param_name' => 'name_font_size',
						'group' => __( 'Name', 'total' ),
						'dependency' => array( 'element' => 'name', 'value' => 'true' ),
					),
					array(
						'type' => 'vcex_text_transforms',
						'heading' => __( 'Text Transform', 'total' ),
						'param_name' => 'name_text_transform',
						'group' => __( 'Name', 'total' ),
						'dependency' => array( 'element' => 'name', 'value' => 'true' ),
					),
					array(
						'type'  => 'textfield',
						'heading' => __( 'Bottom Margin', 'total' ),
						'param_name' => 'name_margin_bottom',
						'group' => __( 'Name', 'total' ),
						'dependency' => array( 'element' => 'name', 'value' => 'true' ),
					),
					// View Profile Link
					array(
						'type'       => 'vcex_ofswitch',
						'std'        => 'true',
						'heading'    => __( 'Enable View Profile Link', 'total' ),
						'param_name' => 'prof_link',
						'group'      => __( 'Name', 'total' ),
					),
					array(
						'type'        => 'textfield',
						'heading'     => __( 'Profile Link text', 'js_composer' ),
						'param_name'  => 'prof_link_text',
						'group'      => __( 'Name', 'total' ),
						'dependency' => array( 'element' => 'prof_link', 'value' => 'true' ),
					),
					array(
						'type'       => 'vcex_select_buttons',
						'heading'    => __( 'Profile Link Tag', 'total' ),
						'param_name' => 'prof_link_tag',
						'choices'    => 'html_tag',
						'std'        => 'div',
						'group'      => __( 'Name', 'total' ),
						'dependency' => array( 'element' => 'prof_link', 'value' => 'true' ),
					),
					array(
						'type'       => 'colorpicker',
						'heading'    => __( 'Profile Link Color', 'total' ),
						'param_name' => 'prof_link_color',
						'group'      => __( 'Name', 'total' ),
						'std'        => '',
						'dependency' => array( 'element' => 'prof_link', 'value' => 'true' ),
					),
					array(
						'type'       => 'vcex_font_family_select',
						'heading'    => __( 'Profile Link Font Family', 'total' ),
						'param_name' => 'prof_link_font_family',
						'group'      => __( 'Name', 'total' ),
						'dependency' => array( 'element' => 'prof_link', 'value' => 'true' ),
					),
					array(
						'type'       => 'vcex_font_weight',
						'heading'    => __( 'Profile Link Font Weight', 'total' ),
						'param_name' => 'prof_link_font_weight',
						'group'      => __( 'Name', 'total' ),
						'dependency' => array( 'element' => 'prof_link', 'value' => 'true' ),
					),
					array(
						'type'       => 'textfield',
						'heading'    => __( 'Profile LinkFont Size', 'total' ),
						'param_name' => 'prof_link_font_size',
						'group'      => __( 'Name', 'total' ),
						'dependency' => array( 'element' => 'prof_link', 'value' => 'true' ),
					),
					array(
						'type'       => 'vcex_text_transforms',
						'heading'    => __( 'Profile Link Text Transform', 'total' ),
						'param_name' => 'prof_link_text_transform',
						'group'      => __( 'Name', 'total' ),
						'dependency' => array( 'element' => 'prof_link', 'value' => 'true' ),
					),
					array(
						'type'       => 'textfield',
						'heading'    => __( 'Profile Link Bottom Margin', 'total' ),
						'param_name' => 'prof_link_margin_bottom',
						'group'      => __( 'Name', 'total' ),
						'dependency' => array( 'element' => 'prof_link', 'value' => 'true' ),
					),
					// Description
					array(
						'type' => 'vcex_ofswitch',
						'std' => 'true',
						'heading' => __( 'Enable', 'total' ),
						'param_name' => 'description',
						'group' => __( 'Description', 'total' ),
					),
					array(
						'type' => 'colorpicker',
						'heading' => __( 'Color', 'total' ),
						'param_name' => 'description_color',
						'group' => __( 'Description', 'total' ),
						'std' => '',
						'dependency' => array( 'element' => 'description', 'value' => 'true' ),
					),
					array(
						'type'  => 'vcex_font_family_select',
						'heading' => __( 'Font Family', 'total' ),
						'param_name' => 'description_font_family',
						'group' => __( 'Description', 'total' ),
						'dependency' => array( 'element' => 'description', 'value' => 'true' ),
					),
					array(
						'type' => 'vcex_font_weight',
						'heading' => __( 'Font Weight', 'total' ),
						'param_name' => 'description_font_weight',
						'group' => __( 'Description', 'total' ),
						'dependency' => array( 'element' => 'description', 'value' => 'true' ),
					),
					array(
						'type'  => 'textfield',
						'heading' => __( 'Font Size', 'total' ),
						'param_name' => 'description_font_size',
						'group' => __( 'Description', 'total' ),
						'dependency' => array( 'element' => 'description', 'value' => 'true' ),
					),
					// Social
					array(
						'type' => 'vcex_ofswitch',
						'std' => 'true',
						'heading' => __( 'Enable', 'total' ),
						'param_name' => 'social_links',
						'group' => __( 'Social', 'total' ),
					),
					array(
						'type' => 'vcex_social_button_styles',
						'heading' => __( 'Style', 'total' ),
						'param_name' => 'social_links_style',
						'std' => wpex_get_mod( 'staff_social_default_style', 'minimal-round' ),
						'group' => __( 'Social', 'total' ),
						'dependency' => array( 'element' => 'social_links', 'value' => 'true' ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Font Size', 'total' ),
						'param_name' => 'social_links_size',
						'group' => __( 'Social', 'total' ),
						'dependency' => array( 'element' => 'social_links', 'value' => 'true' ),
					),
					array(
						'type' => 'vcex_trbl',
						'heading' => __( 'Padding', 'total' ),
						'param_name' => 'social_links_padding',
						'group' => __( 'Social', 'total' ),
						'dependency' => array( 'element' => 'social_links', 'value' => 'true' ),
					),
					array(
						'type' => 'css_editor',
						'heading' => __( 'CSS', 'total' ),
						'param_name' => 'entry_css',
						'group' => __( 'Entry CSS', 'total' ),
					),
					// Deprecated
					array( 'type' => 'hidden', 'param_name' => 'link_to_author_page' ),
				)
			);
		}

		/**
		 * Edit form fields
		 *
		 * @since 4.5.1
		 */
		public function edit_fields( $atts ) {

			if ( isset( $atts['link_to_author_page'] ) ) {
				if ( 'false' == $atts['link_to_author_page'] ) {
					$atts['onclick'] = 'disable';
					unset( $atts['link_to_author_page'] );
				}
			}

			return $atts;

		}

	}
}
new EASL_Users_Grid_Shortcode;