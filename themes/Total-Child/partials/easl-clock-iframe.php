<?php
// Prevent direct access
use TotalTheme\Favicons;if ( ! defined( 'ABSPATH' ) ) {
    die( '-1' );
}
/**
 * @var string $orientation
 */
global $heateor_sss;//plugin_public
$clock_title = get_field( 'easl_clock_title', 'option' );

?><!doctype html>
<html <?php language_attributes(); ?><?php wpex_schema_markup( 'html' ); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <link rel="profile" href="http://gmpg.org/xfn/11">
    <title><?php echo $clock_title; ?></title>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <?php
    if ( $heateor_sss && is_a( $heateor_sss, 'Sassy_Social_Share' ) ) {
        $options       = get_option( 'heateor_sss' );
        $inline_script = 'function heateorSssLoadEvent(e) {var t=window.onload;if (typeof window.onload!="function") {window.onload=e}else{window.onload=function() {t();e()}}};';
        $inline_script .= 'var heateorSssSharingAjaxUrl = \''. get_admin_url() .'admin-ajax.php\', heateorSssCloseIconPath = \''. plugins_url( '../images/close.png', __FILE__ ) .'\', heateorSssPluginIconPath = \''. plugins_url( '../images/logo.png', __FILE__ ) .'\', heateorSssHorizontalSharingCountEnable = '. ( isset( $heateor_sss->options['hor_enable'] ) && ( isset( $heateor_sss->options['horizontal_counts'] ) || isset( $heateor_sss->options['horizontal_total_shares'] ) ) ? 1 : 0 ) .', heateorSssVerticalSharingCountEnable = '. ( isset( $heateor_sss->options['vertical_enable'] ) && ( isset( $heateor_sss->options['vertical_counts'] ) || isset( $heateor_sss->options['vertical_total_shares'] ) ) ? 1 : 0 ) .', heateorSssSharingOffset = '. ( isset( $heateor_sss->options['alignment'] ) && $heateor_sss->options['alignment'] != '' && isset( $heateor_sss->options[$heateor_sss->options['alignment'].'_offset'] ) && $heateor_sss->options[$heateor_sss->options['alignment'].'_offset'] != '' ? $heateor_sss->options[$heateor_sss->options['alignment'].'_offset'] : 0 ) . '; var heateorSssMobileStickySharingEnabled = ' . ( isset( $heateor_sss->options['vertical_enable'] ) && isset( $heateor_sss->options['bottom_mobile_sharing'] ) && $heateor_sss->options['horizontal_screen_width'] != '' ? 1 : 0 ) . ';';
        $inline_script .= 'var heateorSssCopyLinkMessage = "' . htmlspecialchars( __( 'Link copied.', 'sassy-social-share' ), ENT_QUOTES ) . '";';
        if ( isset( $heateor_sss->options['horizontal_counts'] ) && isset( $heateor_sss->options['horizontal_counter_position'] ) ) {
            $inline_script .= in_array( $heateor_sss->options['horizontal_counter_position'], array( 'inner_left', 'inner_right' ) ) ? 'var heateorSssReduceHorizontalSvgWidth = true;' : '';
            $inline_script .= in_array( $heateor_sss->options['horizontal_counter_position'], array( 'inner_top', 'inner_bottom' ) ) ? 'var heateorSssReduceHorizontalSvgHeight = true;' : '';
        }
        if ( isset( $heateor_sss->options['vertical_counts'] ) ) {
            $inline_script .= isset( $heateor_sss->options['vertical_counter_position'] ) && in_array( $heateor_sss->options['vertical_counter_position'], array( 'inner_left', 'inner_right' ) ) ? 'var heateorSssReduceVerticalSvgWidth = true;' : '';
            $inline_script .= ! isset( $heateor_sss->options['vertical_counter_position'] ) || in_array( $heateor_sss->options['vertical_counter_position'], array( 'inner_top', 'inner_bottom' ) ) ? 'var heateorSssReduceVerticalSvgHeight = true;' : '';
        }
        $inline_script .= 'var heateorSssUrlCountFetched = [], heateorSssSharesText = \''. htmlspecialchars( __( 'Shares', 'sassy-social-share' ), ENT_QUOTES ) . '\', heateorSssShareText = \'' . htmlspecialchars( __( 'Share', 'sassy-social-share' ), ENT_QUOTES ) . '\';';
        $inline_script .= 'function heateorSssPopup(e) {window.open(e,"popUpWindow","height=400,width=600,left=400,top=100,resizable,scrollbars,toolbar=0,personalbar=0,menubar=no,location=no,directories=no,status")}';
        if ( $heateor_sss->plugin_public->facebook_like_recommend_enabled() || $heateor_sss->plugin_public->facebook_share_enabled() ) {
            $inline_script .= 'function heateorSssInitiateFB() {FB.init({appId:"",channelUrl:"",status:!0,cookie:!0,xfbml:!0,version:"v12.0"})}window.fbAsyncInit=function() {heateorSssInitiateFB(),' . ( defined( 'HEATEOR_SOCIAL_SHARE_MYCRED_INTEGRATION_VERSION' ) && $heateor_sss->plugin_public->facebook_like_recommend_enabled() ? 1 : 0 ) . '&&(FB.Event.subscribe("edge.create",function(e) {heateorSsmiMycredPoints("Facebook_like_recommend","",e?e:"")}),FB.Event.subscribe("edge.remove",function(e) {heateorSsmiMycredPoints("Facebook_like_recommend","",e?e:"","Minus point(s) for undoing Facebook like-recommend")}) ),'. ( defined( 'HEATEOR_SHARING_GOOGLE_ANALYTICS_VERSION' ) ? 1 : 0 ) .'&&(FB.Event.subscribe("edge.create",function(e) {heateorSsgaSocialPluginsTracking("Facebook","Like",e?e:"")}),FB.Event.subscribe("edge.remove",function(e) {heateorSsgaSocialPluginsTracking("Facebook","Unlike",e?e:"")}) )},function(e) {var n,i="facebook-jssdk",o=e.getElementsByTagName("script")[0];e.getElementById(i)||(n=e.createElement("script"),n.id=i,n.async=!0,n.src="//connect.facebook.net/'. ( $heateor_sss->options['language'] ? $heateor_sss->options['language'] : 'en_GB' ) .'/sdk.js",o.parentNode.insertBefore(n,o) )}(document);';
        }
        
        ?>
        <script type="text/javascript">
            <?php echo $inline_script; ?>
        </script>
        <script src="<?php echo plugins_url( 'sassy-social-share/public/js/sassy-social-share-public.js?' . $heateor_sss->plugin_public->version ); ?>"></script>
        <style type="text/css">
            <?php $heateor_sss->plugin_public->frontend_inline_style(); ?>
        </style>
        <link rel="stylesheet" href="<?php echo plugins_url( 'sassy-social-share/public/css/sassy-social-share-public.css?' . $heateor_sss->plugin_public->version ); ?>">
        <?php
    }
    ?>
    <link rel="stylesheet" href="<?php echo get_stylesheet_directory_uri(); ?>/assets/css/easl-clock-iframe.css?ver=<?php echo EASL_VC_EASL_Clock::$clock_version; ?>">
    <link rel="stylesheet" href="<?php echo get_stylesheet_directory_uri(); ?>/assets/css/easl-clock.css?ver=<?php echo EASL_VC_EASL_Clock::$clock_version; ?>">
    <?php
    
    if ( class_exists( '\TotalTheme\Favicons' ) && is_callable( array( '\TotalTheme\Favicons', 'instance' ) ) ) {
        \TotalTheme\Favicons::instance()->output_favicons();
    } elseif ( $icon = get_theme_mod( 'favicon' ) ) {
        $icon = wp_get_attachment_image_url( $icon, 'full' );
        echo '<link rel="icon" href="' . esc_url( $icon ) . '" sizes="32x32">';
        echo '<link rel="shortcut icon" href="' . esc_url( $icon ) . '">'; // For older IE
    }
    ?>
    <script src="<?php echo get_stylesheet_directory_uri(); ?>/assets/lib/jquery-countdown/js/jquery.plugin.min.js?ver=2.1.0"></script>
    <script src="<?php echo get_stylesheet_directory_uri(); ?>/assets/lib/jquery-countdown/js/jquery.countdown.min.js?ver=2.1.0"></script>
    <script src="<?php echo get_stylesheet_directory_uri(); ?>/assets/js/easl-clock.js?ver=<?php echo EASL_VC_EASL_Clock::$clock_version; ?>"></script>
</head>
<body>
<div class="iframe-wrapper">
    <?php easl_get_template_part( 'easl-clock.php', array( 'orientation' => $orientation, 'is_iframe' => true ) ) ?>
</div>
</body>
</html>