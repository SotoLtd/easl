<?php
// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}
?>
<?php if ( easl_mz_is_member_logged_in() ): ?>
    <div class="vc_row wpb_row vc_row-fluid">
        <div class="wpb_column vc_column_container vc_col-sm-12 vc_col-lg-3 vc_col-md-2">
            <div class="vc_column-inner">
                <div class="easl-mz-page-menu">
                    <h1 class="easl-mz-page-menu-title">Member Zone</h1>
					<?php
                    add_filter('nav_menu_link_attributes', 'easl_mz_jhep_menu_link');
					wp_nav_menu( array(
						'container'      => 'nav',
						'menu_class'     => '',
						'wp_nav_menu'    => '',
						'echo'           => true,
						'fallback_cb'    => false,
						'theme_location' => 'member-zone-pages-menu',
					) );
					remove_filter('nav_menu_link_attributes', 'easl_mz_jhep_menu_link');
					?>
                </div>
            </div>
        </div>
        <div class="wpb_column vc_column_container vc_col-sm-12 vc_col-lg-9 vc_col-md-10">
            <div class="vc_column-inner">
                <div class="wpb_wrapper easl-mz-container-inner">
					<?php the_content(); ?>
                </div>
            </div>
        </div>
    </div>
<?php else:
    $sso = EASL_MZ_SSO::get_instance();?>
    <p>
        Please <a href="<?php echo $sso->get_login_url();?>" class="easl-header-mz-buttons easl-mz-header-login-button">login to your MyEASL account</a> to see this page.
    </p>
<?php endif; ?>