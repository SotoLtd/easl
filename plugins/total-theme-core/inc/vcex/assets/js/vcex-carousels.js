( function( $, l10n ) {

	'use strict';

	if ( 'function' !== typeof window.vcexCarousels ) {
		window.vcexCarousels = function( $context ) {

			if ( 'undefined' === typeof $.fn.wpexOwlCarousel || 'undefined' === typeof $.fn.imagesLoaded ) {
				return;
			}

			$( '.wpex-carousel', $context ).each( function() {

				var $this = $( this );
				var settings = $this.data( 'wpex-carousel' );

				if ( ! settings ) {
					console.log( 'Total Notice: The Carousel template in your child theme needs updating to include wpex-carousel data attribute.' );
					return;
				}

				var defaults = {
						animateIn: false,
						animateOut: false,
						lazyLoad: false,
						autoplayHoverPause: true,
						rtl: l10n.rtl ? true : false,
						navText: [ '<span class="ticon ticon-chevron-left" aria-hidden="true"></span><span class="screen-reader-text">' + l10n.i18n.PREV + '</span>', '<span class="ticon ticon-chevron-right" aria-hidden="true"></span><span class="screen-reader-text">' + l10n.i18n.NEXT + '</span>' ],
						responsive: {
							0: {
								items: settings.itemsMobilePortrait
							},
							480: {
								items: settings.itemsMobileLandscape
							},
							768: {
								items: settings.itemsTablet
							},
							960: {
								items: settings.items
							}
						},
					};

				$this.imagesLoaded( function() {
					var owl = $this.wpexOwlCarousel( $.extend( true, {}, defaults, settings ) );
				} );

			} );

		};

	}

	$( document ).ready( function() {
		window.vcexCarousels();
	} );

} ) ( jQuery, wpexCarouselSettings );