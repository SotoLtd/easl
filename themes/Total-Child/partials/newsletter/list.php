<?php
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

if( function_exists('get_field')){
    $url = get_field('newsletter_url');
    $newtab = get_field('open_in_new_tab');
}else{
	$url = get_post_meta(get_the_ID(), 'newsletter_url', true);
	$newtab = get_post_meta(get_the_ID(), 'open_in_new_tab', true);
}
?>
        <li>
            <a href="<?php echo esc_url($url); ?>"<?php if($newtab){echo ' target="_blank"';} ?>><?php the_title(); ?></a>
        </li>
