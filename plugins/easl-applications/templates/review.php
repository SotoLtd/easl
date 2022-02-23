<?php
// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
    die( '-1' );
}
/**
 * @var EASLAppReview $reviewManager
 * @var boolean $isAdmin
 * @var WP_Post $submission
 * @var WP_Post $programme
 * @var array $reviews
 * @var array $fields
 * @var array $scoringCriteria
 */


$category = get_field( 'programme-category', $programme->ID );
?>
<h1>Review submission: <?=$submission->post_title;?></h1>

<p><a href="<?=$reviewManager->getUrl('programme', ['programmeId' => $programme->ID]);?>">&laquo; Back to all submissions</a></p>

<div class="wpb_column vc_column_container">
    <div class="vc_column-inner">

        <table class="review-submission-table<?php if ($isAdmin):?> widefat striped<?php endif;?>">
            <tbody>

            <?php
            foreach($fields as $key => $field):
                if('easl-schools-all' == $category) {
    
                    $schools = get_field('easl-schools-all_programme_information_schools', $submission->ID);
                    
                    if ( $key == 'easl-schools-all_programme_information_reference_letter_amsterdam' && ! in_array( 'amsterdam', $schools ) ) {
                        continue;
                    }
                    if ( $key == 'easl-schools-all_programme_information_reference_letter_barcelona' && ! in_array( 'barcelona', $schools ) ) {
                        continue;
                    }
                    if ( $key == 'easl-schools-all_programme_information_reference_letter_frankfurt' && ! in_array( 'frankfurt', $schools ) ) {
                        continue;
                    }
                    if ( $key == 'easl-schools-all_programme_information_reference_letter_hamburg' && ! in_array( 'hamburg', $schools ) ) {
                        continue;
                    }
    
    
                    if ( $key == 'easl-schools-all_programme_information_recommendation_letter_amsterdam' && ! in_array( 'amsterdam', $schools ) ) {
                        continue;
                    }
                    if ( $key == 'easl-schools-all_programme_information_recommendation_letter_barcelona' && ! in_array( 'barcelona', $schools ) ) {
                        continue;
                    }
                    if ( $key == 'easl-schools-all_programme_information_recommendation_letter_frankfurt' && ! in_array( 'frankfurt', $schools ) ) {
                        continue;
                    }
                    if ( $key == 'easl-schools-all_programme_information_recommendation_letter_hamburg' && ! in_array( 'hamburg', $schools ) ) {
                        continue;
                    }
                }
                ?>
                <tr>
                    <?php if (is_array($field)):?>
                        <th><?=$field['label'];?></th>
                    <?php else:?>
                        <th>
                            <?php if ($key === 'averageScore'):?>
                                Average score
                            <?php elseif ($key === 'numberReviews'):?>
                                Number reviews
                            <?php else:?>
                                <?=$key;?>
                            <?php endif;?>
                        </th>
                    <?php endif;?>
                    <td>
                        <?php  if($field):?>
                            <?php if (is_string($field)):?>
                                <?=$field;?>
                            <?php elseif ($field['type'] == 'file'):?>
                                <?php if ($field['value']):?>
                                    <a href="<?=$field['value']['url'];?>" target="_blank">View file</a>
                                <?php else:?>
                                    No file uploaded
                                <?php endif;?>
                            <?php elseif ($field['type'] == 'select'):?>
                                <?=$field['choices'][$field['value']];?>
                            <?php elseif ($field['type'] == 'checkbox'):?>
                                <?php
                                    $fields_value = [];
                                    foreach ($field['value'] as $value_key){
                                        $fields_value[] = $field['choices'][$value_key];
                                    }
                                    echo implode(', ', $fields_value);
                                ?>
                            <?php else:?>
                                <?=$field['value'];?>
                            <?php endif;?>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach;?>

            </tbody>
        </table>

        <?php if ($isAdmin && $reviews):?>

            <h2>Reviews</h2>

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
            <h2>Review submission</h2>

            <form method="post">
            <?php
            foreach($scoringCriteria as $i => $category):
                if ($myReview) {
                    $myScore = array_filter($myReview['scoring'], function($c) use ($category) {
                        return $c['name'] === $category['criteria_name'];
                    });
                    $myScore = current($myScore);
                }
                ?>

                <?php
    
                if ( empty ($category['criteria_name']) ) {
                    continue;
                }
                
                if(!empty($category['criteria_label'])) {
                    $school_name = $category['criteria_label'];
                }else{
                    $school_name = $category['criteria_name'];
                }
                ?>
                <div class="mzms-fields-row" style="padding-bottom:10px;">
                    <div class="mzms-fields-con">
                        <label class="mzms-field-label"><?php echo $school_name;?> <span class="mzms-asteric">*</span></label>
                        <div><?=$category['criteria_instructions'];?></div>
                        <div class="mzms-field-wrap<?php if (isset($saveReviewErrors['categories']) && in_array($i, $saveReviewErrors['categories'])):?> easl-mz-field-has-error<?php endif;?>">
                            <input type="number"
                                   max="<?=$category['criteria_max'];?>"
                                   name="category[<?=$i;?>][score]"
                                   value="<?=isset($myScore) ? $myScore['score'] : '';?>"
                                   min="0" />
                            out of <?=$category['criteria_max'];?>
                            <p class="mzms-field-error-msg">Please enter the score for this category</p>
                        </div>
                        <input type="hidden"
                               name="category[<?=$i;?>][name]"
                               value="<?=$category['criteria_name'];?>"/>
                    </div>
                </div>
            <?php endforeach;?>

            <div class="mzms-fields-row">
                <div class="mzms-fields-con">
                    <label class="mzms-field-label">Review <span class="mzms-asteric">*</span></label>
                    <div class="mzms-field-wrap<?php if (in_array('review_text', $saveReviewErrors)):?> easl-mz-field-has-error<?php endif;?>">
                        <textarea name="review_text"><?=isset($myReview) ? $myReview['review_text'] : '';?></textarea>
                        <p class="mzms-field-error-msg">Please enter your review</p>
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