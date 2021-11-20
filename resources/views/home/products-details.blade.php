@extends('layouts.home.app')
@section('content')

@foreach($sub_categories as $category)

    <div class="main-wrapper">      
        <div class="breadcrumb-bar">
            <div class="container">
                <ol class="breadcrumb">
                   <li class="breadcrumb-item"><a href="/"><i class="fa fa-home"></i></a></li>
                   {{-- <li class="breadcrumb-item"><a href="{{ route('show_carts',[$category->id , $market->id]) }}">{{$category->name}}</a></li> --}}
                   <li class="breadcrumb-item">{{$carts->cart_details->cart_name}}</li>
                </ol>
            </div>
        </div>
        <!--breadcrumb-bar-->
        
        <section class="section_details_product">
            <div class="container">
                <div class="content-details">
                    <div class="card-product">
                        <div class="box-card" style="background: linear-gradient(180deg, {{ $carts->sub_category->color_1 }} 0%, {{ $carts->sub_category->color_2 }} 100%);">
                            <div class="title-card">
                                <h2>{{ $carts->cart_details->cart_name }}</h2>
                                
                                <p>{{ $carts->cart_details->short_descript }}</p>
                                
                                <strong>@lang('home.balance')<br> {{$carts->balance}}</strong><br>
                                <strong>@lang('home.price') {{ $carts->amrecan_price }}{{ Session::get('price_icon')}}</strong>
                            
                            </div>
                        </div>
                    </div>
                    <div class="title-product">
                        <h2>{{$carts->cart_details->cart_name}}</h2>
                        <p>{{$carts->cart_details->cart_text}}</p>

                        <p>@lang('home.to_know') <a style="color: #401DB1" href="{{ route('How-Useage',$carts->sub_category_id ) }}">@lang('home.click_here')</a></p>

                        <div class="rate-line">
                            @for ($i = 0; $i < 6; $i++) 
                                {{-- expr --}}
                                <span class="zmdi zmdi-star {{ $carts->rating >= $i ? 'checked' : '' }}"></span>

                            @endfor
                        </div>

                    </div>
                    <ul class="option-card data-pro">
                        <div class="price-pr">
                            <span>@lang('home.price')</span> <strong>{{$carts->amrecan_price}} {{ Session::get('price_icon')}}</strong>
                        </div>
                    @if ($carts->quantity > 0)
                        {{-- expr --}}
                        <li><a class="btn-site btn-shop"><img src="{{ asset('home_file/images/surface.svg')}}" /><span>
                            @if ($carts->amrecan_price > 0)
                            <form action="{{ route('storeToPayment', $carts) }}" method="post">
                                {{ csrf_field() }}
                                {{ method_field('post') }}
                                <button type="submit" class="button button-plain butt-sm col-12 my-0">{{ $carts->amrecan_price }}{{ Session::get('price_icon')}}  @lang('home.pay_cart')</button>
                            </form>
                            @endif      
                        </span></a></li><li><a class="btn-site add-cart"
                                data-url="{{ route('wallet.store', $carts) }}"
                                data-method="post"
                                data-name="{{ $carts->cart_details->cart_name }}"
                                data-desc="{{ $carts->cart_details->short_descript }}"
                                data-id="{{ $carts->id }}"
                                data-price="{{ $carts->amrecan_price }}"
                            ><img src="{{ asset('images/shopping-blue.png')}}"><span>@lang('home.add_cart')   
                        </span></a>
                    </li>
                    <div class="btn btn-info borderd col-12 my-3" style="border-radius: 20px">
                            @lang('dashboard.stars') {{$carts->stars}} тнР
                        </div>
                    @else

                    <li><a class="btn-site btn-shop"><span>
                        @if ($carts->amrecan_price > 0)
                            <button type="submit" class="button button-plain butt-sm col-12 my-0">  @lang('home.not_available')
                            </button>
                        @endif      
                        </span>
                        </a>
                    </li>
                            @auth('cliants')
                                {{-- expr --}}
                            <li> <form action="{{ route('notify') }}" method="post">                                        
                                {{ csrf_field() }}
                                {{ method_field('get') }}

                                <input type="hidden" name="cliant_id" id="cliant_id_available" value="{{ Auth::guard('cliants')->user()->id}}">
                                <input type="hidden" name="cart_id" id="cart_id_available" value="{{ $carts->id }}">
                                <a class="btn-site remind-available"
                                                    data-url="{{ route('notify') }}"
                                                    data-method="get"
                                ><span>                                        
                                    @lang('home.notify_saving')
                                    </span>
                                </a>
                            </form>   
                    </li>
                            @else

                            <li><a class="btn-site" data-target="#exampleModal" data-toggle="modal"><span>                                        
                                    @lang('home.notify_saving')
                            </span>
                                </a>
                                </li>

                            @endauth


                    @endif
                        
                        </ul>
                </div>
            </div>
        </section> 
        
        <!--breadcrumb-bar-->   
        <section class="section_all_products">
            <div class="container">
                <div class="sec_head text-center">
                    <h3>@lang('home.suggest')</h3>
                    <p>@lang('home.bestcart')</p>
                </div>
                <div class="row">
                    @foreach($random_carts as $r_cart)

                    <div class="col-md-3">
                        <a href="{{ route('product_show_details',[$r_cart->sub_category->id,$r_cart->id]) }}">
                            <div class="box-card" style="background: linear-gradient(180deg, {{ $r_cart->sub_category->color_1 }} 0%, {{ $r_cart->sub_category->color_2 }} 100%);">
                            <div class="title-card">
                                <center><img src="{{ asset('uploads/cart_images/' . $r_cart->cart_details->image)}}" width="100px"></center>
                                <h2>{{ $r_cart->cart_details->cart_name }}</h2>
                                <p>{{ $r_cart->cart_details->short_descript }}</p>
                                <span>
                                @if($r_cart->market_id == null)
                                       
                                @else
                                {{ $r_cart->Market->name }}
                                @endif
                                </span>
                                <strong>@lang('home.balance')<br> {{$r_cart->balance}} @if($r_cart->market_id == null)
                                            
                                    @else
                                        {{ $r_cart->Market->balance_type }}
                                    @endif
                                </strong>
                            </div>
                             @if ($r_cart->quantity > 0)

                                    <div class="row d-flex justify-content-around">
                                        

                                        <ul class="option-card" style="width: 45%;padding: 0px;;">
                                            <form action="{{ route('storeToPayment', $r_cart) }}" method="post">
                                                {{ csrf_field() }}
                                                {{ method_field('post') }}
                                                <li style="padding: 7px 0;">
                                                    <a>
                                                        <img src="{{ asset('home_file/images/surface.svg') }}" class="mx-1" />
                                                        <span style="font-size: 10px">
                                                            {{ $r_cart->amrecan_price }}{{ Session::get('price_icon')}} @lang('home.pay_cart')
                                                        </span>
                                                    </a>
                                                </li>
                                            </form>
                                        </ul>

                                        <ul class="option-card" style="width: 45%;padding: 0px;">
                                            <li style="padding: 7px 0;">
                                                <a class="add-cart"
                                                        data-url="{{ route('wallet.store', $r_cart) }}"
                                                        data-method="post"
                                                        data-name="{{ $r_cart->cart_details->cart_name }}"
                                                        data-desc="{{ $r_cart->cart_details->short_descript }}"
                                                        data-id="{{ $r_cart->id }}"
                                                        data-price="{{ $r_cart->amrecan_price }}">
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
                                            <input type="hidden" name="cart_id" id="cart_id_available" value="{{ $r_cart->id }}">
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
                        </div>
                        </a>
                    </div>
                    
                   @endforeach
                </div>
            </div>
        </section>
        @endforeach
        <!--section_best_sellers-->
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
@endpush
