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
 * @var array $guidelinesLink
 * @var string $reviewDeadline
 * @var array $reviewers_data
 */
?>
<p>Dear <?= $firstName; ?> <?= $lastName; ?>,</p>
<p>We are pleased to provide you with information on the review process of the <?= $programmeName; ?>.</p>
<p>The applications are now ready for your review.</p>

<p><strong>STEP 1:</strong> Log in to your MyEASL account at https://easl.eu/</p>
<p>Your username is the email address where you receive this invitation.</p>
<p><strong>STEP 2:</strong> Click on the "Review applications" link in the left hand menu.</p>
<p>
    <strong>STEP 3:</strong> Review all the submitted applicants. The review and scoring criteria are listed on the review platform.
</p>
<p>
    <strong>Please keep in mind that the scores and written feedback may be shared with the applicant so that they may improve any potential future applications.</strong>
</p>
<p><strong>STEP 4:</strong> Click on the Save button on the bottom of the page when making modifications.</p>
<p>Deadline: We would appreciate to receive your feedback by <?= $reviewDeadline; ?>.</p>
<p>Should you require any further information or have any questions on the review process, please do not hesitate to contact us at
    <a href="mailto:<?= $contactEmail; ?>"><?= $contactEmail; ?></a>. Thank you again for your help and valuable time.
</p>
<p>Kind regards,</p>
<p><strong>Education Department / The EASL Office</strong><br>
    7 rue Daubin<br>
    1203 Geneva<br>
    Switzerland<br>
    Tel: +41 (0) 22 807 03 60</p>