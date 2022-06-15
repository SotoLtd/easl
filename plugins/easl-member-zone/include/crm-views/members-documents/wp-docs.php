<?php
// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
    die( '-1' );
}
/**
 * @var array $members_docs
 */
$cards = [];
foreach ( $members_docs as $doc ) {
    $card_file_li = '<li>';
    $card_file_li .= '<a href="' . esc_url( $doc['file'] ) . '" download="' . basename( parse_url( $doc['file'], PHP_URL_PATH ) ) . '"><span>' . $doc['title'] . '</span><span>Download</span></a>';
    $card_file_li .= '</li>';
    if ( ! isset( $cards[ $doc['category'] ] ) ) {
        $cards[ $doc['category'] ] = [];
    }
    $cards[ $doc['category'] ][] = $card_file_li;
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
