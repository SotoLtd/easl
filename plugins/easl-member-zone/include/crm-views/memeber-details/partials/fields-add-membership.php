
<div class="mzms-fields-row easl-row easl-row-col-2">
    <div class="easl-col">
        <div class="easl-col-inner mzms-fields-con">
            <label class="mzms-field-label" for="mzf_title">Profession / Job Title <span class="mzms-asteric">*</span></label>
            <div class="mzms-field-wrap">
                <input type="text" placeholder="" name="title" id="mzf_title" value="<?php echo esc_attr( $member['title'] ); ?>" autocomplete="off">
            </div>
        </div>
    </div>
</div>

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
<div class="mzms-fields-separator"></div>
<?php include $template_base . '/partials/fields-communication.php'; ?>
<div class="mzms-fields-separator"></div>
<?php include $template_base . '/partials/fields-address.php'; ?>
<div class="mzms-fields-separator"></div>
<div class="mzms-fields-row">
    <div class="mzms-fields-con">
        <label class="mzms-field-label" for="mzms_personal_profile">Personal Profile</label>
        <div class="mzms-field-wrap">
            <textarea name="description" id="mzms_personal_profile" placeholder=""></textarea>
        </div>
    </div>
</div>