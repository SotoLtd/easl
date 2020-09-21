<?php
// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}
/**
 * @var $redirect_url
 */
$login_error_messages = easl_mz_get_manager()->get_message( 'login_error' );
$member_dashboard_url = get_field( 'member_dashboard_url', 'option' );

if ( ! empty( $_REQUEST['redirect_url'] ) ) {
    $redirect_url = $_REQUEST['redirect_url'];
} else {
    $redirect_url = home_url( $_SERVER['REQUEST_URI'] );
}

if(!$redirect_url) {
    $redirect_url = $member_dashboard_url;
}

?>
<div class="membership-pages-login-wrap easl-mz-login-form-wrapper">
    <form action="" method="post" class="clr">
        <?php if ( $login_error_messages ): ?>
            <div class="mz-login-row mz-login-errors">
                <?php echo implode( '', $login_error_messages ); ?>
            </div>
        <?php endif; ?>
        <div class="mz-login-row">
            <input type="text" name="mz_member_login" value="" placeholder="Username">
        </div>
        <div class="mz-login-row">
            <input type="password" name="mz_member_password" value="" placeholder="Password">
        </div>
        <div class="mz-login-row">
            <input type="hidden" name="mz_redirect_url" value="<?php echo esc_url( $redirect_url ); ?>">
            <button class="easl-generic-button easl-color-lightblue">Login</button>
        </div>
    </form>
    <div class="mz-forgot-pass-fields clr">
        <input class="mz-reset-pass-email" type="text" value="" placeholder="Your email address">
        <button class="easl-generic-button easl-color-lightblue mz-reset-pass-button">Reset Password</button>
    </div>
    <div class="mz-forgot-pass-row clr">
        <a class="mz-become-member-link" href="https://easl.eu/become-a-member/">Become a member</a>
        <a class="mz-forgot-password" href="#">Forgot your password?</a>
    </div>

    <div class="easl-mz-loader">
        <img src="<?php echo get_stylesheet_directory_uri(); ?>/images/easl-loader.gif" alt="loading...">
    </div>
</div>
