( function( $ ) {

	'use strict';

	if ( 'function' !== typeof window.vcexNavbarFilterLinks ) {
		window.vcexNavbarFilterLinks = function ( $context ) {

			if ( 'undefined' === typeof $.fn.imagesLoaded || 'undefined' === typeof $.fn.isotope ) {
				return;
			}

			var isRTL = false;

			if ( 'object' === typeof wpexLocalize ) {
				isRTL = wpexLocalize.isRTL;
			}

			// Filter Navs
			$( '.vcex-filter-nav', $context ).each( function() {

				var $grid, settings;
				var $nav           = $( this );
				var $filterLinks   = $nav.find( 'a' );
				var $filterGrid    = $( '#' + $nav.data( 'filter-grid' ) );
				var activeItems    = $nav.data( 'filter' );
				var customDuration = $nav.data( 'transitionDuration' );
				var customLayout   = $nav.data( 'layoutMode' );

				if ( ! $filterGrid.hasClass( 'wpex-row' ) ) {
					$filterGrid = $filterGrid.find( '.wpex-row' );
				}

				// Define masonry settings.
				if ( 'object' === typeof wpexMasonrySettings ) {
					settings = $.extend( true, {}, wpexMasonrySettings );
				} else {
					settings = {
						transformsEnabled  : true,
						isOriginLeft       : isRTL ? false : true,
						transitionDuration : '0.4s',
						layoutMode         : 'masonry',
					};
				}

				if ( 'undefined' !== typeof customDuration ) {
					settings.transitionDuration = parseFloat( customDuration ) + 's';
				}

				if ( 'undefined' !== typeof customLayout ) {
					settings.layoutMode = customLayout;
				}

				settings.itemSelector = '.col'; // because the vcex-isotope-entry maynot be added.

				if ( $filterGrid.length ) {

					// Remove isotope class.
					$filterGrid.removeClass( 'vcex-isotope-grid' );

					// Run functions after images are loaded for grid.
					$filterGrid.imagesLoaded( function() {

						// Create Isotope.
						if ( ! $filterGrid.hasClass( 'vcex-navbar-filter-grid' ) ) {

							$filterGrid.addClass( 'vcex-navbar-filter-grid' );

							if ( activeItems && ! $nav.find( '[data-filter="' + activeItems + '"]' ).length ) {
								activeItems = '';
							}

							if ( activeItems ) {
								settings.filter = activeItems;
							}

							$grid = $filterGrid.isotope( settings );

						}

						// Add isotope only, the filter grid already exists @todo revise usage.
						else {
							$grid = $filterGrid.isotope();
						}

						// Add filtering event.
						$filterLinks.click( function() {
							var $link = $( this );
							$grid.isotope( {
								filter : $( this ).attr( 'data-filter' )
							} );
							$filterLinks.removeClass( 'active' );
							$link.addClass( 'active' );
							return false;
						} );

					} );

				}

			} );

		};

	}

	$( document ).ready( function() {
		window.vcexNavbarFilterLinks();
	} );

	$( window ).on( 'orientationchange', function() {
		window.vcexNavbarFilterLinks();
	} );

} ) ( jQuery );