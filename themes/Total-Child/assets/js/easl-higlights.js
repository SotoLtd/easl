jQuery.noConflict();

jQuery(function($) {

    $(document).on('click', '.highlight-filter-item', function (e) {
        e.preventDefault();
        $('.vcex-filter-links li').removeClass('active');
        $(this).parent().addClass('active');
        var element = $('ul.current-filter li.current-active');

        element.removeClass('csic-light-blue');
        element.removeClass('csic-blue');
        element.removeClass('csic-red');
        element.removeClass('csic-orrange');
        element.removeClass('csic-teal');
        element.removeClass('csic-gray');
        element.removeClass('csic-yellow');

        element.addClass($(this).data('bgclass'));

        $('ul.current-filter li.current-active a:first').html($(this).html());
        
        var filter = $(this).data('filter');

        $.ajax({
            url: ajaxurl.ajaxurl,
            dataType: 'html',
            data: {
                action: 'easl_get_highlights',
                filter: filter,
            },
            success: function (response) {
                console.log('response', response);
                $('.highlights-content').html(response);
            }
        });
    });



});