(function ($) {
    var EASLYoutbePlayer = {
        Storage: {},
        defaults: {
            autoplay: 1,
            controls: 1,
            enablejsapi: 1,
            modestbranding: 1,
            origin: window.location.protocol + window.location.host,
            rel: 1
        },
        APILoaded: function() {
            if(typeof YT === "undefined" || typeof YT.Player === "undefined"){
                return false;
            }
            return true;
        },
        doAPILoad: function() {
            var load = true;
            $('head').find('*').each(function(){
                if (jQuery(this).attr('src') == "https://www.youtube.com/iframe_api"){
                    load = false;
                }
            });
            return load;
        },
        createYTPlayer: function(playerID){
            var ob = this,
                playerVars = this.defaults,
                $container = $("#" + playerID).closest(".easl-yt-player-container"),
                player;
            if('undefined' !== typeof this.Storage.Data[playerID].controls){
                playerVars.controls = this.Storage.Data[playerID].controls;
            }
            if('undefined' !== typeof this.Storage.Data[playerID].modestbranding){
                playerVars.modestbranding = this.Storage.Data[playerID].modestbranding;
            }
            if(typeof this.Storage.Data[playerID].start){
                playerVars.start = this.Storage.Data[playerID].start;
            }
            if(typeof this.Storage.Data[playerID].end){
                playerVars.end = this.Storage.Data[playerID].end;
            }
            player = this.Storage.Players[playerID] = new YT.Player(playerID, {
                height: '390',
                width: '640',
                videoId: this.Storage.Data[playerID].id,
                playerVars: playerVars,
                events: {
                    "onReady": function(event) {
                        ob.Storage.Data[playerID].mute &&event.target.mute();
                        $container
                            .addClass('easl-yt-player-loaded')
                            .removeClass('easl-yt-player-loading');
                    }
                }
            });
        },
        initPlayers: function(){
            if(!this.APILoaded()){
                return;
            }
            clearInterval(this.Storage.ApiCheckTimer);
            if (typeof this.Storage.Data === 'undefined'){
                return;
            }
            for(var i = 0; i < this.Storage.StartOnAPILoaded.length; i++){
                this.createYTPlayer(this.Storage.StartOnAPILoaded[i]);
            }
            this.Storage.StartOnAPILoaded = [];
        },
        initAPILoad: function(){
            var ob = this;
            if(this.doAPILoad() ){
                var tag = document.createElement('script');
                tag.async = true;
                tag.src = "https://www.youtube.com/iframe_api";
                var firstScriptTag = document.getElementsByTagName('script')[0];
                firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);
                window.onYouTubeIframeAPIReady = function() {
                    ob.initPlayers();
                };
                return;
            }
            if(!this.Storage.YTApiCheckTimer){
                this.Storage.YTApiCheckTimer = setInterval(function(){
                    ob.initPlayers();
                }, 1000);
            }
        },
        init: function(){
            var ob = this,
                playerCount = 0;

            this.Storage.Data = {};
            this.Storage.StartOnAPILoaded = [];
            this.Storage.Players = {};
            this.Storage.ApiCheckTimer = null;
            $('.easl-yt-player-container').not('.easl-yt-player-lightbox').each(function () {
                var playerID, $container = $(this);
                playerCount++;
                playerID = "easl-yt-player-" + playerCount;
                ob.Storage.Data[playerID] = {
                    playerID: playerID,
                    id: $container.data('id'),
                    autoplay: $container.data('autoplay'),
                    controls: $container.data('controls'),
                    modestbranding: $container.data('modestbranding'),
                    mute: $container.data('mute'),
                    start: $container.data('start'),
                    end: $container.data('end'),
                };
                $container
                    .data('palyerid', playerID)
                    .append('<div class="easl-yt-player-placeholder" id="'+ playerID +'"></div>');
                if(ob.Storage.Data[playerID].autoplay){
                    ob.Storage.StartOnAPILoaded.push(playerID);
                    $container.addClass('easl-yt-player-loading');
                }else{
                    $container.find('.easl-yt-player-trigger').on('click', function (event) {
                        var $c = $(this).closest('.easl-yt-player-container'),
                            pID = $c.data('palyerid');
                        event.preventDefault();
                        $c.addClass('easl-yt-player-loading');
                        if(ob.APILoaded()){
                            ob.createYTPlayer(pID);
                        }else{
                            ob.Storage.StartOnAPILoaded.push(pID);
                            ob.initAPILoad();
                        }
                        return false;
                    })
                }
            });

            if(this.Storage.StartOnAPILoaded.length < 1) {
                return;
            }
            if(this.APILoaded()) {
                this.initPlayers();
                return;
            }
            this.initAPILoad();
        }
    };
    $(document).ready(function(){
        EASLYoutbePlayer.init();
    });
})(jQuery);