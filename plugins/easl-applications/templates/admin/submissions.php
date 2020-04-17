<h1>Programme applications</h1>
<table>
    <thead>
    <tr>
        <th>Programme name</th>
        <th>Opening date</th>
        <th>Closing date</th>
        <th>Number of submissions</th>
        <th></th>
    </tr>
    </thead>
    <tbody>
        <?php foreach($programmes as $programme):?>
            <tr>
                <td><?=$programme->post_title;?></td>
                <td><?=$programme->start_date;?></td>
                <td><?=$programme->end_date;?></td>
                <td><?=$programme->submissions_count;?></td>
                <td>
                    <a href="<?=EASLAppReview::getUrl(EASLAppReview::PAGE_PROGRAMME, ['programmeId' => $programme->ID]);?>">Review</a>
                </td>
            </tr>
        <?php endforeach;?>
    </tbody>
</table>