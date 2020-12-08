<?php
// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
    die( '-1' );
}
$needs_modal = ! easl_mz_is_member_logged_in() && has_term( 'clinical-practice-guidelines', 'publication_category', get_the_ID() );
?>
<?php if ( have_rows( 'publication_other_languages' ) ): ?>
    <div class="publication-sidebar-item pub-download-item"
            style="padding-bottom: 15px;">
        <h3 class="publication-sidebar-item-title">Download as a PDF</h3>
        <form action="" method="post">
            <div class="easl-custom-select">
                <span class="ec-cs-label">Select language</span>
                <select name="ec-meeting-type">
                    <option value="<?php echo get_field( 'publication_link_to_pdf' ); ?>" selected="selected">English</option>
                    <?php while ( have_rows( 'publication_other_languages' ) ): the_row(); ?>
                        <option value="<?php the_sub_field( 'link_to_file' ); ?>"><?php the_sub_field( 'language' ); ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <a href="<?php echo get_field( 'publication_link_to_pdf' ) ?>" class="easl-button easl-button-wide publication-download-pdf-btn" download>Download</a>
        </form>
    </div>
    <?php if ( get_field( 'publication_slide_decks' ) ): ?>
        <div class="" style="border-top: 1px solid #004b87;">
            <a href="<?php echo get_field( 'publication_slide_decks' ) ?>"
                    class="vcex-button theme-button inline animate-on-hover wpex-dhover-0 <?php if ( $needs_modal ): ?> sp-modal-trigger<?php endif; ?>"
                    style="background:#ffffff;
                                           color:#004b87;
                                           font-family: KnockoutHTF51Middleweight;
                                           font-size: 17px;
                                           background-image: url('/wp-content/themes/Total-Child/images/ppoint.png');
                                           background-repeat: no-repeat;
                                           background-size: auto;
                                           background-position: 0px 5px;
                                           padding-left: 38px;"><span
                        class="theme-button-inner">Download Slide Deck<span
                            class="vcex-icon-wrap theme-button-icon-right"><span
                                class="ticon ticon-angle-right"></span></span></span></a>
        </div>
    <?php endif; ?>
<?php else: ?>
    <div class="publication-sidebar-item pub-download-item">
        <a href="<?php echo get_field( 'publication_link_to_pdf' ) ?>" class="text-decoration-none <?php if ( $needs_modal ): ?>sp-modal-trigger<?php endif; ?>" target="_blank">
            <h3 class="publication-sidebar-item-title" style="border-bottom: none;">Download as a PDF</h3>
        </a>
    </div>
    <?php if ( get_field( 'publication_slide_decks' ) ): ?>
        <div class="" style="padding-top: 10px;border-top: 1px solid #004b87;">
            <a href="<?php echo get_field( 'publication_slide_decks' ) ?>"
                    class="vcex-button theme-button inline animate-on-hover wpex-dhover-0 text-decoration-none <?php if ( $needs_modal ): ?> sp-modal-trigger<?php endif; ?>"
                    style="background:#ffffff;
                                           color:#004b87;
                                           font-family: KnockoutHTF51Middleweight;
                                           font-size: 17px;
                                           background-image: url('/wp-content/themes/Total-Child/images/ppoint.png');
                                           background-repeat: no-repeat;
                                           background-size: auto;
                                           background-position: 0px 5px;
                                           padding: 5px 5px 5px 38px;"
                    download>
                <h3 class="publication-sidebar-item-title" style="border-bottom: none;">Download Slide Deck</h3>
            </a>
        </div>
    <?php endif; ?>
<?php endif; ?>
