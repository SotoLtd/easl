( function( $ ) {

	if ( typeof vc === 'undefined' || typeof vc.shortcode_view === 'undefined' ) {
		return false;
	}

	window.vcexBackendViewImageBeforeAfter = vc.shortcode_view.extend( {

		changeShortcodeParams: function ( model ) {

			window.vcexBackendViewImageBeforeAfter.__super__.changeShortcodeParams.call( this, model );

			var self = this;

			$.ajax( {
				type: 'POST',
				url: window.ajaxurl,
				data: {
					action: 'vcex_wpbakery_backend_view_image_before_after',
					source: model.getParam( 'source' ),
					beforeImage: model.getParam( 'before_img' ) ? model.getParam( 'before_img' ) : model.getParam( 'primary_image' ),
					afterImage: model.getParam( 'after_img' ) ? model.getParam( 'after_img' ) : model.getParam( 'secondary_image' ),
					beforeImageCf: model.getParam( 'before_img_custom_field' ) ? model.getParam( 'before_img_custom_field' ) : model.getParam( 'primary_image_custom_field' ),
					afterImageCf: model.getParam( 'after_img_custom_field' ) ? model.getParam( 'after_img_custom_field' ) : model.getParam( 'secondary_image_custom_field' ),
					post_id: vc_post_id,
					_vcnonce: window.vcAdminNonce
				},
				dataType: 'html',
				context: self
			} ).done( function( result ) {
				var grid = self.$el.find( '.vcex-backend-view-ba' );
				if ( grid.length ) {
					grid.remove();
				}
				if ( result ) {
					self.$el.find( '.wpb_element_wrapper' ).append( result );
				}
			} );

		}

	} );

} ) ( jQuery );