<?php
// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}
$forms         = EASL_VC_National_Associations::get_overlay_forms();

if ( is_array( $forms ) && count( $forms ) > 0 ):
	foreach ( $forms as $form ):
		$id = isset( $form['id'] ) ? $form['id'] : '';
		$title = isset( $form['title'] ) ? $form['title'] : '';
		if ( ! $id ) {
			continue;
		}

		?>
        <div id="easl-nas-contribute-form-<?php echo $form['uid']; ?>" class="easl-nas-contribute-form-wrap" style="display: none;visibility: hidden;z-index: -10;">
            <div class="easl-nas-contribute-form-inner">
                <a class="easl-nas-form-close" href="#"><span class="ticon ticon-times"></span></a>
                <?php if ( $title ): ?>
                    <div class="easl-nas-contribute-form-header">
                        <?php echo $title; ?>
                    </div>
                <?php endif; ?>
                <div class="easl-nas-contribute-form">
                    <?php
                    gravity_form_enqueue_scripts( $id, true );
                    gravity_form( $id, false, false, false, '', true );
                    ?>
                </div>
            </div>
        </div>
	<?php
	endforeach;
endif;
?>