<?php
// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
    die( '-1' );
}
/**
 * @var WP_Post       $programme
 * @var EASLAppReview $reviewManager
 * @var array         $submissions
 * @var array         $schools
 * @var string        $current_school
 * @var int           $total_applications
 */

$page_title = $programme->post_title;
if($schools) {
    if($current_school) {
        $page_title .= ' - <span>'. $schools[$current_school]['school_name'] .'</span>';
    }else{
        $page_title .= ' - <span>All schools</span>';
    }
}
?>

<h1 class="application-page-title"><?php echo $page_title; ?></h1>

<?php if($schools): ?>
<?php
    EASLApplicationsPlugin::load_template('review/school-list.php', [
        'schools' => $schools,
        'current_school' => $current_school,
        'total_applications' => $total_applications,
        'programme' => $programme,
        'reviewManager' => $reviewManager,
    ]);
    ?>
<?php endif; ?>

<table>
    <thead>
    <tr>
        <th style="width: 20px">&nbsp;</th>
        <th>Name</th>
        <th>Date submitted</th>
        <th>Status</th>
        <th style="width: 104px;">Total Score</th>
        <th style="width: 133px; text-align: center">&nbsp;</th>
    </tr>
    </thead>
    <tbody>
    <?php
    $count = 0;
    foreach ( $submissions as $submission ):
        $count++;
        $total_score_by_me = '';
        if(!empty($submission['totalScoreByMe'])) {
            $total_score_by_me = $submission['totalScoreByMe'];
        }
        
        if($schools && !empty($submission['myReview'])) {
            $exclude_school_scores = easl_app_school_exclude_list_for_review($submission['id'], 0);
            $total_score_by_me = 0;
            
            foreach ($submission['myReview']['scoring'] as $score_item) {
                if(in_array($score_item['name'], $exclude_school_scores)) {
                    continue;
                }
                $total_score_by_me += $score_item['score'];
            }
        }
        ?>
        <tr>
            <td><?php echo $count; ?></td>
            <td><?= $submission['name']; ?></td>
            <td><?= $submission['date']->format( 'Y-m-d' ); ?></td>
            <td><?= $submission['reviewedByMe'] ? 'Reviewed' : 'Pending'; ?></td>
            <td><?php echo $total_score_by_me;?></td>
            <td>
                <a href="<?= $reviewManager->getUrl( EASLAppReview::PAGE_SUBMISSION, [ 'submissionId' => $submission['id'] ] ); ?>" class="easl-generic-button easl-color-lightblue"><?= $submission['reviewedByMe'] ? 'Edit review' : 'Review'; ?></a>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
