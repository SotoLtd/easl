<?php
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

/**
 * Shortcode attributes
 * @var $atts
 * @var $title
 * @var $el_class
 * @var $el_id
 * @var $css
 * Shortcode class EASL_VC_Mentors_Table
 * @var $this EASL_VC_Mentors_Table
 */
$title = $element_width = $view_all_link = $view_all_url = $view_all_text = $el_class = $el_id = $css_animation = '';
$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts );

$css_animation = $this->getCSSAnimation($css_animation);

if(!$view_all_text){
	$view_all_text = 'View all Events';
}

if($title && $view_all_link){
	$title .= '<a class="easl-events-all-link" href="'. esc_url($view_all_url) .'">' . $view_all_text . '</a>';
}
easlenqueueTtaScript();
$head_row = '
	<div class="easl-mentors-table-row easl-mentors-table-head">
		<div class="easl-mentors-table-col">Year</div>
		<div class="easl-mentors-table-col">Mentor</div>
		<div class="easl-mentors-table-col">Mentee</div>
	</div>
	';
$rows = '';
$rows .= '
	<div class="easl-mentors-table-row">
		<div class="easl-mentors-table-col">
			<span class="emt-year">2018</span>
		</div>
		<div class="easl-mentors-table-col">
			<div class="emt-intro clr">
				<div class="emt-thumb">
					<img alt="" src="' . EASL_HOME_URL . '/wp-content/uploads/2018/09/Peter-Galle-150x150.png"/>
				</div>
				<div class="emt-mbio">
					<h5>Peter R. Galle, MD, PhD</h5>
					<p></p>
				</div>
			</div>
		</div>
		<div class="easl-mentors-table-col">
			<div class="emt-intro clr">
				<div class="emt-thumb">
					<img alt="" src="' . EASL_HOME_URL . '/wp-content/uploads/2018/09/Marta_Alfonso-e1537277880994-150x150.jpg"/>
				</div>
				<div class="emt-mbio">
					<h5>Marta Alfonso</h5>
					<p></p>
				</div>
			</div>
		</div>
	</div>
	';
$rows .= '
	<div class="easl-mentors-table-row">
		<div class="easl-mentors-table-col">
			<span class="emt-year">2018</span>
		</div>
		<div class="easl-mentors-table-col">
			<div class="emt-intro clr">
				<div class="emt-thumb">
					<img alt="" src="' . EASL_HOME_URL . '/wp-content/uploads/2018/09/Olivier-Chazouilleres.png"/>
				</div>
				<div class="emt-mbio">
					<h5>Prof. Olivier Chazouilleres, MD</h5>
					<p></p>
				</div>
			</div>
		</div>
		<div class="easl-mentors-table-col">
			<div class="emt-intro clr">
				<div class="emt-thumb">
					<img alt="" src="' . EASL_HOME_URL . '/wp-content/uploads/2018/09/Iuliana_Nenu-e1537278277659-150x150.jpg"/>
				</div>
				<div class="emt-mbio">
					<h5>Iuliana Nenu</h5>
					<p></p>
				</div>
			</div>
		</div>
	</div>
	';
$rows .= '
	<div class="easl-mentors-table-row">
		<div class="easl-mentors-table-col">
			<span class="emt-year">2017</span>
		</div>
		<div class="easl-mentors-table-col">
			<div class="emt-intro clr">
				<div class="emt-thumb">
					<img alt="" src="' . EASL_HOME_URL . '/wp-content/uploads/2018/09/Dufour_JF-e1537278461487.jpg"/>
				</div>
				<div class="emt-mbio">
					<h5>Prof. Jean Dufour</h5>
					<p>University Clinic Visceral Surgery and Medicine, Switzerland</p>
				</div>
			</div>
		</div>
		<div class="easl-mentors-table-col">
			<div class="emt-intro clr">
				<div class="emt-thumb">
					<img alt="" src="' . EASL_HOME_URL . '/wp-content/uploads/2018/09/Maria-Chiara-Sorbo-150x150.jpg"/>
				</div>
				<div class="emt-mbio">
					<h5>Dr Maria Chiara Sorbo</h5>
					<p>Italy</p>
				</div>
			</div>
		</div>
	</div>
	';
$rows .= '
	<div class="easl-mentors-table-row old-rows hidden">
		<div class="easl-mentors-table-col">
			<span class="emt-year">2017</span>
		</div>
		<div class="easl-mentors-table-col">
			<div class="emt-intro clr">
				<div class="emt-thumb">
					<img alt="" src="' . EASL_HOME_URL . '/wp-content/uploads/2018/09/Strazzabosco_Mario_7403-e1537277439832-150x150.jpg"/>
				</div>
				<div class="emt-mbio">
					<h5>Prof. Mario Strazzabosco</h5>
					<p>Università di Milano-Bicocca & Yale School of Medicine, Italy</p>
				</div>
			</div>
		</div>
		<div class="easl-mentors-table-col">
			<div class="emt-intro clr">
				<div class="emt-thumb">
					<img alt="" src="' . EASL_HOME_URL . '/wp-content/uploads/2018/09/Dr-Emma-Andersson-150x150.jpg"/>
				</div>
				<div class="emt-mbio">
					<h5>Dr Emma Andersson</h5>
					<p>Sweden</p>
				</div>
			</div>
		</div>
	</div>
	';
