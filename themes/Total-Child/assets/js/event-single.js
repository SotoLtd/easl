jQuery.noConflict();

jQuery(function($) {
    $(document).on('click', '.show_more_btn', function (e) {
        e.preventDefault();
        $('.event_description').toggle();

    })
});