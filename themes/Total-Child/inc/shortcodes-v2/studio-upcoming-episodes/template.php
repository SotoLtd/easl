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
 * @var $this EASL_VC_Studio_Upcoming_Episodes
 */
$title         = '';
$el_class      = '';
$el_id         = '';
$css_animation = '';

$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts );


$class_to_filter = 'wpb_easl_studio_uc_episodes wpb_content_element ';
$class_to_filter .= vc_shortcode_custom_css_class( $css, ' ' ) . $this->getExtraClass( $el_class );
$css_class       = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, $class_to_filter, $this->settings['base'], $atts );

$wrapper_attributes = array();
if ( ! empty( $el_id ) ) {
    $wrapper_attributes[] = 'id="' . esc_attr( $el_id ) . '"';
}

$query_args = array(
    'post_type'      => 'easl_studio_episode',
    'post_status'    => 'publish',
    'posts_per_page' => - 1,
    'orderby'        => 'meta_value',
    'order'          => 'ASC',
    'meta_key'       => 'episode_live_date_time',
    'meta_query'     => array(
        array(
            'key'     => 'episode_status',
            'value'   => 'upcoming',
            'compare' => '=',
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
        <div class="easl-studio-widget easl-studio-upcoming-episodes">
            <div class="easl-studio-row easl-row">
                <?php
                while ( $query->have_posts() ) {
                    $query->the_post();
                    $live_date_time = get_field( 'episode_live_date_time', get_the_ID() );
                    if ( $live_date_time ) {
                        $live_date_time = DateTime::createFromFormat( 'Y-m-d H:i:s', $live_date_time );
                    }
                    $episode_season  = get_field( 'episode_season' );
                    $episode_season  = $episode_season - 2020;
                    $episode_number  = get_field( 'episode_number' );
                    $poster_image_id = get_field( 'episode_poster' );
                    $episode_title = "Season {$episode_season}, Episode {$episode_number} - " . get_the_title();
                    if ( $live_date_time ) {
                        $episode_title .= ' (Live from ' .  $live_date_time->format( 'H:i' ) . ' CET)';
                    }
                    $poster_image_src = '';
                    if ( $poster_image_id ) {
                        $poster_image_src = wp_get_attachment_image_url( $poster_image_id, 'medium_large' );
                    }
                    ?>
                    <div class="easl-studio-col easl-col easl-col-2">
                        <div class="easl-col-inner">
                            <article class="easl-studio-item">
                                <?php if ( $live_date_time ): ?>
                                    <h5><?php echo $live_date_time->format( 'j F' ) ?></h5>
                                    <h3 class="easl-studio-item-title"><?php echo $episode_title; ?></h3>
                                <?php endif; ?>
                                <?php if ( $poster_image_src ): ?>
                                    <figure>
                                        <a href="<?php the_permalink(); ?>">
                                            <img src="<?php echo $poster_image_src; ?>" alt="">
                                        </a>
                                    </figure>
                                <?php endif; ?>

                                <div class="easl-studio-button-wrap">
                                    <a class="easl-generic-button easl-color-lightblue easl-size-medium easl-align-inline egb-icon-left" target="_blank" href="<?php the_permalink(); ?>">
                                        <span class="easl-generic-button-icon"><i class="ticon ticon-play-circle-o"></i></span> See the programme
                                    </a>
                                </div>
                            </article>
                        </div>
                    </div>
                    <?php
                }
                wp_reset_query();
                ?>
            </div>
        </div>
    </div>
<?php endif; ?>