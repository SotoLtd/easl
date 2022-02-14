(function ($){
    $(document).ready(function (){
        $('[data-name="easl-schools-all_programme_information_schools"]').each(function(){
            var ul = $(this).find('ul.acf-checkbox-list')[0];
            dragula([ul]);
        });
    });
})(jQuery);