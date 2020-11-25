<?php
// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

trait EASL_SC_Common_Methods {
	/**
	 * @return mixed
	 */
	protected function geTemplatePathForThisSC() {
		$file_name = str_replace( 'easl_', '', $this->shortcode );
		$file_name = strtolower( $file_name );
		$file_name = str_replace( '_', '-', $file_name );

		return get_stylesheet_directory() . "/inc/shortcodes-v2/{$file_name}/template.php";
	}

	/**
	 * Find html template for shortcode output.
	 */
	protected function findShortcodeTemplate() {
		// Check template path in shortcode's mapping settings
		if ( ! empty( $this->settings['html_template'] ) && is_file( $this->settings( 'html_template' ) ) ) {
			return $this->setTemplate( $this->settings['html_template'] );
		}

		// Check template in theme directory
		$user_template = vc_shortcodes_theme_templates_dir( $this->getFileName() . '.php' );
		if ( is_file( $user_template ) ) {
			return $this->setTemplate( $user_template );
		}
		// Check plugin dir
		$sc_template = $this->geTemplatePathForThisSC();

		if ( is_file( $sc_template ) ) {
			return $this->setTemplate( $sc_template );
		}
		// Check default place
		$default_dir = vc_manager()->getDefaultShortcodesTemplatesDir() . '/';
		if ( is_file( $default_dir . $this->getFileName() . '.php' ) ) {
			return $this->setTemplate( $default_dir . $this->getFileName() . '.php' );
		}

		return '';
	}
	public function easlGetSettings($key){
		$settings = $this->getSettings();
		if(isset($settings[$key])){
			return $settings[$key];
		}
		return '';
	}

	public function easlGetCssClass($el_class, $css, $atts) {
        $class_to_filter = '';
        $class_to_filter .= vc_shortcode_custom_css_class( $css, ' ' ) . $this->easlGetSettings( $el_class );
        return apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, $class_to_filter, $this->settings['base'], $atts );
    }
}

class EASL_ShortCode extends WPBakeryShortCode {
	use EASL_SC_Common_Methods;
}


class EASL_ShortCode_Container extends WPBakeryShortCodesContainer {
	use EASL_SC_Common_Methods;
}