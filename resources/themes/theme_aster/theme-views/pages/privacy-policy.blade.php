@extends('theme-views.layouts.app')

@section('title', translate('privacy_Policy').' | '.$web_config['company_name'].' '.translate('ecommerce'))

@section('content')
<style>
        h1, h2 {
            color: #333;
        }
        p {
            color: #666;
        }
</style>
    <main class="main-content d-flex flex-column gap-3 pb-3">
        <div class="page-title overlay py-5 __opacity-half background-custom-fit"
             data-bg-img = {{getStorageImages(path: imagePathProcessing(imageData: (isset($pageTitleBanner['value']) ?json_decode($pageTitleBanner['value'])?->image : null),path: 'banner'),source: theme_asset('assets/img/media/page-title-bg.png'))}}>
        <div class="container">
                <h1 class="absolute-white text-center text-capitalize">{{translate('privacy_policy')}}</h1>
            </div>
        </div>
        <div class="container">
            <div class="card my-4">
                <div class="card-body p-lg-4 text-dark page-paragraph">
                <div class="container">
        <h1>Privacy Policy</h1>
        <p>Effective Date: [Insert Date]</p>
        
        <h2>1. Introduction</h2>
        <p>Welcome to HealthHive. Your privacy is important to us. This Privacy Policy explains how we collect, use, and protect your personal information.</p>
        
        <h2>2. Information We Collect</h2>
        <p>We may collect the following types of data:
            <ul>
                <li>Personal Identification Information (Name, Email, Phone Number, etc.)</li>
                <li>Usage Data (App interactions, preferences, etc.)</li>
                <li>Device Information (IP address, browser type, etc.)</li>
            </ul>
        </p>
        
        <h2>3. How We Use Your Information</h2>
        <p>We use your data to:
            <ul>
                <li>Provide and improve our services</li>
                <li>Process transactions</li>
                <li>Communicate with users</li>
                <li>Enhance security and prevent fraud</li>
            </ul>
        </p>
        
        <h2>4. Data Security</h2>
        <p>We take appropriate security measures to protect your personal data from unauthorized access, alteration, or disclosure.</p>
        
        <h2>5. Third-Party Services</h2>
        <p>We may share your information with trusted third parties for analytics, payment processing, or customer support.</p>
        
        <h2>6. Your Rights</h2>
        <p>You have the right to access, modify, or delete your personal data. Contact us if you wish to exercise these rights.</p>
        
        <h2>7. Changes to This Policy</h2>
        <p>We may update this policy periodically. Any changes will be posted on this page.</p>
        
        <h2>8. Contact Us</h2>
        <p>If you have any questions, please contact us at <strong>admin@healthHive.me</strong>.</p>
    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
