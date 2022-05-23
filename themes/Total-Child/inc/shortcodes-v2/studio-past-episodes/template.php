<?php
// Prevent direct access
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
 * Shortcode class
 * @var $this EASL_VC_Studio_Past_Episodes
 */
$title         = '';
$view_all_url  = '';
$view_all_text = '';
$el_class      = '';
$el_id         = '';
$css_animation = '';

$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts );

if ( ! $view_all_text ) {
    $view_all_text = 'Watch all EASL Studio episodes';
}

$class_to_filter = 'wpb_easl_studio_past_episodes wpb_content_element ';
$class_to_filter .= vc_shortcode_custom_css_class( $css, ' ' ) . $this->getExtraClass( $el_class );
$css_class       = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, $class_to_filter, $this->settings['base'], $atts );

$wrapper_attributes = array();
if ( ! empty( $el_id ) ) {
    $wrapper_attributes[] = 'id="' . esc_attr( $el_id ) . '"';
}

$query_args = array(
    'post_type'      => 'easl_studio_episode',
    'post_status'    => 'publish',
    'posts_per_page' => 6,
    'orderby'        => 'meta_value',
    'order'          => 'DESC',
    'meta_key'       => 'episode_live_date_time',
    'meta_query'     => array(
        array(
            'key'     => 'episode_status',
            'value'   => 'past',
            'compare' => '=',
        ),
        array(
            'key'     => 'episode_on_demand_url',
            'compare' => 'EXISTS'
        ),
    )
);

$query = new WP_Query( $query_args );

if ( $query->have_posts() ):
    ?>
    <div <?php echo implode( ' ', $wrapper_attributes ); ?> class="<?php echo esc_attr( trim( $css_class ) ); ?>">
        <?php if ( $title ): ?>
            <h2><?php echo $title; ?></h2>
        <?php endif; ?>
        <div class="easl-studio-widget easl-studio-past-episodes">
            <div class="easl-studio-row easl-row">
                <?php
                while ( $query->have_posts() ) {
                    $query->the_post();
                    $episode_season              = get_field( 'episode_season' );
                    $episode_number              = get_field( 'episode_number' );
                    $poster_image_id             = get_field( 'episode_poster' );
                    $episode_live_screen_capture = get_field( 'episode_live_screen_capture' );
                    $episode_on_demand_url       = get_field( 'episode_on_demand_url' );
                    $episode_title = '';
                    if ( $episode_season ) {
                        $episode_season = $episode_season - 2020;
                        $episode_title .= "Season {$episode_season}";
                    }
                    if ( $episode_number ) {
                        if ( $episode_season ) {
                            $episode_title .= ', ';
                        }
                        $episode_title .= "Episode {$episode_number}";
                    }
                    if ( $episode_title ) {
                        $episode_title .= ' - ';
                    }
                    $episode_title .= get_the_title();
                    
                    $listing_image_src = '';
                    if ( $episode_live_screen_capture ) {
                        $listing_image_src = wp_get_attachment_image_url( $episode_live_screen_capture, 'medium_large' );
                    }
                    if ( ! $listing_image_src && $poster_image_id ) {
                        $listing_image_src = wp_get_attachment_image_url( $poster_image_id, 'medium_large' );
                    }
                    ?>
                    <div class="easl-studio-col easl-col easl-col-3">
                        <div class="easl-col-inner">
                            <article class="easl-studio-item">
                                <?php if ( $listing_image_src ): ?>
                                    <div class="wpb_single_image">
                                        <figure class="vc_figure">
                                            <a class="vc_single_image-wrapper" target="_blank" href="<?php echo esc_url( $episode_on_demand_url ); ?>">
                                                <img src="<?php echo $listing_image_src; ?>" alt="">
                                            </a>
                                            <a class="easl-vc-image-over" target="_blank" href="<?php echo esc_url( $episode_on_demand_url ); ?>">Watch on demand</a>
                                        </figure>
                                    </div>
                                <?php endif; ?>
                                <h3>
                                    <a href="<?php echo esc_url( $episode_on_demand_url ); ?>" target="_blank"><?php echo $episode_title; ?></a>
                                </h3>
                            </article>
                        </div>
                    </div>
                    <?php
                }
                wp_reset_query();
                ?>
            </div>
        </div>
        <?php if ( $view_all_url ): ?>
            <div class="easl-studio-button-wrap">
                <a class="easl-generic-button easl-color-blue easl-size-medium easl-align-inline egb-icon-left" target="_blank" href="<?php echo esc_url( $view_all_url ); ?>"><span class="easl-generic-button-icon"><i class="ticon ticon-play"></i></span><?php echo $view_all_text ?>
                </a>
            </div>
        <?php endif; ?>
    </div>
<?php endif; ?>