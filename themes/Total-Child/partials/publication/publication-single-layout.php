<?php
/**
 * Single event layout
 *
 * @package Total WordPress theme
 * @subpackage Partials
 * @version 4.4.1
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
$topic_color = easl_get_publication_topic_color( get_the_ID() );
wp_enqueue_script( 'publication-detailed-item-script',
    get_stylesheet_directory_uri() . '/assets/js/publication_detailed.js',
    [ 'jquery' ],
    false,
    true );

$easl_hide_thumb = get_field( 'easl_hide_thumb' );
?>

<article id="single-blocks" class="single-publication-article entry easl-color-<?php echo $topic_color; ?> clr">
    <div class="publication-main-section">
        <div class="vc_row wpb_row vc_row-fluid vc_row-o-equal-height vc_row-flex">
            <div class="wpb_column vc_column_container vc_col-sm-9">
                <div class="vc_column-inner">
                    <div class="wpb_wrapper clr">
                        <?php if ( $easl_hide_thumb ): ?>
                            <?php get_template_part( 'partials/publication/content' ) ?>
                        <?php else: ?>
                            <div class="vc_row wpb_row vc_inner vc_row-fluid">
                                <div class="wpb_column vc_column_container vc_col-sm-4">
                                    <div class="vc_column-inner">
                                        <div class="pub-thumb">
                                            <?php if ( has_post_thumbnail() ): ?>
                                                <img alt="" src="<?php echo wp_get_attachment_image_url( get_post_thumbnail_id( get_the_ID() ), 'single-post-thumbnail' ); ?>"/>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="wpb_column vc_column_container vc_col-sm-8">
                                    <div class="vc_column-inner">
                                        <?php get_template_part( 'partials/publication/content' ) ?>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <div class="wpb_column vc_column_container vc_col-sm-3">
                <div class="vc_column-inner publication-sidebar">
                    <div class="wpb_wrapper">
                        <?php get_template_part( 'partials/publication/sidebar' ) ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

</article>