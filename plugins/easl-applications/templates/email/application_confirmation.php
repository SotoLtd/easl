<?php
// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}
/**
 * @var string $firstName
 * @var string $lastName
 * @var string $programmeName
 * @var string $contactEmail
 * @var string $programmeId
 */
$programmeCategory = '';
if(!empty($programmeId)){
    $programmeCategory = get_field('programme-category', $programmeId);
}

if('easl-schools' == $programmeCategory){
    $message = 'You will be notified as to whether you have been successful by the end of April 2021.';
}else{
    $message = 'Your application will be reviewed by external reviewers and the EASL Governing Board. You will be notified as to whether you have been successful within three months of the application deadline.';
}
?>
<p>Dear <?=$firstName;?> <?=$lastName;?>,</p>
<p>We are pleased to acknowledge your application to the <?=$programmeName;?></p>
<p><?php echo $message; ?></p>
<p>Should you require any further information, please contact: <a href="mailto:<?=$contactEmail;?>"><?= $contactEmail;?></a></p>
<p>Kind regards,</p>
<p><strong>The EASL Office</strong><br>
7 rue Daubin<br>
1203 Geneva<br>
Switzerland<br>
Tel: +41 (0) 22 807 03 60</p>