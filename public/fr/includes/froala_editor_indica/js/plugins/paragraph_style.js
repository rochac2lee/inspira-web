/*!
 * froala_editor v3.0.4 (https://www.froala.com/wysiwyg-editor)
 * License https://froala.com/wysiwyg-editor/terms/
 * Copyright 2014-2019 Froala Labs
 */

!(function (a, e) {
    "object" == typeof exports && "undefined" != typeof module ? e(require("froala-editor")) : "function" == typeof define && define.amd ? define(["froala-editor"], e) : e(a.FroalaEditor);
})(this, function (a) {
    "use strict";
    (a = a && a.hasOwnProperty("default") ? a["default"] : a),
        Object.assign(a.DEFAULTS, { paragraphStyles: { "fr-recuo": "Recuo Primeira Linha", "fr-text-gray": "Gray", "fr-text-bordered": "Bordered", "fr-text-spaced": "Spaced", "fr-text-uppercase": "Uppercase" }, paragraphMultipleStyles: !0 }),
        (a.PLUGINS.paragraphStyle = function (n) {
            var i = n.$;
            return {
                _init: function a() {},
                apply: function p(a, e, t) {
                    void 0 === e && (e = n.opts.paragraphStyles), void 0 === t && (t = n.opts.paragraphMultipleStyles);
                    var r = "";
                    t || ((r = Object.keys(e)).splice(r.indexOf(a), 1), (r = r.join(" "))), n.selection.save(), n.html.wrap(!0, !0, !0, !0), n.selection.restore();
                    var s = n.selection.blocks();
                    n.selection.save();
                    for (var l = i(s[0]).hasClass(a), o = 0; o < s.length; o++)
                        i(s[o]).removeClass(r).toggleClass(a, !l), i(s[o]).hasClass("fr-temp-div") && i(s[o]).removeClass("fr-temp-div"), "" === i(s[o]).attr("class") && i(s[o]).removeAttr("class");
                    n.html.unwrap(), n.selection.restore();
                },
                refreshOnShow: function s(a, e) {
                    var t = n.selection.blocks();
                    if (t.length) {
                        var r = i(t[0]);
                        e.find(".fr-command").each(function () {
                            var a = i(this).data("param1"),
                                e = r.hasClass(a);
                            i(this).toggleClass("fr-active", e).attr("aria-selected", e);
                        });
                    }
                },
            };
        }),
        a.RegisterCommand("paragraphStyle", {
            type: "dropdown",
            html: function () {
                var a = '<ul class="fr-dropdown-list" role="presentation">',
                    e = this.opts.paragraphStyles;
                for (var t in e)
                    e.hasOwnProperty(t) &&
                        (a +=
                            '<li role="presentation"><a class="fr-command ' +
                            t +
                            '" tabIndex="-1" role="option" data-cmd="paragraphStyle" data-param1="' +
                            t +
                            '" title="' +
                            this.language.translate(e[t]) +
                            '">' +
                            this.language.translate(e[t]) +
                            "</a></li>");
                return (a += "</ul>");
            },
            title: "Paragraph Style",
            callback: function (a, e) {
                this.paragraphStyle.apply(e);
            },
            refreshOnShow: function (a, e) {
                this.paragraphStyle.refreshOnShow(a, e);
            },
            plugin: "paragraphStyle",
        }),
        a.DefineIcon("paragraphStyle", { NAME: "magic", SVG_KEY: "paragraphStyle" });
});
