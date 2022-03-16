(function ($) {
    $(document).ready(function () {
        $('.app-review-score-field').on('change', function (e) {
            var $field = $(this),
                $wrap = $field.closest('.mzms-field-wrap');
            score = $field.val();
            var max = $field.attr('max');
            if (!score || score > max) {
                $wrap.addClass('easl-mz-field-has-error');
            } else {
                $wrap.removeClass('easl-mz-field-has-error');
            }
        });
        $('.app-review-score-form').on('submit', function (e) {
            var error = false;
            var $rt = $('textarea[name="review_text"]', $(this));
            $('.app-review-score-field', $(this)).each(function () {
                var $field = $(this),
                    $wrap = $field.closest('.mzms-field-wrap');
                score = $field.val();
                var max = $field.attr('max');
                if (!score || (score < 1) || score > max) {
                    error = true;
                    $wrap.addClass('easl-mz-field-has-error');
                } else {
                    $wrap.removeClass('easl-mz-field-has-error');
                }
            });
            if (!$rt.val()) {
                error = true;
                $rt.closest('.mzms-field-wrap').addClass('easl-mz-field-has-error');
            } else {
                $rt.closest('.mzms-field-wrap').removeClass('easl-mz-field-has-error');
            }
            if(error) {
                e.preventDefault();
            }
        });
    });
})(jQuery);