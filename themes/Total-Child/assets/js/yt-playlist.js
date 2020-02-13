(function($){
    EASLYTPlaylist = {
        apiKey: 'AIzaSyBu8abwUOZH6WyCXQlKDJkHiGw-qtbaE5k',
        defaults: {
            autoplay: 0,
            autohide: 1,
            modestbranding: 1,
            rel: 0,
            controls: 1,
            disablekb: 0,
            enablejsapi: 1,
            iv_load_policy: 3,
            loop: 1,
            playsinline: 1,
            origin: window.location.protocol + window.location.host
        },
        Storage: {},
        YTAPILoad: function() {
            loadit = true;
            $('head').find('*').each(function(){
                if (jQuery(this).attr('src') == "https://www.youtube.com/iframe_api")
                    loadit = false;
            });
            return loadit;
        },
        createYTPlayer: function(playListID, videos){
            var $plcon = $("#" + playListID), playerID = playListID + '-player', $thumbsCon, videoIDs = [];
            $plcon.append('<div class="easl-ytpl-player-wrap"><div id="' + playerID + '" class="easl-ytpl--player"></div></div>');
            $plcon.append('<div class="easl-ytpl-thumbs-wrap"><div class="easl-ytpl-thumbs-inner"></div></div>');
            $thumbsCon = $plcon.find('.easl-ytpl-thumbs-inner');
            var itemHTML = '';
            for(var i = 0; i < videos.length; i++){
                videoIDs.push(videos[i].id);
                itemHTML += '<div class="easl-ytpl-thumbs-item wpex-carousel-slide"><a href="http://youtu.be/' + videos[i].id + '" data-id="' + videos[i].id + '" data-playerid="' + playListID + '"><img alt="" src="' + videos[i].thumb + '"/><span>' + videos[i].title + '</span></a></div>';
            }
            $thumbsCon.html('<div class="easl-ytpl-thumbs-list wpex-carousel clr owl-carousel arrwstyle-default">' + itemHTML + '</div>');

            $.fn.wpexOwlCarousel && $thumbsCon.find('.easl-ytpl-thumbs-list').imagesLoaded(function() {
                $thumbsCon.find('.easl-ytpl-thumbs-list').wpexOwlCarousel({
                    animateIn          : false,
                    animateOut         : false,
                    lazyLoad           : false,
                    dots: false,
                    nav: true,
                    autoplay: false,
                    items: 5,
                    margin: 8,
                    navText            : [ '<span class="ticon ticon-chevron-left"><span>', '<span class="ticon ticon-chevron-right"></span>' ],
                    responsive: {
                        0: {
                            items: 2
                        },
                        480: {
                            items: 3
                        },
                        768: {
                            items: 5
                        }
                    }
                });
            });
            var player = this.Storage.Players[playListID] = new YT.Player(playerID, {
                height: '315',
                width: '560',
                videoId: videoIDs[0],
                playerVars: this.defaults,
                events: {
                    "onReady": function(e) {
                        //e.target.playVideo();
                    },
                    "onStateChange": function(e){
                    }
                }
            });
            $thumbsCon.on('click', '.easl-ytpl-thumbs-item a', function(e){
                e.preventDefault();
                var id = $(this).data('id'), palyerID = $(this).data('playerid');
                EASLYTPlaylist.Storage.Players[playListID].loadVideoById(id);
            });
        },
        createYTPlaylists: function(playListID){
            var playlistData = this.Storage.Data[playListID];
            var apiURL = 'https://www.googleapis.com/youtube/v3/videos';
            var apiData = {
                'part': 'snippet',
                'key': this.apiKey,
            };

            if(playlistData.source === 'ids'){
                apiURL = 'https://www.googleapis.com/youtube/v3/videos';
                apiData['id'] = playlistData['id'];
                apiData['fields'] = 'items(kind,id,snippet/title,snippet/thumbnails/medium/url)';
            }
            if(playlistData.source === 'playlist'){
                apiURL = 'https://www.googleapis.com/youtube/v3/playlistItems';
                apiData['playlistId'] = playlistData['id'];
                apiData['fields'] = 'items(kind,id,snippet/title,snippet/resourceId/videoId,snippet/thumbnails/medium/url)';
            }
            jQuery.ajax({
                dataType: "jsonp",
                url: apiURL,
                data: apiData,
                success: function(d){
                    var videos = [];
                    if(typeof d.items === 'undefined'){
                        return false;
                    }
                    for(var i = 0; i < d.items.length; i++ ){
                        if(d.items[i].kind === 'youtube#video'){
                            videos.push({
                                id: d.items[i].id,
                                title: d.items[i].snippet.title,
                                thumb: d.items[i].snippet.thumbnails.medium.url
                            });
                        }
                        if(d.items[i].kind === 'youtube#playlistItem'){
                            videos.push({
                                id: d.items[i].snippet.resourceId.videoId,
                                title: d.items[i].snippet.title,
                                thumb: d.items[i].snippet.thumbnails.medium.url
                            });
                        }

                    }
                    EASLYTPlaylist.createYTPlayer(playListID, videos);
                }
            });
        },
        initPlaylists: function() {
            if(typeof YT === "undefined" || typeof YT.Player === "undefined"){
                return;
            }
            clearInterval(EASLYTPlaylist.Storage.ApiCheckTimer);
            if (typeof EASLYTPlaylist.Storage.Data === 'undefined'){
                return;
            }
            for(var playlistID in EASLYTPlaylist.Storage.Data) {
                EASLYTPlaylist.createYTPlaylists(playlistID);
            }
        },
        init: function(){
            var playlistCount = 0;

            EASLYTPlaylist.Storage.Data = {};
            EASLYTPlaylist.Storage.Players = {};
            EASLYTPlaylist.Storage.ApiCheckTimer = null;

            $(".easl-yt-playlist").each(function() {
                var playlistID;
                playlistCount++;
                playlistID = "easl-yt-playlist-" + playlistCount;
                $(this).append("<div id='"+ playlistID +"' class='easl-yt-playlist-con'></div>");
                EASLYTPlaylist.Storage.Data[playlistID] = {
                    source: $(this).data("source"),
                    id: $(this).data("id")
                };
            });
            if(!playlistCount) {
                return;
            }
            if(typeof YT !== "undefined") {
                this.initPlaylists();
                return;
            }
            if(this.YTAPILoad() ){
                var tag = document.createElement('script');
                tag.src = "https://www.youtube.com/iframe_api";
                var firstScriptTag = document.getElementsByTagName('script')[0];
                firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);
                window.onYouTubeIframeAPIReady = function() {
                    EASLYTPlaylist.initPlaylists();
                };
            }else{
                EASLYTPlaylist.Storage.YTApiCheckTimer = setInterval(function(){
                    EASLYTPlaylist.initPlaylists();
                }, 1000);
            }
        }
    };
    $(document).ready(function(){
        EASLYTPlaylist.init();
    });
})(jQuery);