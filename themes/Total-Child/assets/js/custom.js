(function () {
    var lastTime = 0;
    var vendors = ['ms', 'moz', 'webkit', 'o'];
    for (var x = 0; x < vendors.length && !window.requestAnimationFrame; ++x) {
        window.requestAnimationFrame = window[vendors[x] + 'RequestAnimationFrame'];
        window.cancelAnimationFrame = window[vendors[x] + 'CancelAnimationFrame']
            || window[vendors[x] + 'CancelRequestAnimationFrame'];
    }

    if (!window.requestAnimationFrame)
        window.requestAnimationFrame = function (callback, element) {
            var currTime = new Date().getTime();
            var timeToCall = Math.max(0, 16 - (currTime - lastTime));
            var id = window.setTimeout(function () {
                    callback(currTime + timeToCall);
                },
                timeToCall);
            lastTime = currTime + timeToCall;
            return id;
        };

    if (!window.cancelAnimationFrame)
        window.cancelAnimationFrame = function (id) {
            clearTimeout(id);
        };
}());
(function ($) {
    var $body, bodyOffsetTop;

    function easlArrayIntersect(a1, a2) {
        var result = [];
        for (var i = 0; i < a1.length; i++) {
            if (result.indexOf(a1[i]) !== -1) {
                continue;
            }
            if (a2.indexOf(a1[i]) === -1) {
                continue;
            }
            result.push(a1[i]);
        }
        return result
    }

    function easlArrayUnion(a1, a2) {
        var result = [];
        for (var i = 0; i < a1.length; i++) {
            if (result.indexOf(a1[i]) !== -1) {
                continue;
            }
            result.push(a1[i]);
        }
        for (var i = 0; i < a2.length; i++) {
            if (result.indexOf(a2[i]) !== -1) {
                continue;
            }
            result.push(a2[i]);
        }
        return result
    }

    function easlIsMobile(w) {
        if (!w) {
            w = 767;
        }
        if (typeof window.matchMedia === 'function') {
            return window.matchMedia("(max-width: " + w + "px)").matches;
        }
        return $(window).width() <= w;
    }

    function setCardBlockHeight() {
        var $this = $('.easl-card-block');
        var w = $this.width();
        if (!easlIsMobile(767)) {
            $this.height((w * 0.5625) + 'px');
        } else {
            $this.height('auto');
        }
    }

    function easlStickyHeader() {
        var headerOffset = 0;
        if (easlIsMobile(600)) {
            headerOffset = $('#header-top-line').offset().top;
        } else if (easlIsMobile(767)) {
            headerOffset = $('#header-top-line').offset().top - bodyOffsetTop;
        }
        if ($(window).scrollTop() <= headerOffset) {
            $('body').addClass('easl-scroll-at-top').removeClass('easl-scrolled easl-header-sticky-active');
        } else {
            $('body').addClass('easl-scrolled easl-header-sticky-active').removeClass('easl-scroll-at-top');
        }

    }

    function easlCompareDates(date1, date2) {
        date1 = new Date(date1);
        date2 = new Date(date2);

        if (date1.getTime() > date2.getTime()) {
            return 1;
        }
        if (date1.getTime() < date2.getTime()) {
            return -1;
        }
        return 0;
    }

    function EventCalendar($wrap) {
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
        this.eventsMap = {};
    };
    EventCalendar.prototype.init = function () {
        if ($('.easl-ec-filter-container', this.$wrap).length > 0) {
            this.$filterCon = $('.easl-ec-filter-container', this.$wrap);
            this.hasFilter = true;
        }
        this.$eventsCon = $('.easl-events-calendar-inner', this.$wrap);
        this.initVars();
        this.addListeners();
        this.updateCountryDropdown(this.getFilters());
    };
    EventCalendar.prototype.initVars = function () {
        this.cssAnimation = "undefined" !== typeof this.$wrap.data('cssanimation') ? this.$wrap.data('cssanimation') : '';
        this.lastPage = "undefined" !== typeof this.$wrap.data('lastpage') ? this.$wrap.data('lastpage') : false;
        this.allTopicsID = "undefined" !== typeof this.$wrap.data('alltopic') ? this.$wrap.data('alltopic') : false;
        this.scrollLoad = !this.lastPage;
        this.requestData = JSON.stringify(this.getFilters());
        var topicsEvents = {
            all: [],
            allTopicEvents: "undefined" !== typeof this.$wrap.data('alltopicevents') ? this.$wrap.data('alltopicevents') : []
        };
        $('[name="ec_filter_topics[]"]', this.$filterCon).each(function () {
            var $this = $(this), events = $this.data('events');
            events = typeof events !== "undefined" ? events : [];
            if ($this.val() && events.length) {
                $.merge(topicsEvents.all, events);
                topicsEvents[$this.val()] = events;
            }
        });
        this.eventsMap.topics = topicsEvents;

        var meetingTypeEvents = {
            all: []
        };
        $('[name="ec_meeting_type"] option', this.$filterCon).each(function () {
            var $this = $(this), events = $this.data('events');
            events = typeof events !== "undefined" ? events : [];
            if ($this.val() && events.length) {
                meetingTypeEvents.all = easlArrayUnion(meetingTypeEvents.all, events);
                meetingTypeEvents[$this.val()] = events;
            }
        });
        this.eventsMap.meetingType = meetingTypeEvents;

        var organizerEvents = {};
        $('[name="organizer"]', this.$filterCon).each(function () {
            var $this = $(this), events = $this.data('events');
            events = typeof events !== "undefined" ? events : [];
            if ($this.val() && events.length) {
                organizerEvents[$this.val()] = events;
            }
        });
        this.eventsMap.organizer = organizerEvents;

        var pastFuturEvents = {};
        $('[name="ec_filter_type"]', this.$filterCon).each(function () {
            var $this = $(this), events = $this.data('events');
            events = typeof events !== "undefined" ? events : [];
            if ($this.val() && events.length) {
                pastFuturEvents[$this.val()] = events;
            }
        });
        this.eventsMap.type = pastFuturEvents;

        var countriesEvents = {};
        $('[name="ec_location"] option', this.$filterCon).each(function () {
            var $this = $(this), events = $this.data('events');
            events = typeof events !== "undefined" ? events : [];
            if ($this.val() && events.length) {
                countriesEvents[$this.val()] = events;
            }
        });
        this.eventsMap.countries = countriesEvents;
    };
    EventCalendar.prototype.addListeners = function () {
        var ob = this;
        $(':input', this.$filterCon).on('change', $.proxy(this, 'filter'));
        if ("undefined" !== typeof $.fn.appear) {
            $('.easl-ec-load-more', this.$wrap).appear($.proxy(this, 'scrollLoadNow'), {one: false});
        }
        $(".easl-ecf-reset", this.$filterCon).on('click', $.proxy(this, 'resetFilter'));
        $(".ec-mob-showhide-filter", this.$filterCon).on('click', function (event) {
            var $t = $(this);
            event.preventDefault();
            if (!easlIsMobile()) {
                return false;
            }
            if ($t.hasClass('easl-active')) {
                $t.removeClass('easl-active');
                ob.$filterCon.css('max-height', '');
            } else {
                $t.addClass('easl-active');
                ob.$filterCon.css('max-height', ob.$filterCon.find('.easl-ec-filter').outerHeight(true) + 36);
            }
        });
    };
    EventCalendar.prototype.showFilterValidateMessage = function () {
        alert(this.filterValdiateMessage.join("\n"));
    };

    EventCalendar.prototype.resetFilter = function () {
        this.resettingFilter = true;
        $('.ec-filter-topics .easl-custom-checkbox', this.$filterCon).not('.easl-cb-all').each(function () {
            $(this)
                .removeClass('easl-active')
                .find('input')
                .prop('checked', false);
        });
        $('.ec-filter-topics .easl-cb-all', this.$filterCon)
            .addClass('easl-active')
            .find('input')
            .prop('checked', true);
        $('.easl-custom-select', this.$filterCon).each(function () {
            var labelText = $(this).find('.ecs-list li').removeClass('easl-active').first().addClass('easl-active').html();
            $(this).find('.ec-cs-label').html(labelText);
            $(this).find('select option').prop('selected', false).first().prop('selected', true);

        });
        $('[name="ecf_search"]', this.$filterCon).val('');
        $('.ecf-events-types', this.$filterCon).each(function () {
            $(this).find('.easl-custom-radio').removeClass('easl-active').find('input').prop('checked', false);
            $(this).find('.easl-custom-radio').first().addClass('easl-active').find('input').prop('checked', true);
        });

        this.resettingFilter = false;
        this.filter();
    };
    EventCalendar.prototype.getFilters = function () {
        var data = {}, topics = [], search = '', meetingType = '', location = '', dateFrom = '', dateTo = '',
            organizer = '', eventType = '';
        this.filterValdiateError = false;
        this.filterValdiateMessage = [];
        $('[name="ec_filter_topics[]"]:checked', this.$filterCon).each(function () {
            $(this).val() && topics.push($(this).val());
        });
        search = $('[name="ecf_search"]', this.$filterCon).val();
        meetingType = $('[name="ec_meeting_type"]', this.$filterCon).val();
        location = $('[name="ec_location"]', this.$filterCon).val();
        eventType = $('[name="ec_filter_type"]:checked', this.$filterCon).val();
        organizer = $('[name="organizer"]:checked', this.$filterCon).val();

        if (organizer) {
            data.organizer = organizer;
        }
        if (topics.length > 0) {
            data.topics = topics;
        }
        if (search) {
            data.search = search;
        }
        if (meetingType) {
            data.meeting_type = meetingType;
        }
        if (location) {
            data.location = location;
        }
        data.event_type = eventType;

        data.event_number = 5;
        return data;
    };
    EventCalendar.prototype.updateCountryDropdown = function (data) {
        var ob = this, events = [], loaded = false;
        if (data.topics) {
            loaded = true;
            for (var i = 0; i < data.topics.length; i++) {
                if (this.eventsMap.topics[data.topics[i]]) {
                    events = easlArrayUnion(events, this.eventsMap.topics[data.topics[i]]);
                }
            }
            events = easlArrayUnion(events, this.eventsMap.topics.allTopicEvents);
        }

        if (data.meeting_type && this.eventsMap.meetingType[data.meeting_type]) {
            events = loaded ? easlArrayIntersect(events, this.eventsMap.meetingType[data.meeting_type]) : (loaded = true, this.eventsMap.meetingType[data.meeting_type]);
        }

        if (data.organizer && this.eventsMap.organizer[data.organizer]) {
            events = loaded ? easlArrayIntersect(events, this.eventsMap.organizer[data.organizer]) : (loaded = true, this.eventsMap.organizer[data.organizer]);
        }

        if (data.event_type && this.eventsMap.type[data.event_type]) {
            events = loaded ? easlArrayIntersect(events, this.eventsMap.type[data.event_type]) : (loaded = true, this.eventsMap.type[data.event_type]);
        }
        var locationEvents = [];
        if (data.location && this.eventsMap.countries[data.location]) {
            locationEvents = ob.eventsMap.countries[data.location] ? ob.eventsMap.countries[data.location] : array();
            locationEvents = easlArrayIntersect(events, locationEvents);
        }

        if ((events.length === 0) || (data.location && locationEvents.length === 0)) {
            data.location = '';
            $('.easl-custom-select-filter-location .ec-cs-label', this.$filterCon).html($('.easl-custom-select-filter-location .ecs-list li', this.$filterCon).first().addClass('easl-active').html());
            $('.easl-custom-select-filter-location select option').prop('selected', false).first().prop('selected', true);
        }
        if (events.length === 0) {
            $('.easl-custom-select-filter-location .ec-cs-label', this.$filterCon).html($('.easl-custom-select-filter-location .ecs-list li', this.$filterCon).first().addClass('easl-active').html());
            $('.easl-custom-select-filter-location select option').prop('selected', false).first().prop('selected', true);
            $('.easl-custom-select-filter-location .ecs-list li', this.$filterCon).not(':first-child').addClass('easl-hide');
        } else {
            $('.easl-custom-select-filter-location .ecs-list li').not(':first-child').each(function () {
                var val = $(this).data('value'), cEs;
                cEs = val && ob.eventsMap.countries[val] ? easlArrayIntersect(events, ob.eventsMap.countries[val]) : [];
                if (cEs.length > 0) {
                    $(this).removeClass('easl-hide')
                } else {
                    $(this).addClass('easl-hide');
                }
            });
        }

        return data;
    };
    EventCalendar.prototype.filter = function () {
        if (this.resettingFilter) {
            return;
        }
        var data = this.getFilters();
        data = this.updateCountryDropdown(data);
        if (this.filterValdiateError) {
            this.showFilterValidateMessage();
            return false;
        }
        this.scrollLoad = false;
        this.requestEvents(data);
    };
    EventCalendar.prototype.getLastEventsMonth = function () {
        if (!this.scrollLoad) {
            return '';
        }
        return $.trim($('.easl-ec-month-label', this.$eventsCon).last().text());
    };
    EventCalendar.prototype.getEventCount = function () {
        if (!this.scrollLoad) {
            return 0;
        }
        return $('.easl-ec-event', this.$eventsCon).length;
    };
    EventCalendar.prototype.clearEventCon = function () {
        this.$eventsCon.html('');
    };
    EventCalendar.prototype.showLoader = function () {
        this.scrollLoad ? this.$wrap.addClass('easl-ec-scroll-loading') : this.$wrap.addClass('easl-ec-filter-loading');
    };
    EventCalendar.prototype.hideLoader = function () {
        this.$wrap.removeClass('easl-ec-scroll-loading easl-ec-filter-loading');
    };
    EventCalendar.prototype.animateRows = function ($rows) {
        if ("function" !== typeof jQuery.fn.vcwaypoint) {
            console.log("vcwaypoint is not installed");
            return false;
        }
        $(".wpb_animate_when_almost_visible:not(.wpb_start_animation)", $rows).each(function () {
            var $el = jQuery(this);
            $el.vcwaypoint(function () {
                $el.addClass("wpb_start_animation animated");
            }, {offset: "85%"});
        });
    };
    EventCalendar.prototype.abortRequest = function () {
        (typeof this.request === 'object') && this.request.abort();
    };
    EventCalendar.prototype.scrollLoadNow = function () {
        if (!this.scrollLoad) {
            return false;
        }
        this.scrollLoading = true;
        this.requestEvents(this.getFilters());
    };
    EventCalendar.prototype.requestEvents = function (data) {
        if (!this.scrollLoading && (JSON.stringify(data) === this.requestData)) {
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
                all_topics_id: this.allTopicsID,
                last_month_text: this.getLastEventsMonth(),
                row_count: this.getEventCount(),
                css_animation: this.cssAnimation
            },
            success: $.proxy(this, 'loadEvents')
        });
    };
    EventCalendar.prototype.loadEvents = function (data) {
        this.hideLoader();
        this.request = false;
        this.scrollLoading = false;
        if ('undefined' === typeof data.status) {
            this.scrollLoad = false;
            return false;
        }
        var $rows = $(data.rows);
        if (this.scrollLoad) {
            this.$eventsCon.append($rows);
        } else {
            this.$eventsCon.html($rows);
        }
        this.animateRows($rows);
        if (data.lastPage) {
            this.lastPage = true;
            this.scrollLoad = false;
        } else {
            this.scrollLoad = true;
        }
        addeventatc && addeventatc.refresh();
    };

    $.fn.easlEventCalander = function () {
        return this.each(function () {
            var ec = new EventCalendar($(this));
            ec.init();
        });
    };

    EASLHighlights = function ($el) {
        this.$el = $el;
        this.$eventWrap = $('.easl-highlights-event-items', this.$el);
        this.$pubWrap = $('.easl-highlights-publications-item', this.$el);
        this.$sdWrap = $('.easl-highlights-slide-desks-items', this.$el);
        this.ajaxURL = EASLSETTINGS.ajaxUrl;
        this.request = false;
        this.$filterLabel = $('.easl-highlights-filter-label', this.$el);
        this.$loader = $('<div class="easl-highlights-loader">' + EASLSETTINGS.loaderImage + '</div>').appendTo('.easl-highlights-items', this.$el);
        this.addEventListener();
    };
    EASLHighlights.prototype.addEventListener = function () {
        $('.easl-highlights-filter-dd li a', this.$el).on('click', $.proxy(this, 'requestItems'));
    };
    EASLHighlights.prototype.abortRequest = function () {
        (typeof this.request === 'object') && this.request.abort();
    };
    EASLHighlights.prototype.requestItems = function (event) {
        var $filter = $(event.target),
            currentColor = this.$filterLabel.data('color'),
            currentTopic = this.$filterLabel.data('topic'),
            filterColor = $filter.data('color'),
            filterTopic = $filter.data('topic');
        event.preventDefault();
        if (currentTopic === filterTopic) {
            return false;
        }
        this.$el.addClass('easl-highlights-loading');
        this.$filterLabel
            .html($filter.html())
            .removeClass('easl-color-' + currentColor)
            .addClass('easl-color-' + filterColor)
            .data('color', filterColor)
            .data('topic', filterTopic);
        this.abortRequest();
        this.request = $.ajax({
            url: this.ajaxURL,
            dataType: 'json',
            data: {
                action: 'easl_get_highlights',
                topic: filterTopic,
            },
            success: $.proxy(this, 'loadHightlights')
        });
    };
    EASLHighlights.prototype.loadHightlights = function (data) {
        this.$el.removeClass('easl-highlights-loading');
        this.request = false;
        if ('OK' !== data.status) {
            return false;
        }
        data.items && data.items.events && this.$eventWrap.html(data.items.events);
        data.items && data.items.publications && this.$pubWrap.html(data.items.publications);
        data.items && data.items.slide_decks && this.$sdWrap.html(data.items.slide_decks);
    };

    $.fn.easlHighlights = function () {
        return this.each(function () {
            new EASLHighlights($(this));
        });
    };

    function EASLSlideDecks($el) {
        this.$el = $el;
        this.$filter = $('.slide-deck-filter', $el);
        this.$topicFilterWrap = $('.ec-filter-topics', $el);
        this.$catFilter = $('[name="sd_cat"]', $el);
        this.$yearFilter = $('[name="sd_year"]', $el);
        this.$yearFilterWrap = $('.ec-filter-year', $el);
        this.activeCatYears();
        this.addListners();
    };
    EASLSlideDecks.prototype.activeCatYears = function () {
        var catID, yearsClass, $yearsEl;
        catID = this.$catFilter.val();
        if (catID) {
            yearsClass = '.sd-cat-childs-' + catID;
        }
        if (yearsClass) {
            $yearsEl = $(yearsClass, this.$el);
        }
        if ($yearsEl && $yearsEl.length) {
            this.$yearFilter.html($yearsEl.html());
            easlCustomSelectEL(this.$yearFilterWrap.find('.easl-custom-select'));
            this.$yearFilterWrap.addClass('easl-active');
        } else {
            this.$yearFilterWrap.removeClass('easl-active');
            this.$yearFilter.val('');
        }
    };
    EASLSlideDecks.prototype.addListners = function () {
        var ob = this;
        this.$filter.on('submit', function (event) {
            if (!ob.$yearFilter.val()) {
                event.preventDefault();
                return false;
            }
        });
        this.$catFilter.on('change', function (event) {
            var catID, yearsClass, $yearsEl;
            catID = $(this).val();
            if (catID) {
                yearsClass = '.sd-cat-childs-' + catID;
            }
            if (yearsClass) {
                $yearsEl = $(yearsClass, ob.$el);
            }
            if ($yearsEl && $yearsEl.length) {
                ob.$yearFilter.html($yearsEl.html());
                easlCustomSelectEL(ob.$yearFilterWrap.find('.easl-custom-select'));
                ob.$yearFilterWrap.addClass('easl-active');
            } else {
                ob.$yearFilterWrap.removeClass('easl-active');
                ob.$yearFilter.val('');
            }
        });
        this.$topicFilterWrap.find(':input').on('change', function (event) {
            ob.$filter.submit();
        });
        this.$yearFilter.on('change', function (event) {
            if ($(this).val()) {
                ob.$filter.submit();
            }
        });
        this.$filter.find('.ec-filter-search .ecs-icon').on('click', function (event) {
            event.preventDefault();
            ob.$filter.submit();
        });
    };
    $.fn.easlSlideDecks = function () {
        return this.each(function () {
            new EASLSlideDecks($(this));
        });
    };

    function easlCustomCheckbox() {
        $('.easl-custom-checkbox').each(function () {
            var $cc = $(this);
            if ($('input', $cc).is(':checked')) {
                $cc.addClass('easl-active');
            } else {
                $cc.removeClass('easl-active');
            }
        });
    };

    function easlCustomRadio() {
        $('.easl-custom-radio').each(function () {
            var $cc = $(this);
            if ($('input', $cc).is(':checked')) {
                $cc.addClass('easl-active');
            } else {
                $cc.removeClass('easl-active');
            }
        });
    };

    function easlCustomSelect() {
        $('.easl-custom-select').each(function () {
            var $cs = $(this), $ul = $(".ecs-list", $cs), lis = '', activeLabel = '';
            $('option', $cs).each(function () {
                var dataval = 'data-value="' + $(this).attr('value') + '"';
                if ($(this).is(':selected')) {
                    $cs.find('.ec-cs-label').html($(this).html());
                    lis = lis + '<li class="easl-active" ' + dataval + '>' + $(this).html() + '</li>';
                } else {
                    lis = lis + '<li ' + dataval + '>' + $(this).html() + '</li>';
                }
            });
            if (lis.length > 0) {
                if ($ul.length) {
                    $ul.html(lis);
                }
                $cs.append('<ul class="ecs-list filter-list">' + lis + '</ul>')
            }
        });
    };

    function easlCustomSelectEL($cs) {
        var lis = '', activeLabel = '', $ul = $cs.find('ul.ecs-list');
        $ul.length && $ul.remove();
        $('option', $cs).each(function () {
            var dataval = 'data-value="' + $(this).attr('value') + '"';
            if ($(this).is(':selected')) {
                $cs.find('.ec-cs-label').html($(this).html());
                lis = lis + '<li class="easl-active" ' + dataval + '>' + $(this).html() + '</li>';
            } else {
                lis = lis + '<li ' + dataval + '>' + $(this).html() + '</li>';
            }
        });
        if (lis.length > 0) {
            $cs.append('<ul class="ecs-list filter-list">' + lis + '</ul>')
        }
    };

    function EASLSlideToggle($el) {
        this.$el = $el;
        this.collapsed = false;
        this.collapsing = false;
        this.className = {
            COLLAPSE: "easl-st-collapse",
            SHOW: "easl-st-collapse-show",
            COLLAPSING: "easl-st-collapsing",
            COLLAPSED: "easl-st-collapsed"
        };
        this.EventsName = {
            TRANSITION_END: "transitionEnd webkitTransitionEnd transitionend oTransitionEnd msTransitionEnd"
        };
        this.$el.removeClass(this.className.COLLAPSING).addClass(this.className.COLLAPSE);
    };
    EASLSlideToggle.prototype.show = function () {
        var ob = this;
        if (this.collapsing || this.$el.hasClass(this.className.SHOW)) {
            return;
        }
        this.$el.removeClass(this.className.COLLAPSE).addClass(this.className.COLLAPSING);
        this.$el[0].style.height = 0;
        this.collapsing = true;
        this.$el.one(this.EventsName.TRANSITION_END, function (e) {
            ob.$el.removeClass(ob.className.COLLAPSING).addClass(ob.className.COLLAPSED).addClass(ob.className.SHOW);
            ob.$el[0].style.height = "";
            ob.collapsing = false;
        });
        this.$el[0].style.height = this.$el[0].scrollHeight + "px";
    };
    EASLSlideToggle.prototype.hide = function () {
        var ob = this;
        if (this.collapsing || !this.$el.hasClass(this.className.SHOW)) {
            return;
        }
        this.$el[0].style.height = this.$el[0].getBoundingClientRect().height + "px";
        this.$el[0].offsetHeight;
        this.$el.addClass(this.className.COLLAPSING).removeClass(this.className.COLLAPSE).removeClass(this.className.SHOW);
        this.collapsing = true;
        this.$el.one(this.EventsName.TRANSITION_END, function (e) {
            ob.$el.removeClass(ob.className.COLLAPSING).addClass(ob.className.COLLAPSE);
            ob.collapsing = false;
        });
        this.$el[0].style.height = "";
    };
    EASLSlideToggle.prototype.toggle = function () {
        if (this.$el.hasClass(this.className.SHOW)) {
            this.hide();
        } else {
            this.show();
        }
    };
    $.fn.easlSlideUp = function () {
        return this.each(function () {
            var st;
            st = $(this).data('easlst');
            if (typeof st === 'undefined') {
                st = new EASLSlideToggle($(this));
            }
            st.hide();
        });
    };
    $.fn.easlSlideDown = function () {
        return this.each(function () {
            var st;
            st = $(this).data('easlst');
            if (typeof st === 'undefined') {
                st = new EASLSlideToggle($(this));
            }
            st.show();
        });
    };
    $.fn.easlSlideToggle = function () {
        return this.each(function () {
            var st;
            st = $(this).data('easlst');
            if (typeof st === 'undefined') {
                st = new EASLSlideToggle($(this));
            }
            st.toggle();
        });
    };

    function easlStickyFooterMsg() {
        var $sf = $('#easl-sticky-footer-message-wrap');
        if ($sf.length < 1) {
            return false;
        }
        $('#outer-wrap').css('padding-bottom', $sf.outerHeight() + 'px');
        $sf.addClass('easl-fms-sticky-enabled');

        $('#easl-close-sticky-message').on('click', function (e) {
            e.preventDefault();
            $.ajax({
                type: "POST",
                url: EASLSETTINGS.ajaxUrl,
                data: {
                    action: 'easl_save_closed_footer_message'
                },
                dataType: 'json',
                success: function (data) {
                    console.log(data)
                },
                fail: function () {
                    console.log('Failed');
                }
            });
            $(this).closest('#easl-sticky-footer-message-wrap').slideUp(250, function () {
                $('#outer-wrap').css('padding-bottom', '0px');
                $(this).remove();
            });
            return false;
        });
    }
    function easlCalculateReadingTime() {
        if($body.hasClass('single-blog')) {
            var wpm = 300;
            var wordCount = $('.single-content').text().split(/\s+/).length;
            var imageCount = $('.single-content img' ).length;
            var readingTime = 0;
            var imageTime = imageCount >= 10 ? 72 + (imageCount - 9) * 3 : imageCount * (25 - imageCount) / 2;
            wordCount += parseInt(imageTime * wpm / 60, 10);
            readingTime = wordCount / wpm;
            if(readingTime <= 1) {
                readingTime = '< 1 minute';
            }else{
                readingTime = Math.ceil(readingTime) + ' minutes';
            }
            $('.easl-article-reading-time').html(readingTime );
        }
    }
    
    function easlLoveTheArticle() {
        if($body.hasClass('single')) {
            $(".easl-love-the-article-button").on("click", function (e){
                e.preventDefault();
                var $button = $(this);
                var articleID = $button.data('postid');
                if(articleID && !$button.hasClass('easl-loved')){
                    $(this).addClass('easl-loved', true);
                    $.ajax({
                        url: EASLSETTINGS.ajaxUrl,
                        type: "POST",
                        dataType: 'json',
                        data: {
                            action: 'easl_love_the_article',
                            article_id: articleID,
                        },
                        success: function (response) {
                            if(response.Status === 'SUCCESS') {
                                $(this).addClass('easl-loved', true);
                            }
                        }
                    });
                }
            });
        }
    }
    
    function easlArticleHitCount() {
        if($body.hasClass('single')) {
            $.ajax({
                url: EASLSETTINGS.ajaxUrl,
                type: "POST",
                dataType: 'json',
                data: {
                    action: 'easl_article_hit_count',
                    article_id: EASLSETTINGS.article_id,
                },
                success: function (response) {
                
                }
            });
        }
    }

    $(document).ready(function () {
        $body = $('body');
        bodyOffsetTop = $body.offset().top;
        easlStickyHeader();
        easlCustomCheckbox();
        easlCustomRadio();
        easlCustomSelect();
        easlCalculateReadingTime();
        easlLoveTheArticle();
        easlArticleHitCount();

        $body.on("mz_reload_custom_fields", function (event, $context) {
            easlCustomCheckbox();
            easlCustomRadio();
            easlCustomSelect();
        });
        $body.on('click', '.easl-custom-select', function () {
            var $cs = $(this);
            if ($('.ecs-list', $cs).is(":animated")) {
                return false;
            }
            if ($cs.hasClass('easl-active')) {
                $cs.removeClass('easl-active');
                $('.ecs-list', $cs).slideUp(250);
            } else {
                $cs.addClass('easl-active');
                $('.ecs-list', $cs).slideDown(250);
            }
        });
        $body.on('click', '.ecs-list li', function () {
            var $li = $(this);
            if ($li.hasClass('easl-active')) {
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
        $body.on('click', '.easl-custom-checkbox input', function (e) {
            e.stopPropagation();
        });
        $body.on('click', '.easl-custom-checkbox', function (e) {
            var $cc = $(this), $ul = $cc.closest('ul');

            if (!$('input', $cc).is(':checked')) {
                $cc.addClass('easl-active');
                if ($cc.hasClass('easl-cb-all')) {
                    $ul
                        .find('.easl-custom-checkbox')
                        .not($cc)
                        .removeClass('easl-active')
                        .find('input')
                        .prop('checked', false);
                } else {
                    $ul
                        .find('.easl-cb-all')
                        .removeClass('easl-active')
                        .find('input')
                        .prop('checked', false);
                }
            } else {
                if ($cc.hasClass('easl-cb-all')) {
                    return false;
                }
                $cc.removeClass('easl-active');
                if (!$ul.find('.easl-active').length) {
                    $ul
                        .find('.easl-cb-all')
                        .addClass('easl-active')
                        .find('input')
                        .prop('checked', true);
                }
            }
        });
        $body.on('click', '.easl-custom-radio', function (e) {
            var $cc = $(this);
            if ($('input', $cc).is(':checked')) {
                $cc.addClass('easl-active');
            }
            $cc.siblings().not($cc).removeClass('easl-active');
        });
        ('undefined' !== typeof $.fn.datepicker) && $('.easl-date-picker').datepicker({
            dateFormat: "yy-mm-dd",
            showOn: "button",
            buttonImageOnly: true,
        });
        $('.easl-carousel').on('initialized.owl.carousel loaded.owl.carousel changed.owl.carousel resized.owl.carousel', function (event) {
            var top = 0;
            if (null !== event.item.index) {
                top = $(this).find(".owl-item").eq(event.item.index).find(".easl-carousel-image").outerHeight();
                $(this).find('.owl-dots').css({"top": top + 'px'});
            }

        });
        $('.easl-events-calendar-wrap').easlEventCalander();
        $('.easl-highlights').easlHighlights();
        $('.easl-slide-decks-wrap').easlSlideDecks();
        $(document).on('click', '.show_more_btn', function (e) {
            e.preventDefault();
            $('.event_description').toggle();

        });
        $body.on('click', '.ec-links-deadline', function (event) {
            event.preventDefault();
            var $li = $(this).closest('li');
            if ($li.hasClass('easl-active')) {
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
        $('.toggle-box-button').on('click', function (e) {
            e.preventDefault();
            var $t = $(this);
            var $target = $($t.data('target'));
            if ($target.length) {
                $target.easlSlideToggle();
            }
            if ($t.hasClass('tbb-shown')) {
                $t.removeClass('tbb-shown').addClass('tbb-hidden');
            } else {
                $t.removeClass('tbb-hidden').addClass('tbb-shown');
            }
        });


        $('.easl-news-list-filter-form').on('click', '.ecs-icon', function (e) {
            $('.easl-news-list-filter-form').submit();
        });
        $('.easl-news-list-filter-form').on('change', 'select', function () {
            $('.easl-news-list-filter-form').submit();
        });
        // National Association
        $('.nas_filter_id').on('change', function (e) {
            var $this = $(this), $con = $this.closest('.easl-nas-wrap');
            var id = $this.val();
            if (!id) {
                $this.data('current', false);
                $con.removeClass('nas-loaded nas-loading');
                return false;
            }
            if ($this.data('current') === id) {
                return false;
            }
            $this.data('current', id);
            $con.removeClass('nas-loaded').addClass('nas-loading');
            $.post(EASLSETTINGS.ajaxUrl, {
                'action': 'get_nas_details',
                'nas_id': id
            }, function (response) {
                $('.easl-nas-details', $con).html(response);
                $con.removeClass('nas-loading').addClass('nas-loaded');
                //history.pushState({id: 'nas', html: $con.html() }, document.title, $this.attr('href'));
            });
        });
        $('.easl-nas-contribute-form-wrap').attr('style', '');
        $('.easl-nas-contribute-button-trigger').on('click', function (event) {
            event.preventDefault();
            var id = $(this).attr('href');
            $formModal = $(id);
            if ($formModal.length) {
                $formModal.addClass('easl-active');
            }
        });
        $('.easl-nas-form-close').on('click', function (event) {
            event.preventDefault();
            $(this).closest('.easl-nas-contribute-form-wrap').removeClass('easl-active');
        });
        $body.on('click', '.show-footer-newsletter', function (event) {
            event.preventDefault();
            $('.footer-newsletter').toggleClass('easl-active');
        });
        $body.on('click', '.close-footer-newsletter', function () {
            $('.footer-newsletter').removeClass('easl-active');
        });
        $body.on('click', '.easl-mentors-table-show-more', function (e) {
            var $this = $(this);
            e.preventDefault();
            if ($this.hasClass('unshown')) {
                $('.easl-mentors-table-row.old-rows').removeClass('hidden');
                $('.easl-mentors-table-show-more.unshown').removeClass('unshown').addClass('shown');
                $('.easl-mentors-table-show-more').find('.theme-button-inner').text('Show less');
            }
            if ($this.hasClass('shown')) {
                $('.easl-mentors-table-row.old-rows').addClass('hidden');
                $('.easl-mentors-table-show-more.unshown').removeClass('shown').addClass('unshown');
                $('.easl-mentors-table-show-more').find('.theme-button-inner').text('Show more');
            }
        });

        setCardBlockHeight();

        $('.read-more-btn').on('click', function (e) {
            e.preventDefault();
            var target = $(this).data('target');
            if (typeof target === 'undefined') {
                return false;
            }
            target = $('.' + target);
            target.length && target.slideToggle(200);
        });
        $('.easl-msc-menu-wrap').each(function () {
            var $this = $(this), $current = $('.msc-menu .current-menu-item', $this);
            if ($current.length === 0) {
                $current = $this.find('.msc-menu li:first-child');
            }
            $this.prepend('<p class="easl-msc-menu-mobile-label easl-hide-desktop" onclick="">' + $current.find('a').html() + '</p>');
            $this.find('.easl-msc-menu-mobile-label').on('click', function (e) {
                e.preventDefault();
                $this.toggleClass('easl-active');
            });
        });

        function easlResizeEvent() {
            setCardBlockHeight();
            $(window).trigger('easl_resize_sampled');
        }

        function easlScrollEvent() {
            easlStickyHeader();
        }

        var easlResizeTimeout;
        $(window).resize(function (event) {
            if (easlResizeTimeout) {
                window.cancelAnimationFrame(easlResizeTimeout);
            }
            easlResizeTimeout = window.requestAnimationFrame(function () {
                easlResizeEvent();
            });
        });

        var easlScrollTimeout;
        $(window).scroll(function (event) {
            if (easlScrollTimeout) {
                window.cancelAnimationFrame(easlScrollTimeout);
            }
            easlScrollTimeout = window.requestAnimationFrame(function () {
                easlScrollEvent();
            });
        });
        window.onpopstate = function (event) {
            if (event.state && event.state.id && event.state.id === 'nas') {
                $('.nas-container').html(event.state.html);
            }
            if (event.state && event.state.id && event.state.id === 'nas') {
                $('.nas-container').html(event.state.html);
            }
        };
        easlStickyFooterMsg();
    });
    $(window).load(function () {
        $('.easl-preloader').fadeOut(200, function () {
            $(this).remove();
        });
        window._oneSignalInitOptions.promptOptions = {
            slidedown: {
                enabled: true,
                autoPrompt: true,
                timeDelay: 5
            }};

        window.OneSignal = window.OneSignal || [];
        /* Why use .push? See: http://stackoverflow.com/a/38466780/555547 */
        window.OneSignal.push(function() {
            /* Never call init() more than once. An error will occur. */
            window.OneSignal.init(window._oneSignalInitOptions);
            console.log("Easl one single iniated");
        });
    });
})(jQuery);