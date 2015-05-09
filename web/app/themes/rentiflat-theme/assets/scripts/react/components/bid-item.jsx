/**
 * @ Lamosty.com 2015
 */

var BidItem = React.createClass({

    render: function () {
        var candidateName, candidateEmail;

        if (this.props.data['candidate_email'] === 'hidden') {
            candidateName = (
                <div className="name">
                    Rastislav
                    <span className="label label-info" dataToggle="tooltip" dataPlacement="top" title="Only flat owners can see the last name.">
                    Hidden
                    </span>
                </div>
            );

            candidateEmail = (
                <div className="email">
                    <span className="label label-info" dataToggle="tooltip" dataPlacement="top"
                    title="Only flat owners can see the email address.">
                    Hidden
                    </span>
                </div>
            );

        } else {
            candidateName = (
                <div className="name">
                    <a href={this.props.data['candidate_fb_url']} target="_blank">
                        {this.props.data['candidate_name']}
                        <sup><i className="mdi-action-open-in-new"></i></sup>
                    </a>
                </div>
            );

            candidateEmail = (
                <div className="email">
                    {this.props.data['candidate_email']}
                </div>
            );
        }

        var dateInGMT = this.props.data['date'];
        var localeDate = moment.utc(dateInGMT).local().format('L');

        return (
            <div className="bid">
                <div className="picture">
                    <img src={this.props.data['candidate_picture']} alt="Candidate picture"/>
                </div>
                <div className="info">
                    <div className="title">Name</div>
                    {candidateName}

                    <div className="title">Email address</div>
                    {candidateEmail}
                </div>
                <div className="price-info">
                    <div className="price">
                        {this.props.data['price_per_month']}
                        <span className="currency">&euro;</span>
                    </div>
                    <div className="date">{localeDate}</div>
                </div>
            </div>
        );
    }

});