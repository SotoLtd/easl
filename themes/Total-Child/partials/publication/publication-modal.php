<?php
// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}
$new_member_url = 'https://easl.eu/join-my-easl/';
$redirect_url = get_permalink(get_queried_object_id());
if($redirect_url) {
    $new_member_url = add_query_arg(array('redirect' => $redirect_url), $new_member_url);
}
?>
<div class="sp-modal-overlay">
    <div class="sp-modal">
        <a class="sp-modal-close" href="#">&times;</a>
        <div class="sp-modal-inner">
            <div class="sp-modal-content">
                <h3>Create a MyEASL profile to access these resources</h3>
                <p>With your MyEASL Profile you get access to:</p>
                <ul>
                    <li>Online education through EASL Campus</li>
                    <li>EASL Clinical Practice Guidelines (CPGs)</li>
                    <li>Event specific information through the EASL App</li>
                    <li>Dedicated EASL newsletter</li>
                    <li>And more</li>
                </ul>

                <p>
                    <a href="<?php echo $new_member_url; ?>" class="easl-button easl-color-lightblue">Create account
                        <span class="easl-generic-button-icon"><span class="ticon ticon-chevron-right"></span></span>
                    </a>
                </p>
                <div>
                    <a class="sp-login-from-trigger" href="#"><strong>Already have an account (MyEASL or Membership)? Sign in here</strong></a>
                </div>
            </div>
            <div class="sp-modal-login-form">
                <?php easl_mz_get_login_form(); ?>
            </div>
        </div>
    </div>
</div>