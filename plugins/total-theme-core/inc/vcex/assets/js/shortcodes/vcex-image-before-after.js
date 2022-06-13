( function( $ ) {

	'use strict';

	if ( 'function' !== typeof window.vcexBeforeAfter ) {

		window.vcexBeforeAfter = function ( $context ) {

			if ( 'undefined' === typeof $.fn.twentytwenty ) {
				return;
			}

			$( '.vcex-image-ba', $context ).each( function() {
				var $this = $( this );
				$this.twentytwenty( $this.data( 'options' ) );
			} );

		};

	}

	$( window ).on( 'load', function() {
		window.vcexBeforeAfter();
	} );

} ) ( jQuery );