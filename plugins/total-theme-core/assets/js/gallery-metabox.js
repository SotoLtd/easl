( function( $, l10n ) {

	'use strict';

	$( document ).ready( function() {

		// Uploading files.
		var image_gallery_frame;
		var $image_gallery_ids = $( '#wpex_image_gallery_field' );
		var $wpex_gallery_images = $( '#wpex_gallery_images_container ul.wpex_gallery_images' );

		jQuery( '.add_wpex_gallery_images' ).on( 'click', 'a', function( event ) {

			event.preventDefault();

			var attachment_ids = $image_gallery_ids.val();

			// If the media frame already exists, reopen it.
			if ( image_gallery_frame ) {
				image_gallery_frame.open();
				return;
			}

			// Create the media frame.
			image_gallery_frame = wp.media.frames.image_gallery_frame = wp.media( {
				frame: 'post',
				title: l10n.title,
				button: {
					text: l10n.button,
				},
				library: {
					type: 'image'
				},
				multiple: true,
				filterable: 'all'
			} );

			// When an image is selected, run a callback.
			image_gallery_frame.on( 'insert', function() {
				var selection = image_gallery_frame.state().get('selection');
				selection.map( function( attachment ) {
					attachment = attachment.toJSON();
					if ( attachment.id ) {
						attachment_ids = attachment_ids ? attachment_ids + "," + attachment.id : attachment.id;
						 $wpex_gallery_images.append('<li class="image" data-attachment_id="' + attachment.id + '">' +
								'<div class="attachment-preview">' +
									'<div class="thumbnail">' +
										'<img src="' + attachment.url + '" />' +
									'</div>' +
								   '<a href="#" class="wpex-gmb-remove">' + l10n.remove + '</a>' +
								'</div>' +
							'</li>'
						);
					}
				} );
				$image_gallery_ids.val( attachment_ids );

			} );

			// Finally, open the modal.
			image_gallery_frame.open();

		} );

		// Image ordering
		$wpex_gallery_images.sortable( {
			items: 'li.image',
			cursor: 'move',
			scrollSensitivity: 40,
			forcePlaceholderSize: true,
			forceHelperSize: false,
			helper: 'clone',
			opacity: 0.65,
			placeholder: 'wpex-metabox-sortable-placeholder',
			start: function( event,ui ) {
				ui.item.css( 'background-color', '#f6f6f6' );
			},
			stop: function( event,ui ) {
				ui.item.removeAttr( 'style' );
			},
			update: function( event, ui ) {
				var attachment_ids = '';
				$( '#wpex_gallery_images_container ul li.image' ).css( 'cursor', 'default' ).each( function() {
					var attachment_id = jQuery(this).attr( 'data-attachment_id' );
					attachment_ids = attachment_ids + attachment_id + ',';
				} );
				$image_gallery_ids.val( attachment_ids );
			}
		} );

		// Remove images.
		$( '#wpex_gallery_images_container' ).on( 'click', 'a.wpex-gmb-remove', function() {
			$( this ).closest( 'li.image' ).remove();
			var attachment_ids = '';
			$( '#wpex_gallery_images_container ul li.image' ).css( 'cursor', 'default' ).each( function() {
				var attachment_id = jQuery( this ).attr( 'data-attachment_id' );
				attachment_ids = attachment_ids + attachment_id + ',';
			} );
			$image_gallery_ids.val( attachment_ids );
			return false;
		} );

	} );

} ) ( jQuery, wpexGalleryMetabox );