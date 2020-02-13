jQuery.noConflict();
jQuery(function($) {
    var $mscContentWrap = $('.easl-msc-content-wrap'), staffsIdsForDeatils = [], staffsIds=[];
    
    $('.easl-staff-details-button', $mscContentWrap).each(function(){
        var staff_id = $(this).data('target');
        if(staff_id && (staffsIds.indexOf(staff_id) === -1)){
            staffsIds.push(staff_id);
            staffsIdsForDeatils.push({id: staff_id, url: $(this).attr('href')});
        }
    });
    
    function getNextPrevStaff(current){
        var next, prev, curStaff;
        if(staffsIdsForDeatils.length === 0) {
            return {
                next: false,
                prev: false
            };
        }
        for(var i=0; i < staffsIdsForDeatils.length; i++){
            if(staffsIdsForDeatils[i].id === current) {
                curStaff = i;
                break;
            }
        }
        
        if(curStaff === 0){
            return {
                next: staffsIdsForDeatils[curStaff + 1],
                prev: false
            };
        }
        
        if(curStaff === staffsIdsForDeatils.length){
            return {
                next: false,
                prev: staffsIdsForDeatils[curStaff - 1]
            };
        }
        return {
            next: staffsIdsForDeatils[curStaff + 1],
            prev: staffsIdsForDeatils[curStaff - 1]
        };
        
    }
    
    function mscLoadStaffDetailsHTML(current) {
        var $staffDetailWrap = $('.easl-staff-details-wrap', $mscContentWrap),
            nextPrev,
            currentPageLink = $('.easl-msc-menu-wrap .current-menu-item a').attr('href');
        if($staffDetailWrap.length === 0){
            $mscContentWrap.append('<div class="easl-staff-details-wrap"><div class="easl-member-profile-back-link"><a class="easl-staff-details-back easl-back-link-chevron" href="#">Back</a></div><div class="easl-staff-details-content"></div><div class="easl-staff-details-navigation"><a href="#" class="sd-next-prev prev-profile easl-staff-details-button">Previous Profile</a><a href="#" class="sd-next-prev next-profile easl-staff-details-button">Next Profile</a></div><div class="easl-sd-load-icon">' + EASLSETTINGS.loaderImage + '</div></div>');
            $staffDetailWrap = $('.easl-staff-details-wrap', $mscContentWrap);
            $('.easl-member-profile-back-link a', $staffDetailWrap).attr('href', currentPageLink);
        }
        
        nextPrev = getNextPrevStaff(current);
        if(nextPrev.prev){
            $('.prev-profile', $staffDetailWrap)
                .attr('href', nextPrev.prev.url)
                .data('target', nextPrev.prev.id)
                .removeClass('easl-hide');
        }else{
            $('.prev-profile', $staffDetailWrap).addClass('easl-hide');
        }
        
        if(nextPrev.next){
            $('.next-profile', $staffDetailWrap)
                .attr('href', nextPrev.next.url)
                .data('target', nextPrev.next.id)
                .removeClass('easl-hide');
        }else{
            $('.next-profile', $staffDetailWrap).addClass('easl-hide');
        }
        
        
    }
    $(document).on('click', '.easl-staff-list-block .vcex-staff-filter a', function (e) {
        e.preventDefault();
        var filter = $(this).data('filter');
        if(filter !== '*'){
            $('.easl-staff-list-block .vcex-staff-filter li').removeClass('active');
            $(this).parent().addClass('active');

            $('.easl-staff-list').hide();
            $(filter).show();
        } else {
            $('.easl-staff-list').show();
        }
    });
    
    
    $(document).on('click', '.msc-filter-menu a', function (e) {
        e.preventDefault();
        var $this = $(this),
            $thisLI = $this.closest('li'),
            filter = $this.attr('href');
        if($thisLI.hasClass('active')){
            return;
        }
        $thisLI.addClass('active');
        if(filter !== '#all'){
            $thisLI.siblings('li').not($thisLI).removeClass('active');
            $('.easl-msc-filterable-con').not(filter).hide();
            $(filter).show();
        } else {
            $('.easl-msc-filterable-con').show();
        }
    });

    $(document).on('click', '.easl-msc-content-wrap .easl-staff-details-button', function (e) {
        e.preventDefault();
        var staff_id = $(this).data('target'), staff_href = $(this).attr('href');
        if(!staff_id){
            return false;
        }
        !$(this).hasClass('sd-next-prev') && $mscContentWrap.data('easlscrollpos', document.documentElement.scrollTop);
        $mscContentWrap.addClass('easl-show-staff-details easl-staff-details-loading');
        
        $('html, body').animate({
            scrollTop: $mscContentWrap.offset().top - $('#site-header').height() - 100
        }, 275);
        mscLoadStaffDetailsHTML(staff_id);
        $mscContentWrap.removeClass('easl-staff-details-loaded');
        var $staffDetailWrap = $('.easl-staff-details-wrap', $mscContentWrap);
        $.post(EASLSETTINGS.ajaxUrl, {
            'action': 'get_staff_profile',
            'staff_id': staff_id
        }, function (response) {
            $mscContentWrap
                .removeClass('easl-staff-details-loading')
                .addClass('easl-staff-details-loaded');
            if(response){
                $('.easl-staff-details-content', $staffDetailWrap).html(response);
                staff_href && history.pushState({id: 'staff_details', html: response, staffID: staff_id }, document.title, staff_href);
            }
        });
    });
    
    $('body').on('click', '.easl-member-profile-back-link a', function(e){
        e.preventDefault();
        $mscContentWrap.removeClass('easl-show-staff-details easl-staff-details-loading easl-staff-details-loaded');
        var scrollPosition = $mscContentWrap.data('easlscrollpos') || false;
        if(scrollPosition){
            $('html, body').animate({
                scrollTop: scrollPosition
            }, 275);
        }
        $mscContentWrap.data('easlscrollpos', false);
    });
    if(window.location.hash && $('.easl-msc-content-wrap .easl-staff-details-button[href="'+ window.location.hash +'"]').length){
        $('.easl-msc-content-wrap .easl-staff-details-button[href="'+ window.location.hash +'"]').eq(0).trigger('click');
    }
});