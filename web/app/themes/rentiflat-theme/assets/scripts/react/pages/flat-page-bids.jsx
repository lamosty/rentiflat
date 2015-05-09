/** @jsx React.DOM */

/**
 * @ Lamosty.com 2015
 */

var ReactCSSTransitionGroup = React.addons.CSSTransitionGroup;

var FlatPageBids = React.createClass({
    getInitialState: function () {
        return {
            bids: this.props.bids
        };
    },
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

        this.setState(function (previousState) {
            var newBids = previousState.bids.slice();

            newBids.unshift({
                candidate_name: this.props.data['tenant_fullname'],
                candidate_email: tenantEmail,
                candidate_picture: this.props.data['tenant_profile_picture'],
                candidate_fb_url: this.props.data['tenant_fb_url'],
                date: moment().utc().format('YYYY-MM-DD HH:MM:SS').toString(),
                price_per_month: flatPricePerMonth
            });

            return {
                bids: newBids
            };
        });

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
    _renderBids: function () {
        var bidItems = [];

        this.state.bids.forEach(function (bid) {
            bidItems.push(<BidItem data={bid} key={bid.date} />);
        });

        return bidItems;
    },
    render: function () {
        return (
            <div className="row">
                <BidForm data={this.props.data} onBidSubmit={this.onBidSubmit} />

                <div className="bids-list col-md-6">
                    <h3>List of candidates</h3>
                    <ReactCSSTransitionGroup transitionName="bid" component="div">
                        {this._renderBids()}
                    </ReactCSSTransitionGroup>
                </div>
            </div>
        );
    }
});