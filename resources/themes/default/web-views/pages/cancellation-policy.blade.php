@extends('layouts.front-end.app')

@section('title',translate('cancellation_policy'))

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
        <h2 class="text-center mb-3 headerTitle">{{ translate('cancellation_policy') }}</h2>
        <div class="card __card">
            <div class="card-body text-justify text-center">
                <!-- {!! $cancellationPolicy['content'] !!} -->
                <p>
                    We understand that sometimes plans change, and you may need to cancel an order. At HealthHive, we strive to make this process as easy as possible. Please read the cancellation policy below:
                </p>

                <h2>Eligibility for Cancellation</h2>
                <ul>
                    <li>Orders can only be canceled before they are shipped. Once the order has been dispatched, it cannot be canceled.</li>
                    <li>Customized or made-to-order products are not eligible for cancellation.</li>
                </ul>

                <h2>Cancellation Process</h2>
                <ol>
                    <li>
                        <strong>Request Cancellation:</strong>
                        <ul>
                            <li>Log in to your HealthHive account and navigate to the "Orders" section.</li>
                            <li>Select the order you wish to cancel and click on "Request Cancellation."</li>
                        </ul>
                    </li>
                    <li><strong>Approval:</strong> If the cancellation is eligible, you will receive a confirmation email or notification.</li>
                </ol>

                <h2>Refund Terms</h2>
                <ul>
                    <li>For prepaid orders, refunds will be initiated within <strong>3-5 business days</strong> after cancellation approval.</li>
                    <li>Refunds will be credited to the original payment method.</li>
                </ul>
            </div>
        </div>
    </div>
@endsection
