<?php
// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
    die( '-1' );
}
$current_sub_page = get_query_var( 'easl_event_subpage' );

$draft_or_pending = get_post_status( get_the_ID() ) && in_array( get_post_status( get_the_ID() ), array( 'draft', 'pending', 'auto-draft', 'future' ) );
?>
<div class="ste-menu-wrap">
    <?php if ( have_rows( 'event_subpages' ) ): ?>
        <ul class="ste-menu">
            <?php while ( have_rows( 'event_subpages' ) ):
                the_row( 'event_subpages' );
                if ( ! current_user_can( 'edit_posts' ) && 'draft' == get_sub_field( 'status' ) ) {
                    continue;
                }
                $title = get_sub_field( 'title' );
                if ( ! $title ) {
                    continue;
                }
                $slug = trim( get_sub_field( 'slug' ) );
                $url  = get_permalink();
                if ( $slug ) {
                    if($draft_or_pending) {
                        $url = add_query_arg(array('easl_event_subpage' => $slug), $url);
                    }else{
                        $url = trailingslashit(untrailingslashit( get_permalink() ) . '/' . $slug);
                    }
                }

                $item_class = 'easl-ste-menu-item';
                if ( $current_sub_page == $slug ) {
                    $item_class .= ' easl-active';
                }
                ?>
                <li class="<?php echo $item_class; ?>">
                    <a href="<?php echo esc_url( $url ); ?>"><?php echo $title; ?></a></li>
            <?php endwhile; ?>
        </ul>
    <?php endif; ?>
    <?php if ( have_rows( 'ste_menu_bellow_widgets' ) ) {
        while ( have_rows( 'ste_menu_bellow_widgets' ) ) {
            the_row();
            $widget_type = get_sub_field( 'type' );
            if ( $widget_type ) {
                $widget_type = str_replace( '_', '-', $widget_type );
                get_template_part( 'partials/event/widgets/' . $widget_type );
            }
        }
    } ?>
    
    <?php
    $twitter_feed      = get_field( 'ste_twitter_feed' );
    $twitter_feed_type = '';
    if ( !empty( $twitter_feed['type'] ) ) {
        $twitter_feed_type = $twitter_feed['type'];
    }
    if ( ! $twitter_feed_type ) {
        $twitter_feed_type = 'screenname';
    }
    $twitter_feed_num = '';
    if ( !empty( $twitter_feed['num'] ) ) {
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
    if(!$twitter_feed_color_theme) {
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
        if($twitter_feed_bgcolor) {
            $wrapper_class .= ' ste-twitter-feed-bg';
        }
        $wrapper_class .= ' ste-twitter-feed-' . $twitter_feed_color_theme;
        ?>
        <div class="<?php echo $wrapper_class; ?>">
            <?php echo do_shortcode('[custom-twitter-feeds ' . $twitter_feed_type . '="' . $twitter_feed_handle . '" num="' . $twitter_feed_num . '" include="author,date,text,avatar,logo" creditctf="false" showbutton="false" bgcolor="'. $twitter_feed_bgcolor .'" textcolor="" linktextcolor="" iconcolor="" logocolor="" headertextcolor=""]'); ?>
        </div>
    <?php endif; ?>
</div>