<?php
// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}
/**
 * @var array $member
 */
?>
<div class="mzms-fields-row easl-row easl-row-col-2">
    <div class="easl-col">
        <div class="easl-col-inner mzms-fields-con">
            <label class="mzms-field-label" for="mzf_phone_work">Phone (work)</label>
            <div class="mzms-field-wrap">
                <input type="text" placeholder="" name="phone_work" id="mzf_phone_work" value="<?php echo esc_attr( $member['phone_work'] ); ?>" autocomplete="off">
            </div>
        </div>
    </div>
    <div class="easl-col">
        <div class="easl-col-inner mzms-fields-con">
            <label class="mzms-field-label" for="mzf_phone_mobile">Mobile</label>
            <div class="mzms-field-wrap">
                <input type="text" placeholder="" name="phone_mobile" id="mzf_phone_mobile" value="<?php echo esc_attr( $member['phone_mobile'] ); ?>" autocomplete="off">
            </div>
        </div>
    </div>
</div>
<div class="mzms-fields-row easl-row easl-row-col-2">
    <div class="easl-col">
        <div class="easl-col-inner mzms-fields-con">
            <label class="mzms-field-label" for="mzf_phone_home">Phone (home)</label>
            <div class="mzms-field-wrap">
                <input type="text" placeholder="" name="phone_home" id="mzf_phone_home" value="<?php echo esc_attr( $member['phone_home'] ); ?>" autocomplete="off">
            </div>
        </div>
    </div>
    <div class="easl-col">
        <div class="easl-col-inner mzms-fields-con">
            <label class="mzms-field-label" for="mzf_phone_other">Other Phone</label>
            <div class="mzms-field-wrap">
                <input type="text" placeholder="" name="phone_other" id="mzf_phone_other" value="<?php echo esc_attr( $member['phone_other'] ); ?>" autocomplete="off">
            </div>
        </div>
    </div>
</div>
<div class="mzms-fields-row easl-row easl-row-col-2">
    <div class="easl-col">
        <div class="easl-col-inner mzms-fields-con">
            <label class="mzms-field-label" for="mzf_phone_fax">Fax number</label>
            <div class="mzms-field-wrap">
                <input type="text" placeholder="" name="phone_fax" id="mzf_phone_fax" value="<?php echo esc_attr( $member['phone_fax'] ); ?>" autocomplete="off">
            </div>
        </div>
    </div>
    <div class="easl-col">
        <div class="easl-col-inner mzms-fields-con">
            <div class="mzms-field-wrap">
                <label class="mzms-field-label" for="mzf_do_not_call" class="easl-custom-checkbox">
                    <input type="checkbox" name="do_not_call" id="mzf_do_not_call" value="1" <?php checked( 1, $member['do_not_call'], true ); ?> autocomplete="off">
                    <span>Do not call</span>
                </label>
            </div>
        </div>
    </div>
</div>
<div class="mzms-fields-row easl-row easl-row-col-2">
    <div class="easl-col">
        <div class="easl-col-inner mzms-fields-con">
            <label class="mzms-field-label" for="mzf_assistant">Assistant</label>
            <div class="mzms-field-wrap">
                <input type="text" placeholder="" name="assistant" id="mzf_assistant" value="<?php echo esc_attr( $member['assistant'] ); ?>" autocomplete="off">
            </div>
        </div>
    </div>
    <div class="easl-col">
        <div class="easl-col-inner mzms-fields-con">
            <label class="mzms-field-label" for="mzf_dotb_assistant_email">Assistant email</label>
            <div class="mzms-field-wrap">
                <input type="text" placeholder="" name="dotb_assistant_email" id="mzf_dotb_assistant_email" value="<?php echo esc_attr( $member['dotb_assistant_email'] ); ?>" autocomplete="off">
            </div>
        </div>
    </div>
</div>
<div class="mzms-fields-row easl-row easl-row-col-2">
    <div class="easl-col">
        <div class="easl-col-inner mzms-fields-con">
            <label class="mzms-field-label" for="mzf_assistant_phone">Assistant telephone</label>
            <div class="mzms-field-wrap">
                <input type="text" placeholder="" name="assistant_phone" id="mzf_assistant_phone" value="<?php echo esc_attr( $member['assistant_phone'] ); ?>" autocomplete="off">
            </div>
        </div>
    </div>
    <div class="easl-col">
        <div class="easl-col-inner mzms-fields-con">
            <label class="mzms-field-label" for="mzf_twitter">Twitter account</label>
            <div class="mzms-field-wrap">
                <input type="text" placeholder="" name="twitter" id="mzf_twitter" value="<?php echo esc_attr( $member['twitter'] ); ?>" autocomplete="off">
            </div>
        </div>
    </div>
</div>
