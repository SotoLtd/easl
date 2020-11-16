(function ($) {
    var $ste_menu_wrap, $ste_menu, top, height, offset = 0, isMobile = false;
    function steResizeMenu() {
        top = $ste_menu_wrap.offset().top - 95;
        height = parseInt($ste_menu.height(), 10);
        isMobile = window.matchMedia('(max-width: 767px)').matches;
    }
    
    function steShowHideMenu(scroll_pos) {
        if(isMobile){
            return;
        }
        console.log(top, offset, scroll_pos);
        if((scroll_pos + offset) > top) {
            $ste_menu_wrap.addClass('ste-sticky-menu-enabled').css('padding-top', height + 'px');
        }else{
            $ste_menu_wrap.removeClass('ste-sticky-menu-enabled').css('padding-top', 0);
        }
    }
    $(document).ready(function () {
        offset = 102;
        if($('body').hasClass('admin-bar')) {
            offset += 32;
        }
        $ste_menu_wrap = $('.ste-menu-wrap');
        $ste_menu = $('.ste-menu');
        steResizeMenu();
        let last_known_scroll_position = 0;
        let ticking = false;
    
        window.addEventListener('scroll', function(e) {
            last_known_scroll_position = window.scrollY;
        
            if (!ticking) {
                window.requestAnimationFrame(function() {
                    steShowHideMenu(last_known_scroll_position);
                    ticking = false;
                });
            
                ticking = true;
            }
        });
    });
})(jQuery);