$rows .= '
	<div class="easl-mentors-table-row old-rows hidden">
		<div class="easl-mentors-table-col">
			<span class="emt-year">2017</span>
		</div>
		<div class="easl-mentors-table-col">
			<div class="emt-intro clr">
				<div class="emt-thumb">
					<img alt="" src="' . EASL_HOME_URL . '/wp-content/uploads/2018/09/Trauner18_10x15cm-e1537277710246-150x150.jpg"/>
				</div>
				<div class="emt-mbio">
					<h5>Prof. Michael Trauner, MD</h5>
					<p>Division of Gastroenterology and Hepatology, Medical University of Vienna, Austria</p>
				</div>
			</div>
		</div>
		<div class="easl-mentors-table-col">
			<div class="emt-intro clr">
				<div class="emt-thumb">
					<img alt="" src="' . EASL_HOME_URL . '/wp-content/uploads/2018/09/Vitor-pereira-e1537279736738.jpg"/>
				</div>
				<div class="emt-mbio">
					<h5>Dr Vitor Magno Pereira</h5>
					<p>Portugal</p>
				</div>
			</div>
		</div>
	</div>
	';
$rows .= '
	<div class="easl-mentors-table-row old-rows  hidden">
		<div class="easl-mentors-table-col">
			<span class="emt-year">2016</span>
		</div>
		<div class="easl-mentors-table-col">
			<div class="emt-intro clr">
				<div class="emt-thumb">
					<img alt="" src="' . EASL_HOME_URL . '/wp-content/uploads/2018/09/Marsha-Y-Morgan.jpg"/>
				</div>
				<div class="emt-mbio">
					<h5>Prof. Marsha Y Morgan, MD</h5>
					<p>Qualified in medicine with distinction and undertook her early clinical training in Manchester and London.</p>
				</div>
			</div>
		</div>
		<div class="easl-mentors-table-col">
			<div class="emt-intro clr">
				<div class="emt-thumb">
					<img alt="" src="' . EASL_HOME_URL . '/wp-content/uploads/2018/09/nina-kimer.jpg"/>
				</div>
				<div class="emt-mbio">
					<h5>Dr. Nina Kimer</h5>
					<p>Hvidovre, Denmark</p>
				</div>
			</div>
		</div>
	</div>
	';
$rows .= '
	<div class="easl-mentors-table-row old-rows hidden">
		<div class="easl-mentors-table-col">
			<span class="emt-year">2016</span>
		</div>
		<div class="easl-mentors-table-col">
			<div class="emt-intro clr">
				<div class="emt-thumb">
					<img alt="" src="' . EASL_HOME_URL . '/wp-content/uploads/2018/09/Beuers-Ulrich-e1537279945164.jpg"/>
				</div>
				<div class="emt-mbio">
					<h5>Prof. dr. U.H.W. Beuers</h5>
					<p>Head of Hepatology, and Professor and Programme Director of Gastroenterology and Hepatology at the Academic Medical Centre (AMC) of the University of Amsterdam.</p>
				</div>
			</div>
		</div>
		<div class="easl-mentors-table-col">
			<div class="emt-intro clr">
				<div class="emt-thumb">
					<img alt="" src="' . EASL_HOME_URL . '/wp-content/uploads/2018/09/Sharat-Varma-e1537278625177-150x150.jpg"/>
				</div>
				<div class="emt-mbio">
					<h5>Dr. Sharat Varma</h5>
					<p>Brussels, Belgium </p>
				</div>
			</div>
		</div>
	</div>
	';
$rows .= '
	<div class="easl-mentors-table-row old-rows hidden">
		<div class="easl-mentors-table-col">
			<span class="emt-year">2015</span>
		</div>
		<div class="easl-mentors-table-col">
			<div class="emt-intro clr">
				<div class="emt-thumb">
					<img alt="" src="' . EASL_HOME_URL . '/wp-content/uploads/2018/09/Prof-Dominique-Charles-Valla-e1537278725346.jpg"/>
				</div>
				<div class="emt-mbio">
					<h5>Prof. Dominique-Charles Valla</h5>
					<p>Professor of hepatology at Université Paris Diderot and Hôpital Beaujon (APHP, Clichy), Paris.</p>
				</div>
			</div>
		</div>
		<div class="easl-mentors-table-col">
			<div class="emt-intro clr">
				<div class="emt-thumb">
					<img alt="" src="' . EASL_HOME_URL . '/wp-content/uploads/2018/09/Georgina-Minzala-romania-Prof-Valla-e1537279102641-150x150.jpg"/>
				</div>
				<div class="emt-mbio">
					<h5>Dr Georgina-Minzala</h5>
					<p>Romania</p>
				</div>
			</div>
		</div>
	</div>
	';
