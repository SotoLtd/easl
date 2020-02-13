jQuery.noConflict();

jQuery(function($) {
    $(document).on('change', '.easl-custom-select select[name="ec-meeting-type"]', function () {
        $(document).find('.publication-download-pdf-btn').attr('href', $('.easl-custom-select select[name="ec-meeting-type"]').val());
    });
});