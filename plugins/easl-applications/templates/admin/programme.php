<?php
// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
    die( '-1' );
}
/**
 * @var WP_Post       $programme
 * @var EASLAppReview $reviewManager
 * @var array         $submissions
 * @var array         $reviewers
 * @var string        $error
 * @var string        $tab
 */

$category = get_field( 'programme-category', $programme->ID );
?>

<?php if ( $error ): ?>
    <div class="notice notice-error"><?= $error; ?></div>
<?php endif; ?>

<h1><?= $programme->post_title; ?></h1>

<h2 class="nav-tab-wrapper">
    <a href="<?= EASLAppReview::getUrl( EASLAppReview::PAGE_PROGRAMME, [
        'programmeId' => $programme->ID,
        'tab'         => 'reviewers'
    ] ); ?>"
            class="nav-tab <?= $tab === 'reviewers' ? 'nav-tab-active' : ''; ?>">Reviewers</a>
    <a href="<?= EASLAppReview::getUrl( EASLAppReview::PAGE_PROGRAMME, [
        'programmeId' => $programme->ID,
        'tab'         => 'submissions'
    ] ); ?>"
            class="nav-tab <?= $tab === 'submissions' ? 'nav-tab-active' : ''; ?>">Applications</a>
</h2>

<?php if ( $tab === 'submissions' ): ?>

    <h2>Applications</h2>

    <a href="<?= $reviewManager->getUrl( EASLAppReview::PAGE_CSV, [ 'programmeId' => $programme->ID ] ); ?>" class="button">Export CSV</a>

    <table class="widefat striped" style="margin-top:20px;">
        <thead>
        <tr>
            <th>Name</th>
            <th>Date submitted</th>
            <th>Reviews</th>
            <th>Score</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ( $submissions as $submission ): ?>
            <tr>
                <td><?= $submission['name']; ?></td>
                <td><?= $submission['date']->format( 'Y-m-d' ); ?></td>
                <td><?= $submission['numberReviews']; ?></td>
                <td>
                    <?php
                    if ( 'easl-schools-all' == $category ){
                        $choices = easl_app_get_schools_selected($submission['id']);
                        echo $choices['first_choice'] . ': ' .  $submission['averageScore1'];
                        if($choices['second_choice']) {
                            echo '<br>' . $choices['second_choice'] . ': ' .  $submission['averageScore2'];
                        }
                    }else{
                        echo $submission['averageScore'];
                    }
                    ?>
                </td>
                <td>
                    <a href="<?= $reviewManager->getUrl( EASLAppReview::PAGE_SUBMISSION, [ 'submissionId' => $submission['id'] ] ); ?>" class="button">Review</a>
                    <a href="<?= get_edit_post_link( $submission['id'] ); ?>" class="button">Edit</a>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

<?php else: ?>

    <div class="postbox" style="margin-top:20px;">
        <div class="inside">
            <h4>Invite reviewer</h4>
            <form method="post">
                <table class="widefat striped">
                    <tr>
                        <th style="width: 120px;">
                            <label for="reviewer_invitation_email">Email</label>
                        </th>
                        <td>
                            <input style="width: 250px;" type="email" name="reviewer_invitation_email" id="reviewer_invitation_email" placeholder="Email address"/>
                        </td>
                    </tr>
                    <?php
                    if ( 'easl-schools-all' == $category ):
                        ?>

                        <tr>
                            <th>Select schools</th>
                            <td>
                                <label style="margin-right: 10px;">
                                    <input type="checkbox" name="reviewer_schools[]" value="amsterdam"/>
                                    <span>Clinical School Padua</span>
                                </label>
                                <label style="margin-right: 10px;">
                                    <input type="checkbox" name="reviewer_schools[]" value="barcelona"/>
                                    <span>Clinical School London</span>
                                </label>
                                <label style="margin-right: 10px;">
                                    <input type="checkbox" name="reviewer_schools[]" value="frankfurt"/>
                                    <span>Basic science school London</span>
                                </label>
                                <label>
                                    <input type="checkbox" name="reviewer_schools[]" value="hamburg"/>
                                    <span>Clinical School Freiburg</span>
                                </label>
                            </td>
                        </tr>
                    <?php endif; ?>
                    <tr>
                        <th>&nbsp;</th>
                        <td>
                            <button type="submit" class="wp-core-ui button">Submit</button>
                        </td>
                    </tr>
                </table>
            </form>
        </div>
    </div>

    <h2>Reviewers</h2>
    <?php if ( $reviewers ): ?>
        <table class="widefat striped">
            <thead>
            <tr>
                <th>Name</th>
                <th>Email address</th>
                <?php if ( 'easl-schools-all' == $category ): ?>
                    <th>Schools</th>
                <?php endif; ?>
                <th></th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ( $reviewers as $reviewer ): ?>
                <tr>
                    <td><?= $reviewer['name']; ?></td>
                    <td><?= $reviewer['email']; ?></td>
                    <?php
                    if ( 'easl-schools-all' == $category ) {
                        $schools_names = [];
                        $schools       = [
                            'amsterdam' => 'Clinical School Padua',
                            'barcelona' => 'Clinical School London',
                            'frankfurt' => 'Basic science school London',
                            'hamburg'   => 'Clinical School Freiburg',
                        ];
                        if ( isset( $reviewer['schools'] ) ) {
                            foreach ( $reviewer['schools'] as $school ) {
                                if ( isset( $schools[ $school ] ) ) {
                                    $schools_names[] = $schools[ $school ];
                                }
                            }
                        } else {
                            $schools_names[] = 'All schools';
                        }
                        echo '<td>' . implode( ', ', $schools_names ) . '</td>';
                    }
                    ?>
                    <td>
                        <a href="<?= EASLAppReview::getUrl( EASLAppReview::PAGE_PROGRAMME, [
                            'programmeId'           => $programme->ID,
                            'tab'                   => 'reviewers',
                            'remove_reviewer_email' => $reviewer['email']
                        ] ); ?>" class="wp-core ui-button">Remove reviewer</a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>You have not yet invited any reviewers.</p>
    <?php endif; ?>

<?php endif; ?>
