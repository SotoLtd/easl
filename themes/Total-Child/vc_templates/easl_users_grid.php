<?php
/**
 * EASL Users Grid
 *
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Helps speed up rendering in backend of VC
if ( is_admin() && ! wp_doing_ajax() ) {
	return;
}

// Required VC functions
if ( ! function_exists( 'vc_map_get_attributes' ) || ! function_exists( 'vc_shortcode_custom_css_class' ) ) {
	vcex_function_needed_notice();
	return;
}

// Define output var
$output = '';

// Get shortcode attributes
$atts = vc_map_get_attributes( 'easl_users_grid', $atts );

// Get user roles to query
$role__in = array();
if ( ! empty( $atts['role__in'] ) ) {
	$role__in = preg_split( '/\,[\s]*/', $atts['role__in'] );
}

// Query arguments
$args = apply_filters( 'vcex_users_grid_query_args', array(
	'order'    => $atts['order'],
	'orderby'  => $atts['orderby'],
	'role__in' => $role__in,
	'number'   => (int) $atts['number'],
) );

// Get users
$users = get_users( $args );

// No users, lets bail
if ( ! $users ) {
	return;
}

// Get onclick action (with fallback for old link_to_author_page param)
if ( isset( $atts['link_to_author_page'] ) ) {
	if ( 'true' == $atts['link_to_author_page'] ) {
		$atts['onclick'] = 'author_page';
	} elseif ( 'false' == $atts['link_to_author_page'] ) {
		$atts['onclick'] = 'disable';
	}
}

// Wrap classes
$wrap_classes = array( 'vcex-module', 'easl-users-grid', 'wpex-row', 'clr' );
if ( 'masonry' == $atts['grid_style'] ) {
	$wrap_classes[] = 'vcex-isotope-grid';
	vcex_inline_js( 'isotope' );
}
if ( $atts['columns_gap'] ) {
	$wrap_classes[] = 'gap-'. $atts['columns_gap'];
}
if ( $atts['visibility'] ) {
	$wrap_classes[] = $atts['visibility'];
}
if ( $atts['classes'] ) {
	$wrap_classes[] = vcex_get_extra_class( $atts['classes'] );
}
$wrap_classes = implode( ' ', $wrap_classes );
$wrap_classes = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, $wrap_classes, 'vcex_users_grid', $atts );

// Entry classes
$entry_classes = array( 'vcex-users-grid-entry', 'clr' );
if ( 'masonry' == $atts['grid_style'] ) {
	$entry_classes[] = 'vcex-isotope-entry';
}
$entry_classes[] = vcex_get_grid_column_class( $atts );
if ( 'false' == $atts['columns_responsive'] ) {
	$entry_classes[] = 'nr-col';
} else {
	$entry_classes[] = 'col';
}
if ( $atts['css_animation'] && 'none' != $atts['css_animation'] ) {
	$entry_classes[] = vcex_get_css_animation( $atts['css_animation'] );
}
$entry_classes = implode( ' ', $entry_classes );

// Entry CSS wrapper
$entry_css_class = $atts['entry_css'] ? vc_shortcode_custom_css_class( $atts['entry_css'] ) : '';

// Avatar classes
if ( 'true' == $atts['avatar'] ) {

	$avatar_wrap_classes = 'entry-media clr';

	if ( $atts['avatar_hover_style'] ) {
		$avatar_wrap_classes .= ' '. wpex_image_hover_classes( $atts['avatar_hover_style'] );
	}

}

// Name classes & css
if ( 'true' == $atts['name'] ) {

	$name_classes = 'entry-title clr';

	if ( $atts['name_color'] && 'disable' != $atts['onclick'] ) {
		$name_classes .= ' wpex-hover-inherit-color'; // So when name has custom color the hover is the same
	}

	$name_css = vcex_inline_style( array(
		'color'          => $atts['name_color'],
		'font_size'      => $atts['name_font_size'],
		'font_weight'    => $atts['name_font_weight'],
		'font_family'    => $atts['name_font_family'],
		'margin_bottom'  => $atts['name_margin_bottom'],
		'text_transform' => $atts['name_text_transform'],
	) );

}

// View Profile Link classes & css
if ( 'true' == $atts['prof_link'] ) {

	$prof_link_classes = 'entry-link clr';

	if ( $atts['prof_link_color'] && 'disable' != $atts['onclick'] ) {
		$prof_link_classes .= ' wpex-hover-inherit-color'; // So when name has custom color the hover is the same
	}

	$prof_link_css = vcex_inline_style( array(
		'color'          => $atts['prof_link_color'],
		'font_size'      => $atts['prof_link_font_size'],
		'font_weight'    => $atts['prof_link_font_weight'],
		'font_family'    => $atts['prof_link_font_family'],
		'margin_bottom'  => $atts['prof_link_margin_bottom'],
		'text_transform' => $atts['prof_link_text_transform'],
	) );

}

// Description CSS
if ( 'true' == $atts['description'] ) {
	
	$description_css = vcex_inline_style( array(
		'color'          => $atts['description_color'],
		'font_size'      => $atts['description_font_size'],
		'font_weight'    => $atts['description_font_weight'],
		'font_family'    => $atts['description_font_family'],
	) );

}

// Social settings
if ( 'true' == $atts['social_links'] ) {

	$social_links_inline_css = vcex_inline_style( array(
		'padding'   => $atts['social_links_padding'],
		'font_size' => $atts['social_links_size'],
	) );

	$social_links_style = wpex_get_social_button_class( $atts['social_links_style'] );

}

