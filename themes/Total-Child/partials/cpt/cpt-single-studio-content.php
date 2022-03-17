<?php
// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
    die( '-1' );
}
$episode_live_date_time = get_field( 'episode_live_date_time' );
$episode_season         = get_field( 'episode_season' );
$episode_season         = $episode_season - 2020;
$episode_number         = get_field( 'episode_number' );
$poster_image_id        = get_field( 'episode_poster' );
$episode_title          = "Season {$episode_season}, Episode {$episode_number} - " . get_the_title();

$episode_status               = get_field( 'episode_status' );
$episode_poster               = get_field( 'episode_poster' );
$episode_live_stream_url      = get_field( 'episode_live_stream_url' );
$episode_live_screen_capture  = get_field( 'episode_live_screen_capture' );
$episode_on_demand_url        = get_field( 'episode_on_demand_url' );
$episode_speakers             = get_field( 'episode_speakers' );
$episode_number_speakers_per_row = get_field( 'episode_number_speakers_per_row' );
$episode_add_to_calendar_code = get_field( 'episode_add_to_calendar_code' );
$episode_survey_link          = get_field( 'episode_survey_link' );

$episode_number_speakers_per_row = absint($episode_number_speakers_per_row);
if($episode_number_speakers_per_row < 1 || $episode_number_speakers_per_row > 4) {
    $episode_number_speakers_per_row = 3;
}
?>

    <div class="studio-episode-details">
        <h1 class="studio-episode-title"><?php echo $episode_title; ?></h1>
        <div class="studio-episode-content">
            <?php the_content(); ?>
        </div>
        <?php
        if ( 'upcoming' == $episode_status ):
            $poster_image_src = '';
            if ( $episode_poster ) {
                $poster_image_src = wp_get_attachment_image_url( $episode_poster, 'full' );
            }
            if ( $poster_image_src ):
                ?>
                <div class="studio-episode-poster">
                    <figure>
                        <img src="<?php echo $poster_image_src; ?>" alt="">
                    </figure>
                </div>
            <?php endif; ?>
        <?php
        elseif ( 'past' == $episode_status ):
            $listing_image_src = '';
            if ( $episode_live_screen_capture ) {
                $listing_image_src = wp_get_attachment_image_url( $episode_live_screen_capture, 'full' );
            }
            if ( ! $listing_image_src && $episode_poster ) {
                $listing_image_src = wp_get_attachment_image_url( $episode_poster, 'full' );
            }
            if ( $listing_image_src ):
                ?>
                <div class="studio-episode-poster">
                    <figure>
                        <a href="<?php echo esc_url($episode_on_demand_url); ?>" target="_blank">
                            <img src="<?php echo $listing_image_src; ?>" alt="">
                        </a>
                    </figure>
                </div>
                <div class="studio-episode-od-link easl-generic-button-wrap easl-content-center">
                    <a class="easl-generic-button easl-color-lightblue easl-size-fullwidth easl-align-center" target="_blank" href="<?php echo esc_url($episode_on_demand_url); ?>">Watch on-demand<span class="easl-generic-button-icon"><span class="ticon ticon-chevron-right"></span></span></a>
                </div>
            <?php endif; ?>
        <?php
        elseif ( 'live' == $episode_status && $episode_live_stream_url ):?>
            <div class="easl-tv-iframe">
                <div class="easl-tv-iframe-inner">
                    <iframe src="<?php echo esc_url( $episode_live_stream_url ); ?>" frameborder="0" scrolling="no" allowfullscreen="allowfullscreen" width="100%" height="100%"></iframe>
                </div>
            </div>
        <?php endif; ?>
        <?php if ( have_rows( 'episode_speakers' ) ): ?>
            <div class="studio-episode-speakers">
                <h3>Speakers</h3>
                <div class="easl-studio-row easl-row">
                    <?php
                    while ( have_rows( 'episode_speakers' ) ):
                        the_row();
                        $speaker_name             = get_sub_field( 'name' );
                        $speaker_institution_name = get_sub_field( 'institution_name' );
                        $speaker_photo            = get_sub_field( 'photo' );
                        $speaker_bio              = get_sub_field( 'bio' );
                        $speaker_bio              = explode( "\n", $speaker_bio );
                        $speaker_bio_excerpt      = is_array( $speaker_bio ) ? array_shift( $speaker_bio ) : '';
                        if ( $speaker_photo ) {
                            $speaker_photo = wp_get_attachment_image_url( $speaker_photo, 'medium' );
                        }
                        
                        ?>
                        <div class="easl-studio-col easl-col easl-col-<?php echo $episode_number_speakers_per_row; ?>">
                            <div class="easl-col-inner">
                                <?php if ( $speaker_name ): ?>
                                    <h4 class="studio-speaker-name"><?php echo $speaker_name; ?></h4>
                                <?php endif; ?>
                                <?php if ( $speaker_institution_name ): ?>
                                    <h5 class="studio-speaker-institution"><?php echo $speaker_institution_name; ?></h5>
                                <?php endif; ?>
                                <?php if ( $speaker_photo ): ?>
                                    <div class="studio-speaker-photo">
                                        <figure>
                                            <img src="<?php echo $speaker_photo; ?>" alt="">
                                        </figure>
                                    </div>
                                <?php endif; ?>
                                <?php if ( $speaker_bio_excerpt ): ?>
                                    <div class="studio-speaker-bio">
                                        <p><?php echo $speaker_bio_excerpt; ?></p>
                                        <?php
                                        $speaker_bio_more = '';
                                        if ( $speaker_bio && count( $speaker_bio ) > 0 ) {
                                            foreach ( $speaker_bio as $speaker_bio_p ) {
                                                $speaker_bio_p = trim( $speaker_bio_p );
                                                if ( $speaker_bio_p ) {
                                                    $speaker_bio_more .= '<p>' . $speaker_bio_p . '</p>';
                                                }
                                            }
                                        }
                                        if ( $speaker_bio_more ):
                                            ?>
                                            <div class="vc_toggle vc_toggle_ vc_toggle_color_blue">
                                                <div class="vc_toggle_title">
                                                    <h4>Read more</h4>
                                                </div>
                                                <div class="vc_toggle_content">
                                                    <?php echo $speaker_bio_more; ?>
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
            </div>
        <?php endif; ?>
        <?php if ( $episode_add_to_calendar_code && ( 'upcoming' == $episode_status ) ): ?>
            <div class="studio-add-to-calendar">
                <?php echo $episode_add_to_calendar_code; ?>
            </div>
        <?php endif; ?>
        <?php if ( $episode_survey_link ): ?>
            <div class="studio-survey">
                <?php echo $episode_survey_link; ?>
            </div>
        <?php endif; ?>
    </div>
<div class="single-studio-past-episodes">
<?php echo do_shortcode( '[easl_studio_past_episodes title="Watch previous EASL Studio episodes" view_all_text="Watch all EASL Studio episodes" view_all_url="https://easlcampus.eu/easl-studio-menu"]' );?>
</div>
