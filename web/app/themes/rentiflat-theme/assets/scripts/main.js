/**
 * @ Lamosty.com 2015
 */

(function ($) {

    function swap_large_image(event) {
        event.preventDefault();

        var newLargePhotoSrc = $(this).data('photo-large');

        $('#large-photo').attr('src', newLargePhotoSrc);
    }

    var RentiFlat = {
        'common': {
            init: function () {
                $.material.init();

                $('[data-toggle="tooltip"]').tooltip();
            },
            finalize: function () {
            }
        },
        'single_rentiflat_flat': {
            init: function () {
                $('#flat-photos').find('[data-photo-large]').on('click', swap_large_image);

                this.initBids();
                this.initGMap();
            },

            initBids: function () {
                React.render(
                    React.createElement(FlatPageBids, {
                        'data': RentiFlatTenantData,
                        'bids': RentiFlatBids
                    }),
                    document.getElementById('bids')
                );
            },

            initGMap: function () {
                var flatLatLng = new google.maps.LatLng(
                    parseFloat(RentiFlatGMapsData['flat_lat']),
                    parseFloat(RentiFlatGMapsData['flat_lng'])
                );

                var mapOptions = {
                    zoom: 15,
                    center: flatLatLng,
                    scrollwheel: false
                };

                var map = new google.maps.Map(document.getElementById('location-map'), mapOptions);

                var flatMarker = new google.maps.Marker({
                    position: flatLatLng,
                    map: map,
                    title: 'Flat'
                });
            }

        },
        page_template_page_fb_register: {
            init: function () {
                var that = this;

                $(document).on('rentiflat_fbInit', function () {
                    that.authBtnClick();
                });
            },
            authBtnClick: function () {
                var that = this;

                $('#fb-login').on('click', function (e) {
                    e.preventDefault();

                    FB.getLoginStatus(function (response) {
                        // not logged in or authorized yet
                        if (response.status !== 'connected') {
                            that.doFBLogin();
                        } else {
                            that.handleFBLoggedIn(response.authResponse);
                        }
                    });
                })
            },
            doFBLogin: function () {
                var that = this;

                FB.login(function (response) {
                    // authorized or logged in successfully
                    if (response.authResponse) {
                        that.handleFBLoggedIn(response.authResponse);
                    }
                });
            },
            handleFBLoggedIn: function (authData) {
                $('#fb-login').hide();
                $('#rentiflat-register-form').show();
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
