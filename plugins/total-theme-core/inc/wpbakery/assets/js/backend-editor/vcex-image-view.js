( function( $ ) {

	if ( typeof vc === 'undefined' || typeof vc.shortcode_view === 'undefined' ) {
		return false;
	}

	/**
	 * Shortcode vcex_image
	 */
	window.vcexBackendViewImage = vc.shortcode_view.extend( {

		changeShortcodeParams: function ( model ) {

			window.vcexBackendViewImage.__super__.changeShortcodeParams.call( this, model );

			var self = this;

			var source = _.isString( model.getParam( 'source' ) ) ? model.getParam( 'source' ) : '';
			var image_data;
			var $thumbnail;

			if ( ! source && _.isString( model.getParam( 'image_source' ) ) ) {
				source = model.getParam( 'image_source' );
			}

			if ( source ) {

				switch( source ) {
					case 'external':
						image_data = model.getParam( 'external_image' );
					break;
					case 'custom_field':
						image_data = model.getParam( 'custom_field_name' );
						if ( ! image_data ) {
							image_data = model.getParam( 'image_custom_field' );
						}
					break;
					default:
						image_data = model.getParam( 'image_id' );
						if ( ! image_data ) {
							image_data = model.getParam( 'image' );
						}
				}

				$.ajax( {
					type: 'POST',
					url: window.ajaxurl,
					data: {
						action: 'vcex_wpbakery_backend_view_image',
						content: image_data,
						size: 'thumbnail',
						post_id: vc_post_id,
						image_source: source,
						_vcnonce: window.vcAdminNonce
					},
					dataType: 'html',
					context: self
				} ).done( function( url ) {
					updateThumbnail( url );
				} );

			}

			function updateThumbnail( url ) {
				if ( url ) {
					$thumbnail = self.$el.find( '.vcex_wpb_image_holder' );
					if ( ! $thumbnail.length ) {
						self.$el.find( '.wpb_element_wrapper' ).append( '<p class="vcex_wpb_image_holder"></p>' );
						$thumbnail = self.$el.find( '.vcex_wpb_image_holder' );
					}
					$thumbnail.html( '<img src="' + url + '" style="max-height:75px" />' );
				} else {
					self.$el.find( '.vcex_wpb_image_holder' ).remove();
				}
			}

		}

	} );

} ) ( jQuery );