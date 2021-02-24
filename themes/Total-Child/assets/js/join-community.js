jQuery.noConflict();

jQuery(function($) {
    var first_cat = $('#menu-membership-categories li:first-child a').data('item');
    $.post(ajaxurl.ajaxurl, {
        'action': 'get_membership_categories_func',
        'category': first_cat
    }, function (response) {
        $('.membership-categories-block .easl-msc-content-wrap-inner').html(response);
    });

    $(document).on('click', '#menu-membership-categories a', function (e) {
        e.preventDefault();
        var cat = $(this).data('item');
        $.post(ajaxurl.ajaxurl, {
            'action': 'get_membership_categories_func',
            'category': cat
        }, function (response) {
            $('.membership-categories-block .easl-msc-content-wrap-inner').html(response);
        });
    });
});

