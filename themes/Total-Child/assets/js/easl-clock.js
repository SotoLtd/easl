(function ($) {
    $(document).ready(function (){
        $('.easl-clock-countdown').each(function (){
            var $clock = $(this);
            var dateTime = $clock.data('datetime');
            $clock.countdown({
                until: new Date(Date.UTC(dateTime.year, dateTime.month-1, dateTime.day, dateTime.hour, dateTime.min, dateTime.sec)),
                format: 'YDHMS'
            });
        });
        $('.easl-clock-share-button').on('click', function (e){
            e.preventDefault();
            $(this).closest('.easl-clock-wrap').addClass('easl-clock-show-share-content');
        });
        $('.easl-clock-share-close').on('click', function (e){
            e.preventDefault();
            $(this).closest('.easl-clock-wrap').removeClass('easl-clock-show-share-content');
        });
    });
})(jQuery);