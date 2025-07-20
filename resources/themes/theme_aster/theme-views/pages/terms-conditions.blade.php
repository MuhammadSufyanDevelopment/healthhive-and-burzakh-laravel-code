@extends('theme-views.layouts.app')

@section('title', translate('Terms_&_Conditions').' | '.$web_config['company_name'].' '.translate('ecommerce'))

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
                <h1 class="absolute-white text-center text-capitalize">{{ translate('terms_&_conditions') }}</h1>
            </div>
        </div>
        <div class="container">
            <div class="card my-4">
                <div class="card-body p-lg-4 text-dark page-paragraph">
                <div class="container">
        <h1>Terms and Conditions</h1>
        <p>Effective Date: [Insert Date]</p>
        
        <h2>1. Introduction</h2>
        <p>Welcome to HealthHive. By using our services, you agree to abide by the following terms and conditions.</p>
        
        <h2>2. User Accounts</h2>
        <p>To access certain features, you must create an account. You are responsible for maintaining the confidentiality of your login credentials.</p>
        
        <h2>3. Acceptable Use</h2>
        <p>You agree not to:
            <ul>
                <li>Use the platform for unlawful purposes</li>
                <li>Post misleading or false information</li>
                <li>Engage in fraudulent or harmful activities</li>
            </ul>
        </p>
        
        <h2>4. Transactions & Payments</h2>
        <p>All purchases and payments made through HealthHive must comply with our policies. Refunds and disputes will be handled per our refund policy.</p>
        
        <h2>5. Privacy & Data Protection</h2>
        <p>We respect your privacy. Please review our <a href="privacy-policy.html">Privacy Policy</a> for details on how we collect and use your data.</p>
        
        <h2>6. Termination</h2>
        <p>We reserve the right to suspend or terminate your account if you violate these terms.</p>
        
        <h2>7. Limitation of Liability</h2>
        <p>We are not responsible for any losses or damages resulting from your use of HealthHive.</p>
        
        <h2>8. Changes to Terms</h2>
        <p>We may update these terms from time to time. Continued use of our services after changes means you accept the new terms.</p>
        
        <h2>9. Contact Us</h2>
        <p>If you have any questions, please contact us at <strong>admin@healthHive.me</strong>.</p>
    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
