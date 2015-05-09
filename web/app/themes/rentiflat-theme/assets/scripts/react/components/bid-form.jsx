/**
 * @ Lamosty.com 2015
 */

var BidForm = React.createClass({
    mixins: [React.addons.LinkedStateMixin],

    getInitialState: function () {
        return {
            flatPricePerMonth: this.props.data['flat_price_per_month'],
            tenantEmail: this.props.data['tenant_email'],
            formSubmitted: false
        };
    },
    onBidSubmit: function (e) {
        e.preventDefault();

        this.props.onBidSubmit(this.state.flatPricePerMonth, this.state.tenantEmail);

        this.setState({
            formSubmitted: true
        });
    },
    _renderForm: function () {
        return (
            <div className="row">
                <div className="tenant">
                    <div className="picture">
                        <img src={this.props.data['tenant_profile_picture']}
                        alt={this.props.data['tenant_fullname'] + " profile picture"}/>
                    </div>
                    <div className="name">
                        {this.props.data['tenant_fullname']}
                    </div>
                </div>
                <div className="col-md-6">
                    <form id="bid-form" className="form-horizontal" onSubmit={this.onBidSubmit}>
                        <fieldset>
                            <div className="form-group">
                                <div className="col-md-6">
                                    <div className="input-group form-control-wrapper">
                                        <input type="number" className="form-control"
                                        valueLink={this.linkState('flatPricePerMonth')}/>

                                        <div className="floating-label">Bidding price</div>
                                        <span className="material-input"></span>
                                        <span className="input-group-addon">$</span>
                                    </div>
                                </div>
                            </div>
                            <div className="form-group">
                                <div className="col-md-12">
                                    <div className="form-control-wrapper">
                                        <input type="email" className="form-control" ref="tenantEmail"
                                        valueLink={this.linkState('tenantEmail')} />

                                        <div className="floating-label">Email address</div>
                                        <div className="material-input"></div>
                                    </div>
                                </div>
                            </div>
                            <div className="form-group">
                                <div className="col-md-12">
                                    <button type="submit" className="btn btn-primary">I'm interested</button>
                                </div>
                            </div>
                        </fieldset>
                    </form>
                </div>
            </div>
        );

    },
    _renderPreviousBid: function () {
        return (
            <div>
                <h3>You have already placed a bid</h3>
                <p>
                To edit or delete the bid, visit the <a href={this.props.data['bid_admin_url']}>administration dashboard</a>
                .
                </p>
            </div>
        )

    },
    _renderBidAdded: function () {
        return (
            <div>
                <h3>Thanks for your bid</h3>
                <p>{this.props.data['flat_owner_name']} (flat owner) will be notified shortly.</p>
            </div>
        )
    },
    render: function () {
        var body;

        // Tenant already has a bid.
        if (this.props.data['tenant_bid_id'] !== null) {
            body = this._renderPreviousBid();
        } else if (this.state.formSubmitted) {
            body = this._renderBidAdded();
        } else {
            body = this._renderForm();
        }

        return (
            <div className="bid-form-section col-md-6">
                {body}
            </div>
        );
    }

});

