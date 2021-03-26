<?php
// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
    die( '-1' );
}

?>
<p class="easl-news-list-meta">
    <span class="easl-news-list-date"></span><?php echo wpex_date_format( array(
        'id'     => get_the_ID(),
        'format' => 'd/m/y',
    ) ); ?></span>
    <?php
    $categories = get_the_term_list( get_the_ID(), 'blog_category', '', ',', '' );
    if ( $categories ):
        ?>
        - <span class="easl-news-list-source"><?php echo $categories; ?></span>
    <?php endif; ?>
</p>