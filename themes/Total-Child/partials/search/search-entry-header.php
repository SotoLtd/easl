<?php
/**
 * Search entry header
 *
 * @package Total WordPress theme
 * @subpackage Partials
 * @version 4.5.4.2
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
$permalink = wpex_get_permalink();
if(get_post_type() == 'associations'){
	$permalink = wpex_get_mod('nat_associaitons_page', '#');
	$nas_cat = wp_get_post_terms(get_the_ID(),'associations_category', array('orderby' => 'name', 'order' => 'ASC', 'number' => 1, 'fields' => 'ids'));
	if($nas_cat) {
		$permalink = add_query_arg( array( 'nas_id' => $nas_cat[0] ), $permalink );
	}
}elseif(get_post_type() == Annual_Reports_Config::get_annual_reports_slug()){
	$permalink = wpex_get_mod('annual_reports_page', '#');
	if($permalink) {
		$permalink = add_query_arg( array( 'annual_report_id' => get_the_ID() ), $permalink );
	}
}
?>

<header class="search-entry-header clr"><h2 class="search-entry-header-title entry-title"><a href="<?php echo $permalink; ?>"><?php the_title(); ?></a></h2></header>