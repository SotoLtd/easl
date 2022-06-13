( function( $ ) {

	'use strict';

	if ( 'function' !== typeof window.vcexSkillbar ) {
		window.vcexSkillbar = function ( $context ) {
			if ( 'undefined' === typeof $.fn.appear ) {
				return;
			}

			$( '.vcex-skillbar', $context ).each( function() {

				var $this = $( this );
				var $bar = $this.find( '.vcex-skillbar-bar' );
				var animateOnScroll = $this.data( 'animate-on-scroll' );

				$this.appear( function() {
					if ( animateOnScroll ) {
						$bar.width(0);
					}
					$bar.animate( {
						width: $this.data( 'percent' )
					}, 800 );
				}, {
					one: animateOnScroll ? false : true,
					accX: 0,
					accY: 0
				} );

			} );

		};
	}

	$( document ).ready( function() {
		window.vcexSkillbar();
	} );

} ) ( jQuery );