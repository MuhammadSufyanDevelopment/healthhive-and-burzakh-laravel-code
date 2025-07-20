@extends('layouts.front-end.app')

@section('title',translate('healthhive_refund_policy'))

@section('content')
<style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 20px;
            color: #333;
        }
        h1, h2 {
            color: #f6a403;
        }
        ul {
            margin: 10px 0;
            padding-left: 20px;
        }
        li {
            margin-bottom: 5px;
            list-style-type: none;
        }
        .contact {
            margin-top: 20px;
        }
    </style>
    <div class="container py-5 rtl text-align-direction">
        <h2 class="text-center mb-3 headerTitle">{{translate('healthHive_refund_policy')}}</h2>
        <div class="card __card">
            <div class="card-body text-justify text-center">
                <h5>
                    At HealthHive, your satisfaction is our top priority. We aim to provide you with the best quality medical products and a seamless shopping experience. If you need to return a product, please review our return policy below.
                </h5>
                <br />
                <h2>Eligibility for Returns</h2>
                <ul>
                    <li><strong>Timeframe:</strong> Returns must be initiated within <strong>7 days</strong> of receiving the product.</li>
                    <li><strong>Condition:</strong> The product must be unused, unopened, and in its original packaging with all seals intact.</li>
                    <li><strong>Exclusions:</strong> For safety and hygiene reasons, certain items such as syringes, opened medicines, and customized medical equipment cannot be returned.</li>
                </ul>
                <h2>Return Process</h2>
                <ol>
                    <li>
                        <strong>Initiate a Return:</strong>
                        <ul>
                            <li>Log in to your HealthHive account and go to the "Orders" section.</li>
                            <li>Select the product you wish to return and click on "Request Return."</li>
                        </ul>
                    </li>
                    <li><strong>Approval:</strong> Once your request is reviewed, you’ll receive an approval email with further instructions.</li>
                    <li>
                        <strong>Shipping the Item:</strong>
                        <ul>
                            <li>Use the original packaging to return the product.</li>
                            <li>Include the invoice and any accessories received with the product.</li>
                        </ul>
                    </li>
                    <li><strong>Inspection and Refund:</strong> Upon receiving the returned item, we will inspect it for compliance with our return policy. Refunds will be processed to your original payment method within <strong>7-10 business days</strong> after approval.</li>
                </ol>
                <h2>Refund Terms</h2>
                <ul>
                    <li><strong>Partial Refunds:</strong> If the product is returned in a condition not compliant with the policy, a partial refund may be issued at our discretion.</li>
                    <li><strong>Non-Refundable Charges:</strong> Delivery charges and any COD fees are non-refundable.</li>
                </ul>
                <h2>Damaged or Incorrect Items</h2>
                <p>
                    If you receive a damaged or incorrect product, please report it to us within <strong>48 hours</strong> of delivery. We will arrange for a replacement or refund without additional costs.
                </p>
            </div>
        </div>
    </div>
@endsection
