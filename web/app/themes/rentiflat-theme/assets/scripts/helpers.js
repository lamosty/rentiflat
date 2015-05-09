/**
 * @ Lamosty.com 2015
 */

var RentiFlatHelpers = {
    wpAPIRequest: function (params) {
        return $.ajax({
            type: params['type'],
            url: params['url'],
            headers: {
                'X-WP-Nonce': params['nonce']
            },
            data: params['data']
        });
    }
};
