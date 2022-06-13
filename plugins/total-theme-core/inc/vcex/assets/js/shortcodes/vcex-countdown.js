( function( $ ) {

	'use strict';

	if ( 'function' !== typeof window.vcexCountDown ) {
		window.vcexCountDown = function ( $context ) {

			if ( 'undefined' === typeof $.fn.countdown ) {
				return;
			}

			$( '.vcex-countdown', $context ).each( function() {

				var $this     = $( this ),
					endDate  = $this.data( 'countdown' ),
					days     = $this.data( 'days' ),
					hours    = $this.data( 'hours' ),
					minutes  = $this.data( 'minutes' ),
					seconds  = $this.data( 'seconds' ),
					timezone = $this.data( 'timezone' );

				if ( timezone && typeof moment.tz !== 'undefined' && $.isFunction( moment.tz ) ) {
					endDate = moment.tz( endDate, timezone ).toDate();
				}

				if ( ! endDate ) {
					return;
				}

				$this.countdown( endDate, function( event ) {
					$this.html( event.strftime( '<div class="wpex-days"><span>%-D</span> <small>' + days + '</small></div> <div class="wpex-hours"><span>%-H</span> <small>' + hours + '</small></div class="wpex-months"> <div class="wpex-minutes"><span>%-M</span> <small>' + minutes + '</small></div> <div class="wpex-seconds"><span>%-S</span> <small>' + seconds + '</small></div>' ) );
				} );

			} );

		};

	}

	$( document ).ready( function() {
		window.vcexCountDown();
	} );

} ) ( jQuery );