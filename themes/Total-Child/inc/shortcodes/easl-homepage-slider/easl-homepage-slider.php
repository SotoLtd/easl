<?php
// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

class EASL_VC_Homepage_Slider extends WPBakeryShortCode {
	public static $slider_instance_count = 0;

	/**
	 * Get homepage slider dropdown data
	 * @return array
	 */
	public static function get_homepage_sliders_dd() {
		$_dd     = array( __( 'No homepage sliders found', 'total-child' ) => '' );
		$objects = get_posts( array(
			'post_type'      => EASL_Homepage_Slider_Config::get_slug(),
			'post_status'    => 'publish',
			'posts_per_page' => - 1,
			'order'          => 'ASC',
			'orderby'        => 'title',
		) );
		if ( ! $objects ) {
			return $_dd;
		}
		$_dd = array( __( 'Select an slider', 'total-child' ) => '' );
		foreach ( $objects as $object ) {
			$_dd[ get_the_title( $object->ID ) ] = $object->ID;
		}

		return $_dd;
	}

	public function escape_slider_text( $text ) {
		$text = wp_kses( $text, array(
			'br'     => array(),
			'a'      => array(
				'style'  => array(),
				'class'  => array(),
				'id'     => array(),
				'href'   => array(),
				'target' => array(),
			),
			'span'   => array(
				'style' => array(),
				'class' => array(),
				'id'    => array(),
			),
			'strong' => array(
				'style' => array(),
				'class' => array(),
				'id'    => array(),
			),
			'em'     => array(
				'style' => array(),
				'class' => array(),
				'id'    => array(),
			),
			'sup'     => array(
				'style' => array(),
				'class' => array(),
				'id'    => array(),
			),
			'sub'     => array(
				'style' => array(),
				'class' => array(),
				'id'    => array(),
			),
		) );
		$text = str_replace( array( "<br>\n", "<br/>\n", "\n", "\r" ), array( '<br/>', '<br/>', '<br/>', '' ), $text );

		return $text;
	}

	public function get_font_family( $font_family ) {
		$font_stack = '';
		switch ( $font_family ) {
			case 'arial':
				$font_stack = 'Arial, Helvetica, sans-serif';
				break;
			case 'knockout91':
				$font_stack = 'Helvetica Neue';
				break;
			case 'knockout51':
				$font_stack = 'Helvetica Neue';
				break;
			case 'knockout31':
				$font_stack = 'Helvetica Neue';
				break;
		}

		return $font_stack;
	}

	public function get_cta( $link, $color ) {
		if ( ! $link ) {
			return '';
		}
		if ( ! $link['url'] ) {
			return '';
		}
		$target_attr = '';
		if ( $link['target'] == '_blank' ) {
			$target_attr = ' target="_blank"';
		}
		if ( ! in_array( $color, array( 'blue', 'lightblue', 'red', 'teal', 'orange', 'gray', 'yellow' ) ) ) {
			$color = 'lightblue';
		}
		$link_html = '<a class="easl-generic-button easl-color-' . $color . '" href="' . esc_url( $link['url'] ) . '" ' . $target_attr . '>' . $link['title'] . ' <span class="easl-generic-button-icon"><span class="ticon ticon-chevron-right"></span></span></a>';

		return $link_html;
	}

	public function build_text_html( $text, $font_family, $font_size, $color, $class = '', $tag = 'div' ) {
		if ( ! $text ) {
			return '';
		}
		$styles = '';
		if ( $font_family ) {
			$styles .= 'font-family: ' . $font_family . ';';
		}
		if ( $font_size ) {
			$styles .= 'font-size: ' . $font_size . ';';
		}
		if ( $color ) {
			$styles .= 'color: ' . $color . ';';
		}

		if ( $styles ) {
			$styles = 'style="' . $styles . '"';
		}

		if ( ! $tag ) {
			$tag = 'div';
		}
		$class_attr = '';
		if ( $class ) {
			$class_attr = 'class="easl-hsc-' . $class . '"';
		}


		return "<{$tag} {$class_attr} {$styles}>{$text}</{$tag}>";
	}

	public function get_slider_settings( $slider_id ) {
		$settings                      = array();
		$settings['autoplay']          = get_field( 'autoplay', $slider_id );
		$settings['autoplay_duration'] = absint( get_field( 'autoplay_duration', $slider_id ) );
		if ( ! $settings['autoplay_duration'] ) {
			$settings['autoplay_duration'] = 5000;
		}

		return $settings;
	}

