<?php
// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
    die( '-1' );
}
?>

<?php
$programme_id = isset($_GET['programme_id']) ? $_GET['programme_id'] : null; //12610;
$programme = get_post($programme_id);

if($programme && 'programme' == get_post_type($programme)):
    
    $sub = new EASLAppSubmission($programme_id);
    $valid = $sub->setup();
?>

<h1><?=$programme->post_title;?>: Your application</h1>
<div><?=$programme->post_content;?></div>

<?php
if ($valid):
    
    $apps = EASLApplicationsPlugin::getInstance();
    $fieldContainer = $apps->getProgrammeFieldSetContainer($programme_id);
    
    $fieldSetKeys = $fieldContainer->getFieldSetKeys();
    
    ?>
    <?php
    $args =
        [
            'post_id' => $sub->getSubmissionId(),
            'field_groups' => $fieldContainer->getFieldSetKeys(),
            'updated_message' => 'Thank you, your application has been submitted.',
            'html_updated_message' => '<div class="application-submitted-message">%s</div>',
            'html_submit_button' => '<input type="submit" value="Submit application" class="mzms-submit">'
        ];
    acf_form($args);
    ?>
<?php else:?>
    <h2>An error occurred</h2>
    <p>An error occurred trying to apply for this programme.  Perhaps the link you followed is out of date.</p>
<?php endif;?>
<?php else:?>
<p>Invalid programme</p>
<?php endif;?>