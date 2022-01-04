<?php
if ( ! defined( 'ABSPATH' ) ) {
    die( '-1' );
}
/**
 * Shortcode attributes
 * @var $atts
 * @var $content - shortcode content
 * Shortcode class
 * @var $this    EASL_VC_Youtube_Player
 */
$el_class         = $el_id = $css = $css_animation = '';
$video_id         = '';
$video_start      = '';
$video_end        = '';
$autoplay         = '';
$mute             = '';
$controls         = '';
$modestbranding   = '';
$widget_title     = '';
$title_pos        = '';
$cover_image_type = '';
$cover_image      = '';
$play_icon_pos    = '';
$lightbox         = '';

$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts );

$css_class = 'wpb_easl_yt_player wpb_content_element ' . $this->easlGetCssClass( $el_class, $css, $atts );


$wrapper_attributes = array();
if ( ! empty( $atts['el_id'] ) ) {
    $wrapper_attributes[] = 'id="' . esc_attr( $atts['el_id'] ) . '"';
}

$widget_title = trim( $widget_title );
$video_id     = trim( $video_id );
$video_start  = absint( trim( $video_start ) );
$video_end    = absint( trim( $video_end ) );

$autoplay       = 'true' == $autoplay ? 1 : 0;
$controls       = 'true' == $controls ? 1 : 0;
$modestbranding = 'true' == $modestbranding ? 1 : 0;
$mute           = 'true' == $mute ? 1 : 0;
$lightbox       = 'true' == $lightbox ? 1 : 0;

if ( ! in_array( $title_pos, array( 'top', 'bottom' ) ) ) {
    $title_pos = 'top';
}

if(!$play_icon_pos) {
    $play_icon_pos = 'center-middle';
}
$css_class .= ' play-icon-pos-' . $play_icon_pos;
$video_id = $this->get_video_id_from_input($video_id);
if ( $video_id ):
    if ( $lightbox ) {
        wp_enqueue_script( 'fancybox', get_stylesheet_directory_uri() . '/assets/js/jquery.fancybox.min.js', array( 'jquery' ), '3.5.7', true );
        wp_enqueue_style( 'fancybox', get_stylesheet_directory_uri() . '/assets/css/jquery.fancybox.min.css' );
    }
    $this->load_scripts();
    $player_data = array(
        'data-id="' . $video_id . '"',
        'data-autoplay="' . $autoplay . '"',
        'data-controls ="' . $controls . '"',
        'data-modestbranding ="' . $modestbranding . '"',
        'data-mute="' . $mute . '"',
        'data-start="' . $video_start . '"',
        'data-end="' . $video_end . '"',
    );
    if ( 'media_lib' == $cover_image_type ) {
        $cover_image = $this->get_attachment_url( $cover_image );
    } else {
        $cover_image = 'https://img.youtube.com/vi/' . $video_id . '/hqdefault.jpg';
    }
    ?>
    <div class=" <?php echo esc_attr( $css_class ); ?>" <?php echo implode( ' ', $wrapper_attributes ); ?>
            xmlns="http://www.w3.org/1999/html">
        <?php
        if ( $widget_title && 'top' == $title_pos ) {
            echo '<h2 class="wpb_heading easl-yt-player-heading easl-yt-player-top">' . $widget_title . '</h2>';
        }
        ?>
        <div class="easl-yt-player-wrap">
            <div class="easl-yt-player-inner">
                <div class="easl-yt-player-container<?php if ( $lightbox ) {
                    echo ' easl-yt-player-lightbox';
                } ?>" <?php echo implode( ' ', $player_data ); ?>>
                    <?php if ( $cover_image ): ?>
                        <div class="easl-yt-player-cover-image"
                                style="background-image: url('<?php echo esc_url( $cover_image ); ?>');"></div>
                    <?php endif; ?>
                    <button class="easl-yt-player-play-button">
                        <svg height="100%" version="1.1" viewBox="0 0 68 48" width="100%">
                            <path class="easl-yt-player-play-button-bg"
                                    d="M66.52,7.74c-0.78-2.93-2.49-5.41-5.42-6.19C55.79,.13,34,0,34,0S12.21,.13,6.9,1.55 C3.97,2.33,2.27,4.81,1.48,7.74C0.06,13.05,0,24,0,24s0.06,10.95,1.48,16.26c0.78,2.93,2.49,5.41,5.42,6.19 C12.21,47.87,34,48,34,48s21.79-0.13,27.1-1.55c2.93-0.78,4.64-3.26,5.42-6.19C67.94,34.95,68,24,68,24S67.94,13.05,66.52,7.74z"
                                    fill="#212121" fill-opacity="0.8"></path>
                            <path d="M 45,24 27,14 27,34" fill="#fff"></path>
                        </svg>
                    </button>
                    <div class="easl-ytp-spinner">
                        <div class="easl-ytp-spinner-container">
                            <div class="easl-ytp-spinner-rotator">
                                <div class="easl-ytp-spinner-left">
                                    <div class="easl-ytp-spinner-circle"></div>
                                </div>
                                <div class="easl-ytp-spinner-right">
                                    <div class="easl-ytp-spinner-circle"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php if ( $lightbox ): ?>
                        <a class="easl-yt-player-trigger easl-yt-player-trigger-lightbox" data-fancybox target="_blank"
                                href="https://www.youtube.com/watch?v=<?php echo $video_id; ?>">&nbsp;</a>
                    <?php else: ?>
                        <a class="easl-yt-player-trigger" target="_blank"
                                href="https://www.youtube.com/watch?v=<?php echo $video_id; ?>">&nbsp;</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php
        if ( $widget_title && 'bottom' == $title_pos ) {
            if ( $lightbox ) {
                $widget_title = '<a data-fancybox target="_blank" href="https://www.youtube.com/watch?v=' . $video_id . '">' . $widget_title . '</a>';
            }
            echo '<h2 class="wpb_heading easl-yt-player-heading easl-yt-player-heading-bottom">' . $widget_title . '</h2>';
        }
        ?>
    </div>
<?php endif; ?>