	public function get_slides( $slider_id ) {
		$slides_data = array();

		while ( have_rows( 'slides', $slider_id ) ) {
			the_row();
			if ( ! get_sub_field( 'is_active' ) ) {
				continue;
			}
			$slider_image     = get_sub_field( 'image' );
			$slider_image_alt = strip_tags( get_sub_field( 'image_alt' ) );
			$slider_image_pos = strip_tags( get_sub_field( 'image_pos' ) );
			if ( ! $slider_image ) {
				continue;
			}
			if ( ! $slider_image_pos ) {
				$slider_image_pos = "center center";
			}
			$slide_link    = get_sub_field( 'link' );
			$slide_link_nt = get_sub_field( 'link_nt' );
			$title         = $this->escape_slider_text( get_sub_field( 'title', false ) );
			$subtitle      = $this->escape_slider_text( get_sub_field( 'subtitle', false ) );
			$text          = $this->escape_slider_text( get_sub_field( 'text', false ) );

			$title_font_family    = $this->get_font_family( get_sub_field( 'title_font_family' ) );
			$subtitle_font_family = $this->get_font_family( get_sub_field( 'subtitle_font_family' ) );
			$text_font_family     = $this->get_font_family( get_sub_field( 'text_font_family' ) );

			$title_font_size    = get_sub_field( 'title_font_size' );
			$subtitle_font_size = get_sub_field( 'subtitle_font_size' );
			$text_font_size     = get_sub_field( 'text_font_size' );

			$title_color    = get_sub_field( 'title_color' );
			$subtitle_color = get_sub_field( 'subtitle_color' );
			$text_color     = get_sub_field( 'text_color' );

			$cta1 = $this->get_cta( get_sub_field( 'cta_1' ), get_sub_field( 'cta_1_color' ) );
			$cta2 = $this->get_cta( get_sub_field( 'cta_2' ), get_sub_field( 'cta_2_color' ) );

			if ( ! $slider_image_alt ) {
				$slider_image_alt = strip_tags( $title );
			}

			$data_atts       = array(
				'data-transition="fade"',
			);
			$slide_link_html = '';
			if ( $slide_link ) {
				if ( $slide_link_nt ) {
					$slide_link_nt = 'target="_blank"';
				} else {
					$slide_link_nt = '';
				}
				$slide_link_html = '<a class="easl-hsp-caption-link" href="' . esc_url( $slide_link ) . '" ' . $slide_link_nt . '></a>';
			}

			$slides_data[] = array(
				'image'     => $slider_image,
				'image_alt' => $slider_image_alt,
				'image_pos' => $slider_image_pos,
				'link_html' => $slide_link_html,
				'title'     => $this->build_text_html( $title, $title_font_family, $title_font_size, $title_color, 'easl-hsc-title', 'h3' ),
				'subtitle'  => $this->build_text_html( $subtitle, $subtitle_font_family, $subtitle_font_size, $subtitle_color, 'easl-hsc-subtitle', 'h4' ),
				'text'      => $this->build_text_html( $text, $text_font_family, $text_font_size, $text_color, 'easl-hsc-text', 'p' ),
				'cta1'      => $cta1,
				'cta2'      => $cta2,
				'data_atts' => implode( ' ', $data_atts ),
			);
		}

		return $slides_data;
	}

	public function enqueue_slider_assets() {
		wp_enqueue_style( 'revolutioncss', get_stylesheet_directory_uri() . '/assets/lib/slider-revolution/css/combined.css' );
		wp_enqueue_script( 'revolutionjs', get_stylesheet_directory_uri() . '/assets/lib/slider-revolution/js/jquery.themepunch.revolution-all.min.js', array( 'jquery' ), '5.4.8', true );
		wp_enqueue_script( 'easl_homepage_slider', get_stylesheet_directory_uri() . '/assets/js/home-page-slider.js', array(
			'jquery',
			'revolutionjs'
		), EASL_THEME_VERSION, true );
	}

}

vc_lean_map( 'easl_homepage_slider', null, get_theme_file_path( 'inc/shortcodes/easl-homepage-slider/map.php' ) );