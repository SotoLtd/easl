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