<?php
/**
 * vcex_testimonials_slider shortcode output.
 *
 * @package Total WordPress Theme
 * @subpackage Total Theme Core
 * @version 1.3.2
 */

defined( 'ABSPATH' ) || exit;

if ( ! vcex_maybe_display_shortcode( 'vcex_testimonials_slider', $atts ) ) {
	return;
}

// Define output var.
$output = '';

// Deprecated Attributes.
if ( ! empty( $atts['term_slug'] ) && empty( $atts['include_categories']) ) {
	$atts['include_categories'] = $atts['term_slug'];
}

// Get shortcode attribtues.
$atts = vcex_shortcode_atts( 'vcex_testimonials_slider', $atts, $this );

// Define non-vc attributes.
$atts['post_type'] = 'testimonials';
$atts['taxonomy']  = 'testimonials_category';
$atts['tax_query'] = '';

// Extract shortcode atts.
extract( $atts );

// Posts per page.
$posts_per_page = $count;

// Build the WordPress query.
$vcex_query = vcex_build_wp_query( $atts );

// Output posts.
if ( $vcex_query->have_posts() ) :

	// Load slider scripts.
	vcex_enqueue_slider_scripts( true );

	// Define and sanitize variables.
	$slideshow = vcex_vc_is_inline() ? 'false' : $slideshow;

	// Add Style.
	$wrap_style = array(
		'animation_delay' => $atts['animation_delay'],
		'animation_duration' => $atts['animation_duration'],
	);

	// OLD deprecated params.
	// @todo remove, doesn't look like this module had these old settings.
	if ( ! $css ) {
		if ( isset( $atts['background'] ) ) {
			$wrap_style['background_color'] = $atts['background'];
		}
		if ( isset( $atts['background_image'] ) ) {
			$wrap_style['background_image'] = wp_get_attachment_url( $atts['background_image'] ) ;
		}
		if ( isset( $atts['padding_top'] ) ) {
			$wrap_style['padding_top'] = $atts['padding_top'];
		}
		if ( isset( $atts['padding_bottom'] ) ) {
			$wrap_style['padding_bottom'] = $atts['padding_bottom'];
		}
	}

	$wrap_style = vcex_inline_style( $wrap_style );

	// Slide Style.
	$slide_entry_class = 'entry wpex-clr';
	$slide_entry_style = vcex_inline_style( array(
		'font_size'   => $font_size,
		'font_weight' => $font_weight,
		'color'       => $text_color,
	) );

	$unique_classname = vcex_element_unique_classname();

	$el_responsive_styles = array(
		'font_size' => $font_size,
	);

	$responsive_css = vcex_element_responsive_css( $el_responsive_styles, $unique_classname );

	if ( $responsive_css ) {
		$slide_entry_class .= ' ' . $unique_classname;
		$output .= '<style>' . $responsive_css . '</style>';
	}

	// Image classes.
	$img_classes = '';
	if ( ( $img_width || $img_height ) || 'wpex_custom' != $img_size ) {
		$img_classes .= 'vcex-custom-dims';
	} else {
		$img_classes .= 'vcex-default-dims';
	}

	// Define wrap attributes.
	$wrap_attrs = array(
		'id'    => $unique_id,
		'class' => '',
	);

	// Wrap classes.
	$wrap_classes = array(
		'vcex-module',
		'vcex-testimonials-fullslider',
	);

	if ( $skin ) {
		$wrap_classes[] = sanitize_html_class( $skin ) . '-skin';
	}

	if ( 'true' == $direction_nav ) {
		$wrap_classes[] = 'has-arrows';
	}

	if ( 'true' == $control_thumbs ) {
		$wrap_classes[] = 'has-thumbs';
	}

	if ( 'true' == $control_nav ) {
		$wrap_classes[] = 'has-controlnav';
	}

	if ( ! empty( $background_style ) && ! empty( $background_image ) ) {
		$wrap_classes[] = 'vcex-background-' . sanitize_html_class( $background_style );
	}

	if ( $css_animation ) {
		$wrap_classes[] = vcex_get_css_animation( $css_animation );
	}

	if ( $bottom_margin ) {
		$wrap_classes[] = vcex_sanitize_margin_class( $bottom_margin, 'wpex-mb-' );
	}

	if ( $visibility ) {
		$wrap_classes[] = vcex_parse_visibility_class( $visibility );
	}

	if ( $css ) {
		$wrap_classes[] = vcex_vc_shortcode_custom_css_class( $css );
	}

	if ( $classes ) {
		$wrap_classes[] = vcex_get_extra_class( $classes );
	}

	// Inner classes.
	$inner_classes = 'vcex-testimonials-fullslider-inner clr';

	$inner_classes .= ' text' . sanitize_html_class( $align ?: 'center' );

	// Turn class array into string.
	$wrap_classes = implode( ' ', $wrap_classes );
	$wrap_attrs['class'] = vcex_parse_shortcode_classes( $wrap_classes, 'vcex_testimonials_slider', $atts );

	// Wrap data.
	$slider_data = '';
	$slider_data .= ' data-fade-arrows="false"';
	$slider_data .= ' data-arrows="true"';

	if ( 'true' == $control_nav || 'true' == $direction_nav ) {
		$slider_data .= ' data-buttons="true"';
	} else {
		$slider_data .= ' data-buttons="false"';
	}

	if ( 'false' != $loop ) {
		$slider_data .= ' data-loop="true"';
	}

	if ( 'false' == $slideshow ) {
		$slider_data .= ' data-auto-play="false"';
	}

	if ( in_array( $animation, array( 'fade', 'fade_slides' ) ) ) {
		$slider_data .= ' data-fade="true"';
	}

	if ( $slideshow && $slideshow_speed ) {
		$slider_data .= ' data-auto-play-delay="' . esc_attr( $slideshow_speed ) . '"';
	}

	if ( 'true' == $control_thumbs ) {
		$slider_data .= ' data-thumbnails="true"';
	}

	if ( $animation_speed ) {
		$slider_data .= ' data-animation-speed="' . intval( $animation_speed ) . '"';
	}

	if ( 'false' == $auto_height ) {
		$slider_data .= ' data-auto-height="false"';
	} elseif ( $height_animation ) {
		$height_animation = intval( $height_animation );
		$height_animation = 0 == $height_animation ? '0.0' : $height_animation;
		$slider_data .= ' data-height-animation-duration="' . esc_attr( $height_animation ) . '"';
	}

	if ( apply_filters( 'vcex_sliders_disable_desktop_swipe', true, 'vcex_testimonials_slider' ) ) {
		$slider_data .= ' data-touch-swipe-desktop="false"';
	}

	// Image settings & style.
	$avatar_style = vcex_inline_style( array(
		'margin_bottom' => $img_bottom_margin,
	) );
	$img_style = vcex_inline_style( array(
		'border_radius' => $img_border_radius
	), false );

	// Meta settings.
	$meta_class = 'vcex-testimonials-fullslider-author clr';
	$meta_style = vcex_inline_style( array(
		'color'       => $meta_color,
		'font_size'   => $meta_font_size,
		'font_weight' => $meta_font_weight,
	) );

	$unique_classname = vcex_element_unique_classname();

	$el_responsive_styles = array(
		'font_size' => $meta_font_size,
	);

	$responsive_css = vcex_element_responsive_css( $el_responsive_styles, $unique_classname );

	if ( $responsive_css ) {
		$meta_class .= ' ' . $unique_classname;
		$output .= '<style>' . $responsive_css . '</style>';
	}

	// Start output.
	$output .= '<div' . vcex_parse_html_attributes( $wrap_attrs ) . $wrap_style . '>';

		$output .= '<div class="wpex-slider slider-pro"' . $slider_data . '>';

			$output .= '<div class="wpex-slider-slides sp-slides">';

				// Store posts in an array for use with the thumbnails later.
				$posts_cache = array();

				// Loop through posts.
				while ( $vcex_query->have_posts() ) :

					// Get post from query.
					$vcex_query->the_post();

					// Get post data and make available in $atts array.
					$atts['post_id']           = get_the_ID();
					$atts['post_content']      = get_the_content();
					$atts['post_meta_author']  = get_post_meta( $atts['post_id'], 'wpex_testimonial_author', true );
					$atts['post_meta_company'] = get_post_meta( $atts['post_id'], 'wpex_testimonial_company', true );
					$atts['post_meta_url']     = get_post_meta( $atts['post_id'], 'wpex_testimonial_url', true );

					// Store post ids.
					$posts_cache[] = $atts['post_id'];

					// Testimonial start.
					if ( '' != $atts['post_content'] ) :

						$output .= '<div ' . vcex_grid_get_post_class( array( 'wpex-slider-slide', 'sp-slide' ), $atts['post_id'], false ) . '>';

							$output .= '<div class="' . esc_attr( $inner_classes ) . '">';

								// Author avatar.
								$avatar_output = '';
								if ( 'yes' === $display_author_avatar && has_post_thumbnail( $atts['post_id'] ) ) {

									$avatar_output .= '<div class="vcex-testimonials-fullslider-avatar"' . $avatar_style . '>';

										// Output thumbnail
										$avatar_output .= vcex_get_post_thumbnail( array(
											'size'          => $img_size,
											'crop'          => $img_crop,
											'width'         => $img_width,
											'height'        => $img_height,
											'style'         => $img_style,
											'class'         => $img_classes,
											'apply_filters' => 'vcex_testimonials_slider_thumbnail_args',
											'filter_arg1'   => $atts,
										) );

									$avatar_output .= '</div>';

								}

								$output .= apply_filters( 'vcex_testimonials_slider_avatar', $avatar_output, $atts );

								// Content.
								$excerpt_output = '<div class="' . esc_attr( $slide_entry_class ) . '"' . $slide_entry_style . '>';

								// Custom Excerpt.
								if ( 'true' == $excerpt ) {

									if ( 'true' == $read_more ) {

										$read_more_text = $read_more_text ?: esc_html__( 'read more', 'total' );

										$read_more_link = '&hellip;<a href="' . esc_url( get_permalink() ) . '">' . esc_html( $read_more_text ) . ' <span>&rarr;</span></a>';

									} else {

										$read_more_link = '&hellip;';

									}

									$excerpt_output .= vcex_get_excerpt( array(
										'length' => $excerpt_length,
										'more' => $read_more_link,
										'context' => 'vcex_testimonials_slider',
										'custom_excerpts_more' => true, // force readmore on custom excerpts
									) );

								// Full content.
								} else {

									$excerpt_output .= vcex_the_content( get_the_content(), 'vcex_testimonials_slider' );

								}

								$excerpt_output .= '</div>'; // close excerpt

								$output .= apply_filters( 'vcex_testimonials_slider_excerpt', $excerpt_output, $atts );

								// Details name.
								$meta_output = '';
								if ( 'yes' === $display_author_name
									|| 'yes' === $display_author_company
									|| 'true' == $rating
								) :

									$meta_output .= '<div class="' . esc_attr( $meta_class ) . '"' . $meta_style . '>';

										// Display author name.
										$meta_author_output = '';
										if ( 'yes' === $display_author_name ) {
											$meta_author_output .= '<div class="vcex-testimonials-fullslider-author-name">' . wp_kses_post( $atts['post_meta_author'] ) . '</div>';
										}
										$meta_output .= apply_filters( 'vcex_testimonials_slider_meta_author', $meta_author_output, $atts );

										// Display company.
										$meta_company_output = '';
										if ( $atts['post_meta_company'] && 'yes' == $display_author_company ) {

											if ( $atts['post_meta_url'] ) {

												if ( function_exists( 'wpex_get_testimonial_company_url_target' ) ) {
													$company_target = wpex_get_testimonial_company_url_target();
												} else {
													$company_target = '_blank';
												}

												if ( 'blank' === $company_target || '_blank' === $company_target ) {
													$company_target_escaped = ' target="_blank" rel="noopener noreferrer"';
												} else {
													$company_target_escaped = '';
												}

												$meta_company_output .= '<a href="' . esc_url( $atts['post_meta_url'] ) . '" class="vcex-testimonials-fullslider-company display-block" title="' . esc_attr( $atts['post_meta_company'] ) . '"' . $company_target_escaped . '>';

													$meta_company_output .= wp_kses_post( $atts['post_meta_company'] );

												$meta_company_output .= '</a>';

											} else {

												$meta_company_output .= '<div class="vcex-testimonials-fullslider-company">';

													$meta_company_output .= wp_kses_post( $atts['post_meta_company'] );

												$meta_company_output .= '</div>';

											}

										}
										$meta_output .= apply_filters( 'vcex_testimonials_slider_meta_company', $meta_company_output, $atts );

										// Display rating.
										$meta_rating_output = '';

										if ( 'true' == $rating ) {

											$atts['post_rating'] = vcex_get_star_rating( '', $atts['post_id'] );

											if ( $atts['post_rating'] ) {

												$meta_rating_output .= '<div class="vcex-testimonials-fullslider-rating clr">'. $atts['post_rating'] .'</div>';

											}

										}

										$meta_output .= apply_filters( 'vcex_testimonials_slider_meta_rating', $meta_rating_output, $atts );

									$meta_output .= '</div>';

								endif;

								$output .= apply_filters( 'vcex_testimonials_slider_meta', $meta_output, $atts );

							$output .= '</div>';

						$output .= '</div>';

					endif;

				endwhile;

			$output .= '</div>';

			if ( 'true' == $control_thumbs ) {

				$output .= '<div class="wpex-slider-thumbnails sp-nc-thumbnails">';

					foreach ( $posts_cache as $post_id ) {

						$output .= vcex_get_post_thumbnail( array(
							'attachment' => get_post_thumbnail_id( $post_id ),
							'crop' => $control_thumbs_crop,
							'width' => $control_thumbs_width,
							'height' => $control_thumbs_height,
							'class' => 'sp-nc-thumbnail',
							'apply_filters' => 'vcex_testimonials_slider_nav_thumbnail_args',
							'filter_arg1' => $atts,
						) );

					}

				$output .= '</div>';

			} // end control_thumbs check.

		$output .= '</div>';

	$output .= '</div>';

	// Reset the post data to prevent conflicts with WP globals.
	wp_reset_postdata();

	// @codingStandardsIgnoreLine
	echo $output;

// If no posts are found display message.
else :

	// Display no posts found error if function exists.
	echo vcex_no_posts_found_message( $atts );

// End post check.
endif;