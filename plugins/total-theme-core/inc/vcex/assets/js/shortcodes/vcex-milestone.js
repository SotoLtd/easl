( function( $ ) {

	'use strict';

	if ( 'function' !== typeof window.vcexMilestone ) {
		window.vcexMilestone = function ( $context ) {

			if ( typeof CountUp !== 'function' || 'undefined' === typeof $.fn.appear ) {
				return;
			}

			$( '.vcex-milestone .vcex-countup', $context ).each( function() {

				var $this = $( this ),
					data = $this.data( 'options' ),
					startVal = data.startVal,
					endVal = data.endVal,
					decimals = data.decimals,
					duration = data.duration,
					animateOnScroll = data.animateOnScroll;

				var options = {
					useEasing: true,
					useGrouping: true,
					separator: data.separator,
					decimal: data.decimal,
					prefix: '',
					suffix: ''
				};

				var numAnim = new CountUp( this, startVal, endVal, decimals, duration, options );

				$this.appear( function() {
					if ( animateOnScroll ) {
						numAnim.reset();
					}
					numAnim.start();
				}, {
					one: animateOnScroll ? false : true
				} );

			} );

		};

	}

	$( document ).ready( function() {
		window.vcexMilestone();
	} );

} ) ( jQuery );