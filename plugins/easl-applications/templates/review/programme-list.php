<?php
// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
    die( '-1' );
}
/**
 * @var array         $validProgrammes
 * @var EASLAppReview $reviewManager
 */

if ( count( $validProgrammes ) > 0 ):
    ?>
    <h4>Please select a programme below.</h4>
    <table class="review-programmes-table">
        <thead>
        <tr>
            <th>Programme</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ( $validProgrammes as $programme ): ?>
            <tr>
                <td>
                    <a href="<?= $reviewManager->getUrl( 'programme', [ 'programmeId' => $programme->ID ] ); ?>" class="programme-name">
                        <?php echo $programme->post_title; ?>
                    </a>
                    <a href="<?php echo $reviewManager->getUrl( 'programme', [ 'programmeId' => $programme->ID ] ); ?>" class="easl-generic-button easl-color-lightblue">
                        Review
                    </a>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

<?php else: ?>
    <h2>Sorry, you have not been invited to review applications for any programme.</h2>
<?php endif; ?>
