<?php
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

$topics = get_terms( array(
	'taxonomy' => EASL_Event_Config::get_topic_slug(),
	'hide_empty' => false,
	'orderby' => 'name',
	'order' => 'ASC',
	'fields' => 'all',
) );
$current_item_name = __('All topics', 'total-child');
$current_item_color = 'lightblue';
$current_page_url = remove_query_arg('recent_items_topic');
$current_topic = !empty($_GET['recent_items_topic']) ? $_GET['recent_items_topic'] : '';
if($current_topic == 'all') {
	$current_topic = '';
}
$topics_lis = '';
if(is_array($topics) && count($topics) > 0):
	foreach ($topics as $topic) {
		$topic_color = 'blue';
		$topic_color = get_term_meta( $topic->term_id, 'easl_tax_color', true );
		if ( ! $topic_color ) {
			$topic_color = 'blue';
		}
		$topics_class = 'easl-ri-topics-item easl-color-' . $topic_color;
		if ( $topic->slug == $current_topic ) {
			$current_item_name = esc_html( $topic->name );
			$current_item_color = $topic_color;
			$topics_class      .= ' easl-active';
		}
		$topics_lis .= '<li class="' . $topics_class . '">';
		$topics_lis .= '<a href="' . add_query_arg( array(
				'recent_items_topic' => $topic->slug
			), $current_page_url ) . '"><span>' . esc_html( $topic->name ) . '</span></a>';
		$topics_lis .= '</li>';
	}
?>
<div class="easl-recent-items-topics easl-dropdown">
	<span class="easl-dropdown-label easl-color-<?php echo $current_item_color; ?>"><?php echo $current_item_name; ?></span>
	<ul class="easl-ri-topics-list">
		<li class="easl-ri-topics-item easl-color-lightblue"><a href="<?php echo add_query_arg(array('recent_items_topic' => 'all'), $current_page_url); ?>"><span><?php _e('All topics', 'total-child'); ?></span></a></li>
		<?php echo $topics_lis; ?>
	</ul>
</div>
<?php endif; ?>