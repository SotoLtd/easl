/* http://keith-wood.name/countdown.html
   German initialisation for the jQuery countdown extension
   Written by Samuel Wulf. */
(function($) {
	'use strict';
	$.easlCountdown.regionalOptions.de = {
		labels: ['Jahre','Monate','Wochen','Tage','Stunden','Minuten','Sekunden'],
		labels1: ['Jahr','Monat','Woche','Tag','Stunde','Minute','Sekunde'],
		compactLabels: ['J','M','W','T'],
		whichLabels: null,
		digits: ['0','1','2','3','4','5','6','7','8','9'],
		timeSeparator: ':',
		isRTL: false
	};
	$.easlCountdown.setDefaults($.easlCountdown.regionalOptions.de);
})(jQuery);