// Begin output
$output .= '<div class="'. esc_attr( $wrap_classes ) .'"'. vcex_get_unique_id( $atts['unique_id'] ) .'>';

	// Display Head
	if ( 'true' == $atts['entry_head'] ) {
		if ( $atts['widget_title'] ) {
			// Begin entry output
			// Entry Classes
			$entry_classes_1   = array();
			$entry_classes_1[] = 'col span_1_of_1';
			$entry_classes_1[] = 'textleft';
			$output .= '<div class="' . implode( ' ', $entry_classes_1 ) . '"">';

			// Inner entry classes
			$classes = 'vcex-post-type-entry-inner entry-inner clr wpb_easl_post_type_grid_heading';
			if ( $entry_css_class ) {
				$classes .= ' ' . $entry_css_class;
			}

			// Inner entry output
			$output .= '<div class="' . $classes . '">';
			$output .= '<div class="wpb_easl_post_type_grid_head">';

			$output .= '<h2 class="wpb_easl_post_type_grid_head_title">' . $atts['widget_title'] . '</h2>';
			if ( $atts['widget_subtitle'] ) {
				$output .= '<p class="wpb_easl_post_type_grid_head_subtitle">' . $atts['widget_subtitle'] . '</p>';
			}
			$output .= '</div>';

			if ( $atts['view_all_url'] ) {
				$view_all_link = '<a class="wpb_easl_post_type_grid_head_all_link" href="' . esc_url( $atts['view_all_url'] ) . '">' . $atts['view_all_text'] . '</a>';
				$output        .= $view_all_link;
			}

			$output .= '</div>';
			$output .= '</div>';
//			$output .= '</div>';
		}
	}

	$counter = 0;

	// Loop through users
	foreach ( $users as $user ) :

		$counter++;

		$author_link = ''; // Reset after each user

		$output .= '<div class="'. esc_attr( $entry_classes ) .' col-'. $counter .'">';

			if ( $entry_css_class ) {

				$output .= '<div class="entry-css-wrap clr '. $entry_css_class .'">';

			}

			// Avatar
			if ( 'true' == $atts['avatar'] ) {

				$output .= '<div class="'. esc_attr( $avatar_wrap_classes ) .'">';

					if ( 'disable' != $atts['onclick'] ) {

						if ( 'author_page' == $atts['onclick'] ) {
							$author_link = get_author_posts_url( $user->ID );
						} else if ( 'user_website' == $atts['onclick'] ) {
							$author_link = $user->user_url;
						}

						if ( $author_link ) {

							$author_link = '<a href="'. esc_url( $author_link ) .'" title="'. esc_attr( $user->display_name ) .'">';

							$output .= $author_link;

						}

					}

					if ( $atts['avatar_meta_field'] ) {

						if ( $avatar = get_user_meta( $user->ID, $atts['avatar_meta_field'], true ) ) {

							if ( is_numeric( $avatar ) && $get_avatar = wp_get_attachment_image( $avatar ) ) {

								$output .= $get_avatar;

							} else {

								$output .= '<img src="'. esc_url( $avatar ) .'" alt="'. esc_attr( $user->display_name ) .'" />';

							}

						}

					} else {

						$output .= get_avatar( $user->ID, $atts['avatar_size'], '', $user->display_name );

					}

					if ( $author_link ) {

						$output .= '</a>';

					}

				$output .= '</div>';

			}


			// Display name
			if ( 'true' == $atts['name'] ) {

				$name_heading_tag = $atts['name_heading_tag'] ? $atts['name_heading_tag'] : 'div';

				$output .= '<'. $name_heading_tag .' class="'. esc_attr( $name_classes ) .'"'. $name_css .'>';

					if ( $author_link ) {

						$output .= $author_link;

					}

					$output .= $user->display_name;

					if ( $author_link ) {

						$output .= '</a>';

					}

				$output .= '</'. $name_heading_tag .'>';

			}


			// Display View Profile Link
			if ( 'true' == $atts['prof_link'] ) {

				$prof_link_tag = $atts['prof_link_tag'] ? $atts['prof_link_tag'] : 'div';

				$output .= '<' . $prof_link_tag . ' class="' . esc_attr( $prof_link_classes ) . '"' . $prof_link_css . '>';

				if ( $author_link ) {

					$output .= $author_link;

				}

				$output .= $atts['prof_link_text'];

				if ( $author_link ) {

					$output .= '</a>';

				}

				$output .= '</' . $prof_link_tag . '>';

			}


			// Description
			if ( 'true' == $atts['description'] && $description = get_the_author_meta( 'description', $user->ID ) ) {

				$output .= '<div class="entry-excerpt clr" '. $description_css .'>';

					$output .= wpautop( wp_kses_post( $description ) );

				$output .= '</div>';

			}

			// Display social
			if ( 'true' == $atts['social_links'] ) {

				$output .= '<div class="entry-social-links clr"'. $social_links_inline_css .'>';

					$output .= wpex_get_user_social_links( $user->ID, 'icons', array(
						'class' => $social_links_style
					) );

				$output .= '</div>';

			}

			if ( $entry_css_class ) {

				$output .= '</div>';

			}

		$output .= '</div>';

		// Clear counter
		if ( $counter == $atts['columns'] ) {
			$counter = 0;
		}

	// End loop
	endforeach;

$output .= '</div>';

// Echo ouput
echo $output;