(function ($) {
    function easlProcessHash() {
        var $targetElement, $accordion, hash = window.location.hash;//vc_toggle
        $targetElement = hash ? $(hash) : [];
        if ($targetElement.length && $targetElement.hasClass('vc_toggle')) {
            setTimeout(function () {
                $("html, body").animate({scrollTop: $targetElement.offset().top - .2 * $(window).height()}, 0)
            }, 300);
            console.log($targetElement.find('.vc_toggle_title'));
            $targetElement.find('.vc_toggle_title').trigger('click');
        }
        if ($targetElement.length && !$targetElement.hasClass('vc_toggle')) {
            $parentToggle = $targetElement.closest('.vc_toggle');
            if ($parentToggle.length) {
                setTimeout(function () {
                    $("html, body").animate({scrollTop: $parentToggle.offset().top - .2 * $(window).height()}, 0)
                }, 300);
                $parentToggle.find('.vc_toggle_title').trigger('click');
            }
        }
    }
    
    $(document).ready(function () {
        easlProcessHash();
        $('.easl-vc-toggle-link').on('click', function (event) {
            console.log($(this).attr('href'));
            history.pushState(null, null, $(this).attr('href'));
        });
    });
})(jQuery);