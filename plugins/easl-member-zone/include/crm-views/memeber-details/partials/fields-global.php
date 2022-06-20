<?php
// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}
/**
 * @var $member array
 */
?>
<div class="mzms-fields-row easl-row easl-row-col-2">
    <div class="easl-col">
        <div class="easl-col-inner mzms-fields-con">
            <label class="mzms-field-label" for="mzf_dotb_job_function">Job function <span class="mzms-asteric">*</span></label>
            <div class="mzms-field-wrap">
                <select class="easl-mz-select2" name="dotb_job_function" id="mzf_dotb_job_function" data-placeholder="Select an option" style="width: 100%;">
                    <option value=""></option>
					<?php echo easl_mz_get_crm_dropdown_items( 'job_functions', $member['dotb_job_function'] ); ?>
                </select>
            </div>
        </div>
    </div>
    <div class="easl-col">
        <div class="easl-col-inner mzms-fields-con" id="mzms-fields-con-dotb_job_function_other">
            <label class="mzms-field-label" for="mzf_dotb_job_function_other">Job function - Other <span class="mzms-asteric">*</span></label>
            <div class="mzms-field-wrap">
                <input type="text" placeholder="" name="dotb_job_function_other" id="mzf_mzms_first_name" value="<?php echo esc_attr( $member['dotb_job_function_other'] ); ?>">
            </div>
        </div>
    </div>
</div>
<div class="mzms-fields-row easl-row easl-row-col-2">
    <div class="easl-col">
        <div class="easl-col-inner mzms-fields-con">
            <label class="mzms-field-label" for="mzf_area_of_interest_c">Area of interest <span class="mzms-asteric">*</span></label>
            <div class="mzms-field-wrap">
                <select class="easl-mz-select2" name="area_of_interest_c[]" id="mzf_area_of_interest_c" multiple="multiple" data-placeholder="Select one/more options" style="width: 100%;">
					<?php echo easl_mz_get_crm_dropdown_items( 'area_of_interests', $member['area_of_interest_c'] ); ?>
                </select>
            </div>
        </div>
    </div>
    <?php if (easl_mz_user_is_member()):?>
        <div class="easl-col">
            <div class="easl-col-inner mzms-fields-con">
                <label class="mzms-field-label" for="mzf_title">Profession / Job Title <span class="mzms-asteric">*</span></label>
                <div class="mzms-field-wrap">
                    <input type="text" placeholder="" name="title" id="mzf_title" value="<?php echo esc_attr( $member['title'] ); ?>" autocomplete="off">
                </div>
            </div>
        </div>
    <?php endif;?>
</div>
<div class="mzms-fields-row easl-row easl-row-col-2">
    <div class="easl-col">
        <div class="easl-col-inner mzms-fields-con">
            <label class="mzms-field-label" for="mzf_participant_type_c">User category <span class="mzms-asteric">*</span></label>
            <div class="mzms-field-wrap">
                <select class="easl-mz-select2" name="participant_type_c" id="mzf_participant_type_c" data-placeholder="Select an option"  style="width: 100%;">
                    <option value=""></option>
                    <?php echo easl_mz_get_crm_dropdown_items( 'user_categories', $member['participant_type_c']); ?>
                </select>
            </div>
        </div>
    </div>
    <div class="easl-col">
        <div class="easl-col-inner mzms-fields-con" id="mzms-fields-con-participant_type_c_other">
            <label class="mzms-field-label" for="mzf_participant_type_c_other">User category - Other <span class="mzms-asteric">*</span></label>
            <div class="mzms-field-wrap">
                <input type="text" placeholder="" name="participant_type_c_other" id="mzf_participant_type_c_other" autocomplete="off" "<?php echo esc_attr( $member['participant_type_c_other'] ); ?>">
            </div>
        </div>
    </div>
</div>

<div class="mzms-fields-row easl-row easl-row-col-2">
    <div class="easl-col">
        <div class="easl-col-inner mzms-fields-con">
            <label class="mzms-field-label" for="mzf_medical_speciality_c">Specialty <span class="mzms-asteric">*</span></label>
            <div class="mzms-field-wrap">
                <select class="easl-mz-select2" name="medical_speciality_c[]" id="mzf_medical_speciality_c" multiple="multiple" data-placeholder="Select one/more options" style="width: 100%;">
					<?php echo easl_mz_get_crm_dropdown_items( 'specialities', $member['medical_speciality_c'] ); ?>
                </select>
            </div>
        </div>
    </div>
    <div class="easl-col">
        <div class="easl-col-inner mzms-fields-con" id="mzms-fields-con-medical_speciality_c_other">
            <label class="mzms-field-label" for="mzf_medical_speciality_c_other">Specialty - Other <span class="mzms-asteric">*</span></label>
            <div class="mzms-field-wrap">
                <input type="text" placeholder="" name="medical_speciality_c_other" id="mzf_medical_speciality_c_other" value="<?php echo esc_attr( $member['medical_speciality_c_other'] ); ?>" autocomplete="off">
            </div>
        </div>
    </div>
