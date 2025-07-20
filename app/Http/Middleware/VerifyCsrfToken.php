<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * Indicates whether the XSRF-TOKEN cookie should be set on the response.
     *
     * @var bool
     */
    protected $addHttpCookie = true;

    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        '/pay-via-ajax', '/success', '/cancel', '/fail', '/ipn', '/bkash/*',
        '/paytabs-response', '/customer/choose-shipping-address', '/system_settings',
        '/paytm*', 'payment/paytabs/callback*','verified','apply-to-job','stripe/webhook','/products/stripe/webhook','/api/products/stripe/webhook','api/v1/products/stripe/webhook','/api/stripe/webhook','send-otp','burzakh/send-otp','burzakh/reset-password','reset-password'
    ];
}
