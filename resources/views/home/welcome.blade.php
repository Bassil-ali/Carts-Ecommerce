@extends('layouts.home.app')

@section('content')

<div class="container text-center">

@if (session()->has('error_message'))
    <div class="alert alert-danger">
        <p>{{ session()->get('error_message') }}</p>
    </div>
@endif

@if (session()->has('success'))
    <div class="alert alert-success">
        {{ session()->get('success') }}
    </div>
@endif

    @auth('cliants')
        {{-- expr --}}
        @if (Auth::guard('cliants')->user()->isVerified == 0)
            <div class="alert alert-danger">
                <p>@lang('home.verifed_phone') <a style="color: rgb(84, 84, 214)"
                     data-toggle="modal" 
                     data-target="#modal-activation-code">
                    @lang('home.verifed')</a>   
                </p>
            </div>
        @endif  

        @if (Auth::guard('cliants')->user()->emailVerified == 0)
            <div class="alert alert-danger">
                <p>@lang('home.verifed_email')</p>
            </div>
        @endif  

    @endauth

</div>

        <section class="section_home">
            <div class="container">
            
       
                <div class="home_txt">
                    <div class="sec-title">
                        <h2>@lang('home.shoping')</h2>
                        <span class="shape-site">@lang('home.store_domain')</span>
                        <p>@lang('home.store_domain_text')</p>
                        <a style="color: #aaa" class="btn-site" href="{{ route('purchase.index') }}"><span>@lang('home.quick_urchase')</span></a>
                    </div>
                    <figure><img src="{{ asset('home_file/images/slide-home.svg')}}" /></figure>
                </div>
            </div>
        </section>
        <!--section_home-->
        
  
         <section class="section_best_sellers">
            <div class="container">

                <div class="sec_head">
                    <h3>@lang('home.best_cards')</h3>
                    <p>@lang('home.selling_cartds')</p>
                </div>
                <div class="owl-carousel" id="card-sellers-slider">
                    @foreach($carts as $cart)
                    <div class="item">
                        <div class="box-card" style="background: linear-gradient(180deg, {{ $cart->sub_category->color_1 }} 0%, {{ $cart->sub_category->color_2 }} 100%); ">
                            <a href="{{ route('product_show_details',[$cart->sub_category->id,$cart->id]) }}" class="non">

                            <div class="title-card">
                                <center><img src="{{ $cart->cart_details->image_path }}" width="100%"></center>
                                <h2>{{ $cart->cart_details->cart_name }}</h2>
                                <p>{{ $cart->cart_details->short_descript }}</p>
                                <span>
                                    @if($cart->market_id == null)
                                        
                                    @else
                                        {{ $cart->Market->name }}
                                    @endif
                                </span>
                                <strong>
                                    @lang('home.balance')<br> {{$cart->balance}} 
                                    @if($cart->market_id == null)
                                            
                                        @else
                                            {{ $cart->Market->balance_type }}
                                        @endif
                                    </strong>
                                </div>
                            

                                @if ($cart->quantity > 0)

                                    <div class="row d-flex justify-content-around">
                                        

                                        <ul class="option-card" style="width: 45%;padding: 0px;;">
                                            <form action="{{ route('storeToPayment', $cart) }}" method="post">
                                                {{ csrf_field() }}
                                                {{ method_field('post') }}
                                                <li style="padding: 7px 0;">
                                                    <a onclick="$(this).closest('form').submit();">
                                                        <img src="{{ asset('home_file/images/surface.svg') }}" class="mx-1" />
                                                        <span style="font-size: 10px">
                                                            {{ $cart->amrecan_price }}{{ Session::get('price_icon')}} @lang('home.pay_cart')
                                                        </span>
                                                    </a>
                                                </li>
                                            </form>
                                        </ul>

                                        <ul class="option-card" style="width: 45%;padding: 0px;">
                                            <li style="padding: 7px 0;">
                                                <a class="add-cart"
                                                        data-url="{{ route('wallet.store', $cart) }}"
                                                        data-method="post"
                                                        data-name="{{ $cart->cart_details->cart_name }}"
                                                        data-desc="{{ $cart->cart_details->short_descript }}"
                                                        data-id="{{ $cart->id }}"
                                                        data-price="{{ $cart->amrecan_price }}">
                                                    <img src="{{ asset('home_file/images/shopping-cart.svg') }}" />
                                                    <span style="font-size: 10px">@lang('home.add_cart')</span>
                                                </a>
                                            </li>
                                        </ul>

                                    </div>

                                @else

                                    @auth('cliants')

                                    <div class="row d-flex justify-content-around">



                                        <ul class="option-card" style="width: 45%;padding: 0px;">
                                            <form action="{{ route('notify') }}" method="post">                                        
                                            <li style="padding: 7px 0;">
                                                <a>
                                                    {{-- <img src="{{ asset('home_file/images/surface.svg') }}" /> --}}
                                                    <span style="font-size: 10px">@lang('home.not_available')</span>
                                                </a>
                                            </li>
                                        </ul>

                                        <ul class="option-card" style="width: 45%;padding: 0px;">
                                        <form action="{{ route('notify') }}" method="post">
                                
                                            {{ csrf_field() }}
                                            {{ method_field('get') }}

                                            <input type="hidden" name="cliant_id" id="cliant_id_available" value="{{ Auth::guard('cliants')->user()->id}}">
                                            <input type="hidden" name="cart_id" id="cart_id_available" value="{{ $cart->id }}">
                                                <li style="padding: 7px 0;">
                                                    <a>
                                                        {{-- <img src="{{ asset('home_file/images/shopping-cart.svg') }}" /> --}}
                                                        <span class="remind-available" style="font-size: 10px"
                                                                    data-url="{{ route('notify') }}"
                                                                    data-method="get"
                                                        >@lang('home.notify_saving')
                                                        </span>
                                                    </a>
                                                </li>
                                        </form>
                                        </ul>

                                    </div>
                                        
                                    @else

                                    <div class="row d-flex justify-content-around">
                                                                    
                                        <ul data-target="#exampleModal" data-toggle="modal" class="option-card" style="width: 45%;padding: 0px;">
                                            <li style="padding: 7px 0;">
                                                <a>
                                                    {{-- <img src="{{ asset('home_file/images/surface.svg') }}" /> --}}
                                                    <span style="font-size: 10px">@lang('home.not_available')</span>
                                                </a>
                                            </li>
                                        </ul>

                                        <ul data-target="#exampleModal" data-toggle="modal" class="option-card" style="width: 45%;padding: 0px;">
                                            <li style="padding: 7px 0;">
                                                <a>
                                                    {{-- <img src="{{ asset('home_file/images/shopping-cart.svg') }}" /> --}}
                                                    <span style="font-size: 10px">@lang('home.notify_saving')</span>
                                                </a>
                                            </li>
                                        </ul>

                                    </div>

                                    @endauth

                                @endif

                            </a>
                
                        </div>
                    </div>
              
                    @endforeach

                    <!-- <div class="item">
                        <div class="box-card" style="background:linear-gradient(180deg, rgba(25,154,254,1) 0%, rgba(143,6,250,1) 100%); ">
                            <div class="title-card">
                                <h2>GOOGLE GM</h2>
                                <p>حمل ما تريد من ألعاب PC المدفوعة</p>
                                <span>المتجر السعودي</span>
                                <strong>1050 ر.س</strong>
                            </div>
                            <ul class="option-card">
                                <li><a><img src="{{ asset('home_file/images/surface.svg')}}" /><span>اشتري الان</span></a></li>
                                <li><a><img src="{{ asset('home_file/images/shopping-cart.svg')}}" /><span>أضف للسلة</span></a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="item">
                        <div class="box-card" style="background:linear-gradient(180deg, rgb(124 254 25) 0%, rgb(12 111 10) 100%); ">
                            <div class="title-card">
                                <h2>GOOGLE GM</h2>
                                <p>حمل ما تريد من ألعاب PC المدفوعة</p>
                                <span>المتجر السعودي</span>
                                <strong>1050 ر.س</strong>
                            </div>
                            <ul class="option-card">
                                <li><a><img src="{{ asset('home_file/images/surface.sv')}}g" /><span>اشتري الان</span></a></li>
                                <li><a><img src="{{ asset('home_file/images/shopping-cart.svg')}}" /><span>أضف للسلة</span></a></li>
                            </ul>
                        </div>
                    </div> -->
                </div>
            </div>
            
        </section>
        <!--section_best_sellers-->
        
        <section class="section_about">
            <div class="flex-about">
                <div class="img-about">
                    <img src="{{ asset('home_file/images/bg-about.svg')}}" alt="" />
                </div>
                <div class="title-about">
                    <div class="head-about">
                        <p>@lang('home.abouts')</p>
                    </div>
                    <div class="video-about">
                        <a data-fancybox="images" href="{{setting('vedio_link')}}">
                            <div class="play-btn"><i class="fa fa-play"></i></div> 
                            <img class="img-fluid video-pic" src="{{ asset('home_file/images/vedio.png')}}" alt="">
                         </a>
                    </div>
                    <div class="shape-about"></div>
                </div>
            </div>
        </section>
        <!--section_about-->
        
    <section class="section_what_site">
        <div class="container">
            <div class="sec_head">
                <h3>@lang('home.what_store_website')</h3>
                <p>@lang('home.simple')</p>
            </div>
            <div class="row">
                <div class="col-md-3">
                    <div class="box-callouts">
                        <figure>
                            <img src="{{ asset('images/credit-card.png')}}" alt="">
                        </figure>
                        <div class="sec-title">
                            <h5>@lang('home.hoose_card')</h5>
                            <p>@lang('home.hoose_card_text')</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="box-callouts">
                        <figure>
                            <img src="{{ asset('images/shopping-cart.png')}}" alt="">
                        </figure>
                        <div class="sec-title">
                            <h5>@lang('home.choose_store')</h5>
                            <p>@lang('home.choose_store_text')</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="box-callouts">
                        <figure>
                            <img src="{{ asset('images/coin.png')}}" alt="">
                        </figure>
                        <div class="sec-title">
                            <h5>@lang('home.choose_price')</h5>
                            <p>@lang('home.choose_price_text')</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="box-callouts">
                        <figure>
                            <img src="{{ asset('images/qr-code.png')}}" alt="">
                        </figure>
                        <div class="sec-title">
                            <h5>@lang('home.receive_code')</h5>
                            <p>@lang('home.receive_code_text')<p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
        <!--section_what_site-->
        
        <section class="section_subscriber_ratings">
            <div class="container">
                <div class="sec_head">
                    <h3>@lang('home.what_store_website')</h3>
                    <p>@lang('home.simple')</p>
                </div>
                <div class="row">
                    <div class="col-md-8">
                        <div class="owl-carousel" id="slider-subscriber-ratings">
                            <div class="item">
                                <div class="box-rate">
                                    <figure>
                                        <img src="{{ asset('home_file/images/pro.jpg')}}" alt="" />
                                    </figure>
                                    <div class="sec-title">
                                        <h5>Hind Shaban</h5>
                                        <p>من أجمل المواقع لشراء البطاقات الاكترونية , وامان في الشراء</p>
                                    </div>
                                    <div class="rate-line">
                                        <span class="zmdi zmdi-star checked"></span>
                                        <span class="zmdi zmdi-star checked"></span>
                                        <span class="zmdi zmdi-star checked"></span>
                                        <span class="zmdi zmdi-star checked"></span>
                                        <span class="zmdi zmdi-star"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="item">
                                <div class="box-rate">
                                    <figure>
                                        <img src="{{ asset('home_file/images/pro1.png')}}" alt="" />
                                    </figure>
                                    <div class="sec-title">
                                        <h5>Salah Khattab</h5>
                                        <p>من أجمل المواقع لشراء البطاقات الاكترونية , وامان في الشراء</p>
                                    </div>
                                    <div class="rate-line">
                                        <span class="zmdi zmdi-star checked"></span>
                                        <span class="zmdi zmdi-star checked"></span>
                                        <span class="zmdi zmdi-star checked"></span>
                                        <span class="zmdi zmdi-star checked"></span>
                                        <span class="zmdi zmdi-star"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="img-subscriber-ratings">
                            <img src="{{ asset('home_file/images/shape-rate.svg')}}" alt="" />
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!--section_subscriber_ratings-->
        
        <section class="section_download_app">
            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="1366" height="518.657" viewBox="0 0 1366 518.657">
              <defs>
                <linearGradient id="linear-gradient" x1="0.5" x2="0.5" y2="1" gradientUnits="objectBoundingBox">
                  <stop offset="0" stop-color="#199afe"/>
                  <stop offset="1" stop-color="#8f06fa"/>
                </linearGradient>
              </defs>
              <path id="Path_778" data-name="Path 778" d="M0,9.855S341.87-55.777,683.37-55.777,1366,9.855,1366,9.855V462.88S1024.87,398.9,683.37,398.9,0,462.88,0,462.88Z" transform="translate(0 55.777)" fill="url(#linear-gradient)"/>
            </svg>
            <div class="container">
                <div class="content-download">
                    <div class="img-download">
                        <img src="{{ asset('home_file/images/img-dwonload-app.svg')}}" alt="" />
                    </div>
                    <div class="title-download">
                        <h2>@lang('home.download_application')</h2>
                        <p>
                            @lang('home.application_text')
                        </p>
                        <span>@lang('home.application_new')</span>
                        <ul>
                            <li><a href=""><img src="{{ asset('home_file/images/google-play.svg')}}" /></a></li>
                            <li><a href=""><img src="{{ asset('home_file/images/app-store.svg')}}" /></a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </section>

        <!--section_download_app-->


@endsection
@push('RemindAvailable')
    <script>

    $(document).ready(function() {

        $(".remind-available").click(function(e){
            e.preventDefault();

            var url       = $(this).data('url');
            var method    = $(this).data('method');

            var cliant_id   = $('#cliant_id_available').val();
            var cart_id     = $('#cart_id_available').val();

            $.ajax({
                url: url,
                method: method,
                data:{
                    cliant_id:cliant_id,
                    cart_id:cart_id,
                },
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                success: function (response) {
                    if (response.success == true) {
                        
                        swal({
                            title: '@lang("home.addessuccfluy")',
                            timer: 12000,
                        });

                    }//end of if

                },//end of success

            });//end of ajax  

        });//end of click

    });//end of document ready functiom

    </script>

     <script type="text/javascript">window.$crisp=[];window.CRISP_WEBSITE_ID="4559d5fc-077c-4cf2-9a39-9823b657ed78";(function(){d=document;s=d.createElement("script");s.src="https://client.crisp.chat/l.js";s.async=1;d.getElementsByTagName("head")[0].appendChild(s);})();</script>

@endpush