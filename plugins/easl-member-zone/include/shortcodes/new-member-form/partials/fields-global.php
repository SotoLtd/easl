<?php
// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}
?>
<div class="mzms-fields-row easl-row easl-row-col-2">
    <div class="easl-col">
        <div class="easl-col-inner mzms-fields-con">
            <label class="mzms-field-label" for="mzf_dotb_job_function">Job function <span class="mzms-asteric">*</span></label>
            <div class="mzms-field-wrap">
                <select class="easl-mz-select2" name="dotb_job_function" id="mzf_dotb_job_function" data-placeholder="Select an option" style="width: 100%;">
                    <option value=""></option>
                    <?php echo easl_mz_get_crm_dropdown_items( 'job_functions' ); ?>
                </select>
            </div>
        </div>
    </div>
    <div class="easl-col">
        <div class="easl-col-inner mzms-fields-con" id="mzms-fields-con-dotb_job_function_other">
            <label class="mzms-field-label" for="mzf_dotb_job_function_other">Job function - Other <span class="mzms-asteric">*</span></label>
            <div class="mzms-field-wrap">
                <input type="text" placeholder="" name="dotb_job_function_other" id="mzf_dotb_job_function_other" value="" autocomplete="off">
            </div>
        </div>
    </div>
</div>
<div class="mzms-fields-row easl-row easl-row-col-2">
    <div class="easl-col">
        <div class="easl-col-inner mzms-fields-con">
            <label class="mzms-field-label" for="mzf_area_of_interest_c">Area of interest <span class="mzms-asteric">*</span></label>
            <div class="mzms-field-wrap">
                <select class="easl-mz-select2" name="area_of_interest_c[]" id="mzf_area_of_interest_c" multiple="multiple" data-placeholder="Select one/more options"  style="width: 100%;">
                    <?php echo easl_mz_get_crm_dropdown_items( 'area_of_interests' ); ?>
                </select>
            </div>
        </div>
    </div>
</div>
<div class="mzms-fields-row easl-row easl-row-col-2">
    <div class="easl-col">
        <div class="easl-col-inner mzms-fields-con">
            <label class="mzms-field-label" for="mzf_dotb_user_category">User category <span class="mzms-asteric">*</span></label>
            <div class="mzms-field-wrap">
                <select class="easl-mz-select2" name="dotb_user_category" id="mzf_dotb_user_category" data-placeholder="Select an option"  style="width: 100%;">
                    <option value=""></option>
                    <?php echo easl_mz_get_crm_dropdown_items( 'user_categories' ); ?>
                </select>
            </div>
        </div>
    </div>
    <div class="easl-col">
        <div class="easl-col-inner mzms-fields-con" id="mzms-fields-con-dotb_user_category_other">
            <label class="mzms-field-label" for="mzf_dotb_user_category_other">User category - Other <span class="mzms-asteric">*</span></label>
            <div class="mzms-field-wrap">
                <input type="text" placeholder="" name="dotb_user_category_other" id="mzf_dotb_user_category_other" autocomplete="off">
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
                    <?php echo easl_mz_get_crm_dropdown_items( 'specialities'); ?>
                </select>
            </div>
        </div>
    </div>
    <div class="easl-col">
        <div class="easl-col-inner mzms-fields-con" id="mzms-fields-con-medical_speciality_c_other">
            <label class="mzms-field-label" for="mzf_medical_speciality_c_other">Specialty - Other <span class="mzms-asteric">*</span></label>
            <div class="mzms-field-wrap">
                <input type="text" placeholder="" name="medical_speciality_c_other" id="mzf_medical_speciality_c_other" autocomplete="off">
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
					<?php echo easl_mz_get_crm_dropdown_items( 'genders' ); ?>
                </select>
            </div>
        </div>
    </div>
</div>
<div class="mzms-fields-row easl-row easl-row-col-2">
    <div class="easl-col">
        <div class="easl-col-inner mzms-fields-con">
            <label class="mzms-field-label" for="mzf_birthdate">Date of birth</label>
            <div class="mzms-field-wrap">
                <input type="hidden" placeholder="" name="birthdate" id="mzf_birthdate" value="" class="easl-mz-date" autocomplete="off">
                <input type="text" placeholder="" name="" id="mzf_birthdate_fz" class="easl-mz-date" autocomplete="off">
            </div>
        </div>
    </div>
</div>

<div class="mzms-fields-row easl-row easl-row-col-2">
    <div class="easl-col">
        <div class="easl-col-inner mzms-fields-con">
            <label class="mzms-field-label" for="mzf_dotb_place_of_work">Place of work <span class="mzms-asteric">*</span></label>
            <div class="mzms-field-wrap">
                <select class="easl-mz-select2" name="dotb_place_of_work" id="mzf_dotb_place_of_work" data-placeholder="Select one option"  style="width: 100%;">
                    <option value=""></option>
                    <?php echo easl_mz_get_crm_dropdown_items( 'places_of_work' ); ?>
                </select>
            </div>
        </div>
    </div>
</div>

<div class="mzms-fields-row easl-row easl-row-col-2">
    <div class="easl-col">
        <div class="easl-col-inner mzms-fields-con">
            <label class="mzms-field-label" for="mzf_primary_address_country">Country <span class="mzms-asteric">*</span></label>
            <div class="mzms-field-wrap">
                <select class="easl-mz-select2" name="primary_address_country" id="mzf_primary_address_country" data-placeholder="Select an option"  style="width: 100%;">
                    <option value=""></option>
                    <?php echo easl_mz_get_crm_dropdown_items( 'countries' ); ?>
                </select>
            </div>
        </div>
    </div>
</div>
