( function( $ ) {

	if ( typeof vc === 'undefined' || typeof vc.shortcode_view === 'undefined' ) {
		return false;
	}

	window.vcexBackendViewImageGallery = vc.shortcode_view.extend( {

		changeShortcodeParams: function ( model ) {

			window.vcexBackendViewImageGallery.__super__.changeShortcodeParams.call( this, model );

			var self = this;
			var imageIds = model.getParam( 'image_ids' );
			var postGallery = model.getParam( 'post_gallery' );
			var customField = model.getParam( 'custom_field_gallery' );

			$.ajax( {
				type: 'POST',
				url: window.ajaxurl,
				data: {
					action: 'vcex_wpbakery_backend_view_image_gallery',
					imageIds: imageIds,
					postGallery: postGallery,
					customField: customField,
					post_id: vc_post_id,
					_vcnonce: window.vcAdminNonce
				},
				dataType: 'html',
				context: self
			} ).done( function( result ) {
				var grid = self.$el.find( '.vcex-backend-view-images' );
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