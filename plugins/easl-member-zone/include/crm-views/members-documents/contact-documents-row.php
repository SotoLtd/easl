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
$cards = [];
foreach ($member_notes as $note) {
    $card_file_li = '<li>';
    $card_file_li .= '<a href="' . easl_mz_get_note_download_url( $note ) . '"><span>' . basename($note['filename']) . '</span><span>Download</span></a>';
    $card_file_li .= '</li>';
    
    $doc_type_title = '';
    switch ( $note['dotb_type'] ) {
        case 'receipt':
            $doc_type_title = 'Receipts';
            break;
        case 'certificate':
            $doc_type_title = 'Certificates';
            break;
        case 'invoice':
            $doc_type_title = 'Invoices';
            break;
        case 'proof_age':
            $doc_type_title = 'Proofs of age';
            break;
        case 'proof_nurse':
            $doc_type_title = 'Proofs of nurse';
            break;
        default:
            $doc_type_title = 'Others';
            break;
    }
    if ( ! isset( $cards[ $doc_type_title ] ) ) {
        $cards[ $doc_type_title ] = [];
    }
    $cards[ $doc_type_title ][] = $card_file_li;
}

ksort($cards);

foreach ( $cards as $card_title => $card_lis ):
    ?>
    <div class="mzmd-docs-card">
        <h3><?php echo $card_title; ?></h3>
        <ul>
            <?php echo implode( "\n", $card_lis ); ?>
        </ul>
    </div>
<?php endforeach; ?>