$rows .= '
	<div class="easl-mentors-table-row old-rows hidden">
		<div class="easl-mentors-table-col">
			<span class="emt-year">2015</span>
		</div>
		<div class="easl-mentors-table-col">
			<div class="emt-intro clr">
				<div class="emt-thumb">
					<img alt="" src="' . EASL_HOME_URL . '/wp-content/uploads/2018/09/Michael-Manns-e1537279204202-150x150.jpg"/>
				</div>
				<div class="emt-mbio">
					<h5>Prof. Michael P. Manns, MD</h5>
					<p>Director of the Department of Gastroenterology, Hepatology and Endocrinology at the Medical School of Hannover in Germany.</p>
				</div>
			</div>
		</div>
		<div class="easl-mentors-table-col">
			<div class="emt-intro clr">
				<div class="emt-thumb">
					<img alt="" src="' . EASL_HOME_URL . '/wp-content/uploads/2018/09/Dr.-Jelena-Martinov-Serbia-Michael-Manns-mentee-150x150.jpg"/>
				</div>
				<div class="emt-mbio">
					<h5>Dr Jelena Martinov</h5>
					<p>Serbia</p>
				</div>
			</div>
		</div>
	</div>
	';
$rows .= '
	<div class="easl-mentors-table-row old-rows hidden">
		<div class="easl-mentors-table-col">
			<span class="emt-year">2014</span>
		</div>
		<div class="easl-mentors-table-col">
			<div class="emt-intro clr">
				<div class="emt-thumb">
					<img alt="" src="' . EASL_HOME_URL . '/wp-content/uploads/2018/09/Peter-Ferenci-e1537279353130.jpg"/>
				</div>
				<div class="emt-mbio">
					<h5>Prof. Dr Peter Ferenci, MD</h5>
					<p>Medical University of Vienna, Austria.</p>
				</div>
			</div>
		</div>
		<div class="easl-mentors-table-col">
			<div class="emt-intro clr">
				<div class="emt-thumb">
					<img alt="" src="' . EASL_HOME_URL . '/wp-content/uploads/2018/09/Natalia-Tikhonova.jpg"/>
				</div>
				<div class="emt-mbio">
					<h5>Natalia Tikhonova</h5>
					<p>Moscow, Russia</p>
				</div>
			</div>
		</div>
	</div>
	';
$rows .= '
	<div class="easl-mentors-table-row old-rows hidden">
		<div class="easl-mentors-table-col">
			<span class="emt-year">2014</span>
		</div>
		<div class="easl-mentors-table-col">
			<div class="emt-intro clr">
				<div class="emt-thumb">
					<img alt="" src="' . EASL_HOME_URL . '/wp-content/uploads/2018/09/Peter-Jansen-e1537279628173.jpg"/>
				</div>
				<div class="emt-mbio">
					<h5>Prof. Peter L.M. Jansen, MD, PhD</h5>
					<p>Prof, Gastroenterology and Hepatology, Academic Medical Center, Amsterdam, The Netherlands.</p>
				</div>
			</div>
		</div>
		<div class="easl-mentors-table-col">
			<div class="emt-intro clr">
				<div class="emt-thumb">
					<img alt="" src="' . EASL_HOME_URL . '/wp-content/uploads/2018/09/Alina-Habic-new.png"/>
				</div>
				<div class="emt-mbio">
					<h5>Alina Habic</h5>
					<p>Romania</p>
				</div>
			</div>
		</div>
	</div>
	';
$rows .= '
	<div class="easl-mentors-table-row old-rows hidden">
		<div class="easl-mentors-table-col">
			<span class="emt-year">2013</span>
		</div>
		<div class="easl-mentors-table-col">
			<div class="emt-intro clr">
				<div class="emt-thumb">
					<img alt="" src="' . EASL_HOME_URL . '/wp-content/uploads/2018/09/massimo-colombo.jpg"/>
				</div>
				<div class="emt-mbio">
					<h5>Prof. Massimo G. Colombo, MD</h5>
					<p>Professor of Gastroenterology, University of Milan, Italy.</p>
				</div>
			</div>
		</div>
		<div class="easl-mentors-table-col">
			<div class="emt-intro clr">
				<div class="emt-thumb">
					<img alt="" src="' . EASL_HOME_URL . '/wp-content/uploads/2018/09/dr-bulent-baran-e1537280149626.jpg"/>
				</div>
				<div class="emt-mbio">
					<h5>Bulent Baran</h5>
					<p>Turkey</p>
				</div>
			</div>
		</div>
	</div>
	';
