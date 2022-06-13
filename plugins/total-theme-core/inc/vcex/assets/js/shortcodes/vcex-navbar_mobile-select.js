( function( $ ) {

	'use strict';

	if ( 'function' !== typeof window.vcexNavbarMobileSelect ) {
		window.vcexNavbarMobileSelect = function ( $context ) {

			var $selects = $( '.vcex-navbar-mobile-select' );

			if ( ! $selects.length ) {
				return;
			}

			$selects.each( function() {

				var $this   = $( this );
				var $select = $( this ).find( 'select' );
				var $navbar = $this.parent( '.vcex-navbar' ).find( '.vcex-navbar-inner' );

				$select.change( function() {

					var val = $( this ).val();

					if ( val ) {
						$navbar.find( 'a[href="' + val + '"]' ).get(0).click();
					}

				} );

			} );

		};

	}

	$( document ).ready( function() {
		window.vcexNavbarMobileSelect();
	} );

} ) ( jQuery );