( function( $ ) {

	'use strict';

	if ( 'function' !== typeof window.vcexAnimatedText ) {
		window.vcexAnimatedText = function( $context ) {

			if ( 'undefined' === typeof Typed || 'undefined' === typeof $.fn.appear ) {
				return;
			}

			$( '.vcex-typed-text', $context ).each( function() {
				var $this = $( this );
				var strings = $this.data( 'strings' );
				var settings = $this.data( 'settings' );

				settings.typeSpeed  = parseInt( settings.typeSpeed );
				settings.backDelay  = parseInt( settings.backDelay );
				settings.backSpeed  = parseInt( settings.backSpeed );
				settings.startDelay = parseInt( settings.startDelay );
				settings.strings    = strings;

				$this.appear( function() {
					var typed = new Typed( this, settings );
				} );

			} );
		};

	}

	$( document ).ready( function() {
		window.vcexAnimatedText();
	} );

} ) ( jQuery );