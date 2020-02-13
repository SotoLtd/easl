<?php
// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}
$association_name = trim( get_field( 'association_name' ) );
$address          = trim( get_field( 'address' ) );
$telephone        = trim( get_field( 'telephone' ) );
$fax              = trim( get_field( 'fax' ) );
$email            = trim( get_field( 'email' ) );
$website          = trim( get_field( 'website' ) );
$peoples          = get_field( 'peoples' );

$website_label = wp_parse_url( $website );
if ( $website_label ) {
	$website_label = $website_label['host'];
} else {
	$website_label = $website;
}

?>
<div class="easl-nas-details-header <?php if ( has_post_thumbnail() ) {
	echo 'easl-nas-has-logo';
} ?>">
	<?php
	if ( has_post_thumbnail() ): ?>
        <div class="easl-nas-logo">
            <img src="<?php echo wp_get_attachment_image_url( get_post_thumbnail_id() ); ?>" alt="">
        </div>
	<?php endif; ?>
    <h3><?php echo $association_name; ?></h3>
</div>
<div class="easl-nas-details-content">
    <div class="easl-nas-details-contacts">
		<?php if ( $address ): ?>
            <h5 class="nas-details-heading">Address</h5>
            <div class="easl-nas-address"><?php echo $address; ?></div>
		<?php endif; ?>
		<?php if ( $telephone || $fax || $email ): ?>
            <div class="easl-nas-contacts">
				<?php if ( $telephone ): ?>
                    <p><strong>Tel:</strong> <span><?php echo $telephone; ?></span></p>
				<?php endif; ?>
				<?php if ( $fax ): ?>
                    <p><strong>Fax:</strong> <span><?php echo $fax; ?></span></p>
				<?php endif; ?>
				<?php
                if ( $email ):
				    $email = explode(',', $email);
				    foreach($email as $s_email):
                    ?>
                    <p><strong>E-mail:</strong> <span><a href="mailto:<?php echo trim($s_email); ?>"><?php echo trim($s_email); ?></a></span></p>
				<?php
				    endforeach;
				endif;
				?>
            </div>
		<?php endif; ?>
		<?php if ( $website ): ?>
            <div class="easl-nas-website">
                <a href="<?php echo $website; ?>" target="_blank"><?php echo $website_label; ?></a>
            </div>
		<?php endif; ?>
    </div>
    <div class="easl-nas-details-people-wrap">
		<?php if ( have_rows( 'peoples' ) ): ?>
            <div class="easl-nas-people-list easl-row easl-row-col-3">
				<?php
				while ( have_rows( 'peoples' ) ):
                    the_row('peoples');
					$people_position = trim( get_sub_field( 'position' ) );
					$people_name = trim( get_sub_field( 'name' ) );
					$people_bio = trim( get_sub_field( 'bio' ) );
					$people_telephone = trim( get_sub_field( 'telephone' ) );
					$people_fax = trim( get_sub_field( 'fax' ) );
					$people_email = trim( get_sub_field( 'email' ) );
					?>
                    <div class="easl-col easl-nas-people">
                        <div class="easl-col-inner">
							<?php if ( $people_position ): ?>
                                <h5 class="nas-details-heading nas-people-position"><?php echo $people_position; ?></h5>
							<?php endif; ?>
							<?php if ( $people_name ): ?>
                                <div class="nas-people-name"><?php echo $people_name; ?></div>
							<?php endif; ?>
							<?php if ( $people_bio ): ?>
                                <div class="nas-people-name"><?php echo $people_bio; ?></div>
							<?php endif; ?>
	                        <?php if ( $people_telephone || $people_fax || $people_email ): ?>
                                <div class="easl-nas-people-contacts">
			                        <?php if ( $people_telephone ): ?>
                                        <p><strong>Tel:</strong> <span><?php echo $people_telephone; ?></span></p>
			                        <?php endif; ?>
			                        <?php if ( $people_fax ): ?>
                                        <p><strong>Fax:</strong> <span><?php echo $people_fax; ?></span></p>
			                        <?php endif; ?>
			                        <?php
                                    if ( $people_email ):
	                                    $people_email = explode(',', $people_email);
                                        foreach($people_email as $p_email):
                                    ?>
                                        <p><strong>E-mail:</strong> <span><a href="mailto:<?php echo trim($p_email); ?>"><?php echo trim($p_email); ?></a></span></p>
			                        <?php
			                            endforeach;
			                        endif;
			                        ?>
                                </div>
	                        <?php endif; ?>
                        </div>
                    </div>
				<?php endwhile; ?>
            </div>
		<?php endif; ?>
    </div>
</div>

