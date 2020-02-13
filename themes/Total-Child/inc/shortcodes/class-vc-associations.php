<?php
/**
 * EASL_VC_National_Associations
 */

if( class_exists('WPBakeryShortCode') ){
    class EASL_VC_National_Associations extends WPBakeryShortCode {
	    public static function get_national_associations(){
		    $category = $_POST['category'];
		    $the_associations = new WP_Query( array(
			    'posts_per_page' => -1,
			    'post_type' => 'associations',
			    'tax_query' => array(
				    array(
					    'taxonomy' => 'associations_category',
					    'field' => 'term_id',
					    'terms' => $category,
				    )
			    )
		    ) );

		    if ( $the_associations->have_posts() ):
			    ob_start();
			    while ($the_associations->have_posts()):
				    $the_associations->the_post();
				    $image = has_post_thumbnail( get_the_ID() ) ?
					    wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ), 'single-post-thumbnail' ) : '';
				    ?>
				    <div class="associations clr">
					    <div class="associations-content-wrapper">
						    <div class="d-flex">
							    <?php echo ($image ? '<div class="associations-thumb"><img alt="" src="'.$image[0].'"/></div>' : '')?>
							    <div class="associations-title-wrap clr">
								    <?php echo the_title('<h3>','</h3>');?>
							    </div>
						    </div>
						    <div class="associations-content"><?php the_content();?></div>
					    </div>
				    </div>
			    <?php endwhile;
		    else:?>
			    <p>'there is not any post yet'</p>
		    <?php endif;

		    $html = ob_get_contents();
		    ob_end_clean();
		    echo $html;
		    die();
	    }
    }
}

add_action( 'wp_ajax_get_national_associations_func', array( 'EASL_VC_National_Associations', 'get_national_associations') );
add_action( 'wp_ajax_nopriv_get_national_associations_func', array( 'EASL_VC_National_Associations', 'get_national_associations') );
