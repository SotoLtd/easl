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
                    <?php if (!easl_mz_user_is_member() && !is_page('add-a-membership') && !is_page('membership-confirm')):?>
                        <div class="easl-member-banner">
                            Become an EASL member
                            <a href="<?php echo $add_membership_url;?>" class="easl-generic-button easl-color-lightblue">Join today</a>
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
    $sso = EASL_MZ_SSO::get_instance();?>
    <p>
        Please <a href="<?php echo $sso->get_login_url();?>" class="easl-header-mz-buttons easl-mz-header-login-button">login to your MyEASL account</a> to see this page.
    </p>
<?php endif; ?>