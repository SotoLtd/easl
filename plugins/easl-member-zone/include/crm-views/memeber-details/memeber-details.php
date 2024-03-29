<?php
// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}
/**
 * @var $member array
 */
$title = 'My profile';
$template_base = easl_mz_get_manager()->path( 'CRM_VIEWS', '/memeber-details' );
?>

    <div class="easl-mz-membership-fields">
        <form id="easl-mz-membership-form" action="" method="post">
            <input type="hidden" name="id" id="mzf_id" value="<?php echo $member['id']; ?>">
            <input type="hidden" name="dotb_public_profile_fields" id="mzf_dotb_public_profile_fields" value="<?php echo esc_attr( $member['dotb_public_profile_fields'] ); ?>">
            <div class="mzms-fields-row easl-mz-membership-header">
				<?php if ( $title ): ?>
                    <h2 class="mz-page-heading"><?php echo $title; ?></h2>
				<?php endif; ?>
                <?php if(easl_mz_user_is_member()): ?>
                    <div class="mzms-field-wrap mzms-field-wrap-public">
                        <label for="mzms_dotb_public_profile" class="easl-custom-checkbox">
                            <input type="checkbox" name="dotb_public_profile" id="mzms_dotb_public_profile" value="Yes" <?php checked( in_array( $member['dotb_public_profile'], array(
                                'Yes',
                                'Yes_Partial'
                            ) ), true ); ?>>
                            <span>Make my profile public</span>
                        </label>
                    </div>
                <?php endif; ?>
            </div>
            <div class="mzms-fields-separator"></div>
			<?php include $template_base . '/partials/fields-basic.php'; ?>
			<?php include $template_base . '/partials/fields-global.php'; ?>

            <?php if (easl_mz_user_is_member()):?>
                <div class="mzms-fields-separator"></div>
                <?php include $template_base . '/partials/fields-communications.php'; ?>

                <div class="mzms-fields-separator"></div>
                <?php include $template_base . '/partials/fields-address.php'; ?>
                <div class="mzms-fields-separator"></div>
                <div class="mzms-fields-row">
                    <div class="mzms-fields-con">
                        <label class="mzms-field-label" for="mzms_personal_profile">Personal Profile</label>
                        <div class="mzms-field-wrap">
                            <textarea name="description" id="mzms_personal_profile" placeholder="" autocomplete="new-password"><?php echo esc_textarea( wp_unslash($member['description'] )); ?></textarea>
                        </div>
                    </div>
                </div>
            <?php else: ?>
                <div class="mzms-fields-row">
                    <div class="mzms-fields-con">
                        <label class="mzms-field-label" for="mzf_primary_address_country">Country</label>
                        <div class="mzms-field-wrap mzms-field-has-privacy<?php if(!easl_mz_field_is_public('primary_address_country', $member['dotb_public_profile'], $member['dotb_public_profile_fields'])){echo ' mzms-privacy-enabled';} ?>">
                            <select class="easl-mz-select2" name="primary_address_country" id="mzf_primary_address_country" data-placeholder="Select an option" style="width: 100%;">
                                <option value=""></option>
					            <?php echo easl_mz_get_crm_dropdown_items( 'countries', $member['primary_address_country'] ); ?>
                            </select>
				            <?php echo easl_mz_field_public_field('primary_address_country', $member['dotb_public_profile'], $member['dotb_public_profile_fields']); ?>
                        </div>
                    </div>
                </div>
            <?php endif;?>
            <div class="mzms-fields-row">
                <p>EASL can not be held responsible for the accuracy of the information published on this page. All data is provided by EASL members themselves.</p>
            </div>
            <div class="mzms-fields-row">
                <p>*mandatory fields</p>
            </div>
            <div class="mzms-fields-separator"></div>
            <div class="mzms-fields-row mzms-submit-row">
                <button class="mzms-submit">Save Updates</button>
            </div>
        </form>

		<?php include $template_base . '/partials/change-picture-form.php'; ?>
		<?php include $template_base . '/partials/change-password.php'; ?>
    </div>

<?php include $template_base . '/partials/sidebar.php'; ?>