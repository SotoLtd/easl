jQuery(document).ready(function ($) {
		/* LEGEND
			scrollinit(); - default with no additional pages.
			
			scrollinit('carousel', 1, 0, true, true, true, true, true); - custom settings
			
			1. Scroll effect: 'classic', 'cube', 'carousel', 'concave', 'coverflow', 'spiraltop', 'spiralbottom', 'classictilt'.
		 	2. Number of scroll pages. '1' means no additional pages.
			3. Select which slide to be on focus when slider is loaded. '0' means first slide.
			4. Enable / disable keys navigation: true, false.
			5. Enable / disable buttons navigation: true, false.
			6. Enable / disable slide gestures navigation on touch devices: true, false.
			7. Enable / disable click navigation: true, false.
			8. Enable / disable mouse wheel navigation: true, false.
		*/
		
		scrollinit("carousel", 1, 1, true, true, true, true, true);
	});