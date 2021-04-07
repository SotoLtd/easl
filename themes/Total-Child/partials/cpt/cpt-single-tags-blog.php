<?php
// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}
echo get_the_term_list( get_the_ID(), 'blog_tag', '<div class="easl-tags post-tags clr">','','</div>' );