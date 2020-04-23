<h1>Review submission: <?=$submission->post_title;?></h1>

<div class="wpb_column vc_column_container">
    <div class="vc_column-inner">

        <table class="review-submission-table<?php if ($isAdmin):?> widefat striped<?php endif;?>">
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

        <h2>Reviews</h2>

        <?php if ($isAdmin && $reviews):?>
            <table class="widefat striped">
                <thead>
                <th>Reviewer</th>
                <th>Review text</th>
                <th>Score</th>
                <th>Score breakdown</th>
                </thead>
                <tbody>
                <?php foreach($reviews as $review):?>
                    <tr>
                        <td><?=$review['reviewer_name'];?> (<?=$review['reviewer_email'];?>)</td>
                        <td><?=$review['review_text'];?></td>
                        <td><?=$review['total_score'];?></td>
                        <td>
                            <?php foreach($review['scoring'] as $score):?>
                                <div>
                                    <strong><?=$score['name'];?>: </strong> <?=$score['score'];?>
                                </div>
                            <?php endforeach;?>
                        </td>
                        </tr>
                <?php endforeach;?>
                </tbody>
            </table>
        <?php endif;?>

        <?php if (!$isAdmin):?>
        <form method="post">
            <?php foreach($scoringCriteria as $i => $category):
                if ($myReview) {
                    $myScore = array_filter($myReview['scoring'], function($c) use ($category) {
                        return $c['name'] === $category['criteria_name'];
                    });
                    $myScore = current($myScore);
                }
                ?>

                <?php if (!$category['criteria_name']): continue; endif;?>
                <div class="mzms-fields-row">
                    <div class="mzms-fields-con">
                        <label class="mzms-field-label"><?=$category['criteria_name'];?> <span class="mzms-asteric">*</span></label>
                        <div class="mzms-field-wrap">
                            <input type="number"
                                   max="<?=$category['criteria_max'];?>"
                                   name="category[<?=$i;?>][score]"
                                   value="<?=isset($myScore) ? $myScore['score'] : '';?>"
                                   min="0" />
                            out of <?=$category['criteria_max'];?>
                        </div>
                        <p><?=$category['criteria_instructions'];?></p>
                        <input type="hidden"
                               name="category[<?=$i;?>][name]"
                               value="<?=$category['criteria_name'];?>"/>
                    </div>
                </div>
            <?php endforeach;?>

            <div class="mzms-fields-row">
                <div class="mzms-fields-con">
                    <label class="mzms-field-label">Review <span class="mzms-asteric">*</span></label>
                    <div class="mzms-field-wrap">
                        <textarea name="review_text"><?=isset($myReview) ? $myReview['review_text'] : '';?></textarea>
                    </div>
                </div>
            </div>

            <?php if ($myReview):?>
                <input type="hidden" name="review_id" value="<?=$myReview['id'];?>">
            <?php endif;?>
            <div class="mzms-fields-row mzms-submit-row">
                <input type="submit" value="Submit review" name="review_submitted" class="mzms-submit">
            </div>
        </form>
        <?php endif;?>
    </div>
</div>