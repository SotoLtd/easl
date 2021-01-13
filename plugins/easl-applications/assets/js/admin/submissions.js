(function ($) {
    console.log('OK');
    $(document).ready(function (e) {
        $('#sync-member-data-button').on("click", function (e) {
            e.preventDefault();
            var id = $(this).data('id');
            var nonce = $(this).data('nonce');
            var $table = $('#sub-member-details-table');
            $.ajax({
                method: "POST",
                url: ajaxurl,
                data: {
                    action: 'sync_submission_member_data',
                    _wpnonce: nonce,
                    sub_id: id
                },
                success: function (res) {
                    if (res) {
                        $table.html(res);
                    }
                }
            });
        });
    });
})(jQuery);