</div>
<?php if (easl_mz_user_is_member()):?>
<div class="mzms-fields-row easl-row easl-row-col-2">
    <div class="easl-col">
        <div class="easl-col-inner mzms-fields-con">
            <label class="mzms-field-label" for="mzf_department">Department</label>
            <div class="mzms-field-wrap">
                <input type="text" placeholder="" name="department" id="mzf_department" value="<?php echo esc_attr( $member['department'] ); ?>" autocomplete="off">
            </div>
        </div>
    </div>
    <div class="easl-col">
        <div class="easl-col-inner mzms-fields-con">
            <label class="mzms-field-label" for="mzf_dotb_interaction_with_patient">Interaction with patients</label>
            <div class="mzms-field-wrap">
                <select class="easl-mz-select2" name="dotb_interaction_with_patient" id="mzf_dotb_interaction_with_patient" data-placeholder="Select an option" style="width: 100%;">
                    <option value=""></option>
					<?php echo easl_mz_get_crm_dropdown_items( 'interactions_patient', $member['dotb_interaction_with_patient'] ); ?>
                </select>
            </div>
        </div>
    </div>
</div>
<?php endif;?>

<div class="mzms-fields-row easl-row easl-row-col-2">
    <div class="easl-col">
        <div class="easl-col-inner mzms-fields-con">
            <label class="mzms-field-label" for="mzf_dotb_place_of_work">Place of work <span class="mzms-asteric">*</span></label>
            <div class="mzms-field-wrap">
                <select class="easl-mz-select2" name="dotb_place_of_work" id="mzf_dotb_place_of_work" data-placeholder="Select one option"  style="width: 100%;">
                    <option value=""></option>
                    <?php echo easl_mz_get_crm_dropdown_items( 'places_of_work', $member['dotb_place_of_work'] ); ?>
                </select>
            </div>
        </div>
    </div>
</div>

<div class="mzms-fields-row easl-row easl-row-col-2">
    <div class="easl-col">
        <div class="easl-col-inner mzms-fields-con">
            <label class="mzms-field-label" for="mzf_dotb_gender">Gender <span class="mzms-asteric">*</span></label>
            <div class="mzms-field-wrap">
                <select class="easl-mz-select2" name="dotb_gender" id="mzf_dotb_gender" data-placeholder="Select an option" style="width: 100%;">
                    <option value=""></option>
					<?php echo easl_mz_get_crm_dropdown_items( 'genders', $member['dotb_gender'] ); ?>
                </select>
            </div>
        </div>
    </div>
</div>

<div class="mzms-fields-row easl-row easl-row-col-2">
    <div class="easl-col">
        <div class="easl-col-inner mzms-fields-con">
            <label class="mzms-field-label" for="mzf_birthdate_fz">Date of birth</label>
            <div class="mzms-field-wrap mzms-field-has-privacy<?php if ( ! easl_mz_field_is_public( 'birthdate', $member['dotb_public_profile'], $member['dotb_public_profile_fields'] ) ) {
                echo ' mzms-privacy-enabled';
            } ?>">
                <?php
                $date_of_birth           = $member['birthdate'];
                $date_of_birth_formatted = '';
                if ( $date_of_birth ) {
                    $date_of_birth = explode( '-', $date_of_birth );
                    if ( count( $date_of_birth ) == 3 ) {
                        $date_of_birth_formatted = trim( $date_of_birth[2] ) . '.' . trim( $date_of_birth[1] ) . '.' . trim( $date_of_birth[0] );
                    }
                }
                ?>
                <input type="hidden" placeholder="" name="birthdate" id="mzf_birthdate" value="<?php echo esc_attr( $member['birthdate'] ); ?>" class="easl-mz-date">
                <input type="text" placeholder="" name="" id="mzf_birthdate_fz" value="<?php echo esc_attr( $date_of_birth_formatted ); ?>" class="easl-mz-date" autocomplete="off">
                <?php echo easl_mz_field_public_field( 'birthdate', $member['dotb_public_profile'], $member['dotb_public_profile_fields'] ); ?>
            </div>
        </div>
    </div>
</div>

<div class="mzms-fields-row">
    <div class="mzms-fields-con">
        <label class="mzms-field-label" for="mzf_email1">Email <span class="mzms-asteric">*</span></label>
        <div class="mzms-field-wrap mzms-field-has-privacy<?php if(!easl_mz_field_is_public('email1', $member['dotb_public_profile'], $member['dotb_public_profile_fields'])){echo ' mzms-privacy-enabled';} ?>">
            <input type="hidden" placeholder="" name="email1" id="mzf_email1" value="<?php echo esc_attr( $member['email1'] ); ?>" autocomplete="off">
            <input type="email" placeholder="" id="mzf_email1" value="<?php echo esc_attr( $member['email1'] ); ?>" autocomplete="off" disabled="disabled" readonly="readonly">
            <?php echo easl_mz_field_public_field('email1', $member['dotb_public_profile'], $member['dotb_public_profile_fields']); ?>
        </div>
    </div>
</div>