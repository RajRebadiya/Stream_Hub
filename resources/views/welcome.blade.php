<!DOCTYPE html>
<html lang="en">

<head>
    <!-- ========== Meta Tags ========== -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="StreamSave - Premium OTT Subscriptions at Unbeatable Prices">

    <!-- ========== Page Title ========== -->
    <title>StreamSave - Premium OTT Subscriptions at Affordable Prices</title>

    <!-- ========== Favicon Icon ========== -->
    <link rel="shortcut icon" href="{{ 'assets/img/favicon.png' }}" type="image/x-icon">

    <!-- ========== Start Stylesheet ========== -->
    <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/font-awesome.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/themify-icons.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/elegant-icons.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/flaticon-set.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/magnific-popup.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/swiper-bundle.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/animate.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/validnavs.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/helper.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/unit-test.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    {{-- <link href="style.css" rel="stylesheet"> --}}
    <!-- ========== End Stylesheet ========== -->

    <style>
        /* Auth Modal Styles */
        .auth-modal-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.8);
            backdrop-filter: blur(5px);
            z-index: 99999;
            align-items: center;
            justify-content: center;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .auth-modal-overlay.active {
            display: flex;
            opacity: 1;
        }

        .auth-modal-container {
            background: #fff;
            border-radius: 20px;
            width: 90%;
            max-width: 480px;
            position: relative;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            transform: scale(0.9);
            transition: transform 0.3s ease;
            max-height: 90vh;
            overflow-y: auto;
        }

        .auth-modal-overlay.active .auth-modal-container {
            transform: scale(1);
        }

        .auth-modal-close {
            position: absolute;
            top: 20px;
            right: 20px;
            background: #f5f5f5;
            border: none;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
            z-index: 10;
        }

        .auth-modal-close:hover {
            background: #ff4757;
            color: #fff;
            transform: rotate(90deg);
        }

        .auth-modal-content {
            padding: 50px 40px 40px;
        }

        /* Platform Selection Styles */
        .platforms-grid {
            margin: 30px 0;
            max-height: 400px;
            overflow-y: auto;
            padding-right: 10px;
        }

        .platforms-grid::-webkit-scrollbar {
            width: 6px;
        }

        .platforms-grid::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }

        .platforms-grid::-webkit-scrollbar-thumb {
            background: #667eea;
            border-radius: 10px;
        }

        .platform-section {
            margin-bottom: 35px;
            background: linear-gradient(135deg, #f5f7ff 0%, #f0f3ff 100%);
            border-radius: 16px;
            padding: 20px;
            border: 2px solid #e8edf7;
        }

        .platform-header {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid #e8e8e8;
        }

        .platform-logo {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            object-fit: cover;
            box-shadow: 0 4px 10px rgba(102, 126, 234, 0.2);
        }

        .platform-info h4 {
            margin: 0;
            font-size: 16px;
            font-weight: 700;
            color: #2d3436;
        }

        .plan-cards {
            display: grid;
            grid-template-columns: 1fr;
            gap: 12px;
        }

        .plan-card {
            background: #fff;
            border: 2px solid #e8e8e8;
            border-radius: 12px;
            padding: 15px;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .plan-card:hover {
            border-color: #667eea;
            box-shadow: 0 8px 20px rgba(102, 126, 234, 0.15);
            transform: translateY(-2px);
        }

        .plan-card.selected {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-color: #667eea;
            color: #fff;
        }

        .plan-card.selected .plan-name,
        .plan-card.selected .plan-duration,
        .plan-card.selected .plan-quality,
        .plan-card.selected .plan-price {
            color: #fff;
        }

        .plan-details {
            flex: 1;
        }

        .plan-name {
            font-size: 15px;
            font-weight: 700;
            margin: 0 0 5px 0;
            color: #2d3436;
            transition: color 0.3s ease;
        }

        .plan-meta {
            display: flex;
            gap: 15px;
            font-size: 13px;
            color: #636e72;
        }

        .plan-duration,
        .plan-quality {
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .plan-card.selected .plan-meta {
            color: rgba(255, 255, 255, 0.9);
        }

        .plan-price {
            font-size: 16px;
            font-weight: 700;
            color: #667eea;
            text-align: right;
            margin-left: 20px;
            transition: color 0.3s ease;
        }

        .plan-card.selected .plan-price {
            color: #fff;
        }

        .plan-checkbox {
            width: 20px;
            height: 20px;
            cursor: pointer;
            accent-color: #667eea;
        }

        .plan-card.selected .plan-checkbox {
            accent-color: #fff;
        }

        .selection-summary {
            background: linear-gradient(135deg, #f5f7ff 0%, #f0f3ff 100%);
            border: 2px solid #e8edf7;
            border-radius: 12px;
            padding: 15px;
            margin: 20px 0;
            display: none;
        }

        .selection-summary.active {
            display: block;
        }

        .summary-title {
            font-size: 13px;
            font-weight: 600;
            color: #636e72;
            margin-bottom: 10px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .selected-plans-list {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-bottom: 10px;
        }

        .plan-badge {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #fff;
            padding: 6px 12px;
            border-radius: 50px;
            font-size: 12px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .plan-badge .remove {
            cursor: pointer;
            font-weight: bold;
            margin-left: 4px;
        }

        .summary-total {
            display: flex;
            justify-content: space-between;
            padding-top: 10px;
            border-top: 1px solid #ddd;
            font-weight: 700;
            color: #667eea;
            font-size: 15px;
        }

        .error-message {
            background: #ffe5e5;
            color: #d32f2f;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 15px;
            display: none;
            font-size: 13px;
            border-left: 4px solid #d32f2f;
        }

        .error-message.show {
            display: block;
        }

        .plans-loading {
            text-align: center;
            padding: 30px;
            color: #667eea;
        }

        .spinner {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 3px solid rgba(102, 126, 234, 0.3);
            border-radius: 50%;
            border-top-color: #667eea;
            animation: spin 0.8s linear infinite;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        .auth-tabs {
            display: flex;
            gap: 10px;
            margin-bottom: 30px;
            background: #f8f9fa;
            border-radius: 50px;
            padding: 5px;
        }

        .auth-tab {
            flex: 1;
            padding: 12px 20px;
            border: none;
            background: transparent;
            border-radius: 50px;
            font-weight: 600;
            font-size: 15px;
            cursor: pointer;
            transition: all 0.3s ease;
            color: #666;
        }

        .auth-tab.active {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #fff;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
        }

        .auth-form-wrapper {
            display: none;
            animation: fadeIn 0.3s ease;
        }

        .auth-form-wrapper.active {
            display: block;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .auth-form-wrapper h3 {
            font-size: 28px;
            font-weight: 700;
            color: #2d3436;
            margin-bottom: 8px;
        }

        .auth-subtitle {
            color: #636e72;
            margin-bottom: 25px;
            font-size: 14px;
        }

        .auth-form .form-group {
            margin-bottom: 20px;
        }

        .auth-form label {
            display: block;
            margin-bottom: 8px;
            color: #2d3436;
            font-weight: 600;
            font-size: 14px;
        }

        .auth-form label i {
            color: #667eea;
            margin-right: 5px;
        }

        .auth-form .form-control {
            width: 100%;
            padding: 14px 18px;
            border: 2px solid #e8e8e8;
            border-radius: 12px;
            font-size: 15px;
            transition: all 0.3s ease;
            background: #f8f9fa;
        }

        .auth-form .form-control:focus {
            outline: none;
            border-color: #667eea;
            background: #fff;
            box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
        }

        .form-options {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            font-size: 14px;
        }

        .remember-me {
            display: flex;
            align-items: center;
            gap: 8px;
            margin: 0;
            font-weight: 500;
            color: #636e72;
        }

        .remember-me input {
            width: 18px;
            height: 18px;
            cursor: pointer;
        }

        .forgot-password {
            color: #667eea;
            text-decoration: none;
            font-weight: 600;
            transition: color 0.3s ease;
        }

        .forgot-password:hover {
            color: #764ba2;
        }

        .btn-auth {
            width: 100%;
            padding: 15px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #fff;
            border: none;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
        }

        .btn-auth:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.5);
        }

        .btn-auth:active {
            transform: translateY(0);
        }

        .social-login {
            margin-top: 25px;
            text-align: center;
        }

        .social-login p {
            color: #636e72;
            font-size: 14px;
            margin-bottom: 15px;
            position: relative;
        }

        .social-login p::before,
        .social-login p::after {
            content: '';
            position: absolute;
            top: 50%;
            width: 30%;
            height: 1px;
            background: #e8e8e8;
        }

        .social-login p::before {
            left: 0;
        }

        .social-login p::after {
            right: 0;
        }

        .social-buttons {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
        }

        .btn-social {
            padding: 12px;
            border: 2px solid #e8e8e8;
            border-radius: 10px;
            background: #fff;
            cursor: pointer;
            font-weight: 600;
            font-size: 14px;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .btn-social:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .btn-social.google {
            color: #ea4335;
        }

        .btn-social.google:hover {
            border-color: #ea4335;
            background: #fff5f5;
        }

        .btn-social.facebook {
            color: #1877f2;
        }

        .btn-social.facebook:hover {
            border-color: #1877f2;
            background: #f0f7ff;
        }

        .terms-checkbox {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            margin: 0;
            font-size: 14px;
            color: #636e72;
            font-weight: 400;
        }

        .terms-checkbox input {
            margin-top: 3px;
            width: 18px;
            height: 18px;
            cursor: pointer;
        }

        .terms-checkbox a {
            color: #667eea;
            text-decoration: none;
            font-weight: 600;
        }

        .terms-checkbox a:hover {
            color: #764ba2;
        }

        /* Responsive */
        @media (max-width: 576px) {
            .auth-modal-content {
                padding: 40px 25px 30px;
            }

            .auth-form-wrapper h3 {
                font-size: 24px;
            }

            .social-buttons {
                grid-template-columns: 1fr;
            }
        }
    </style>

</head>

<body>

    <!--[if lte IE 9]>
        <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="https://browsehappy.com/">upgrade your browser</a> to improve your experience and security.</p>
    <![endif]-->

    <!-- Start Preloader
    ============================================= -->
    <div id="preloader">
        <div class="tranzi-loader-inner">
            <div class="tranzi-loader">
                <span class="tranzi-loader-item"></span>
                <span class="tranzi-loader-item"></span>
                <span class="tranzi-loader-item"></span>
                <span class="tranzi-loader-item"></span>
                <span class="tranzi-loader-item"></span>
                <span class="tranzi-loader-item"></span>
                <span class="tranzi-loader-item"></span>
                <span class="tranzi-loader-item"></span>
            </div>
        </div>
    </div>
    <!-- preloader end -->


    <!-- Header
    ============================================= -->
    <header>
        <!-- Start Navigation -->
        <nav class="navbar mobile-sidenav navbar-sticky navbar-default validnavs navbar-fixed dark no-background">

            <!-- Start Top Search -->
            <div class="top-search">
                <div class="container-xl">
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-search"></i></span>
                        <input type="text" class="form-control" placeholder="Search">
                        <span class="input-group-addon close-search"><i class="fa fa-times"></i></span>
                    </div>
                </div>
            </div>
            <!-- End Top Search -->

            <div class="container d-flex justify-content-between align-items-center">


                <!-- Start Header Navigation -->
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbar-menu">
                        <i class="fa fa-bars"></i>
                    </button>
                    <a class="navbar-brand" href="#home">
                        <img src="{{ asset('assets/img/logo.png') }}" class="logo" alt="Logo">
                    </a>
                </div>
                <!-- End Header Navigation -->

                <!-- Collect the nav links, forms, and other content for toggling -->
                <div class="collapse navbar-collapse" id="navbar-menu">

                    <div class="collapse-header">
                        <img src="{{ asset('assets/img/logo.png') }}" alt="Logo">
                        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbar-menu">
                            <i class="fa fa-times"></i>
                        </button>
                    </div>

                    <ul class="nav navbar-nav navbar-center" data-in="fadeInDown" data-out="fadeOutUp">
                        <li class="dropdown">
                            <a href="#home">Home</a>

                        </li>
                        <li>
                            <a href="#services">Platforms</a>
                        </li>
                        <li>
                            <a href="#about">About</a>
                        </li>
                        <li>
                            <a href="#process">How It Works</a>
                        </li>
                        <li>
                            <a href="#skills">Why Choose Us</a>
                        </li>

                    </ul>
                </div><!-- /.navbar-collapse -->

                <div class="attr-right">
                    <!-- Start Atribute Navigation -->
                    <div class="attr-nav">
                        <ul>
                            {{-- <li class="search"><a href="#"><i class="far fa-search"></i></a></li> --}}
                            <li class="button">
                                <a href="#" id="subscribeBtn">Subscribe Now</a>
                            </li>
                        </ul>
                    </div>
                    <!-- End Atribute Navigation -->
                </div>


            </div>
            <!-- Overlay screen for menu -->
            <div class="overlay-screen"></div>
            <!-- End Overlay screen for menu -->
        </nav>
        <!-- End Navigation -->
    </header>
    <!-- End Header -->

    <!-- Start Banner Area
    ============================================= -->
    <div id="home" class="banner-style-five-area bg-cover"
        style="background-image: url(assets/img/shape/banner-4.jpg);">
        <div class="container">
            <div class="row align-center">
                <div class="col-lg-6">
                    <div class="banner-style-five-info">
                        {{-- <h2>Stream Your Favorite</h2>
                        <h2>Shows & Movies</h2> --}}
                        <div class="content">
                            <h2><strong>Stream More - Pay Less</strong></h2>
                            <p>
                                Access premium OTT platforms including Netflix, Amazon Prime, Disney+ Hotstar, YouTube
                                Premium and more at incredibly affordable rates without compromising quality.
                            </p>
                            <div class="button">
                                <a class="btn btn-theme btn-md radius animation" href="contact-us.html">View Plans</a>
                                <a class="btn btn-border-dark btn-md radius animation" href="about-us.html">Learn
                                    More</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="banner-style-five-thumb">
                        <img src="assets/img/illustration/7.png" alt="Image Not Found">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Main -->

    <!-- Start Services
    ============================================= -->
    <div id="services" class="services-style-five-area default-padding bottom-less">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 offset-lg-2">
                    <div class="site-heading text-center">
                        <h4 class="sub-title">Premium OTT Platforms</h4>
                        <h2 class="title split-text">Popular streaming services <br> at discounted prices</h2>
                        <div class="devider"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="row">
                <!-- Single Item -->
                <div class="col-xl-4 col-lg-6 col-md-6 mb-30 services-style-five hover-active-item active">
                    <div class="item">
                        <div class="shape">
                            <img src="{{ 'assets/img/shape/25.png' }}" alt="Image Not Found">
                        </div>
                        <div class="icon">
                            <img src="{{ 'assets/img/icon/34.png' }}" alt="Image Not Found">
                        </div>
                        <h4><a href="#">Netflix Premium</a></h4>
                        <p>
                            Enjoy unlimited movies, TV shows, and documentaries in Ultra HD quality. Watch on multiple
                            devices simultaneously with our affordable Netflix subscriptions.
                        </p>
                        <ul>
                            <li>4K Ultra HD Streaming</li>
                            <li>Multiple Screen Access</li>
                            <li>Download & Watch Offline</li>
                        </ul>
                    </div>
                </div>
                <!-- End Single Item -->
                <!-- Single Item -->
                <div class="col-xl-4 col-lg-6 col-md-6 mb-30 services-style-five hover-active-item">
                    <div class="item">
                        <div class="shape">
                            <img src="{{ 'assets/img/shape/25.png' }}" alt="Image Not Found">
                        </div>
                        <div class="icon">
                            <img src="{{ 'assets/img/icon/35.png' }}" alt="Image Not Found">
                        </div>
                        <h4><a href="#">Amazon Prime Video</a></h4>
                        <p>
                            Access thousands of movies, exclusive Prime Originals, and live sports. Get premium
                            entertainment plus free delivery benefits at discounted rates.
                        </p>
                        <ul>
                            <li>Prime Original Series</li>
                            <li>Free Fast Delivery</li>
                            <li>Live Sports Coverage</li>
                        </ul>
                    </div>
                </div>
                <!-- End Single Item -->
                <!-- Single Item -->
                <div class="col-xl-4 col-lg-6 col-md-6 mb-30 services-style-five hover-active-item">
                    <div class="item">
                        <div class="shape">
                            <img src="{{ 'assets/img/shape/25.png' }}" alt="Image Not Found">
                        </div>
                        <div class="icon">
                            <img src="{{ 'assets/img/icon/36.png' }}" alt="Image Not Found">
                        </div>
                        <h4><a href="#">Disney+ Hotstar</a></h4>
                        <p>
                            Stream latest movies, Disney classics, Marvel shows, and live cricket matches. Get premium
                            access to India's leading OTT platform at the best prices.
                        </p>
                        <ul>
                            <li>Live Cricket & Sports</li>
                            <li>Disney+ Originals</li>
                            <li>Regional Content Library</li>
                        </ul>
                    </div>
                </div>
                <!-- End Single Item -->
            </div>
        </div>
    </div>
    <!-- End Services -->

    <!-- Start Mission Vision Area
    ============================================= -->
    <div id="about" class="mission-vison-area default-padding bg-gray bg-cover"
        style="background-image: url(assets/img/shape/banner-6.jpg);">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 offset-lg-2">
                    <div class="site-heading text-center">
                        <h4 class="sub-title">About StreamSave</h4>
                        <h2 class="title split-text">Your trusted partner for <br> affordable OTT subscriptions</h2>
                        <div class="devider"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="mission-vision-items">

                        <div class="mission-vission-navs text-center">
                            <ul class="nav nav-tabs" id="myTab" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="tab_1" data-bs-toggle="tab"
                                        data-bs-target="#tabs_1" type="button" role="tab"
                                        aria-controls="tabs_1" aria-selected="true">Our Mission</button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active" id="tab_2" data-bs-toggle="tab"
                                        data-bs-target="#tabs_2" type="button" role="tab"
                                        aria-controls="tabs_2" aria-selected="false">Our Vision</button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="tab_3" data-bs-toggle="tab"
                                        data-bs-target="#tabs_3" type="button" role="tab"
                                        aria-controls="tabs_3" aria-selected="false">Core Values</button>
                                </li>
                            </ul>
                        </div>

                        <div class="mission-style-two-tab-content">
                            <div class="tab-content" id="myTabContent">
                                <div class="tab-pane fade" id="tabs_1" role="tabpanel" aria-labelledby="tab_1">
                                    <div class="mission-vision-item">
                                        <div class="row align-center">
                                            <div class="col-lg-6">
                                                <h2>Making premium entertainment accessible to everyone</h2>
                                                <p>
                                                    We believe that everyone deserves access to quality entertainment
                                                    without breaking the bank. Our mission is to provide legitimate OTT
                                                    subscriptions at prices that work for every budget, ensuring you
                                                    never miss your favorite content.
                                                </p>
                                                <ul class="list-style-one">
                                                    <li>Affordable pricing for all premium platforms</li>
                                                    <li>100% genuine and legal subscriptions</li>
                                                    <li>Instant activation and reliable support</li>
                                                </ul>
                                            </div>
                                            <div class="col-lg-5 offset-lg-1">
                                                <div class="thumb">
                                                    <img src="{{ 'assets/img/illustration/8.png' }}"
                                                        alt="Image Not Found">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade show active" id="tabs_2" role="tabpanel"
                                    aria-labelledby="tab_2">
                                    <div class="mission-vision-item">
                                        <div class="row align-center">
                                            <div class="col-lg-6">
                                                <h2>Becoming India's most trusted OTT subscription provider</h2>
                                                <p>
                                                    Our vision is to revolutionize how people access streaming services
                                                    in India. We aim to be the go-to platform where millions can enjoy
                                                    premium content from Netflix, Prime Video, Disney+ Hotstar, YouTube
                                                    Premium, and more at unbeatable prices.
                                                </p>
                                                <ul class="list-style-one">
                                                    <li>Largest collection of OTT platforms</li>
                                                    <li>Customer satisfaction guaranteed</li>
                                                    <li>Transparent pricing with no hidden costs</li>
                                                </ul>
                                            </div>
                                            <div class="col-lg-5 offset-lg-1">
                                                <div class="thumb">
                                                    <img src="{{ 'assets/img/illustration/12.png' }}"
                                                        alt="Image Not Found">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="tabs_3" role="tabpanel" aria-labelledby="tab_3">
                                    <div class="mission-vision-item">
                                        <div class="row align-center">
                                            <div class="col-lg-6">
                                                <h2>Built on trust, transparency, and customer satisfaction</h2>
                                                <p>
                                                    We operate with complete transparency, offering only legitimate
                                                    subscriptions through authorized channels. Our commitment to quality
                                                    service, honest pricing, and customer care sets us apart in the
                                                    industry.
                                                </p>
                                                <ul class="list-style-one">
                                                    <li>100% authentic and legal services</li>
                                                    <li>24/7 customer support assistance</li>
                                                    <li>Money-back satisfaction guarantee</li>
                                                </ul>
                                            </div>
                                            <div class="col-lg-5 offset-lg-1">
                                                <div class="thumb">
                                                    <img src="{{ 'assets/img/illustration/13.png' }}"
                                                        alt="Image Not Found">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Mission Vision Area -->

    <!-- Start Why Choose Us
    ============================================= -->
    <div class="choose-us-style-three-area default-padding">
        <div class="container">
            <div class="row">
                <div class="col-xl-5">
                    <div class="choose-us-style-three-thumb">
                        <img src="{{ 'assets/img/illustration/10.png' }}" alt="Image Not Found">
                    </div>
                </div>
                <div class="col-xl-6 offset-xl-1">
                    <div class="choose-us-style-three-info">
                        <h2 class="title">Why thousands choose StreamSave for their entertainment needs</h2>
                        <p>
                            We have established ourselves as India's most reliable OTT subscription provider by
                            delivering authentic services at competitive prices. Our streamlined process ensures you get
                            instant access to premium platforms without any hassle, supported by our dedicated customer
                            care team.
                        </p>
                        <div class="d-flex">
                            <div class="left">
                                <h4>Our Premium Features</h4>
                                <ul class="list-style-one">
                                    <li>Instant Account Activation</li>
                                    <li>Original Quality Streaming</li>
                                    <li>Multiple Platform Support</li>
                                    <li>100% Secure Transactions</li>
                                </ul>
                            </div>
                            <div class="fun-fact">
                                <div class="counter">
                                    <div class="timer" data-to="25" data-speed="2000">25</div>
                                    <div class="operator">K+</div>
                                </div>
                                <span class="medium">Happy Subscribers</span>
                            </div>
                        </div>
                        <div class="progress-box">
                            <h5>98% - Customer Satisfaction Rate</h5>
                            <div class="progress">
                                <div class="progress-bar" role="progressbar" data-width="98"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Why Choose Us -->

    <!-- Start Process
    ============================================= -->
    <div id="process" class="process-style-three-area default-padding bg-gray">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 offset-lg-2">
                    <div class="site-heading text-center">
                        <h4 class="sub-title">Simple Process</h4>
                        <h2 class="title split-text">How to get your <br> OTT subscription</h2>
                        <div class="devider"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="row">
                <div class="col-xl-8 offset-xl-2 col-lg-10 offset-lg-1">
                    <div class="process-style-three-items">

                        <!-- Single Item -->
                        <div class="process-style-three">
                            <h4>Choose Your Platform</h4>
                            <div class="info">
                                <div class="icon">
                                    <img src="{{ 'assets/img/icon/38.png' }}" alt="Image Not Found">
                                </div>
                                <p>
                                    Select from our wide range of OTT platforms including Netflix, Prime Video, Disney+
                                    Hotstar, YouTube Premium, and many more.
                                </p>
                            </div>
                        </div>
                        <!-- End Single Item -->
                        <!-- Single Item -->
                        <div class="process-style-three">
                            <h4>Select Your Plan</h4>
                            <div class="info">
                                <div class="icon">
                                    <img src="{{ 'assets/img/icon/39.png' }}" alt="Image Not Found">
                                </div>
                                <p>
                                    Pick the subscription plan that fits your needs and budget. All plans come with
                                    original quality and full features.
                                </p>
                            </div>
                        </div>
                        <!-- End Single Item -->
                        <!-- Single Item -->
                        <div class="process-style-three">
                            <h4>Make Payment</h4>
                            <div class="info">
                                <div class="icon">
                                    <img src="{{ 'assets/img/icon/40.png' }}" alt="Image Not Found">
                                </div>
                                <p>
                                    Complete your secure payment through our trusted payment gateway. We accept UPI,
                                    cards, and net banking.
                                </p>
                            </div>
                        </div>
                        <!-- End Single Item -->
                        <!-- Single Item -->
                        <div class="process-style-three">
                            <h4>Start Streaming</h4>
                            <div class="info">
                                <div class="icon">
                                    <img src="{{ 'assets/img/icon/41.png' }}" alt="Image Not Found">
                                </div>
                                <p>
                                    Get instant access to your subscription. Login credentials are delivered within
                                    minutes and you're ready to stream!
                                </p>
                            </div>
                        </div>
                        <!-- End Single Item -->

                        <div class="process-arrow">
                            <img src="{{ 'assets/img/shape/process-arrow.svg' }}" alt="Image Not Found">
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Process -->

    <!-- Start Skill Facts
    ============================================= -->
    <div id="skills" class="skill-facts-area default-padding bg-dark text-light"
        style="background-image: url(assets/img/shape/49.png);">
        <div class="container">
            <div class="row align-center">
                <div class="col-lg-5">
                    <div class="skill-fact-thumb">
                        <img src="{{ 'assets/img/illustration/11.png' }}" alt="Image Not Found">
                    </div>
                </div>
                <div class="col-lg-7 pl-60 pl-md-15 pl-xs-15">
                    <div class="skill-fact-info">
                        <h4 class="sub-title">Why Choose StreamSave</h4>
                        <h2 class="title">We deliver excellence in every subscription</h2>
                        <p>
                            Our commitment to providing genuine OTT subscriptions at affordable prices has made us the
                            preferred choice for thousands of streaming enthusiasts across India. We ensure original
                            quality, instant activation, and reliable customer support for every platform we offer.
                        </p>
                        <div class="circle-progress">
                            <div class="progressbar">
                                <div class="circle" data-percent="95">
                                    <strong></strong>
                                </div>
                                <h4>Premium Quality</h4>
                            </div>
                            <div class="progressbar">
                                <div class="circle" data-percent="98">
                                    <strong></strong>
                                </div>
                                <h4>Customer Satisfaction</h4>
                            </div>
                            <div class="progressbar">
                                <div class="circle" data-percent="92">
                                    <strong></strong>
                                </div>
                                <h4>Instant Delivery</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Skill Facts -->

    <!-- Start Footer
    ============================================= -->
    <footer class="bg-dark text-light bg-cover" style="background-image: url(assets/img/shape/banner-8.jpg);">
        <div class="footer-shape">
            <div class="item">
                <img src="{{ 'assets/img/shape/7.png' }}" alt="Shape">
            </div>
            <div class="item">
                <img src="{{ 'assets/img/shape/9.png' }}" alt="Shape">
            </div>
        </div>
        <div class="container">
            <div class="f-items relative pt-70 pb-120 pt-xs-0 pb-xs-50">
                <div class="row">
                    <div class="col-lg-4 col-md-6 footer-item pr-50 pr-xs-15">
                        <div class="f-item about">
                            <img class="logo" src="{{ 'assets/img/logo-light-solid.png' }}" alt="Logo">
                            <p>
                                Your trusted partner for affordable OTT subscriptions. Stream your favorite content
                                without breaking the bank.
                            </p>
                            <div class="opening-hours">
                                <h5>Support Hours</h5>
                                <ul>
                                    <li>
                                        <div class="working-day">Monday – Saturday:</div>
                                        <div class="marker"></div>
                                        <div class="working-hour">9am – 9pm</div>
                                    </li>
                                    <li>
                                        <div class="working-day">Sunday:</div>
                                        <div class="marker"></div>
                                        <div class="working-hour">10am – 6pm</div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-6 footer-item">
                        <div class="f-item link">
                            <h4 class="widget-title">Quick Links</h4>
                            <ul>
                                <li>
                                    <a href="about-us.html">About Us</a>
                                </li>
                                <li>
                                    <a href="faq.html">FAQ</a>
                                </li>
                                <li>
                                    <a href="about-us.html">How It Works</a>
                                </li>
                                <li>
                                    <a href="pricing.html">Pricing Plans</a>
                                </li>
                                <li>
                                    <a href="contact-us.html">Contact Support</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-6 footer-item">
                        <div class="f-item link">
                            <h4 class="widget-title">Popular Platforms</h4>
                            <ul>
                                <li>
                                    <a href="services-details.html">Netflix Premium</a>
                                </li>
                                <li>
                                    <a href="services-details.html">Amazon Prime Video</a>
                                </li>
                                <li>
                                    <a href="services-details.html">Disney+ Hotstar</a>
                                </li>
                                <li>
                                    <a href="services-details.html">YouTube Premium</a>
                                </li>
                                <li>
                                    <a href="services-details.html">Zee5 Premium</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 footer-item">
                        <h4 class="widget-title">Stay Updated</h4>
                        <p>
                            Subscribe to get the latest offers <br> and exclusive deals on OTT plans.
                        </p>
                        <div class="f-item newsletter">
                            <form action="#">
                                <input type="email" placeholder="Your Email" class="form-control" name="email">
                                <button type="submit">
                                    <svg width="20" height="18" viewBox="0 0 20 18" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path d="M1 17L17 1H7.8" stroke="#232323" stroke-width="2"></path>
                                    </svg>
                                </button>
                            </form>
                        </div>
                        <ul class="footer-social">
                            <li>
                                <a href="#">
                                    <i class="fab fa-facebook-f"></i>
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    <i class="fab fa-twitter"></i>
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    <i class="fab fa-youtube"></i>
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    <i class="fab fa-instagram"></i>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Start Footer Bottom -->
        <div class="footer-bottom">
            <div class="container">
                <div class="row">
                    <div class="col-lg-6">
                        <p>&copy; Copyright 2026. All Rights Reserved by <a href="#">StreamSave</a></p>
                    </div>
                    <div class="col-lg-6 text-end">
                        <ul>
                            <li>
                                <a href="about-us.html">Terms of Service</a>
                            </li>
                            <li>
                                <a href="about-us.html">Privacy Policy</a>
                            </li>
                            <li>
                                <a href="contact-us.html">Support</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Footer Bottom -->

    </footer>
    <!-- End Footer -->

    <!-- Start Login/Register Modal -->
    <div class="auth-modal-overlay" id="authModal">
        <div class="auth-modal-container">
            <button class="auth-modal-close" id="closeModal">
                <i class="fa fa-times"></i>
            </button>

            <div class="auth-modal-content">
                <!-- Toggle Tabs -->
                <div class="auth-tabs">
                    <button class="auth-tab active" id="loginTab">Login</button>
                    <button class="auth-tab" id="registerTab">Register</button>
                </div>

                <!-- Login Form -->
                <div class="auth-form-wrapper active" id="loginForm">
                    <h3>Welcome Back!</h3>
                    <p class="auth-subtitle">Login to access your subscriptions</p>

                    <form class="auth-form">
                        <div class="form-group">
                            <label for="loginEmail">
                                <i class="fa fa-envelope"></i> Email Address
                            </label>
                            <input type="email" id="loginEmail" class="form-control"
                                placeholder="Enter your email" required>
                        </div>

                        <div class="form-group">
                            <label for="loginPassword">
                                <i class="fa fa-lock"></i> Password
                            </label>
                            <input type="password" id="loginPassword" class="form-control"
                                placeholder="Enter your password" required>
                        </div>

                        <div class="form-options">
                            <label class="remember-me">
                                <input type="checkbox"> Remember me
                            </label>
                            <a href="#" class="forgot-password">Forgot Password?</a>
                        </div>

                        <button type="submit" class="btn-auth">Login</button>

                        <div class="social-login">
                            <p>Or login with</p>
                            <div class="social-buttons">
                                <button type="button" class="btn-social google">
                                    <i class="fab fa-google"></i> Google
                                </button>
                                <button type="button" class="btn-social facebook">
                                    <i class="fab fa-facebook-f"></i> Facebook
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Register Form -->
                <div class="auth-form-wrapper" id="registerForm">
                    <h3>Create Your Account</h3>
                    <p class="auth-subtitle">Select platforms you want to subscribe to</p>

                    <form class="auth-form" id="registerFormElement">
                        <!-- Error Message -->
                        <div class="error-message" id="registerError"></div>

                        <!-- Personal Information -->
                        <div class="form-group">
                            <label for="registerName">
                                <i class="fa fa-user"></i> Full Name
                            </label>
                            <input type="text" id="registerName" class="form-control"
                                placeholder="Enter your full name" required>
                        </div>

                        <div class="form-group">
                            <label for="registerEmail">
                                <i class="fa fa-envelope"></i> Email Address
                            </label>
                            <input type="email" id="registerEmail" class="form-control"
                                placeholder="Enter your email" required>
                        </div>

                        <div class="form-group">
                            <label for="registerMobile">
                                <i class="fa fa-phone"></i> Mobile Number
                            </label>
                            <input type="tel" id="registerMobile" class="form-control"
                                placeholder="Enter 10-digit mobile number" required maxlength="10"
                                pattern="[0-9]{10}">
                        </div>

                        <div class="form-group">
                            <label for="registerPassword">
                                <i class="fa fa-lock"></i> Password
                            </label>
                            <input type="password" id="registerPassword" class="form-control"
                                placeholder="Create a strong password (min 8 characters)" required minlength="8">
                        </div>

                        <!-- Platform Selection -->
                        <div class="form-group">
                            <label style="margin-bottom: 15px;">
                                <i class="fa fa-tv" style="color: #667eea; margin-right: 5px;"></i>
                                <strong>Select Streaming Platforms</strong>
                                <span style="color: #d32f2f;">*</span>
                            </label>
                            <div class="platforms-grid" id="platformsGrid">
                                <div class="plans-loading">
                                    <div class="spinner"></div>
                                    <p>Loading available plans...</p>
                                </div>
                            </div>
                        </div>

                        <!-- Selection Summary -->
                        <div class="selection-summary" id="selectionSummary">
                            <div class="summary-title">Selected Plans</div>
                            <div class="selected-plans-list" id="selectedPlansList"></div>
                            <div class="summary-total">
                                <span>Total Amount:</span>
                                <span id="totalAmount">₹0</span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="terms-checkbox">
                                <input type="checkbox" id="registerTerms" required>
                                I agree to the <a href="#"
                                    style="color: #667eea; text-decoration: none; font-weight: 600;">Terms &
                                    Conditions</a>
                            </label>
                        </div>

                        <button type="submit" class="btn-auth" id="registerSubmitBtn">Create Account &
                            Subscribe</button>

                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- End Login/Register Modal -->

    <!-- jQuery Frameworks
    ============================================= -->
    <script src="{{ asset('assets/js/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/js/jquery.appear.js') }}"></script>
    <script src="{{ asset('assets/js/jquery.easing.min.js') }}"></script>
    <script src="{{ asset('assets/js/jquery.magnific-popup.min.js') }}"></script>
    <script src="{{ asset('assets/js/swiper-bundle.min.js') }}"></script>
    <script src="{{ asset('assets/js/wow.min.js') }}"></script>
    <script src="{{ asset('assets/js/progress-bar.min.js') }}"></script>
    <script src="{{ asset('assets/js/circle-progress.js') }}"></script>
    <script src="{{ asset('assets/js/isotope.pkgd.min.js') }}"></script>
    <script src="{{ asset('assets/js/imagesloaded.pkgd.min.js') }}"></script>
    <script src="{{ asset('assets/js/count-to.js') }}"></script>
    <script src="{{ asset('assets/js/YTPlayer.min.js') }}"></script>
    <script src="{{ asset('assets/js/validnavs.js') }}"></script>
    <script src="{{ asset('assets/js/gsap.js') }}"></script>
    <script src="{{ asset('assets/js/ScrollTrigger.min.js') }}"></script>
    <script src="{{ asset('assets/js/SplitText.min.js') }}"></script>
    <script src="{{ asset('assets/js/main.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    {{-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> --}}
    <script>
        // Auth Modal JavaScript
        $(document).ready(function() {
            const authModal = $('#authModal');
            const subscribeBtn = $('#subscribeBtn');
            const closeModal = $('#closeModal');
            const loginTab = $('#loginTab');
            const registerTab = $('#registerTab');
            const loginForm = $('#loginForm');
            const registerForm = $('#registerForm');

            // Open modal
            subscribeBtn.on('click', function(e) {
                e.preventDefault();
                authModal.addClass('active');
                $('body').css('overflow', 'hidden');
            });

            // Close modal
            closeModal.on('click', function() {
                authModal.removeClass('active');
                $('body').css('overflow', '');
            });

            // Close modal on overlay click
            authModal.on('click', function(e) {
                if (e.target === authModal[0]) {
                    authModal.removeClass('active');
                    $('body').css('overflow', '');
                }
            });

            // Close modal on ESC key
            $(document).on('keydown', function(e) {
                if (e.key === 'Escape' && authModal.hasClass('active')) {
                    authModal.removeClass('active');
                    $('body').css('overflow', '');
                }
            });

            // Switch to Login tab
            loginTab.on('click', function() {
                loginTab.addClass('active');
                registerTab.removeClass('active');
                loginForm.addClass('active');
                registerForm.removeClass('active');
            });

            // Handle Login Form Submit
            loginForm.find('form').on('submit', function(e) {
                e.preventDefault();

                const email = $('#loginEmail').val();
                const password = $('#loginPassword').val();
                const remember = loginForm.find('input[type="checkbox"]').is(':checked');

                // Show loading state
                const submitBtn = $(this).find('.btn-auth');
                const originalText = submitBtn.text();
                submitBtn.text('Logging in...').prop('disabled', true);

                // AJAX Request
                $.ajax({
                    url: '/api/login',
                    type: 'POST',
                    dataType: 'json',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        email: email,
                        password: password,
                        remember: remember
                    },
                    success: function(data) {
                        if (data.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Login Successful',
                                text: data.message,
                                timer: 1500,
                                showConfirmButton: false
                            }).then(() => {
                                window.location.href = data.redirect;
                            });
                        } else {
                            alert(data.message ||
                                'Login failed. Please check your credentials.');
                            submitBtn.text(originalText).prop('disabled', false);
                        }
                    },
                    error: function(xhr) {
                        console.error('Error:', xhr);
                        let errorMessage = 'An error occurred. Please try again.';

                        if (xhr.responseJSON) {
                            if (xhr.responseJSON.message) {
                                errorMessage = xhr.responseJSON.message;
                            } else if (xhr.responseJSON.errors) {
                                errorMessage = 'Validation errors:\n';
                                $.each(xhr.responseJSON.errors, function(key, value) {
                                    errorMessage += '- ' + value[0] + '\n';
                                });
                            }
                        }

                        alert(errorMessage);
                        submitBtn.text(originalText).prop('disabled', false);
                    }
                });
            });

            // Switch to Register tab
            registerTab.on('click', function() {
                registerTab.addClass('active');
                loginTab.removeClass('active');
                registerForm.addClass('active');
                loginForm.removeClass('active');
                loadAvailablePlans();
            });

            // Load available plans from API
            function loadAvailablePlans() {
                $.ajax({
                    url: '/api/registration-plans',
                    type: 'GET',
                    success: function(response) {
                        if (response.success) {
                            displayPlans(response.data);
                        }
                    },
                    error: function() {
                        $('#platformsGrid').html(
                            '<p style="text-align: center; color: #d32f2f;">Failed to load plans. Please try again.</p>'
                            );
                    }
                });
            }

            // Display plans in a professional grid
            function displayPlans(platforms) {
                let html = '';

                platforms.forEach((platform, index) => {
                    if (platform.plans.length === 0) return;

                    html += `
                        <div class="platform-section">
                            <div class="platform-header">
                                ${platform.platform_logo ? `<img src="${platform.platform_logo}" alt="${platform.platform_name}" class="platform-logo">` : ''}
                                <div class="platform-info">
                                    <h4>${platform.platform_name}</h4>
                                </div>
                            </div>
                            <div class="plan-cards">
                    `;

                    platform.plans.forEach((plan) => {
                        const discountBadge = plan.discount_percentage > 0 ?
                            `<span style="background: #ff6b6b; color: #fff; padding: 2px 8px; border-radius: 50px; font-size: 11px; font-weight: 700;">-${plan.discount_percentage}%</span>` :
                            '';

                        html += `
                            <div class="plan-card" data-plan-id="${plan.id}" data-price="${plan.selling_price}">
                                <div class="plan-details">
                                    <p class="plan-name">${plan.name.replace(/^([^-]+)-\s*/, '$1')} ${discountBadge}</p>
                                    <div class="plan-meta">
                                        <span class="plan-duration">
                                            <i class="fa fa-calendar"></i> ${plan.duration_months} month${plan.duration_months > 1 ? 's' : ''}
                                        </span>
                                        <span class="plan-quality">
                                            <i class="fa fa-star"></i> ${plan.quality}
                                        </span>
                                        <span>
                                            <i class="fa fa-tv"></i> ${plan.max_screens} screens
                                        </span>
                                    </div>
                                </div>
                                <span class="plan-price">₹${parseFloat(plan.selling_price).toFixed(0)}</span>
                                <input type="checkbox" class="plan-checkbox" style="margin-left: 15px;">
                            </div>
                        `;
                    });

                    html += `
                            </div>
                        </div>
                    `;
                });

                $('#platformsGrid').html(html);

                // Add click handler to plan cards
                $('.plan-card').on('click', function(e) {
                    // Don't toggle if clicking the checkbox
                    if (e.target.tagName === 'INPUT') return;
                    $(this).find('.plan-checkbox').prop('checked', !$(this).find('.plan-checkbox').is(
                        ':checked')).trigger('change');
                });

                // Add change handler to checkboxes
                $('.plan-checkbox').on('change', function() {
                    $(this).closest('.plan-card').toggleClass('selected');
                    updateSelectionSummary();
                });
            }

            // Update selection summary
            let selectedPlans = {};

            function updateSelectionSummary() {
                selectedPlans = {};
                let totalAmount = 0;
                let selectedCount = 0;

                $('.plan-card.selected').each(function() {
                    const planId = $(this).data('plan-id');
                    const planName = $(this).find('.plan-name').text().trim();
                    const price = parseFloat($(this).data('price'));

                    selectedPlans[planId] = {
                        name: planName,
                        price: price
                    };

                    totalAmount += price;
                    selectedCount++;
                });

                // Update summary display
                if (selectedCount > 0) {
                    $('#selectionSummary').addClass('active');

                    let plansList = '';
                    Object.entries(selectedPlans).forEach(([id, data]) => {
                        plansList += `
                            <div class="plan-badge">
                                ${data.name.replace(/\s*-\s*[0-9]+%/, '')}
                                <span class="remove" onclick="removePlan(${id})">✕</span>
                            </div>
                        `;
                    });

                    $('#selectedPlansList').html(plansList);
                    $('#totalAmount').text('₹' + totalAmount.toFixed(0));
                } else {
                    $('#selectionSummary').removeClass('active');
                }
            }

            // Remove plan from selection
            window.removePlan = function(planId) {
                $(`.plan-card[data-plan-id="${planId}"] .plan-checkbox`).prop('checked', false).trigger(
                    'change');
            };

            // Handle Register Form Submit
            $('#registerFormElement').on('submit', function(e) {
                e.preventDefault();

                const name = $('#registerName').val();
                const email = $('#registerEmail').val();
                const mobile = $('#registerMobile').val();
                const password = $('#registerPassword').val();
                const terms = $('#registerTerms').is(':checked');
                const subscriptionPlanIds = Object.keys(selectedPlans).map(id => parseInt(id));

                // Clear previous error
                $('#registerError').removeClass('show').text('');

                // Validation
                if (!terms) {
                    showRegisterError('Please agree to the Terms & Conditions');
                    return;
                }

                if (subscriptionPlanIds.length === 0) {
                    showRegisterError('Please select at least one streaming platform to subscribe');
                    return;
                }

                // Show loading state
                const submitBtn = $('#registerSubmitBtn');
                const originalText = submitBtn.text();
                submitBtn.text('Creating Account...').prop('disabled', true);

                // AJAX Request
                $.ajax({
                    url: '/api/register',
                    type: 'POST',
                    dataType: 'json',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        name: name,
                        email: email,
                        mobile: mobile,
                        password: password,
                        subscription_plan_ids: subscriptionPlanIds
                    },
                    success: function(data) {
                        if (data.success) {
                            // CLOSE MODAL FIRST
                            authModal.removeClass('active');
                            $('body').css('overflow', '');

                            // RESET BUTTON
                            submitBtn.text(originalText).prop('disabled', false);

                            // SHOW SUCCESS MESSAGE
                            Swal.fire({
                                icon: 'success',
                                title: 'Account Created Successfully!',
                                html: `<p>Welcome ${name}!</p><p>Your ${subscriptionPlanIds.length} streaming subscription${subscriptionPlanIds.length > 1 ? 's' : ''} ${subscriptionPlanIds.length > 1 ? 'are' : 'is'} now active.</p>`,
                                timer: 2000,
                                showConfirmButton: false,
                                allowOutsideClick: false,
                                allowEscapeKey: false
                            }).then(() => {
                                // REDIRECT TO DASHBOARD
                                window.location.href = data.redirect || '/dashboard';
                            });
                        } else {
                            showRegisterError(data.message || 'Registration failed');
                            submitBtn.text(originalText).prop('disabled', false);
                        }
                    },
                    error: function(xhr) {
                        console.error('Error:', xhr);
                        let errorMessage = 'An error occurred. Please try again.';

                        if (xhr.responseJSON) {
                            if (xhr.responseJSON.message) {
                                errorMessage = xhr.responseJSON.message;
                            } else if (xhr.responseJSON.errors) {
                                let errors = [];
                                $.each(xhr.responseJSON.errors, function(key, value) {
                                    errors.push('- ' + value[0]);
                                });
                                errorMessage = errors.join('\n');
                            }
                        }

                        showRegisterError(errorMessage);
                        submitBtn.text(originalText).prop('disabled', false);
                    }
                });
            });

            // Show error message
            function showRegisterError(message) {
                $('#registerError').text(message).addClass('show');
            }

            // Social Login Handlers (Optional)
            $('.btn-social').on('click', function(e) {
                e.preventDefault();
                const provider = $(this).hasClass('google') ? 'Google' : 'Facebook';
                alert(`${provider} login will be implemented here!`);
            });
        });
    </script>

</body>

</html>
