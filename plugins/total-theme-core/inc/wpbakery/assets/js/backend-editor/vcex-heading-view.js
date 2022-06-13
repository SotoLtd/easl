( function( $ ) {

	if ( typeof vc === 'undefined' || typeof vc.shortcode_view === 'undefined' ) {
		return false;
	}

	window.vcexHeadingView = vc.shortcode_view.extend( {
		changeShortcodeParams: function ( model ) {
			window.vcexHeadingView.__super__.changeShortcodeParams.call( this, model );
			var inverted_value;
			var text = model.getParam( 'text' );
			var source = model.getParam( 'source' );
			var target  = this.$el.find( '.vcex-heading-text > span' );
			if ( text && _.isString( text ) && ! text.match(/^#E\-8_/) ) {
				switch( source ) {
					case 'custom':
						target.html( ': ' + text );
						break;
					default:
						inverted_value = _.invert( this.params.source.value );
						target.html( ': ' + inverted_value[ source ] );
				}
			} else {
				target.html( '' );
			}
		}
	} );

} ) ( jQuery );