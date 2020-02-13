(function($){
    $(document).ready(function () {
        $(".easl-history-carousel").on("initialized.owl.carousel", function(event){
            var $car = $(this),
                carObj = null,
                $wrap = $(this).closest(".easl-history-slider"),
                $next = $wrap.find(".easl-history-slider-next"),
                $prev = $wrap.find(".easl-history-slider-prev"),
                handle = $wrap.find(".easl-hisgtory-slider-bar-handle"),
                custoHandle = $wrap.find(".easl-hisgtory-slider-bar-chandle"),
                barMax = window.parseInt(handle.data("syearnum"), 10);
            $wrap.addClass("easl-hs-loaded");
            var sliderBar = handle.slider({
                min: 1,
                max: barMax,
                range: "max",
                value: 1,
                create: function() {
                    custoHandle.text('');
                },
                slide: function( event, ui ) {
                    var currentYear = "";
                    if(!carObj){
                        carObj = $car.data("owl.carousel");
                    }
                    carObj.to(ui.value - 1);
                    if(barMax === ui.value){
                        $next.addClass("easl-hs-hide");
                    }else{
                        $next.removeClass("easl-hs-hide");
                    }
                    if(1 === ui.value){
                        $prev.addClass("easl-hs-hide");
                    }else{
                        $prev.removeClass("easl-hs-hide");
                    }

                    if(barMax !== ui.value && 1 !== ui.value){
                        currentYear = carObj.$stage.children().eq(carObj.current()).find(".easl-history-carousel-item").data("year")
                    }
                    custoHandle.html(currentYear);
                }
            });
            $next.on("click", function (e) {
                e.preventDefault();
                var currentVal = sliderBar.slider("value") + 1, currentYear = "";
                sliderBar.slider("value", currentVal);
                if(!carObj){
                    carObj = $car.data("owl.carousel");
                }
                carObj.to(currentVal - 1 );
                if(barMax !== currentVal && 1 !== currentVal){
                    currentYear = carObj.$stage.children().eq(carObj.current()).find(".easl-history-carousel-item").data("year")
                }
                if(barMax === currentVal){
                    $next.addClass("easl-hs-hide");
                }else{
                    $next.removeClass("easl-hs-hide");
                }
                if(1 === currentVal){
                    $prev.addClass("easl-hs-hide");
                }else{
                    $prev.removeClass("easl-hs-hide");
                }
                custoHandle.html(currentYear);
            });
            $prev.on("click", function (e) {
                e.preventDefault();
                var currentVal = sliderBar.slider("value") - 1, currentYear = "";
                sliderBar.slider("value", currentVal);
                if(!carObj){
                    carObj = $car.data("owl.carousel");
                }
                carObj.to(currentVal - 1);
                if(barMax !== currentVal && 1 !== currentVal){
                    currentYear = carObj.$stage.children().eq(carObj.current()).find(".easl-history-carousel-item").data("year")
                }
                if(barMax === currentVal){
                    $next.addClass("easl-hs-hide");
                }else{
                    $next.removeClass("easl-hs-hide");
                }
                if(1 === currentVal){
                    $prev.addClass("easl-hs-hide");
                }else{
                    $prev.removeClass("easl-hs-hide");
                }
                custoHandle.html(currentYear);
            });
            $car.on("dragged.owl.carousel", function (event) {
                if(!carObj){
                    carObj = $car.data("owl.carousel");
                }
                var currentVal = carObj.current() + 1;
                sliderBar.slider("value", currentVal);
                if(barMax !== currentVal && 1 !== currentVal){
                    currentYear = carObj.$stage.children().eq(carObj.current()).find(".easl-history-carousel-item").data("year")
                }
                if(barMax === currentVal){
                    $next.addClass("easl-hs-hide");
                }else{
                    $next.removeClass("easl-hs-hide");
                }
                if(1 === currentVal){
                    $prev.addClass("easl-hs-hide");
                }else{
                    $prev.removeClass("easl-hs-hide");
                }
                custoHandle.html(currentYear);
            });
        });
    });
})(jQuery);
jQuery(function($) {

    var minValue = 1;
    var maxValue = $( ".slider-frame-points" ).size();
    var handle = $( "#custom-handle" );
    var slider = $( "#slider" ).slider({
        min: minValue,
        max: maxValue,
        range: "max",
        value: 1,
        create: function() {
            handle.text('');
        },
        slide: function( event, ui ) {
            document.getElementsByClassName('slider-frame-points')[ui.value - 1].children[0].click();
            var text = '';
            var selected_el = $('#dates a.selected').parent().data('year');
            if( (maxValue !== ui.value) && (minValue !== ui.value) ){
                text = selected_el;
            }
            handle.text( text );
        }
    });

    $(document).on('click', '#next', function(){
        var current_value = slider.slider( "value");
        var next_value = current_value + 1;
        var text = '';
        var selected_el = $('#dates li:eq('+current_value+')').data('year');

        if( next_value !== maxValue){
            text = selected_el;
        }
        $('#custom-handle').text(text);
        slider.slider( "value", next_value );
    });

    $(document).on('click', '#prev', function(){
        var current_value = slider.slider( "value");
        var prev_value = current_value - 1;
        var text = '';
        var selected_el = $('#dates li:eq('+(prev_value-1)+')').data('year');

        if( prev_value !== 1){
            text = selected_el;
        }

        $('#custom-handle').text(text);
        slider.slider( "value", prev_value );
    });

});


