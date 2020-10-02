<?php
// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}


/**
 * Shortcode attributes
 * @var $atts
 * @var $title
 * Shortcode class
 * @var $this EASL_VC_MZ_Applications
 */
extract( $atts );

$programme_categories = $this->get_categories();
$add_membership_url = get_field('membership_plan_url', 'option');

$has_content = '';
$html_tabs = '';
$html_content = '';
?>

<?php
ob_start();
foreach($programme_categories as $i => $category):?>
    <a class="tab<?php if ($i === 0):?> active<?php endif;?>" href="#" data-tab-index="<?=$i;?>"><?=$category->name;?></a>
<?php
endforeach;
$html_tabs = ob_get_clean();
?>

<?php
ob_start();
foreach($programme_categories as $i => $category):
    $programmes = $this->get_programmes_for_term($category->term_id);?>
    <div class="tab-pane<?php if ($i === 0):?> active<?php endif;?>">
        <?php if (!easl_mz_user_is_member()):?>
            <div class="easl-member-banner" style="margin-top:10px;">
                Become an EASL member to apply for our programmes
                <a href="<?php echo $add_membership_url;?>" class="easl-generic-button easl-color-lightblue">Join today</a>
            </div>
        <?php endif;?>
        <?php
        if ($programmes):
            $has_content = true;
            ?>
            <?php foreach($programmes as $programme):
                $more_info_link = get_field('more_info_link', $programme->ID);?>
                <div class="applications-widget-programme">
                    <div class="programme-title"><?=$programme->post_title;?></div>
                    <div>
                        <?php if ($more_info_link):?>
                            <a class="easl-generic-button easl-color-blue" href="<?=$more_info_link;?>">
                                More info
                                <span class="easl-generic-button-icon"><span class="ticon ticon-chevron-right"></span></span>
                            </a>
                        <?php endif;?>
                        <?php if ($this->applications_open($programme->ID)):?>
                            <?php if (easl_mz_user_is_member()):?>
                                <?php if ($this->memberHasAlreadyApplied($programme->ID)):?>
                                    <span class="applications-widget-closed easl-generic-button">Already applied</span>
                                <?php else:?>
                                    <a class="easl-generic-button easl-color-blue" href="<?=$this->get_apply_link($programme->ID);?>">
                                        Apply now
                                        <span class="easl-generic-button-icon"><span class="ticon ticon-chevron-right"></span></span>
                                    </a>
                                <?php endif;?>
                            <?php else:?>
                                <span class="applications-widget-closed easl-generic-button">
                                                Apply now
                                                <span class="easl-generic-button-icon"><span class="ticon ticon-chevron-right"></span></span>
                                            </span>
                            
                            <?php endif;?>
                        <?php else:?>
                            <span class="applications-widget-closed easl-generic-button">Applications closed</span>
                        <?php endif;?>
                    </div>
                </div>
            <?php endforeach;?>
        <?php else:?>
            <p></p>
        <?php endif;?>
    </div>
<?php
endforeach;
$html_content = ob_get_clean();

if($has_content):
?>

<script>
    jQuery(function() {
        jQuery('.tab').click(function(e) {
            e.preventDefault();
            var index = jQuery(this).attr('data-tab-index');
            var tabPanes = jQuery('.tab-pane');
            var tabPane = tabPanes.eq(index);
            tabPanes.removeClass('active');
            tabPane.addClass('active');

            var tabs = jQuery('.tabs-nav').find('.tab');
            tabs.removeClass('active');
            jQuery(this).addClass('active');
        })
    });
</script>

<div class="easl-mz-applications-widget">
    <?php if ( $title ): ?>
        <h2 class="mz-section-heading"><?php echo $title; ?></h2>
    <?php endif; ?>
    <div class="easl-mz-tabs">
        <div class="tabs-nav">
            <?php echo $html_tabs; ?>
        </div>
        <div class="tabs-content">
            <?php echo $html_content; ?>
        </div>
    </div>
</div>
<?php endif; ?>