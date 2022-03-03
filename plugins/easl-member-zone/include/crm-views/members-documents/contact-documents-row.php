<?php
// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
    die( '-1' );
}
/**
 * @var array  $member_notes {
 * @var string $id
 * @var string $name
 * @var string $file_mime_type
 * @var string $filename
 * @var string $file_ext
 * @var string $dotb_type    certificate|receipt|invoice|proof_age|proof_nurse
 * @var string $parent_type
 * }
 */
?>
<?php

foreach ( $member_notes as $note ):
    $download_title = '';
    $doc_type_title = '';
    switch ( $note['dotb_type'] ) {
        case 'receipt':
            $download_title = 'Download receipt';
            $doc_type_title = 'Receipt';
            break;
        case 'certificate':
            $download_title = 'Download certificate';
            $doc_type_title = 'Certificate';
            break;
        case 'invoice':
            $download_title = 'Download invoice';
            $doc_type_title = 'Invoice';
            break;
        case 'proof_age':
            $download_title = 'Download proof of age';
            $doc_type_title = 'Proof of age';
            break;
        case 'proof_nurse':
            $download_title = 'Download proof of nurse';
            $doc_type_title = 'Proof of nurse';
            break;
        default:
            $download_title = 'Download document';
            $doc_type_title = 'Other';
            break;
    }
    ?>
    <div class="mzmd-docs-table-row">
        <div class="mzmd-docs-table-col mzmd-docs-table-col-name"><?php echo $note['name']; ?></div>
        <div class="mzmd-docs-table-col mzmd-docs-table-col-type"><?php echo $doc_type_title; ?></div>
        <div class="mzmd-docs-table-col mzmd-docs-table-col-download">
            <a class="mzmd-download-link" href="<?php echo easl_mz_get_note_download_url( $note ); ?>" data-type="<?php echo $note['dotb_type']; ?>" target="_blank"><?php echo $download_title; ?></a>
        </div>
    </div>
<?php endforeach; ?>