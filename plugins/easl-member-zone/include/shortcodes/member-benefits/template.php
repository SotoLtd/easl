<?php
// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
    die( '-1' );
}


/**
 * Shortcode attributes
 * @var $atts
 * @var $content
 * Shortcode class
 * @var $this EASL_VC_MZ_Member_Benefits
 */
extract( $atts );

$add_membership_url = get_field('membership_plan_url', 'option');
?>

<div class="easl-mz-member-benefits-widget">
    <h2 class="easl-mz-member-benefits-heading">Membership Benefits</h2>

    <div class="easl-mz-member-benefits-content">
        <?=$content;?>
    </div>

    <div class="easl-mz-member-benefits-button">
        <a href="<?=$add_membership_url;?>" class="easl-generic-button easl-color-lightblue">Join today</a>
    </div>
</div>