<?php
// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

/**
 * Shortcode attributes
 * @var $atts
 * @var $el_class
 * @var $el_id
 * @var $css
 * Shortcode class
 * @var $this EASL_VC_MZ_Member_Login
 */
$el_class = $el_id = $css_animation = $css = '';
$atts     = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts );

$sso = EASL_MZ_SSO::get_instance();

$css_animation = $this->getCSSAnimation( $css_animation );

$member_dashboard_url     = get_field( 'member_dashboard_url', 'option' );
$members_profile_page     = get_field( 'member_dashboard_url', 'option' );
$member_zone_button_title = get_field( 'member_zone_button_title', 'option' );
$member_login_link_title  = get_field( 'member_login_link_title', 'option' );

$buttons_to_display = array();
$button_nt          = '';
$button_html_format = '<a href="%s" class="easl-header-mz-buttons"%s>%s</a>';

$login_error_messages = easl_mz_get_manager()->get_message( 'login_error' );
$login_form_class     = 'easl-mz-login-form-wrapper easl-mz-login-form';
if ( $login_error_messages ) {
	$login_form_class .= ' mz-login-form-has-error easl-active';
}

if(isset($_GET['ms_mimic_login'])):
?>
    <div class="easl-mz-header-member-card">
        <?php
        while ( have_rows( 'mz_logged_in_links', 'option' ) ) {
            the_row();
            $button_title   = get_sub_field( 'title' );
            $button_url     = get_sub_field( 'url' );
            $button_new_tab = get_sub_field( 'new_tab' );
            if ( $button_new_tab ) {
                $button_new_tab = ' target="_blank"';
            }
            $buttons_to_display[] = sprintf( $button_html_format, esc_url( $button_url ), $button_new_tab, strip_tags( $button_title ) );
        }
        ?>
        <?php if ( $buttons_to_display ): ?>
            <div class="easl-mz-header-buttons">
                <?php echo implode( '', $buttons_to_display ); ?>
            </div>
        <?php endif; ?>
        <div class="easl-mz-crm-view2 easl-mz-membercard">
            <?php
            $member_name_parts = ['M.', 'Cyriac', 'Couvas'];
            ?>
            <div class="mz-member-card-inner">
                <div class="mz-member-welcome-block">
                    <p class="mz-member-welcome-row">Welcome back <span class="mz-member-name"><?php echo implode( ' ', $member_name_parts ); ?></span></p>
                    <p class="mz-member-duration-row">Your membership is active until 23/12/2022</p>
                    <p class="mz-member-buttons-row">
                        <a class="mz-member-panel-button" href="<?php echo esc_url( $member_dashboard_url ); ?>">My portal</a>
                        <span class="mz-buttonsep">|</span>
                        <a class="mz-logout-link" href="<?php echo EASL_MZ_SSO::get_instance()->get_logout_url(); ?>">Logout</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
<?php
else:
if ( ! easl_mz_is_member_logged_in() ):
	while ( have_rows( 'mz_logged_out_links', 'option' ) ) {
		the_row();
		$button_title   = get_sub_field( 'title' );
		$button_url     = get_sub_field( 'url' );
		$button_new_tab = get_sub_field( 'new_tab' );
		if ( $button_new_tab ) {
			$button_new_tab = ' target="_blank"';
		}
		$buttons_to_display[] = sprintf( $button_html_format, esc_url( $button_url ), $button_new_tab, strip_tags( $button_title ) );
	}
	if ( $member_login_link_title ) {
		$buttons_to_display[] = '<a href="' . $sso->get_login_url() . '" class="easl-header-mz-buttons easl-mz-header-login-button">' . $member_login_link_title . '</a>';
	}
	?>
    <div class="header-aside-buttons mz-loggedout-buttons">
		<?php echo implode( '', $buttons_to_display ); ?>
    </div>
<?php else: ?>
    <div class="easl-mz-header-member-card">
		<?php
		while ( have_rows( 'mz_logged_in_links', 'option' ) ) {
			the_row();
			$button_title   = get_sub_field( 'title' );
			$button_url     = get_sub_field( 'url' );
			$button_new_tab = get_sub_field( 'new_tab' );
			if ( $button_new_tab ) {
				$button_new_tab = ' target="_blank"';
			}
			$buttons_to_display[] = sprintf( $button_html_format, esc_url( $button_url ), $button_new_tab, strip_tags( $button_title ) );
		}
		?>
		<?php if ( $buttons_to_display ): ?>
            <div class="easl-mz-header-buttons">
				<?php echo implode( '', $buttons_to_display ); ?>
            </div>
		<?php endif; ?>
        <div class="easl-mz-crm-view easl-mz-membercard easl-mz-loading">

        </div>
    </div>
<?php endif; ?>
<?php endif; ?>