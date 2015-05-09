/** @jsx React.DOM */

/**
 * @ Lamosty.com 2015
 */

var FlatPageBids = React.createClass({
    onBidSubmit: function (flatPricePerMonth, tenantEmail) {
        var bid = {
            title: 'Bid on ' + this.props.data['flat_page_title'],
            content_raw: '',
            status: 'publish',
            type: 'rentiflat_bid',
            parent: this.props.data['flat_page_id']
        };

        var bidMetas = [
            {
                key: 'flat_price_per_month',
                value: flatPricePerMonth
            },
            {
                key: 'tenant_email',
                value: tenantEmail
            }
        ];

        var that = this;

        RentiFlatHelpers.wpAPIRequest({
            type: 'POST',
            url: this.props.data['api_url'] + '/posts',
            data: bid,
            nonce: this.props.data['nonce']
        }).done(function (response) {
            bidMetas.forEach(function (bidMeta) {
                RentiFlatHelpers.wpAPIRequest({
                    type: 'POST',
                    url: that.props.data['api_url'] + '/posts/' + response.ID + '/meta',
                    data: bidMeta,
                    nonce: that.props.data['nonce']
                });
            });
        });
    },
    _renderBids: function() {
        var bidItems = [];

        this.props.bids.forEach(function(bid) {
            bidItems.push(<BidItem data={bid} />);
        });

        return bidItems;
    },
    render: function () {
        return (
            <div className="row">
                <BidForm data={this.props.data} onBidSubmit={this.onBidSubmit} />

                <div className="bids-list col-md-6">
                    <h3>List of candidates</h3>
                    {this._renderBids()}
                </div>
            </div>
        );
    }
});