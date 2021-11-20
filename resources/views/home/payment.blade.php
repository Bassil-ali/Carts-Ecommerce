@extends('layouts.home.app')

@section('content')
<!--header-->
<div class="breadcrumb-bar">
    <div class="container">
        @foreach ($errors->all() as $error)
        <p class="text-danger">
            {{ $error }}
        </p>
        @endforeach
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="/">
                    <i class="fa fa-home">
                    </i>
                </a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('wallet.index') }}">
                    @lang('home.paymaent')
                </a>
            </li>
            <li class="breadcrumb-item">
                @lang('home.paying_off')
            </li>
        </ol>
    </div>
</div>
<!--breadcrumb-bar-->
<section class="section_payment">
    <div class="container">
        <div class="sec_head text-center">
            <h2>
                @lang('home.paying_off')
            </h2>
        </div>
        <div class="">
            <ul class="option-payment">
                <li>
                    <p>
                        @lang('home.tota')
                    </p>
                    <h6>
                        {{ Session::get('price_icon')}} {{$newTotal}}
                    </h6>
                </li>
                <li>
                    <p>
                        @lang('home.value_added_tax')
                    </p>
                    <h6>
                        {{ Session::get('price_icon')}} {{number_format($newTax,2,".",",")}}
                    </h6>
                </li>
                <li>
                    <p>
                        @lang('home.the_final_total')
                    </p>
                    <h6 class="total-final">
                        {{ Session::get('price_icon')}} {{number_format($newTotalwithTax,2,".",",")}}
                    </h6>
                </li>
            </ul>
        </div>
        @if(Auth::guard('cliants')->check())
        <div class="content-payment">
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item">
                    <a aria-controls="mada" aria-selected="true" class="nav-link active" data-toggle="tab" href="#mada" id="mada-tab" role="tab">
                        <img src="{{ asset('home_file/images/mada.png')}}"/>
                    </a>
                </li>

                <li class="nav-item">
                    <a aria-controls="balance" aria-selected="false" class="nav-link" data-toggle="tab" href="#balance" id="Etherum-tab" role="tab">
                        <img src="{{ asset('home_file/images/balance.svg') }}" width="30px"/>
                        <span>
                          
                        </span>
                    </a>
                </li>
                <li class="nav-item">
                    <a aria-controls="visa" aria-selected="false" class="nav-link" data-toggle="tab" href="#visa" id="visa-tab" role="tab">
                        <img src="{{ asset('home_file/images/visa.png')}}"/>
                    </a>
                </li>
                <li class="nav-item">
                    <a aria-controls="master_card" aria-selected="false" class="nav-link" data-toggle="tab" href="#master_card" id="master_card-tab" role="tab">
                        <img src="{{ asset('home_file/images/master_card.png')}}"/>
                    </a>
                </li>
                <li class="nav-item">
                    <a aria-controls="paypal" aria-selected="false" class="nav-link" data-toggle="tab" href="#paypal" id="paypal-tab" role="tab">
                        <img src="{{ asset('home_file/images/paypal.png')}}"/>
                        <span>
                            @lang('home.PayPal')
                        </span>
                    </a>
                </li>
                <li class="nav-item">
                    <a aria-controls="apple-pay" aria-selected="false" class="nav-link" data-toggle="tab" href="#apple-pay" id="apple-pay-tab" role="tab">
                        <img src="{{ asset('home_file/images/apple-pay.png')}}"/>
                        <span>
                            @lang('home.Apple_Pay')
                        </span>
                    </a>
                </li>
                <li class="nav-item">
                    <a aria-controls="stc" aria-selected="false" class="nav-link" data-toggle="tab" href="#stc" id="stc-tab" role="tab">
                        <img src="{{ asset('home_file/images/stc.png')}}"/>
                        <span>
                            @lang('home.STC_Pay')
                        </span>
                    </a>
                </li>
                <li class="nav-item">
                    <a aria-controls="pay-bank" aria-selected="false" class="nav-link" data-toggle="tab" href="#pay-bank" id="pay-bank-tab" role="tab">
                        <img src="{{ asset('home_file/images/pay-bank.png')}}"/>
                        <span>
                            @lang('home.Bank_transfer')
                        </span>
                    </a>
                </li>
                <li class="nav-item">
                    <a aria-controls="usdt" aria-selected="false" class="nav-link" data-toggle="tab" href="#usdt" id="usdt-tab" role="tab">
                        <img src="{{ asset('home_file/images/usdt.jpeg') }}" width="30px"/>
                        <span>
                            @lang('home.USDT')
                        </span>
                    </a>
                </li>
                <li class="nav-item">
                    <a aria-controls="Bitcoin" aria-selected="false" class="nav-link" data-toggle="tab" href="#Bitcoin" id="Bitcoin-tab" role="tab">
                        <img src="{{ asset('home_file/images/Bitcoin.jpeg') }}" width="30px"/>
                        <span>
                            @lang('home.Bitcoin')
                        </span>
                    </a>
                </li>
                <li class="nav-item">
                    <a aria-controls="Dogecoin" aria-selected="false" class="nav-link" data-toggle="tab" href="#Dogecoin" id="Dogecoin-tab" role="tab">
                        <img src="{{ asset('home_file/images/Dogecoin.jpeg')}}" width="30px"/>
                        <span>
                            @lang('home.Dogecoin')
                        </span>
                    </a>
                </li>
                <li class="nav-item">
                    <a aria-controls="Etherum" aria-selected="false" class="nav-link" data-toggle="tab" href="#Etherum" id="Etherum-tab" role="tab">
                        <img src="{{ asset('home_file/images/Etherum.jpeg') }}" width="30px"/>
                        <span>
                            @lang('home.Etherum')
                        </span>
                    </a>
                </li>

                
            </ul>
            <div class="tab-content" id="myTabContent">
                <h4>
                    @lang('home.card_data')
                </h4>
                <div aria-labelledby="mada-tab" class="tab-pane fade" id="mada" role="tabpanel">
                    <form class="form-payment">
                        <div class="form-group">
                            <label>
                                @lang('home.name_card_data')
                            </label>
                            <input class="form-control" placeholder="@lang('home.name_card_data')" type="text"/>
                        </div>
                        <div class="form-group">
                            <label>
                                @lang('home.card_data')
                            </label>
                            <input class="form-control" placeholder="0000  - 0000  -  0000  -  0000" type="text"/>
                        </div>
                        <div class="form-group">
                            <label>
                                @lang('home.data_cart')
                            </label>
                            <input class="form-control" placeholder="MM/YY" type="text"/>
                        </div>
                        <div class="form-group">
                            <label>
                                @lang('home.vary_cart')
                            </label>
                            <input class="form-control" placeholder="CVV" type="text"/>
                        </div>
                    </form>
                </div>

                <div aria-labelledby="balance-tab" class="tab-pane fade" id="balance" role="tabpanel">

                    <form  action="{{route('balance_bay')}}">
                    <center>

                        <h5>Your balance in the wallet <span style="color: blue">{{Auth::guard('cliants')->user()->account_balance }} $</span></h5><br>

                        <h5>the final total of price carts <span style="color: blue">{{number_format($newTotalwithTax,2,".",",") }} $</span></h5><br>

                    @if(!Auth::guard('cliants')->user()->account_balance >=  number_format($newTotalwithTax,2,".",","))

                        <h5 style="color: crimson">you do not have enough credit to buy</h5>

                        @else
                       
                    </li>
                    @if(Auth::guard('cliants')->user()->isVerified == 1  && Auth::guard('cliants')->user()->emailVerified == 1)
    
                            @if(Auth::guard('cliants')->user()->code_cart_email == 1 || Auth::guard('cliants')->user()->code_cart_phone == 1)
                   
                        <a class="btn-site btn-shop" href="{{route('balance_bay')}}">
                            <span>
                                @lang('home.Confirm_payment')
                            </span>
                        </a>
                   
                    @endif
                            @else
                   
                        <button class="btn-site btn-shop">
                            <span>
                                @lang('home.please_activate')
                            </span>
                        </button>
                    
                    @endif
                    @endif
                       </center>
                   
                      
                          
                       
                    </form>
                </div>

                
                <div aria-labelledby="visa-tab" class="tab-pane fade" id="visa" role="tabpanel">
                    <form class="form-payment">
                        <div class="form-group">
                            <label>
                                @lang('home.name_card_data')
                            </label>
                            <input class="form-control" placeholder="@lang('home.name_card_data')" type="text"/>
                        </div>
                        <div class="form-group">
                            <label>
                                @lang('home.card_data')
                            </label>
                            <input class="form-control" placeholder="0000  - 0000  -  0000  -  0000" type="text"/>
                        </div>
                        <div class="form-group">
                            <label>
                                @lang('home.data_cart')
                            </label>
                            <input class="form-control" placeholder="MM/YY" type="text"/>
                        </div>
                        <div class="form-group">
                            <label>
                            </label>
                            <input class="form-control" placeholder="CVV" type="text"/>
                        </div>
                    </form>
                </div>
                <div aria-labelledby="master_card-tab" class="tab-pane fade" id="master_card" role="tabpanel">
                    <form class="form-payment">
                        <div class="form-group">
                            <label>
                                @lang('home.name_card_data')
                            </label>
                            <input class="form-control" placeholder="@lang('home.name_card_data')" type="text"/>
                        </div>
                        <div class="form-group">
                            <label>
                                @lang('home.card_data')
                            </label>
                            <input class="form-control" placeholder="0000  - 0000  -  0000  -  0000" type="text"/>
                        </div>
                        <div class="form-group">
                            <label>
                                @lang('home.data_cart')
                            </label>
                            <input class="form-control" placeholder="MM/YY" type="text"/>
                        </div>
                        <div class="form-group">
                            <label>
                                @lang('home.vary_cart')
                            </label>
                            <input class="form-control" placeholder="CVV" type="text"/>
                        </div>
                    </form>
                </div>
                <div aria-labelledby="paypal-tab" class="tab-pane fade show active" id="paypal" role="tabpanel">
                    <form class="form-payment">
                        <div class="form-group">
                            <label>
                                @lang('home.name_card_data')
                            </label>
                            <input class="form-control" placeholder="@lang('home.name_card_data')" type="text"/>
                        </div>
                        <div class="form-group">
                            <label>
                                @lang('home.card_data')
                            </label>
                            <input class="form-control" placeholder="0000  - 0000  -  0000  -  0000" type="text"/>
                        </div>
                        <div class="form-group">
                            <label>
                                @lang('home.data_cart')
                            </label>
                            <input class="form-control" placeholder="MM/YY" type="text"/>
                        </div>
                        <div class="form-group">
                            <label>
                                @lang('home.vary_cart')
                            </label>
                            <input class="form-control" placeholder="CVV" type="text"/>
                        </div>
                    </form>
                </div>
                <div aria-labelledby="apple-pay-tab" class="tab-pane fade" id="apple-pay" role="tabpanel">
                    <form class="form-payment">
                        <div class="form-group">
                            <label>
                                @lang('home.name_card_data')
                            </label>
                            <input class="form-control" placeholder="@lang('home.name_card_data')" type="text"/>
                        </div>
                        <div class="form-group">
                            <label>
                                @lang('home.card_data')
                            </label>
                            <input class="form-control" placeholder="0000  - 0000  -  0000  -  0000" type="text"/>
                        </div>
                        <div class="form-group">
                            <label>
                                @lang('home.data_cart')
                            </label>
                            <input class="form-control" placeholder="MM/YY" type="text"/>
                        </div>
                        <div class="form-group">
                            <label>
                                @lang('home.vary_cart')
                            </label>
                            <input class="form-control" placeholder="CVV" type="text"/>
                        </div>
                    </form>
                </div>
                <div aria-labelledby="stc-tab" class="tab-pane fade" id="stc" role="tabpanel">
                    <form class="form-payment">
                        <div class="form-group">
                            <label>
                                @lang('home.name_card_data')
                            </label>
                            <input class="form-control" placeholder="@lang('home.name_card_data')" type="text"/>
                        </div>
                        <div class="form-group">
                            <label>
                                @lang('home.card_data')
                            </label>
                            <input class="form-control" placeholder="0000  - 0000  -  0000  -  0000" type="text"/>
                        </div>
                        <div class="form-group">
                            <label>
                                @lang('home.data_cart')
                            </label>
                            <input class="form-control" placeholder="MM/YY" type="text"/>
                        </div>
                        <div class="form-group">
                            <label>
                                @lang('home.vary_cart')
                            </label>
                            <input class="form-control" placeholder="CVV" type="text"/>
                        </div>
                    </form>
                </div>
                <div aria-labelledby="pay-bank-tab" class="tab-pane fade" id="pay-bank" role="tabpanel">
                    <form class="form-payment">
                        <div class="form-group">
                            <label>
                                @lang('home.name_card_data')
                            </label>
                            <input class="form-control" placeholder="@lang('home.name_card_data')" type="text"/>
                        </div>
                        <div class="form-group">
                            <label>
                                @lang('home.card_data')
                            </label>
                            <input class="form-control" placeholder="0000  - 0000  -  0000  -  0000" type="text"/>
                        </div>
                        <div class="form-group">
                            <label>
                                @lang('home.data_cart')
                            </label>
                            <input class="form-control" placeholder="MM/YY" type="text"/>
                        </div>
                        <div class="form-group">
                            <label>
                                @lang('home.vary_cart')
                            </label>
                            <input class="form-control" placeholder="CVV" type="text"/>
                        </div>
                    </form>
                </div>

              
                
                @foreach ($pay_currencie as $pay)
                <div aria-labelledby="{{ $pay->name }}-tab" class="tab-pane fade" id="{{ $pay->name }}" role="tabpanel">
                    <form action="{{ route('order.pay.store') }}" class="form-payment mt-5" enctype="multipart/form-data" method="post">
                        {{ csrf_field() }}
                              {{ method_field('post') }}
                        <div class="mb-3">
                            <label class="form-label" for="formFile">
                                <i class="fa fa-image">
                                </i>
                                <h4>add  invoice</h4>
                            </label>
                            <input class="form-control" id="formFile" name="image" type="file">
                            </input>
                        </div>
                        <div class="container-fluid">
                            <div class="row justify-content-center">
                                <p>
                                    {{ $pay->link }}
                                    <a class="copy-ad">
                                        نسخ الرابط
                                    </a>
                                </p>
                            </div>
                        </div>
                        <div class="container-fluid mt-5">
                            <div class="row justify-content-center">
                                <img src="{{ $pay->image_path }}" width="200px"/>
                            </div>
                        </div>
                        @if(Auth::guard('cliants')->user()->isVerified == 1  && Auth::guard('cliants')->user()->emailVerified == 1)

                        @if(Auth::guard('cliants')->user()->code_cart_email == 1 || Auth::guard('cliants')->user()->code_cart_phone == 1)
                       
                            <a class="btn-site btn-shop" href="{{route('order.pay.store')}}">
                                <span>
                                    @lang('home.Confirm_payment')
                                </span>
                            </a>
                      
                        @endif
                        @else
                       
                            <button class="btn-site btn-shop">
                                <span>
                                    @lang('home.please_activate')
                                </span>
                            </button>
                        
                        @endif
                    </form>
                </div>
                @endforeach
            </div>
            <ul class="brn-payment">
                <li>
                    <button class="btn-cancel">
                        @lang('dashboard.no')
                    </button>
                </li>
                @if(Auth::guard('cliants')->user()->isVerified == 1  && Auth::guard('cliants')->user()->emailVerified == 1)

                        @if(Auth::guard('cliants')->user()->code_cart_email == 1 || Auth::guard('cliants')->user()->code_cart_phone == 1)
                <li>
                    <a class="btn-site btn-shop" href="{{route('payment-store')}}">
                        <span>
                            @lang('home.Confirm_payment')
                        </span>
                    </a>
                </li>
                @endif
                        @else
                <li>
                    <button class="btn-site btn-shop">
                        <span>
                            @lang('home.please_activate')
                        </span>
                    </button>
                </li>
                @endif
            </ul>
            @else
            <section class="payment">
                <div class="container">
                    <div class="row">
                        <div class="col-md-12 payment-method margin-top-25">
                            <div class="intro-title">
                                <h1>
                                    @lang('home.paying_off')
                                </h1>
                            </div>
                        </div>
                        <div class="row text-center">
                            <h3 class="orders_colosed_text">
                                @lang('home.')
                            </h3>
                            <img class="orders_colosed_img " src="{{ asset('images/not_auth.svg')}}">
                                <div>
                                    <h3 class="orders_colosed_text" data-target="#exampleModal" data-toggle="modal" onclick="showLoginModal()" style="cursor: pointer;">
                                        @lang('home.login')
                                    </h3>
                                </div>
                            </img>
                        </div>
                    </div>
                </div>
            </section>
            @endif
        </div>
    </div>
</section>
<!--section_ticit_supp-->
@endsection