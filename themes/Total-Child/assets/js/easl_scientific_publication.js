jQuery(function ($) {
    var $form = $(".publication-filter");
    $("select", $form).on("change", function (event) {
        $form.submit();
    });
    $(".ecs-icon", $form).on("click", function (event) {
        $form.submit();
    }).on("keyup", function (event) {
        if (event.which === 13) {
            $form.submit();
        }
    });
});

