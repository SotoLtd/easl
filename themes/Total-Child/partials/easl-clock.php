<?php
// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
    die( '-1' );
}
/**
 * @var string $orientation
 * @var boolean $is_iframe
 */

EASL_VC_EASL_Clock::updateClockCount();

if ( empty( $orientation ) ) {
    $orientation = 'landscape';
}

$target_time        = get_field( 'easl_clock_target_time', 'option', false );
$clock_title        = get_field( 'easl_clock_title', 'option' );
$clock_desc         = get_field( 'easl_clock_description', 'option' );
$clock_bg_landscape = get_field( 'easl_clock_bg_img', 'option' );
$clock_bg_portrait  = get_field( 'easl_clock_bg_img_mobile', 'option' );
$clock_bg_color     = get_field( 'easl_clock_bg_color', 'option' );
$clock_page         = get_field( 'clock_page', 'option' );

$logos = array();
if ( have_rows( 'easl_clock_logos', 'option' ) ) {
    while ( have_rows( 'easl_clock_logos', 'option' ) ) {
        the_row();
        $img = get_sub_field( 'logo_image' );
        if ( $img ) {
            $logos[] = $img;
        }
    }
}

$background_image = $clock_bg_landscape;
if ( $orientation == 'portrait' ) {
    $background_image = $clock_bg_portrait;
}

$wrapper_atts    = array();
$wrapper_id      = 'easl_clock_wrap_' . EASL_VC_EASL_Clock::get_clock_count();
$wrapper_atts[] = 'id="'. $wrapper_id .'"';
$wrapper_classes = 'easl-clock-wrap easl-clock-' . $orientation;

$wrapper_styles = array();

$wrapper_atts[] = 'class="' . $wrapper_classes . '"';

if ( $clock_bg_color ) {
    $wrapper_styles[] = 'background-color: ' . $clock_bg_color;
}
if ( $background_image ) {
    $wrapper_styles[] = 'background-image: url(' . esc_url( $background_image ) . ')';
}

$clock_share_url = '';
if ( $clock_page ) {
    $clock_share_url = get_permalink( $clock_page );
}
if ( ! $clock_share_url ) {
    $clock_share_url = home_url();
}

$date_time_parts = EASL_VC_EASL_Clock::get_date_time_parts( $target_time );
if ( $date_time_parts ):
    ?>
    <style type="text/css">
        #<?php echo $wrapper_id; ?> {
        <?php if($clock_bg_color): ?>
            background-color: <?php echo $clock_bg_color;?>;
        <?php endif; ?>
        }
        <?php if($clock_bg_landscape): ?>
        @media only screen and (min-width: 768px) {
            #<?php echo $wrapper_id; ?> {
                background-image: url('<?php echo $clock_bg_landscape;?>');
            }
        }
        <?php endif; ?>
        <?php if($clock_bg_portrait): ?>
        @media only screen and (max-width: 767px) {
            #<?php echo $wrapper_id; ?> {
                background-image: url('<?php echo $clock_bg_portrait;?>');
            }
        }
        <?php endif; ?>
    </style>
    <div <?php echo implode( ' ', $wrapper_atts ); ?>>
        <div class="easl-clock-inner">
            <div class="easl-clock-countdown" data-datetime="<?php echo esc_attr( json_encode( $date_time_parts ) ); ?>"></div>
            <div class="easl-clock-content">
                <?php if ( $clock_title ): ?>
                    <h2><?php echo $clock_title; ?></h2>
                <?php endif; ?>
                <?php if ( $logos ): ?>
                    <div class="easl-clock-logos">
                        <?php foreach ( $logos as $logo ): ?>
                            <div class="easl-clock-logo">
                                <img class="no-lazyload" src="<?php echo $logo; ?>" alt="">
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
                <?php if ( $clock_desc ): ?>
                    <div class="easl-clock-desc">
                        <?php echo do_shortcode( $clock_desc ); ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <button class="easl-clock-share-button">
            <span>Share</span>
            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 26 24">
                <defs/>
                <defs>
                    <path id="a" d="M0 0h26v24H0z"/>
                </defs>
                <g fill="none" fill-rule="evenodd">
                    <mask id="b" fill="#fff">
                        <use xlink:href="#a"/>
                    </mask>
                    <path fill="#004B87" d="M26 11.548L13.484 0v6.117C-1.657 8.737.059 24 .059 24s5.45-8.99 13.425-7.531v6.627L26 11.548z" mask="url(#b)"/>
                </g>
            </svg>
        </button>
        <div class="easl-clock-share-content">
            <div class="easl-clock-share-code">
                <button class="easl-clock-share-close">close</button>
                <?php
                $iframe_wrapper_style = '<style type="text/css">@media only screen and (max-width:767px){iframe#easl-clock-iframe{width:767px!important;max-width:100%!important;height:350px!important}}@media only screen and (min-width:768px){iframe#easl-clock-iframe{width:100%!important;max-width:1125px!important;height:250px!important}}</style>';
                $iframe_url_wide      = add_query_arg( array(
                    'easl_clock_iframe' => true,
                    'ot'                => 'landscape'
                ), home_url() );
                //$iframe_url_small = add_query_arg( array( 'easl_clock_iframe' => true, 'ot' => 'portrait' ), home_url() );
                
                $iframe_code_wide = '<iframe id="easl-clock-iframe" class="no-lazyload" frameborder="0" scrolling="no" src="' . $iframe_url_wide . '"></iframe>' . "\n" . $iframe_wrapper_style;
                ?>
                <script>
                    function easlClockCopyCode() {
                        var field = document.getElementById('easl-clock-share-code-field');
                        field.select();
                        field.setSelectionRange(0, 99999);
                        document.execCommand("copy");
                    }
                </script>
                <div>Embed in your website</div>
                <textarea id="easl-clock-share-code-field" cols="30" rows="3"><?php echo $iframe_code_wide; ?></textarea>
                <button class="easl-clock-share-copy-code" onclick="easlClockCopyCode()">Copy embed code</button>
            </div>
            <div class="easl-clock-share-socials">
                <?php echo do_shortcode( '[Sassy_Social_Share title="" url="' . $clock_share_url . '"]' ); ?>
            </div>
        </div>
    </div>
<?php endif; ?>