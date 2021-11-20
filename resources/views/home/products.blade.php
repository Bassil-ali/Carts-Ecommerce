@extends('layouts.home.app')

@section('content')

  
                   
        <!--header-->
    @foreach($sub_categories as $category)
        <div class="head_page">
            <svg class="svg-top" xmlns="http://www.w3.org/2000/svg" width="1366" height="84" viewBox="0 0 1366 84">
              <path id="Path_71110" data-name="Path 71110" d="M0,0H1366V84S1047.2,33.783,689.219,33.783,0,84,0,84Z" fill="#f9f9f9"></path>
            </svg>
            <div class="img-head-page">
                <img src="{{$category->image_path}}" alt="">
            </div>
            <svg class="svg-bottom" xmlns="http://www.w3.org/2000/svg" width="1366" height="84" viewBox="0 0 1366 84">
              <path id="Path_71110" data-name="Path 71110" d="M0,0H1366V84S1047.2,33.783,689.219,33.783,0,84,0,84Z" fill="#f9f9f9"></path>
            </svg>
        </div>


        <div class="breadcrumb-bar">
            <div class="container">
          
                <ol class="breadcrumb">
                   <li class="breadcrumb-item"><a href="/"><i class="fa fa-home"></i></a></li>
                   {{-- <li class="breadcrumb-item"><a href="/">{{ $category->id }}</a></li> --}}
                   <li class="breadcrumb-item">{{ $category->name }}</li>
                </ol>
            </div>
        </div>

        <section class="section_all_products">
            <div class="container">
                  @if (session()->has('success'))
    <div class="alert alert-success">
        {{ session()->get('success') }}
    </div>
@endif
                <div class="sec_head text-center">
                    <h3>{{$category->name}}</h3>
                    <p>@lang('home.bestcart')</p>
                </div>
            @if($carts->count()  >= 1)   
            @if(! $markets[0]  == null)
                <div class="row">
                @foreach($markets as $market)

                    <div class="col-md-3">
                        
                        <div class="box-card" 
                        style="background: linear-gradient(180deg, {{ $category->color_1 }} 0%, {{ $category->color_2 }} 100%);" 
                        data-color1="#ff0000" data-color2="" onchange="pickRedInt()">
                        <a href="{{ route('show_carts',[$category->id,$market->id]) }}" class="non">
                            <div class="title-card">
                                <img src="{{ asset('uploads/cart_images/' .$SubImage->cart_details->image)}}" class="pb-3" alt="" width="100px">
                                <h6 class="pt-4">@lang('home.cart_market') {{ $category->name }}</h6>
                                <p style="color: #ddd" class="pt-4">@lang('home.market')</p>
                                <h2>{{ $market->name }}</h2>
                                <span class="spa-im"><img class="flag-pro" src="{{ $market->image_path }}" ></span>
                            </div>
                        </a>
                        </div>
                    </div>

                @endforeach

                </div>
            @else
                <div class="row">
                @foreach($carts as $cart)

                    <div class="col-md-3">
                        <div class="box-card" style="background: linear-gradient(180deg, {{ $cart->sub_category->color_1 }} 0%, {{ $cart->sub_category->color_2 }} 100%);">
                        <a href="{{ route('product_show_details',[$category->id,$cart->id]) }}" class="non">
                            <div class="title-card">
                                <center><img src="{{ asset('uploads/cart_images/' . $cart->cart_details->image)}}" width="100px"></center>
                                <h2>{{ $cart->cart_details->cart_name }}</h2>
                                <p>{{ $cart->cart_details->short_descript }}</p>
                                <span>
                                @if($cart->market_id == null)
                                       
                                @else
                                {{ $cart->Market->name }}
                                @endif
                                </span>
                                <strong>@lang('home.balance')<br> {{$cart->balance}} @if($cart->market_id == null)
                                            
                                    @else
                                        {{ $cart->Market->balance_type }}
                                    @endif</strong>
                            </div>
                             @if ($cart->quantity > 0)

                                    <div class="row d-flex justify-content-around">
                                        

                                        <ul class="option-card" style="width: 45%;padding: 0px;;">
                                            <form action="{{ route('storeToPayment', $cart) }}" method="post">
                                                {{ csrf_field() }}
                                                {{ method_field('post') }}
                                                <li style="padding: 7px 0;">
                                                    <a>
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
                </div>

                <section class="section_all_products">
                    <div class="container">
                         @if ($cart->Subscrip_status == 1)
                        
                            <div class="sec_head text-center">
                                <h3>{{$category->name}}</h3>
                                <p>@lang('dashboard.Subscrip_status')</p>
                            </div>
        
                        @endif
        
        
                        @foreach ($carts as $cart)
        
                        @if ($cart->Subscrip_status == 1)
        
                        <div class="col-md-3">
                                <div class="box-card" style="background: linear-gradient(180deg, {{ $cart->sub_category->color_1 }} 0%, {{ $cart->sub_category->color_2 }} 100%);">
                                <a href="{{ route('product_show_details',[$category->id,$cart->id]) }}" class="non">
                                    <div class="title-card">
                                        <center><img src="{{ asset('uploads/cart_images/' . $cart->cart_details->image)}}" width="100px"></center>
                                        <h2>{{ $cart->cart_details->cart_name }}</h2>
                                        <p>{{ $cart->cart_details->short_descript }}</p>
                                        <span>
                                        @if($cart->market_id == null)
                                            
                                        @else
                                        {{ $cart->Market->name }}
                                        @endif
                                        </span>
                                        <strong>
                                            @lang('home.balance')<br>{{ $cart->balance }} {{ $cart->time_Subscrip }} 
                                        </strong>
                                        </div>
                                     @if ($cart->quantity > 0)

                                    <div class="row d-flex justify-content-around">
                                        

                                        <ul class="option-card" style="width: 45%;padding: 0px;;">
                                            <form action="{{ route('storeToPayment', $cart) }}" method="post">
                                                {{ csrf_field() }}
                                                {{ method_field('post') }}
                                                <li style="padding: 7px 0;">
                                                    <a>
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
                            
                        @endif
                            
                        @endforeach
                    </div>
                </section>
            @endif


            @endif
            </div>

            
        </section>
        
    @endforeach

            
                            
                
@endsection
