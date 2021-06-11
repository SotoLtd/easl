<?php
// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
    die( '-1' );
}
$member_profile_url = get_field( 'member_profile_url', 'option' );
$member_profile_url = add_query_arg('highlight_errors', 1, $member_profile_url);
?>
<div class="easl-mz-new-membership-form-inner">

    <div class="easl-mz-page-intro">
        <p>We need you to revise your profile fields so that there are no empty fields. Please go <a href="<?php echo $member_profile_url; ?>">My Profile</a> page</p>
    </div>
</div>
