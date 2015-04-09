/**
 * @ Lamosty.com 2015
 */

(function ($) {

    var RentiFlat = {
        'common': {
            init: function () {
                $.material.init();
            },
            finalize: function () {
            }
        }
    };


    /*
     * DOM-based Routing
     * Based on http://goo.gl/EUTi53 by Paul Irish
     */

    var UTIL = {
        fire: function (func, funcname, args) {
            var fire;
            var namespace = RentiFlat;
            funcname = (funcname === undefined) ? 'init' : funcname;
            fire = func !== '';
            fire = fire && namespace[func];
            fire = fire && typeof namespace[func][funcname] === 'function';

            if (fire) {
                namespace[func][funcname](args);
            }
        },
        loadEvents: function () {
            // Fire common init JS
            UTIL.fire('common');

            // Fire page-specific init JS, and then finalize JS
            $.each(document.body.className.replace(/-/g, '_').split(/\s+/), function (i, classnm) {
                UTIL.fire(classnm);
                UTIL.fire(classnm, 'finalize');
            });

            // Fire common finalize JS
            UTIL.fire('common', 'finalize');
        }
    };

    // Load Events
    $(document).ready(UTIL.loadEvents);

})(jQuery);
