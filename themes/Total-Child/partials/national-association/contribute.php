<?php
// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}
$contribute_title      = EASL_VC_National_Associations::get_contribute_data( 'title' );
$contribute_subtitle   = '';
$contribute_btitle     = '';
$contribute_bcolor     = '';
$contribute_form       = '';
$contribute_form_title = '';

$title        = EASL_VC_National_Associations::get_contribute_data( 'title' );
$subtitle     = EASL_VC_National_Associations::get_contribute_data( 'subtitle' );
$button_title = EASL_VC_National_Associations::get_contribute_data( 'button_title' );
$button_color = EASL_VC_National_Associations::get_contribute_data( 'button_color' );
$form_id      = EASL_VC_National_Associations::get_contribute_data( 'form_id' );
$form_title   = EASL_VC_National_Associations::get_contribute_data( 'form_title' );
if(class_exists('\TotalTheme\iLightbox')){
	\TotalTheme\iLightbox::load_css();
}

?>
<div class="esl-nas-contribute-wrap">
    <div class="easl-nas-contribute-text">
		<?php if ( $title ): ?>
            <h4><?php echo $title; ?></h4>
		<?php endif; ?><?php if ( $subtitle ): ?>
            <h5><?php echo $subtitle; ?></h5>
		<?php endif; ?>
    </div>
	<?php if ( $button_title ): ?>
        <div class="easl-nas-contribute-button">
            <a href="#easl-nas-contribute-form-<?php echo $form_id . '-' . EASL_VC_National_Associations::get_nas_count(); ?>"
               class="easl-nas-contribute-button-trigger easl-generic-button <?php echo EASL_VC_Generic_Button::get_color_class( $button_color ); ?> easl-size-small"
               data-type="inline" data-options="width:1920,height:1080"><?php echo $button_title; ?>
                <span class="easl-generic-button-icon"><span class="ticon ticon-chevron-right"></span></span></a>
        </div>
	<?php endif; ?>
</div>
