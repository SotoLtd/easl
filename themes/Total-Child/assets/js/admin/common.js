(function ($) {
    $(document).ready(function () {
        console.log('ADMin ready');
        $('.acf-field.acf-field-5e1f10cce11732 .acf-checkbox-list li').on("click", function (e) {
            var $selected = $(this).closest('ul').find('label.selected');
            var currentLayout = '';
            if($selected.length > 0){
                currentLayout = $selected.closest('li').data('id');
            }
            console.log(currentLayout);
            if(!(currentLayout != 3341) && ("none" == $("#wpb_visual_composer").css("display")) ){
                $("#wpb_visual_composer").css('display', 'none');
            }
        });
    });
})(jQuery);