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
<table>
    <thead>
        <tr>
            <th>Name</th>
            <th>Date submitted</th>
        </tr>
    </thead>
    <tbody>
    <?php foreach($submissions as $submission):?>
        <tr>
            <td><?=$submission['name'];?></td>
            <td><?=$submission['date']->format('Y-m-d');?></td>
            <td><a href="<?=EASLAppReview::getUrl(EASLAppReview::PAGE_SUBMISSION, ['submissionId' => $submission['id']]);?>">Review</a></td>
        </tr>
    <?php endforeach;?>
    </tbody>
</table>

<?php else:?>

<div class="postbox">
    <h4>Invite reviewer</h4>
    <div class="inside">
        <form method="post">
            <input type="email" name="reviewer_invitation_email" placeholder="Email address" />
            <button type="submit" class="wp-core-ui button">Submit</button>
        </form>
    </div>
</div>

<h2>Reviewers</h2>
<table>
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
