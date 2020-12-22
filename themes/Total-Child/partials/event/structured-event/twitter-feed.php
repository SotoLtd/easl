<?php
// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
    die( '-1' );
}
$twitter_feed      = get_field( 'ste_twitter_feed' );
$twitter_feed_type = '';
if ( ! empty( $twitter_feed['type'] ) ) {
    $twitter_feed_type = $twitter_feed['type'];
}
if ( ! $twitter_feed_type ) {
    $twitter_feed_type = 'screenname';
}
$twitter_feed_num = '';
if ( ! empty( $twitter_feed['num'] ) ) {
    $twitter_feed_num = absint( $twitter_feed['num'] );
}
if ( ! $twitter_feed_num ) {
    $twitter_feed_num = 3;
}
$twitter_feed_handle = '';
if ( ! empty( $twitter_feed['value'] ) ) {
    $twitter_feed_handle = $twitter_feed['value'];
}
$twitter_feed_bgcolor = '';
if ( ! empty( $twitter_feed['background_color'] ) ) {
    $twitter_feed_bgcolor = $twitter_feed['background_color'];
}
$twitter_feed_color_theme = '';
if ( ! empty( $twitter_feed['color_theme'] ) ) {
    $twitter_feed_color_theme = $twitter_feed['color_theme'];
}
if ( ! $twitter_feed_color_theme ) {
    $twitter_feed_color_theme = 'blue';
}
if ( $twitter_feed_handle ):
    if ( $twitter_feed_type == 'screenname' ) {
        $twitter_feed_handle = ltrim( $twitter_feed_handle, '@' );
    }
    if ( ( $twitter_feed_type == 'hashtag' ) && ( 0 !== strpos( $twitter_feed_handle, '#' ) ) ) {
        $twitter_feed_handle = '#' . $twitter_feed_handle;
    }
    $wrapper_class = 'ste-twitter-feed';
    if ( $twitter_feed_bgcolor ) {
        $wrapper_class .= ' ste-twitter-feed-bg';
    }
    $wrapper_class .= ' ste-twitter-feed-' . $twitter_feed_color_theme;
    ?>
    <div class="<?php echo $wrapper_class; ?>">
        <?php echo do_shortcode( '[custom-twitter-feeds ' . $twitter_feed_type . '="' . $twitter_feed_handle . '" num="' . $twitter_feed_num . '" include="author,date,text,avatar,logo" creditctf="false" showbutton="false" bgcolor="' . $twitter_feed_bgcolor . '" textcolor="" linktextcolor="" iconcolor="" logocolor="" headertextcolor=""]' ); ?>
    </div>
<?php endif; ?>