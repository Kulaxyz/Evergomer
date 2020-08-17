<!DOCTYPE html>
<html class="wide wow-animation" lang="en">
<head>
    <title>{{ \App\FrontendSetting::get('name', 'Energomer') }}</title>
    <meta name="description" content="{{ \App\FrontendSetting::get('desc', 'Charging') }}">
    <meta name="viewport" content="width=device-width height=device-height initial-scale=1.0">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="icon" href="/images/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" type="text/css" href="//fonts.googleapis.com/css?family=Work+Sans:300,400,500,700%7CZilla+Slab:300,400,500,700,700i%7CGloria+Hallelujah">
    <link rel="stylesheet" href="/css/bootstrap.css">
    <link rel="stylesheet" href="/css/fonts.css">
    <link rel="stylesheet" href="/css/style.css">
    <style>.ie-panel{display: none;background: #212121;padding: 10px 0;box-shadow: 3px 3px 5px 0 rgba(0,0,0,.3);clear: both;text-align:center;position: relative;z-index: 1;} html.ie-10 .ie-panel, html.lt-ie-10 .ie-panel {display: block;}</style>
</head>
<body>
<div class="ie-panel"><a href="http://windows.microsoft.com/en-US/internet-explorer/"><img src="/images/ie8-panel/warning_bar_0000_us.jpg" height="42" width="820" alt="You are using an outdated browser. For a faster, safer browsing experience, upgrade for free today."></a></div>
<div class="preloader">
    <div class="preloader-logo"><a class="brand" href="/"><img class="brand-logo-dark" src="{{ \App\FrontendSetting::get('logo', '/images/logo-default-355x118.png') }}" alt="" width="177" height="59"/></a>
    </div>
    <div class="preloader-body">
        <div class="cssload-container">
            <div class="cssload-speeding-wheel"></div>
        </div>
    </div>
</div>
<div class="page">
    <!-- Page Header-->
    <header class="page-header">
        <!-- Logo -->
        <div class="rd-navbar-panel">
            <!-- RD Navbar Brand-->
            <div class="rd-navbar-brand" id="top-logo">
                <a class="brand" href="/">
                    <img class="brand-logo-dark"
                         src="{{ \App\FrontendSetting::get('logo', '/images/logo-default-355x118.png') }}"
                         alt="" width="177" height="59"/>
                </a>
            </div>
        </div>

        <!-- RD Navbar-->
        <div class="rd-navbar-wrap">
            <nav class="rd-navbar rd-navbar-modern" data-layout="rd-navbar-fixed" data-sm-layout="rd-navbar-fixed" data-md-layout="rd-navbar-fixed" data-md-device-layout="rd-navbar-fixed" data-lg-layout="rd-navbar-static" data-lg-device-layout="rd-navbar-fixed" data-xl-layout="rd-navbar-static" data-xl-device-layout="rd-navbar-static" data-body-class="rd-navbar-modern-linked" data-lg-stick-up="true" data-xl-stick-up="true" data-xxl-stick-up="true">
                <div class="rd-navbar-main-outer">
                    <div class="rd-navbar-main">
                        <div class="rd-navbar-nav-wrap">
                            <!-- RD Navbar Nav		-->
                            <ul class="rd-navbar-nav">
                                @foreach (\App\Models\MenuItem::getTree() as $item)
                                    <li class="rd-nav-item @if(!$item->children->isEmpty()) rd-navbar--has-dropdown rd-navbar-submenu @endif
                                    @if($item->page && Route::currentRouteName() == $item->page->name) active @endif">
                                        <a class="rd-nav-link" href="{{$item->url()}}">
                                            {{ $item->name }}
                                        </a>
                                        @if(!$item->children->isEmpty()) <span class="rd-navbar-submenu-toggle"></span>
                                            <ul class="rd-menu rd-navbar-dropdown">
                                                @foreach($item->children as $child)
                                                    <li class="rd-dropdown-item"><a class="rd-dropdown-link" href="{{ $child->url() }}">{{ $child->name }}</a></li>
                                                @endforeach
                                            </ul>
                                        @endif
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                        <!-- RD Navbar Search-->
                        <div class="rd-navbar-search">
                            <button class="rd-navbar-search-toggle rd-navbar-fixed-element-1" data-rd-navbar-toggle=".rd-navbar-search"><span></span></button>
                            <form class="rd-search" action="#" data-search-live="rd-search-results-live" method="GET">
                                <div class="form-wrap">
                                    <label class="form-label" for="rd-navbar-search-form-input">Search</label>
                                    <input class="rd-navbar-search-form-input form-input" id="rd-navbar-search-form-input" type="text" name="s" autocomplete="off"/>
                                    <div class="rd-search-results-live" id="rd-search-results-live"></div>
                                </div>
                                <button class="rd-search-form-submit fa-search" type="submit"></button>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="rd-navbar-aside-outer">
                    <div class="rd-navbar-aside">
                        <!-- RD Navbar Panel-->
                        <div class="rd-navbar-panel">
                            <!-- RD Navbar Toggle-->
                            <button class="rd-navbar-toggle" data-rd-navbar-toggle=".rd-navbar-nav-wrap"><span></span></button>
                            <!-- RD Navbar Brand-->
                            <div class="rd-navbar-brand"><a class="brand" href="/"><img class="brand-logo-dark" src="{{ \App\FrontendSetting::get('logo', '/images/logo-default-355x118.png') }}" alt="" width="177" height="59"/></a>
                            </div>
                        </div>
                        <div class="rd-navbar-collapse">
                            <button class="rd-navbar-collapse-toggle" data-rd-navbar-toggle=".rd-navbar-collapse-content"><span></span></button>
                            <div class="rd-navbar-collapse-content">
                                <div class="link-icon-title"><a class="link-icon-1" href="tel:{{ \App\FrontendSetting::get('phone') }}"><span class="icon mdi mdi-phone"></span><span>{{ \App\FrontendSetting::get('phone') }}</span></a></div>
                                <div class="link-icon-title"><a class="link-icon-1" href="mailto:{{\App\FrontendSetting::get('email')}}"><span class="icon mdi mdi-email-outline"></span><span>{{ \App\FrontendSetting::get('email') }}</span></a></div>
                                @if(!backpack_user())
                                    <div class="link-icon-title"><a class="link-icon-1" href="{{ route('login') }}"><span class="icon mdi mdi-login"></span><span>Login/Register</span></a></div>
                                @else
                                    <div class="link-icon-title"><a class="link-icon-1" href="{{ route('login') }}"><span class="icon mdi mdi-login"></span><span>Cabinet</span></a></div>
                                @endguest
                            </div>
                        </div>
                    </div>
                </div>
            </nav>
        </div>
    </header>

    @yield('content')

    <footer class="section footer-modern bg-gray-950">
        <div class="footer-modern-main">
            <div class="container">
                <div class="row row-50 justify-content-center justify-content-lg-between">
                    <div class="col-md-6 col-lg-4">
                        <h4 class="font-weight-regular footer-modern-title">Twitter Feed</h4>
                        <!-- RD Twitter Feed-->
                        <div class="twitter twitter-thin-group" data-twitter-username="templatemonster" data-twitter-date-hours=" hours ago" data-twitter-date-minutes=" minutes ago">
                            <article class="tweet-thin" data-twitter-type="tweet">
                                <div class="icon tweet-thin-icon fa-twitter"></div>
                                <div class="tweet-thin-main">
                                    <p>Brave #Theme - Multipurpose #HTML Website Template - https://goo.gl/pzJx6G #webdesign</p>
                                    <p class="text-white-dark">2 days ago</p>
                                </div>
                            </article>
                            <article class="tweet-thin" data-twitter-type="tweet">
                                <div class="icon tweet-thin-icon fa-twitter"></div>
                                <div class="tweet-thin-main">
                                    <div class="tweet-thin-text" data-tweet="text">
                                        <p>It is Proved That the #Discount Can Cheer You Up! Catch a Chance to Save 35% OFF Any #HTML Template!</p>
                                        <p class="text-white-dark">2 days ago</p>
                                    </div>
                                </div>
                            </article>
                            <article class="tweet-thin" data-twitter-type="tweet">
                                <div class="icon tweet-thin-icon fa-twitter"></div>
                                <div class="tweet-thin-main">
                                    <div class="tweet-thin-text" data-tweet="text">
                                        <p>Easy Steps to Create Cool #Vector Art on Your #iPhone https://goo.gl/QH3xAc</p>
                                        <p class="text-white-dark">2 days ago</p>
                                    </div>
                                </div>
                            </article>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-4">
                        <h4 class="font-weight-regular footer-modern-title">Gallery</h4>
                        <div class="grid-1" data-lightgallery="group">
                            @php
                                if (!$gallery_images) {
                                    $gallery_images = [];
                                }
                            @endphp
                            @for($g = 0; ($g < 9 && array_key_exists($g, $gallery_images)); $g++)
                                <div class="grid-1-item"><a class="thumbnail-creative thumbnail-creative-2" href="{{ $gallery_images[$g] }}" data-lightgallery="item">
                                    <figure class="thumbnail-creative-media"><img class="thumbnail-creative-image" src="{{ $gallery_images[$g] }}" alt="" width="100" height="100"/>
                                    </figure>
                                    <div class="thumbnail-creative-overlay"></div></a>
                                </div>
                            @endfor
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <h4 class="font-weight-regular footer-modern-title">Get in Touch</h4>
                        <!-- RD Mailform-->
                        <form class="rd-form form-sm rd-mailform" data-form-output="form-output-global" data-form-type="contact" method="post" action="bat/rd-mailform.php">
                            <div class="form-wrap">
                                <input class="form-input" id="contact-form-name-3" type="text" name="name" data-constraints="@Required">
                                <label class="form-label" for="contact-form-name-3">Your name</label>
                            </div>
                            <div class="form-wrap">
                                <input class="form-input" id="contact-form-email-3" type="email" name="email" data-constraints="@Email @Required">
                                <label class="form-label" for="contact-form-email-3">E-mail</label>
                            </div>
                            <div class="form-wrap">
                                <label class="form-label" for="contact-form-message-3">Message</label>
                                <textarea class="form-input" id="contact-form-message-3" name="message" data-constraints="@Required"></textarea>
                            </div>
                            <div class="form-wrap">
                                <button class="button button-primary button-raven" type="submit">Send</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="footer-modern-aside">
            <div class="container">
                <div class="layout-2">
                    <!-- Rights-->
                    <p class="rights"><span>&copy;&nbsp;</span><span class="copyright-year"></span>. All Rights Reserved.</p>
                    <div>
                        <div class="group group-middle"><a class="link-social-2 icon mdi mdi-facebook" href="#"></a><a class="link-social-2 icon mdi mdi-twitter" href="#"></a><a class="link-social-2 icon mdi mdi-google" href="#"></a><a class="link-social-2 icon mdi mdi-instagram" href="#"></a><a class="link-social-2 icon mdi mdi-linkedin" href="#"></a></div>
                    </div>
                </div>
            </div>
        </div>
    </footer>
</div>
<div class="snackbars" id="form-output-global"></div>
<script src="/js/core.min.js"></script>
<script src="/js/script.js"></script>
</body>
</html>
