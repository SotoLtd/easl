( function( $ ) {

	if ( typeof vc === 'undefined' || typeof vc.shortcode_view === 'undefined' ) {
		return false;
	}

	window.vcexIconBoxVcBackendView = vc.shortcode_view.extend( {
		changeShortcodeParams: function ( model ) {
			window.vcexIconBoxVcBackendView.__super__.changeShortcodeParams.call( this, model );
			var heading = model.getParam( 'heading' );
			var target  = this.$el.find( '.vcex-heading-text > span' );
			if ( target.length ) {
				if ( heading && _.isString( heading ) && ! heading.match(/^#E\-8_/) ) {
					target.html( ': ' + heading );
				} else {
					target.html( '' );
				}
			}
		}
	} );

} ) ( jQuery );