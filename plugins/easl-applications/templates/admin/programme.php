<?php if ($error):?>
    <div class="notice notice-error"><?=$error;?></div>
<?php endif;?>

<h1><?=$programme->post_title;?></h1>

<h2 class="nav-tab-wrapper">
    <a href="<?=EASLAppReview::getUrl(EASLAppReview::PAGE_PROGRAMME, ['programmeId' => $programme->ID, 'tab' => 'reviewers']);?>"
       class="nav-tab <?=$tab === 'reviewers' ? 'nav-tab-active' : '';?>">Reviewers</a>
    <a href="<?=EASLAppReview::getUrl(EASLAppReview::PAGE_PROGRAMME, ['programmeId' => $programme->ID, 'tab' => 'submissions']);?>"
       class="nav-tab <?=$tab === 'submissions' ? 'nav-tab-active' : '';?>">Applications</a>
</h2>

<?php if ($tab === 'submissions'):?>

<h2>Applications</h2>

<a href="<?=$reviewManager->getUrl(EASLAppReview::PAGE_CSV, ['programmeId' => $programme->ID]);?>" class="button">Export CSV</a>

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
    <?php foreach($submissions as $submission):?>
        <tr>
            <td><?=$submission['name'];?></td>
            <td><?=$submission['date']->format('Y-m-d');?></td>
            <td><?=$submission['numberReviews'];?></td>
            <td><?=$submission['averageScore'] ? $submission['averageScore'] : '-';?></td>
            <td><a href="<?=$reviewManager->getUrl(EASLAppReview::PAGE_SUBMISSION, ['submissionId' => $submission['id']]);?>" class="button">Review</a></td>
        </tr>
    <?php endforeach;?>
    </tbody>
</table>

<?php else:?>

<div class="postbox" style="margin-top:20px;">
    <div class="inside">
        <h4>Invite reviewer</h4>
        <form method="post">
            <input type="email" name="reviewer_invitation_email" placeholder="Email address" />
            <button type="submit" class="wp-core-ui button">Submit</button>
        </form>
    </div>
</div>

<h2>Reviewers</h2>
<table class="widefat striped">
    <thead>
    <tr>
        <th>Name</th>
        <th>Email address</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach($reviewers as $reviewer):?>
        <tr>
            <td><?=$reviewer['name'];?></td>
            <td><?=$reviewer['email'];?></td>
        </tr>
    <?php endforeach;?>
    </tbody>
</table>

<?php endif;?>
