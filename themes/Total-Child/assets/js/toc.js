(function ($) {
    var $toc_menu_wrap, $toc_menu, top, height, offset = 0, isMobile = false;
    function tocResizeMenu() {
        top = $toc_menu_wrap.offset().top - 95;
        height = parseInt($toc_menu.height(), 10);
        isMobile = window.matchMedia('(max-width: 767px)').matches;
    }
    
    function tocShowHideMenu(scroll_pos) {
        if(isMobile){
            return;
        }
        if((scroll_pos + offset) > top) {
            $toc_menu_wrap.addClass('toc-sticky-menu-enabled').css('padding-top', height + 'px');
        }else{
            $toc_menu_wrap.removeClass('toc-sticky-menu-enabled').css('padding-top', 0);
        }
    }
    $(document).ready(function () {
        offset = 102;
        if($('body').hasClass('admin-bar')) {
            offset += 32;
        }
        $toc_menu_wrap = $('.easl-toc-os-sticky .easl-toc-menu-wrap');
        $toc_menu = $('.easl-toc-menu');
        tocResizeMenu();
        let last_known_scroll_position = 0;
        let ticking = false;
        tocShowHideMenu(window.scrollY);
    
        window.addEventListener('scroll', function(e) {
            last_known_scroll_position = window.scrollY;
        
            if (!ticking) {
                window.requestAnimationFrame(function() {
                    tocShowHideMenu(last_known_scroll_position);
                    ticking = false;
                });
            
                ticking = true;
            }
        });
    
        $(window).on('easl_resize_sampled', function(e) {
            tocResizeMenu();
        });
    });
})(jQuery);