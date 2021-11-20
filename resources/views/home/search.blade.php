@extends('layouts.home.app')

@section('content')

    <!--head_page-->
    <div class="breadcrumb-bar">
        <div class="container">
            <ol class="breadcrumb">
               <li class="breadcrumb-item"><a href="/"><i class="fa fa-home"></i></a></li>
               <li class="breadcrumb-item"><@lang('home.search_cart')</li>
            </ol>
        </div>
    </div>
    <!--breadcrumb-bar-->
    <section class="section_all_products">
        <div class="container">
            
          @if (session()->has('success'))
                <div class="alert alert-success">
                    {{ session()->get('success') }}
                </div>
            @endif
            
            @if($carts->count() > 1)
            <div class="row">
                @foreach($carts as $cart)
                <div class="col-md-3">
                    <div class="box-card" style="background: linear-gradient(180deg, {{ $cart->sub_category->color_1 }} 0%, {{ $cart->sub_category->color_2 }} 100%);">
                    <a href="{{ route('product_show_details',[$cart->sub_category->id,$cart->id]) }}" class="non">
                        <div class="title-card">
                            <center><img src="{{ asset('uploads/cart_images/' . $cart->cart_details->image)}}"></center>
                            <h2>{{ $cart->cart_details->cart_name }}</h2>
                            <p>{{ $cart->cart_details->short_descript }}</p>
                            <span>
                            @if($cart->market_id == null)
                                  -
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
            @else 

            <div class="sec_head text-center">
                <h2>@lang('dashboard.no_data_found')</h2>
            </div>
            @endif
        </div>
    </section>

   
    <!--section_best_sellers-->
@endsection

