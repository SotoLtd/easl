<?php
/**
 * @var $sticky_message
 * @var $enabled_for
 */
?> 
<div id="easl-sticky-footer-message-wrap" data-page="<?php echo $enabled_for; ?>">
	<a id="easl-close-sticky-message" href="#">
		<span class="ticon ticon-times" aria-hidden="true"></span>
	</a>
	<div id="easl-sticky-footer-message-inner" class="container clr">
		<?php echo $sticky_message; ?>
	</div>
</div>