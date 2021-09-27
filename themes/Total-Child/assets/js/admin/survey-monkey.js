(function ($) {
    $(document).ready(function () {
        $('#easl-sm-sync-button').on('click', function (e) {
            var $button = $(this);
            $button.removeClass('easl-sm-processed-ok easl-sm-processed-not-ok');
            e.preventDefault();
            !$button.hasClass('easl-sm-processing') && $.ajax({
                url: ajaxurl,
                method: "POST",
                data: {
                    action: 'easl_sm_sync',
                    _smnonce: $button.data('nonce')
                },
                dataType: 'json',
                beforeSend: function (){
                    $button.addClass('easl-sm-processing');
                },
                success: function (response) {
                    if (response && response.Status && ('Success' === response.Status)) {
                        $button.removeClass('easl-sm-processing').addClass('easl-sm-processed-ok');
                    } else {
                        $button.removeClass('easl-sm-processing').addClass('easl-sm-processed-not-ok');
                    }
                    
                }
            });
        });
    });
})(jQuery);