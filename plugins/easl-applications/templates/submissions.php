<?php
// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
    die( '-1' );
}
?>

<?php if (isset($error)):?>

<h2><?=$error;?></h2>

<?php elseif ($programme):?>

    <?php if (isset($_GET['review_submitted'])):?>
        <div class="easl-application-submitted-notice">
            Thank you, your review has been submitted.
        </div>
    <?php endif;?>
    <h1><?=$programme->post_title;?></h1>
    <table>
        <thead>
        <tr>
            <th>Name</th>
            <th>Date submitted</th>
            <th>Status</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        <?php foreach($submissions as $submission):?>
            <tr>
                <td><?=$submission['name'];?></td>
                <td><?=$submission['date']->format('Y-m-d');?></td>
                <td><?=$submission['reviewedByMe'] ? 'Reviewed' : 'Pending';?></td>
                <td><a href="<?=$reviewManager->getUrl(EASLAppReview::PAGE_SUBMISSION, ['submissionId' => $submission['id']]);?>" class="easl-generic-button easl-color-lightblue"><?=$submission['reviewedByMe'] ? 'Edit review' : 'Review';?></a></td>
            </tr>
        <?php endforeach;?>
        </tbody>
    </table>

<?php elseif (count($validProgrammes) > 0):?>

<p>Please select a programme below.</p>
<table class="review-programmes-table">
    <thead>
    <tr>
        <th>Programme</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach($validProgrammes as $programme):?>
        <tr>
            <td>
                <a href="<?=$reviewManager->getUrl('programme', ['programmeId' => $programme->ID]);?>" class="programme-name">
                    <?=$programme->post_title;?>
                </a>
                <a href="<?=$reviewManager->getUrl('programme', ['programmeId' => $programme->ID]);?>" class="easl-generic-button easl-color-lightblue">
                    Review
                </a>
            </td>
        </tr>
    <?php endforeach;?>
    </tbody>
</table>

<?php else:?>
    <h2>Sorry, you have not been invited to review applications for any programme.</h2>
<?php endif;?>
