@extends('layouts.app')

@section('content')
    @php
        $top_carousel_items = $data->top_carousel;
        $testim_carousel_items = $data->testim_carousel;
        $adv_carousel_items = $data->adv_carousel;
        $mob_app = $data->mob_app;
    @endphp
    <!-- FScreen-->

    @if(count($top_carousel_items) > 1)
        <div class="owl-carousel wow fadeIn" style="margin-top: 0 !important;" data-wow-delay=".2s" data-items="1" data-dots="false" data-nav="true" data-stage-padding="0" data-loop="true" data-animation-in="fadeIn" data-animation-out="fadeOut" data-autoplay="true" data-mouse-drag="false">
    @endif

    @forelse($top_carousel_items as $item)
            <section class="section parallax-container section-xl bg-gray-900" data-parallax-img="{{ $item->img }}">
                <div class="parallax-content">
                    <div class="container">
                        <div class="product-creative-main text-center">
                            <p class="heading-1 product-creative-title"><a href="{{$item->href}}">{{ $item->title }}</a></p>
                            <div class="product-creative-text">
                                <p class="heading-5 text-white">{{ $item->subtitle }}</p>
                            </div>
                            <p class="heading-2 product-creative-price text-primary"><a href="{{$item->href}}">{{ $item->text }}</a></p>
                            <a class="button button-lg button-primary button-raven" href="{{$item->href}}">{{ $item->btn }}</a>
                        </div>
                    </div>
                </div>
            </section>
        @empty
            <p></p>
    @endforelse

    @if(count($top_carousel_items) > 1)
        </div>
    @endif
        <!-- Counters-->
        <section class="section section-md bg-default">
            <div class="container">
                <div class="row row-50">
                    <div class="col-6 col-md-3 wow fadeIn">
                        <!-- Counter Modern-->
                        <article class="counter-modern">
                            <div class="icon counter-modern-icon mdi mdi-car"></div>
                            <div class="counter-modern-main"><span>{{ \App\Device::count() }}</span><span></span></div>
                            <h4 class="font-weight-regular counter-modern-title">Device Installed</h4>
                        </article>
                    </div>
                    <div class="col-6 col-md-3 wow fadeIn" data-wow-delay=".2s">
                        <!-- Counter Modern-->
                        <article class="counter-modern">
                            <div class="icon counter-modern-icon mdi mdi-account"></div>
                            <div class="counter-modern-main">
                                <div class="counter">
                                    {{ \App\Invoice::where('status', \App\Invoice::STATUS_COMPLETED)->sum('charge_duration') }}
                                </div>
                            </div>
                            <h4 class="font-weight-regular counter-modern-title">Hours</h4>
                        </article>
                    </div>
                    <div class="col-6 col-md-3 wow fadeIn" data-wow-delay=".2s">
                        <!-- Counter Modern-->
                        <article class="counter-modern">
                            <div class="icon counter-modern-icon mdi mdi-coin"></div>
                            <div class="counter-modern-main">
                                <div class="counter">
                                    {{ \App\Invoice::where('status', \App\Invoice::STATUS_COMPLETED)->sum('charge_power') }}
                                </div>
                            </div>
                            <h4 class="font-weight-regular counter-modern-title">Total kWh</h4>
                        </article>
                    </div>
                    <div class="col-6 col-md-3 wow fadeIn" data-wow-delay=".2s">
                        <!-- Counter Modern-->
                        <article class="counter-modern">
                            <div class="icon counter-modern-icon mdi mdi-flag-variant"></div>
                            <div class="counter-modern-main">
                                <div class="counter">10</div>
                            </div>
                            <h4 class="font-weight-regular counter-modern-title">Locations</h4>
                        </article>
                    </div>
                </div>
            </div>
        </section>

        <!-- Taxi Service App-->
        <section class="section section-lg bg-gray-100 bg-image bg-image-1" style="background-image: url({{$mob_app->img}});">
            <div class="container">
                <div class="row">
                    <div class="col-sm-9 col-md-8 col-lg-7 col-xl-6">
                        <h2 class="wow fadeIn">{{ $mob_app->title }}</h2>
                        <p class="heading-5 wow fadeIn" data-wow-delay=".2s">{{ $mob_app->subtitle }}</p>
                        <p class="wow fadeIn" data-wow-delay=".4s">{{$mob_app->text}}.</p>
                        <a class="button button-lg button-primary button-raven wow fadeIn" data-wow-delay=".6s"
                           href="{{ $mob_app->href }}">{{$mob_app->btn}}</a>
                    </div>
                </div>
            </div>
        </section>

        <!-- Testimonials-->
        <section class="section section-lg bg-default text-center">
            <div class="container">
                <h2 class="wow fadeIn">Testimonials</h2>
                <!-- Owl Carousel-->
                <div class="owl-carousel wow fadeIn" data-wow-delay=".2s" data-items="1" data-dots="false" data-nav="true" data-stage-padding="0" data-loop="true" data-margin="30" data-animation-in="fadeIn" data-animation-out="fadeOut" data-autoplay="true" data-mouse-drag="false">
                    @forelse($testim_carousel_items as $item)

                    <blockquote class="quote-light"><img class="quote-light-avatar" src="{{$item->img}}" alt="" width="68" height="68"/>
                        <p class="heading-5 quote-light-cite">{{ $item->name }}</p>
                        <p class="quote-light-position">{{ $item->status }}</p>
                        <div class="quote-light-text">
                            <p class="font-weight-regular heading-5">{{ $item->text }}</p>
                        </div>
                    </blockquote>
                    @empty

                    @endforelse
                </div>
            </div>
        </section>

        <!-- Our Advantages-->
            <section class="section section-lg text-center bg-gray-950">
                    <div class="container">
                        <h2 class="wow fadeIn">Our Advantages</h2>
                        @if(count($adv_carousel_items) > 1)
                            <div class="owl-carousel owl-style-1 wow fadeIn" data-wow-delay=".2s" data-items="1" data-dots="false" data-nav="true" data-stage-padding="0" data-loop="true" data-margin="30" data-animation-in="fadeIn" data-animation-out="fadeOut" data-autoplay="true" data-mouse-drag="false">
                        @endif
                        @forelse($adv_carousel_items as $item)
                            <div class="row row-30 justify-content-center">
                            @for($k = 1; $k <= 4; $k++)
                                <div class="col-sm-6 col-lg-4 col-xl-3 wow fadeIn" data-wow-delay=".2s">
                                    <!-- Box Classic-->
                                    @php
                                        $title = 'title'.$k;
                                        $text = 'text'.$k;
                                        $icon = 'icon'.$k;

                                    @endphp
                                    <article class="box-classic"><a class="icon box-classic-icon {{$item->$icon}}" href="#"></a><a class="box-classic-main" href="#">
                                            <h4 class="box-classic-title">{{ $item->$title }}</h4>
                                            <p>{{$item->$text}}</p></a></article>
                                </div>
                            @endfor
                        </div>
                        @empty

                        @endforelse
                        @if(count($adv_carousel_items) > 1)
                            </div>
                        @endif
                    </div>
                </section>

        <!-- Blog-->
        <section class="section section-lg bg-default text-center">
            <div class="container">
                <h2 class="wow fadeIn">Blog</h2>
                <div class="row row-40 justify-content-center">
                    <div class="col-md-6 wow fadeIn">
                        <!-- Post Classic-->
                        <article class="post-classic"><a class="post-classic-media" href="#"><img class="post-classic-image" src="/images/classic-blog-1-570x380.jpg" alt="" width="570" height="380"/></a>
                            <div class="post-classic-meta">
                                <div class="badge">News</div>
                                <time datetime="2019">July 11, 2019 at 10:41 am </time>
                            </div>
                            <h4 class="font-weight-regular post-classic-title"><a href="#">Finding Lost Property: Tips from Express on How to Find Things That You’ve Lost in a Taxi</a></h4>
                            <p>After an outing, you decide to catch a cab. You get home only to discover that you lost something really important in your taxi. Most people won’t bother to try to find the item. We...</p><a class="button button-link button-lg" href="#">Continue Reading</a>
                        </article>
                    </div>
                    <div class="col-md-6 wow fadeIn" data-wow-delay=".2s">
                        <!-- Post Classic-->
                        <article class="post-classic"><a class="post-classic-media" href="#"><img class="post-classic-image" src="/images/classic-blog-2-570x380.jpg" alt="" width="570" height="380"/></a>
                            <div class="post-classic-meta">
                                <div class="badge">News</div>
                                <time datetime="2019">July 11, 2019 at 10:41 am </time>
                            </div>
                            <h4 class="font-weight-regular post-classic-title"><a href="#">Your #1 Guide On Selecting a Taxi Service for Business or Entertainment Trips in the United States</a></h4>
                            <p>A taxi is an essential component of the public transportation system. Whether you are calling a taxi to your home or office on a regular basis, or are visiting a strange city and need...</p><a class="button button-link button-lg" href="#">Continue Reading</a>
                        </article>
                    </div>
                </div><a class="button button-lg button-primary button-raven wow fadeIn mt-40" href="#">View all Blog Posts</a>
            </div>
        </section>

@endsection
