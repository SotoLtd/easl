(function ($) {

    window.EASLVcToggleView = vc.shortcode_view.extend({
        events: {
            "click .vc_toggle_title": "toggle",
            "click .toggle_title": "toggle",
            "click > .vc_controls .column_delete": "deleteShortcode",
            "click > .vc_controls .column_add": "addElement",
            "click > .vc_controls .column_edit": "editElement",
            "click > .vc_controls .column_clone": "clone",
            mousemove: "checkControlsPosition"
        },
        toggle: function (e) {
            e && e.preventDefault && e.preventDefault(), $(e.currentTarget).toggleClass("vc_toggle_title_active"), $(".vc_toggle_content", this.$el).slideToggle(500)
        },
        changeShortcodeParams: function (params) {
            window.EASLVcToggleView.__super__.changeShortcodeParams.call(this, params), params = params.get("params"), _.isObject(params) && _.isString(params.open) && "true" === params.open && $(".vc_toggle_title", this.$el).addClass("vc_toggle_title_active").next().show()
        }
    })
})(jQuery);