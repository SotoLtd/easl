(function ($) {
    var xhr = null;
    
    acf.add_action('ready_field/name=episode_live_date_time', function( $liveDateFieldWrap ){
        $liveDateFieldWrap.find('input').on('change', function (event){
            var liveDate = $(this).val();
            if(!liveDate) {
                return true;
            }
            liveDate = liveDate.split('-');
            var $seasonField = $('[data-name="episode_season"]').find('input');
            var prevVal = $seasonField.val();
            (liveDate[0] !== prevVal) && $seasonField.val(liveDate[0]).trigger('change');
        });
        
    });
    
    acf.add_action('ready_field/name=episode_season', function( $seasonFieldWrap ){
        var $dateFieldWrap = $('[data-name="episode_live_date_time"]');
        var $episodeFieldWrap = $('[data-name="episode_number"]');
        $seasonFieldWrap.find('input').on('change', function (event){
            var season = $(this).val();
            if(!season) {
                return true;
            }
            $dateFieldWrap.addClass('es-loading');
            $seasonFieldWrap.addClass('es-loading');
            $episodeFieldWrap.addClass('es-loading');
            if(xhr) {
                xhr.abort();
            }
            xhr = $.ajax({
                url: ajaxurl,
                method: 'post',
                data: {
                    'action': 'es_get_episode_number',
                    'season': season
                },
                dataType: 'json',
                success: function (res){
                    $dateFieldWrap.removeClass('es-loading');
                    $seasonFieldWrap.removeClass('es-loading');
                    $episodeFieldWrap.removeClass('es-loading');
                    if(res && 'OK' === res.Status) {
                        $episodeFieldWrap.find('input').val(res.EpisodeNumber);
                    }
                }
            });
        });
        
    });
    
})(jQuery);