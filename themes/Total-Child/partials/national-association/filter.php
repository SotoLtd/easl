<?php
// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

$current_nas_id = ! empty( $_GET['nas_id'] ) ? absint( $_GET['nas_id'] ) : '';

$nas_query = new WP_Query( array(
	'posts_per_page' => - 1,
	'post_type'      => 'associations',
	'post_status'    => 'publish',
	'order'          => 'ASC',
	'orderby'        => 'title',
) );
?>
<div class="easl-nas-filter-label">
	<span>Select a Country</span>
</div>
<div class="easl-nas-filter-field">
	<div class="easl-custom-select easl-custom-select-filter-country">
		<span class="ec-cs-label">Please Select</span>
		<select name="nas_id" class="nas_filter_id">
			<option value="">Please Select</option>
			<?php
			if ( $nas_query->have_posts() ):
				while ( $nas_query->have_posts() ):
					$nas_query->the_post();
					?>
					<option value="<?php the_ID(); ?>" <?php selected( $current_nas_id, get_the_ID(), true ); ?>><?php the_title(); ?></option>
				<?php
				endwhile;
				wp_reset_query();
			endif;
			?>
		</select>
	</div>
</div>
