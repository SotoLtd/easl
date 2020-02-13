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
 * Shortcode class EASL_VC_Mentors
 * @var $this EASL_VC_Mentors
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
$rows = '';
$rows .= '
	<div id="prof-jean-francois-dufour" class="vc_tta-panel vc_active" data-vc-content=".vc_tta-panel-body">
		<div class="vc_tta-panel-heading">
			<h4 class="vc_tta-panel-title vc_tta-controls-icon-position-right">
				<a href="#prof-jean-francois-dufour" data-vc-accordion="" data-vc-container=".vc_tta-container">
					<span class="vc_tta-title-text">Prof. Thomas Berg, Germany</span><i class="vc_tta-controls-icon vc_tta-controls-icon-chevron"></i>
				</a>
			</h4>
		</div>
		<div class="vc_tta-panel-body">
			<div class="mentors-section-body clr">
				<p><img class="mentors-thumb alignleft" alt="" src="' . EASL_HOME_URL . '/wp-content/uploads/2018/09/Berg-e1537280922673-150x150.jpg"/>Professor Dr. Thomas Berg completed his medical training at the Universities of Tübingen, Freiburg and Berlin, Germany. He specialized in internal medicine in 2001 and in Gastroenterology and Hepatology in 2007 at the University Medicine Berlin, and became a lecturer in this subject in 2002. In 2002, Professor Berg took up the position of Associate Director and Professor of Medicine at the Department of Hepatology and Gastroenterology of Charité, Campus Virchow-Clinic, University Medicine in Berlin, where he was Head of the Liver Out-Patient Clinic and the Laboratory for Molecular Hepatitis and Viral Diagnostics.</p>
				<p>Since December 2009, he has been the Head of the Section of Hepatology at the University Hospital in Leipzig, Germany. His clinical and translational research is focused on chronic viral hepatitis, liver transplantation, hepatocellular carcinoma, genetics in liver disease, and liver failure, and he participated in numerous national and international clinical trials. He is Co-Editor of the Journal of Hepatology since 2014, and member of the American (AASLD), European (EASL), and German (GASL) Associations for the Study of the Liver, The European Society for Organ Transplantation (ESOT),  The European Liver and Intestine Transplant Association (ELITA), German Transplantation Society (DTG), Working Group  Internal Oncology (AiO), Working Group  Gastroenterological Oncology (AGO), German Cancer Society (DKG), German Society of Gastroenterology (DGVS), and the representative of the DGVS in the foundation council of the German liver foundation. </p>
				<p>He has published more than 350 articles in peer-reviewed journals and more than 100 reviews and textbook contributions. His h-index is 66 (Scopus).</p>
			</div>
		</div>
	</div>
	';
$rows .= '
	<div id="mario-strazzabosco" class="vc_tta-panel vc_active" data-vc-content=".vc_tta-panel-body">
		<div class="vc_tta-panel-heading">
			<h4 class="vc_tta-panel-title vc_tta-controls-icon-position-right">
				<a href="#mario-strazzabosco" data-vc-accordion="" data-vc-container=".vc_tta-container">
					<span class="vc_tta-title-text">Prof. Maurizia Brunetto, Italy</span><i class="vc_tta-controls-icon vc_tta-controls-icon-chevron"></i>
				</a>
			</h4>
		</div>
		<div class="vc_tta-panel-body">
			<div class="mentors-section-body clr">
				<p><img class="mentors-thumb alignleft" alt="" src="' . EASL_HOME_URL . '/wp-content/uploads/2018/09/Brunetto-photo-150x150.jpg"/>Maurizia R. Brunetto is Adjunct Professor of Internal Medicine at the University Pisa and Director of the Liver Unit and Laboratory of Molecular Genetics and Pathology of Hepatitis Viruses of Pisa University Hospital.
She received a 2-year (1982-1983) fellowship from the National Foundation of Cancer Research of Bethesda to study the role of  aldehyde metabolites and lipid peroxidation in liver damage, and 1 year (1993-1994) fellowship from the Human Science Foundation of Japan to study of hepatitis viruses heterogeneity. </p>
				<p>In 1989 she received the Young Investigator Award for the discovery of the HBeAg-defective HBV  mutant from the European Association fro the study of the Liver (EASL), and in 2007 the Young Investigator Award»of the Asian Pacific Association for the Study of the Liver (APSL) for the study of HBsAg serum levels kinetics during antiviral treatment in HBeAg negative chronic hepatitis B.</p>
				<p>Her research activity has been mainly focused on the study of the pathogenetic implications of hepatitis virus  (HBV, HCV and HDV) infection on the analysis of factors influencing the progression of chronic hepatitis to cirrhosis and hepatocellular carcinoma and on the treatment optimization of chronic viral hepatitis. She did seminal work in the clinic-pathologic characterization of the HBeAg negative form of chronic hepatitis B and its natural history and response to antiviral therapy. </p>
				<p>Google Scholar Citation Report (Aug 2018)<br>Citations 14968<br>h-Index         58</p>
			</div>
		</div>
	</div>
	';

$class_to_filter = 'wpb_easl_yi_meontors wpb_content_element ';
$class_to_filter .= vc_shortcode_custom_css_class( $css, ' ' ) . $this->getExtraClass( $el_class );
$css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, $class_to_filter, $this->settings['base'], $atts );


$html = '<div class="vc_tta-container mentors" data-vc-action="collapseAll">
			<div class="vc_general vc_tta vc_tta-accordion vc_tta-color-grey vc_tta-style-classic vc_tta-shape-rounded vc_tta-o-shape-group vc_tta-controls-align-left vc_tta-o-all-clickable">
				<div class="vc_tta-panels-container">
					<div class="vc_tta-panels">
						'. $rows .'
					</div>
				</div>
			</div>
		</div>';

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
//wp_get_archives();