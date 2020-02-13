(function ($) {
    $(document).ready(function () {
        if ('function' == typeof $.fn.revolution) {
            $(".easl-homepage-slider").each(function () {
                var $slider = $(this);
                var autoplay = $slider.data('autoplay');
                var delay = $slider.data('delay');
                autoplay = autoplay || true;
                delay = delay || 6000;

                var options = {
                    delay: delay,
                    sliderLayout: "fullwidth",
                    responsiveLevels: [1660, 1366, 1024, 480],
                    gridwidth: [2000, 1366, 1024, 480],
                    gridheight: [495, 400, 400, 250]
                };

                if (!autoplay) {
                    options.stopLoop = "on";
                    options.stopAfterLoops = 0;
                    options.stopAtSlide = 1;
                }

                var api = $slider.show().revolution(options);
                var $wrap = $slider.closest('.easl-home-page-slider-wrap');
                $wrap.find(".easl-hps-nav-arrow-left").on('click', function (e) {
                    e.preventDefault();
                    api.revprev();
                });
                $wrap.find(".easl-hps-nav-arrow-right").on('click', function (e) {
                    e.preventDefault();
                    api.revnext();
                });
                $wrap.find(".easl-hps-nav-dot-item").on('click', function (e) {
                    var n = $(this).data('slidenumber');
                    e.preventDefault();
                    if (!$(this).hasClass('easl-hps-dot-current')) {
                        api.revnext(n);
                        $wrap.find(".easl-hps-dot-current").removeClass('easl-hps-dot-current');
                        $(this).addClass('easl-hps-dot-current');
                    }
                }).eq(0).addClass("easl-hps-dot-current");
                api.on('revolution.slide.onchange', function(event, data) {
                    $wrap.find(".easl-hps-dot-current").removeClass('easl-hps-dot-current');
                    $wrap.find(".easl-hps-nav-dot-item").eq(data.slideLIIndex).addClass('easl-hps-dot-current');

                });
            });
        }
    });
})(jQuery);