$rows .= '
	<div class="easl-mentors-table-row old-rows hidden">
		<div class="easl-mentors-table-col">
			<span class="emt-year">2013</span>
		</div>
		<div class="easl-mentors-table-col">
			<div class="emt-intro clr">
				<div class="emt-thumb">
					<img alt="" src="' . EASL_HOME_URL . '/wp-content/uploads/2018/09/Jaime-Bosch-e1537280049200.jpg"/>
				</div>
				<div class="emt-mbio">
					<h5>Dr Jaime Bosch</h5>
					<p>Professor of Medicine and Chief of Hepatology Section at the IMD, Hospital Clinic, University of Barcelona, Spain.</p>
				</div>
			</div>
		</div>
		<div class="easl-mentors-table-col">
			<div class="emt-intro clr">
				<div class="emt-thumb">
					<img alt="" src="' . EASL_HOME_URL . '/wp-content/uploads/2018/09/Bogdan-Procopet-150x150.jpg"/>
				</div>
				<div class="emt-mbio">
					<h5>Bogdan Procopet</h5>
					<p>Romania</p>
				</div>
			</div>
		</div>
	</div>
	';
$class_to_filter = 'wpb_easl_yi_meontors wpb_content_element ';
$class_to_filter .= vc_shortcode_custom_css_class( $css, ' ' ) . $this->getExtraClass( $el_class );
$css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, $class_to_filter, $this->settings['base'], $atts );


$html = '<div class="easl-mentors-table-wrap">
			<div class="easl-mentors-table">
			'. $head_row .'
			'. $rows .'
			</div>
		</div>';

$html .= '<div class="vc_empty_space" style="height: 16px"><span class="vc_empty_space_inner"></span></div>
          <a href="#" class="vcex-button theme-button inline animate-on-hover show-more easl-mentors-table-show-more unshown">
                <span class="theme-button-inner">Show more</span>
          </a>
          <div class="vc_empty_space" style="height: 38px"><span class="vc_empty_space_inner"></span></div>';

$html .=  do_shortcode('[vcex_social_share style="custom" sites="%5B%7B%22site%22%3A%22twitter%22%7D%2C%7B%22site%22%3A%22facebook%22%7D%2C%7B%22site%22%3A%22linkedin%22%7D%5D"]');


$wrapper_attributes = array();
if ( ! empty( $el_id ) ) {
	$wrapper_attributes[] = 'id="' . esc_attr( $el_id ) . '"';
}
$output = '
	<div ' . implode( ' ', $wrapper_attributes ) . ' class="' . esc_attr( trim( $css_class ) ) . '">
		' . wpb_widget_title( array( 'title' => $title, 'extraclass' => 'wpb_easl_widget_heading' ) ) . '
			' . $html . '
	</div>
';

echo $output;

echo '<div class="vc_row wpb_row vc_row-fluid">
        <div class="wpb_column vc_column_container vc_col-sm-12">
            <div class="vc_column-inner " style="margin-bottom: 0">
                <div class="wpb_wrapper">
                    <div style="float: left;margin-right: 10px;font-family: \'Helvetica Neue\';
font-size: 16px;
font-weight: normal;color:#104f85;">Share this page</div>
                    <div class="wpex-social-share position-horizontal style-custom display-block" style="margin-bottom: 0"
                         data-source="<?php echo get_bloginfo(\'url\')?>"
                         data-url="<?php the_permalink();?>"
                         data-title="<?php the_title();?>"
                         data-specs="menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600">
                        <ul class="clr">
                            <li class="wpex-twitter">
                                <a role="button" tabindex="1" style="background-image: url(\'/wp-content/themes/Total-Child/images/title-icons/tw.png\');
background-repeat: no-repeat;
background-position: top left;
background-size: cover;
height: 25px;
width: 25px;
display: block;">

                                </a>
                            </li>
                            <li class="wpex-facebook">
                                <a role="button" tabindex="1" style="background-image: url(\'/wp-content/themes/Total-Child/images/title-icons/f.png\');
background-repeat: no-repeat;background-position: top left; background-size: cover;
height: 25px;
width: 25px;
display: block;">

                                </a>
                            </li>
                            <li class="wpex-linkedin">
                                <a role="button" tabindex="1" style="background-image: url(\'/wp-content/themes/Total-Child/images/title-icons/in.png\');
background-repeat: no-repeat;background-position: top left; background-size: cover;
height: 25px;
width: 25px;
display: block;">
                                </a>
                            </li>

                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>';