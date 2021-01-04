<?php
// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
    die( '-1' );
}
add_action( 'template_redirect', 'easl_clock_iframe_loader' );

function easl_clock_iframe_loader() {
    if ( !isset( $_GET['easl_clock_iframe'] ) ) {
        return;
    }
    $orientation = 'landscape';
    if ( ! empty( $_GET['ot'] ) ) {
        $orientation = $_GET['ot'];
    }
    easl_get_template_part( 'easl-clock-iframe.php', array( 'orientation' => $orientation ) );
    die();
}