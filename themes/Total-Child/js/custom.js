(function($){
    var $body;
    
    function easlStickyHeader(){
        if( $(window).scrollTop() <= 0 ){
            $('body').addClass('easl-scroll-at-top').removeClass('easl-scrolled')
        }else{
            $('body').addClass('easl-scrolled').removeClass('easl-scroll-at-top');
        }
        
    }
    function easlScrollEvent(){
        easlStickyHeader();
    }
    
    function easlCompareDates(date1, date2){
        date1 = new Date(date1);
        date2 = new Date(date2);
        
        if(date1.getTime() > date2.getTime()){
            return 1;
        }
        if(date1.getTime() < date2.getTime()){
            return - 1;
        }
        return 0;
    }
    
    function EventCalendar($wrap){
        this.$wrap = $wrap;
        this.$filterCon = false;
        this.$eventsCon = false;
        this.ajaxURL = EASLSETTINGS.ajaxUrl;
        this.request = false;
        this.hasFilter = false;
        this.scrollLoad = true;
        this.filterValdiateError = false;
        this.filterValdiateMessage = [];
        this.resettingFilter = false;
        this.requestData = false;
        this.scrollLoading = false;
    };
    EventCalendar.prototype.init = function(){
        if($('.easl-ec-filter-container', this.$wrap).length > 0){
            this.$filterCon = $('.easl-ec-filter-container', this.$wrap);
            this.hasFilter = true;
        }
        this.$eventsCon = $('.easl-events-calendar-inner', this.$wrap);
        this.initVars();
        this.addListeners();
    };
    EventCalendar.prototype.initVars = function (){
        this.cssAnimation = "undefined" !== typeof this.$wrap.data('cssanimation') ? this.$wrap.data('cssanimation') : '';
        this.lastPage = "undefined" !== typeof this.$wrap.data('lastpage') ? this.$wrap.data('lastpage') : false;
        this.scrollLoad = !this.lastPage;
        this.requestData = JSON.stringify(this.getFilters());
    };
    EventCalendar.prototype.addListeners = function(){
        $(':input', this.$filterCon).on('change', $.proxy(this, 'filter'));
        if("undefined" !== typeof $.fn.appear){ 
            $('.easl-ec-load-more', this.$wrap).appear($.proxy(this, 'scrollLoadNow'), {one: false});
        }
        $(".easl-ecf-reset", this.$filterCon).on('click', $.proxy(this, 'resetFilter'));
    };
    EventCalendar.prototype.showFilterValidateMessage = function(){
        alert(this.filterValdiateMessage.join("\n"));
    };
    
    EventCalendar.prototype.resetFilter = function(){
        this.resettingFilter = true;
        $('.ec-filter-topics .easl-custom-checkbox', this.$filterCon).not('.easl-cb-all').each(function(){
            $(this)
                .removeClass('easl-active')
                .find('input')
                .prop('checked', false);
        });
        $('.ec-filter-topics .easl-cb-all', this.$filterCon)
                .addClass('easl-active')
                .find('input')
                .prop('checked', true);
        $('.easl-custom-select', this.$filterCon).each(function(){
            var labelText = $(this).find('.ecs-list li').removeClass('easl-active').first().addClass('easl-active').html();
            $(this).find('.ec-cs-label').html(labelText);
            $(this).find('select option').prop('selected', false).first().prop('selected', true);
            
        });
        $('[name="ecf_search"]', this.$filterCon).val('');
        $('.ecf-events-types', this.$filterCon).each(function(){
            $(this).find('.easl-custom-radio').removeClass('easl-active').find('input').prop('checked', false);
            $(this).find('.easl-custom-radio').first().addClass('easl-active').find('input').prop('checked', true);
        });
        
        this.resettingFilter = false;
        this.filter();
    };
    EventCalendar.prototype.getFilters = function(){
        var data = {}, topics = [], search = '', meetingType = '', location = '', dateFrom = '', dateTo = '', eventType = '';
        this.filterValdiateError = false;
        this.filterValdiateMessage = [];
        $('[name="ec_filter_topics[]"]:checked', this.$filterCon).each(function(){
            $(this).val() && topics.push($(this).val());
        });
        search = $('[name="ecf_search"]', this.$filterCon).val();
        meetingType = $('[name="ec_meeting_type"]', this.$filterCon).val();
        location = $('[name="ec_location"]', this.$filterCon).val();
        eventType = $('[name="ec_filter_type"]:checked', this.$filterCon).val();
        
        if(topics.length > 0){
            data.topics = topics;
        }
        if(search){
            data.search = search;
        }
        if(meetingType){
            data.meeting_type = meetingType;
        }
        if(location){
            data.location = location;
        }
        data.event_type = eventType;
        
        data.event_number = 5;
        return data;
    };
    EventCalendar.prototype.filter = function(){
        if(this.resettingFilter){
            return;
        }
        var data = this.getFilters();
        if(this.filterValdiateError){
            this.showFilterValidateMessage();
            return false;
        }
        this.scrollLoad = false;
        this.requestEvents(data);
    };
    EventCalendar.prototype.getLastEventsMonth = function(){
        if(!this.scrollLoad){
            return '';
        }
        return $.trim($('.easl-ec-month-label', this.$eventsCon).last().text());
    };
    EventCalendar.prototype.getEventCount = function(){
        if(!this.scrollLoad){
            return 0;
        }
        return $('.easl-ec-event', this.$eventsCon).length;
    };
    EventCalendar.prototype.clearEventCon = function(){
        this.$eventsCon.html('');
    };
    EventCalendar.prototype.showLoader = function(){
        this.scrollLoad ? this.$wrap.addClass('easl-ec-scroll-loading') : this.$wrap.addClass('easl-ec-filter-loading');
    };
    EventCalendar.prototype.hideLoader = function(){
        this.$wrap.removeClass('easl-ec-scroll-loading easl-ec-filter-loading');
    };
    EventCalendar.prototype.animateRows = function($rows){
        if("function" !== typeof jQuery.fn.waypoint){
            return false;
        }
        $(".wpb_animate_when_almost_visible:not(.wpb_start_animation)", $rows).waypoint(function(){
            jQuery(this).addClass("wpb_start_animation animated");
        },{offset:"85%"});

    };
    EventCalendar.prototype.abortRequest = function(){
        (typeof this.request === 'object') && this.request.abort();
    };
    EventCalendar.prototype.scrollLoadNow = function(){
        if(!this.scrollLoad){
            return false;
        }
        this.scrollLoading = true;
        this.requestEvents(this.getFilters());
    };
    EventCalendar.prototype.requestEvents = function(data){
        if(!this.scrollLoading && (JSON.stringify(data) === this.requestData) ){
            return;
        }
        
        this.requestData = JSON.stringify(data);
        this.abortRequest();
        this.showLoader();
        !this.scrollLoad && this.clearEventCon();
        this.request = $.ajax({
            url: this.ajaxURL,
            dataType: 'json',
            data: {
                action: 'easl_ec_get_events',
                filters: data,
                last_month_text: this.getLastEventsMonth(),
                row_count: this.getEventCount(),
                css_animation: this.cssAnimation
            },
            success: $.proxy(this, 'loadEvents')
        });
    };
    EventCalendar.prototype.loadEvents = function(data){
        this.hideLoader();
        this.request = false;
        this.scrollLoading = false;
        if('undefined' === typeof data.status){
            this.scrollLoad = false;
            return false;
        }
        var $rows = $(data.rows);
        if(this.scrollLoad){
            this.$eventsCon.append($rows);
        }else{
            this.$eventsCon.html($rows);
        }
        this.animateRows($rows);
        if(data.lastPage){
            this.lastPage = true;
            this.scrollLoad = false;
        }else{
            this.scrollLoad = true;
        }
    };
    
    $.fn.easlEventCalander = function(){
        return this.each(function(){
            var ec = new EventCalendar($(this));
            ec.init();
        });
    };
    
    function easlCustomCheckbox(){
        $('.easl-custom-checkbox').each(function(){
            var $cc = $(this);
            if($('input', $cc).is(':checked')){
                $cc.addClass('easl-active');
            }else{
                $cc.removeClass('easl-active');
            }
        });
    };
    function easlCustomRadio(){
        $('.easl-custom-radio').each(function(){
            var $cc = $(this);
            if($('input', $cc).is(':checked')){
                $cc.addClass('easl-active');
            }else{
                $cc.removeClass('easl-active');
            }
        });
    };
    function easlCustomSelect(){
        $('.easl-custom-select').each(function(){
            var $cs = $(this), lis = '', activeLabel = '';
            $('option', $cs).each(function(){
                if($(this).is(':selected')){
                    $cs.find('.ec-cs-label').html($(this).html() );
                    lis = lis + '<li class="easl-active">' + $(this).html() + '</li>';
                }else{
                    lis = lis + '<li>' + $(this).html() + '</li>';
                }
            });
            if(lis.length > 0){
                $cs.append('<ul class="ecs-list">' + lis + '</ul>')
            }
        });  
    };
    $(window).load(function(){
    });
    $(document).ready(function(){
        $body = $('body');
        easlStickyHeader();
        easlCustomCheckbox();
        easlCustomRadio();
        easlCustomSelect();
        $body.on('click', '.easl-custom-select', function(){
            var $cs = $(this);
            if($('.ecs-list', $cs).is(":animated")){
                return false;
            }
            if($cs.hasClass('easl-active')){
                $cs.removeClass('easl-active');
                $('.ecs-list', $cs).slideUp(250);
            }else{
                $cs.addClass('easl-active');
                $('.ecs-list', $cs).slideDown(250);
            }
        });
        $body.on('click', '.ecs-list li', function(){
            var $li = $(this);
            if($li.hasClass('easl-active')){
                return false;
            }
            $li.siblings().not($li).removeClass('easl-active');
            $li
                .addClass('easl-active')
                .closest('.easl-custom-select')
                .find('.ec-cs-label')
                    .html($li.html())
                .end()
                .find('select option')
                    .prop('selected', false)
                    .eq($li.index())
                    .prop('selected', true)
                    .change()
                .end()
                .find('.ecs-list')
                .slideUp(250);
        });
        $body.on('click', '.easl-custom-checkbox input', function(e){
            e.stopPropagation();
        });
        $body.on('click', '.easl-custom-checkbox', function(e){
            var $cc = $(this), $ul = $cc.closest('ul');
            
            if(!$('input', $cc).is(':checked')){
                $cc.addClass('easl-active');
                if($cc.hasClass('easl-cb-all')){
                    $ul
                        .find('.easl-custom-checkbox')
                        .not($cc)
                        .removeClass('easl-active')
                        .find('input')
                            .prop('checked', false);
                }else{
                   $ul
                        .find('.easl-cb-all')
                        .removeClass('easl-active')
                        .find('input')
                            .prop('checked', false);
                }
            }else{
                if($cc.hasClass('easl-cb-all')){
                    return false;
                }
                $cc.removeClass('easl-active');
                if(!$ul.find('.easl-active').length){
                    $ul
                        .find('.easl-cb-all')
                        .addClass('easl-active')
                        .find('input')
                            .prop('checked', true);
                }
            }
        });
        $body.on('click', '.easl-custom-radio', function(e){
            var $cc = $(this);
            if($('input', $cc).is(':checked')){
                $cc.addClass('easl-active');
            }
            $cc.siblings().not($cc).removeClass('easl-active');
        });
        ('undefined' !== typeof $.fn.datepicker) && $('.easl-date-picker').datepicker({
            dateFormat: "yy-mm-dd",
            showOn: "button",
            buttonImageOnly: true,
        });
        $('.easl-carousel').on('loaded.owl.carousel changed.owl.carousel resized.owl.carousel', function(event){
            var top = 0;
            if(null !== event.item.index){
                top = $(this).find(".owl-item").eq(event.item.index).find(".easl-carousel-image").outerHeight();
                $(this).find('.owl-dots').css({"top": top + 'px'});
            }
            
        });
        $('.easl-events-calendar-wrap').easlEventCalander();
        $body.on('click', '.ec-links-deadline', function(event){
            event.preventDefault();
            var $li = $(this).closest('li');
            if($li.hasClass('easl-active')){
                $li
                .removeClass('easl-active')
                .closest('.ec-icons')
                .find('.ec-links-details-key-deadlines')
                .removeClass('easl-active');
            return false;
            }
            $li
                .addClass('easl-active')
                .closest('.ec-icons')
                .find('.ec-links-details-key-deadlines')
                .addClass('easl-active');
            return false;
        });
        
        // Stuff to be done when windows resize
        $(window).resize(function(){
        });
        $(window).scroll( function() {
            easlScrollEvent();
        } );
    });
})(jQuery);