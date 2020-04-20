<h1>Review submission: <?=$submission->post_title;?></h1>

<table class="review-submission-table">
    <tbody>

    <?php foreach($fields as $field):
        if ($field['parent'] == $confirmationFieldsetId) continue;?>
        <tr>
            <th><?=$field['label'];?></th>
            <td>
                <?php if ($field['type'] == 'file'):?>
                    <?php if ($field['value']):?>
                        <a href="<?=$field['value']['link'];?>">View file</a>
                    <?php else:?>
                        No file uploaded
                    <?php endif;?>
                <?php elseif ($field['type'] == 'select'):?>
                    <?=$field['choices'][$field['value']];?>
                <?php else:?>
                    <?=$field['value'];?>
                <?php endif;?>
            </td>
        </tr>
    <?php endforeach;?>

    </tbody>
</table>

<h2>Scoring criteria</h2>
<?php print_r($scoringCriteria);?>

<form>
    <?php foreach($scoringCriteria as $category):?>
        <div>
            <h4><?=$category['criteria_name'];?></h4>
            <input type="number" max="<?=$category['criteria_max'];?>" min="0" />
            <p><?=$category['criteria_instructions'];?></p>
        </div>
    <?php endforeach;?>
</form>