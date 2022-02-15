<?php
// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

$add_membership_url = get_field('membership_plan_url', 'option');
?>
<?php if ( easl_mz_user_can_access_memberzone_page(get_the_ID()) ): ?>
    <div class="vc_row wpb_row vc_row-fluid">
        <div class="wpb_column vc_column_container vc_col-sm-12 vc_col-lg-3 vc_col-md-2">
            <div class="vc_column-inner">
                <div class="easl-mz-page-menu">
                    <h1 class="easl-mz-page-menu-title">MyEASL</h1>
					<?php
                    add_filter('nav_menu_link_attributes', 'easl_mz_menu_attrs');
					wp_nav_menu( array(
						'container'      => 'nav',
						'menu_class'     => '',
						'wp_nav_menu'    => '',
						'echo'           => true,
						'fallback_cb'    => false,
						'theme_location' => 'member-zone-pages-menu',
					) );
					remove_filter('nav_menu_link_attributes', 'easl_mz_menu_attrs');
					?>
                </div>
	            <?php if ( have_rows( 'mz_sb_banners', 'option' ) ): ?>
                    <div class="easl-mz-page-banners">
			            <?php
			            while ( have_rows( 'mz_sb_banners', 'option' ) ):
				            the_row();
				            $img     = get_sub_field( 'image' );
				            $link    = get_sub_field( 'link' );
				            $new_tab = get_sub_field( 'new_tab' );
				            if ( ! $img ) {
					            continue;
				            }
				            $banner_html = '<img src="' . $img . '" alt="">';

				            if ( $link && $new_tab ) {
					            $new_tab = ' target="_blank"';
				            }
				            if ( $link ) {
					            $banner_html = '<a href="' . esc_url( $link ) . '"' . $new_tab . '>' . $banner_html . '</a>';
				            }
				            ?>
                            <div class="easl-mz-sb-banner">
					            <?php echo $banner_html; ?>
                            </div>
			            <?php endwhile; ?>
                    </div>
	            <?php endif; ?>
            </div>
        </div>
        <div class="wpb_column vc_column_container vc_col-sm-12 vc_col-lg-9 vc_col-md-10">
            <div class="vc_column-inner">
                <div class="wpb_wrapper easl-mz-container-inner">
                    <?php
                    if (!easl_mz_user_is_member() && !is_page('add-a-membership') && !is_page('membership-confirm')):
                        $banner_link_text = 'Become an EASL member';
                        $banner_link_title = 'Join today';
                        $member = easl_mz_get_logged_in_member_data();
                        $membership_status = !empty($member['dotb_mb_current_status']) ? $member['dotb_mb_current_status'] : '';
                        if( in_array($membership_status, ['expired', 'cancelled']) ) {
                            $banner_link_title = 'Renew Now';
                        }
                        ?>
                        <div class="easl-member-banner">
                            <span><?php echo $banner_link_text; ?></span>
                            <a href="<?php echo $add_membership_url;?>" class="easl-generic-button easl-color-lightblue"><?php echo $banner_link_title; ?></a>
                        </div>
                    <?php endif;?>

                    <?php if (isset($_GET['application_submitted'])):?>
                        <div class="easl-application-submitted-notice">
                            Thank you for submitting your application. We will be in touch soon.
                        </div>
                    <?php endif;?>
					<?php the_content(); ?>
                </div>
            </div>
        </div>
    </div>
<?php elseif (easl_mz_is_member_logged_in()): ?>
    <?php
    wp_redirect($add_membership_url);
    ?>
<?php else:
    $login_error_messages = easl_mz_get_manager()->get_message( 'login_error' );
    $member_dashboard_url = get_field( 'member_dashboard_url', 'option' );

	if ( ! empty( $_GET['redirect_url'] ) ) {
		$redirect_url = $_GET['redirect_url'];
	} else {
		$redirect_url = home_url( $_SERVER['REQUEST_URI'] );
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
            <a class="mz-become-member-link" href="https://easl.eu/join-my-easl/?skip_dashboard=1">Become a member</a>
            <a class="mz-forgot-password" href="#">Forgot your password?</a>
        </div>

        <div class="easl-mz-loader">
            <img src="<?php echo get_stylesheet_directory_uri(); ?>/images/easl-loader.gif" alt="loading...">
        </div>
    </div>
<?php endif; ?>