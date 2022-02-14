<?php
// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
    die( '-1' );
}
/**
 * @var array         $schools
 * @var string        $current_school
 * @var int           $total_applications
 * @var EASLAppReview $reviewManager
 * @var object        $programme
 */
?>
<div class="easl-app-school-list">
    <ul>
        <li class="<?php if ( ! $current_school ) {
            echo 'current-school';
        } ?>"><a href="<?php echo $reviewManager->getUrl( 'programme', [
                'programmeId' => $programme->ID
            ] ); ?>">All schools (<?php echo $total_applications; ?>)</a></li>
        <?php
        foreach ( $schools as $school_key => $school_info ):
            $item_class = '';
            if ( $school_key == $current_school ) {
                $item_class = 'current-school';
            }
            ?>
            <li class="<?php echo $item_class; ?>"><a href="<?php echo $reviewManager->getUrl( 'programme', [
                    'programmeId' => $programme->ID,
                    'school'      => $school_key
                ] ); ?>"><?php echo $school_info['school_name']; ?> (<?php echo $school_info['count']; ?>)</a></li>
        <?php endforeach; ?>
    </ul>
</div>
