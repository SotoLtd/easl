<?php
// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}
/**
 * @var array $member
 */

$member_dashboard_url     = get_field( 'member_dashboard_url', 'option' );
$member_zone_button_title = get_field( 'member_zone_button_title', 'option' );

/**
 * @todo Replace with actual picture
 */
$member_image = $member['profile_picture'];
if ( ! $member_image ) {
	$member_image = easl_mz_get_asset_url( 'images/default-avatar.jpg' );
}

$member_name_parts = array();
if ( $member['salutation'] ) {
	$member_name_parts[] = $member['salutation'];
}
if ( $member['first_name'] ) {
	$member_name_parts[] = $member['first_name'];
}
if ( $member['last_name'] ) {
	$member_name_parts[] = $member['last_name'];
}

?>
<div class="mz-member-card-inner">
    <div class="mz-member-welcome-block mz-membership-status-<?php echo $member['dotb_mb_current_status']; ?>">
        <p class="mz-member-welcome-row">Welcome back <span class="mz-member-name"><?php echo implode( ' ', $member_name_parts ); ?></span></p>
        <?php if($member['dotb_mb_current_end_date']): ?>
        <p class="mz-member-duration-row">Your membership is active until <?php echo $member['dotb_mb_current_end_date']; ?></p>
        <?php endif; ?>
        <p class="mz-member-buttons-row">
            <a class="mz-member-panel-button" href="<?php echo esc_url( $member_dashboard_url ); ?>">My portal</a>
            <span class="mz-buttonsep">|</span>
            <a class="mz-logout-link" href="<?php echo EASL_MZ_SSO::get_instance()->get_logout_url(); ?>">Logout</a>
        </p>
    </div>
</div>
