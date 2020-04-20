<?php
$programme_id = isset($_GET['programme_id']) ? $_GET['programme_id'] : null; //12610;
$programme = get_post($programme_id);
$sub = new EASLAppSubmission($programme_id);
$valid = $sub->setup();

$programmeCategory = get_field('programme-category', $programme_id);

$apps = EASLApplicationsPlugin::getInstance();
if ($valid):?>
    <?php acf_form(
        [
            'post_id' => $sub->getSubmissionId(),
            'field_groups' => array_values($apps->submissionFieldSets[$programmeCategory]),
            'updated_message' => 'Thank you, your application has been submitted.',
            'submit_value' => 'Submit application'
        ]
    );
    ?>
<?php else:?>
    <h2>An error occurred</h2>
    <p>An error occurred trying to apply for this programme.  Perhaps the link you followed is out of date.</p>
<?php endif;?>