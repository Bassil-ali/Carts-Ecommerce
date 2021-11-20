@extends('layouts.home.app')

@section('content')
        
<div class="breadcrumb-bar">
    <div class="container">
        @foreach ($errors->all() as $error)
        <p class="text-danger">
            {{ $error }}
        </p>
        @endforeach
        <ol class="breadcrumb">
           <li class="breadcrumb-item"><a href="/"><i class="fa fa-home"></i></a></li>
           <li class="breadcrumb-item">@lang('home.mjal_stars')</li>
        </ol>
    </div>
</div>
       <section class="section_ticit_supp">
            <div class="container">

                @if (session()->has('success'))
                <div class="alert alert-success" style="text-align: center">
                    {{ session()->get('success') }}
                </div>
            @endif
                @if(count($errors) > 0)
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
                <div class="complete-req">
                    <figure><img src="{{asset('home_file/images/stars.svg')}}" alt="" /></figure>
                    <div class="sec-title">
                        <h2>@lang('home.mjal_stars')</h2>
                        <p>@lang('home.mjal_stars_text')</p>
                        <h5>@lang('home.mjal_stars_pointer')</h5>
                        <p>@lang('home.mjal_stars_body')</p>


                        <h5>your stars: {{\Auth::guard('cliants')->user()->stars }}⭐</h5>
                        <br>
                        <a class="btn btn-dark" @if(\Auth::guard('cliants')->user()->stars < 100) title="you dont have enough stars" href="#" @endif  }} href="{{ route('ConvertStars') }}"><span>@lang('home.convert')</span></a>

                    </div>
                </div>
                <div class="sec-proposed">
                    <div class="sec_head">
                        <h3>@lang('home.get_gifts')</h3>
                    </div>
                    <div class="row">
                     
                        @foreach($carts as $cart)

                        <div class="col-md-3">
                            <div class="box-card" style="background: linear-gradient(180deg, {{ $cart->sub_category->color_1 }} 0%, {{ $cart->sub_category->color_2 }} 100%);">
                            <a href="{{ route('product_show_details',[$cart->sub_category->id,$cart->id]) }}" class="non">
                                <div class="title-card">
                                    <center><img src="{{ $cart->cart_details->image_path }}" width="100%"></center>                                   
                                    <h2>{{ $cart->cart_details->cart_name }}</h2>
                                    <p>{{ $cart->cart_details->short_descript }}</p>
                                    <span>
                                    @if($cart->market_id == null)
                                           -
                                    @else
                                    {{ $cart->Market->name }}
                                    @endif
                                    </span>
                                    <strong>@lang('home.balance')<br> {{$cart->balance}}  @if($cart->market_id == null)
                                            
                                    @else
                                        {{ $cart->Market->balance_type }}
                                    @endif</strong>
                                </div>
                                <ul class="option-card">
                                @if ($cart->quantity > 0)
                               
                                <li>
                                    <a>
                                        <img src="{{ asset('home_file/images/shopping-cart.svg') }}">
                                       
                                        <span>
                                            @if ($cart->amrecan_price > 0)

                                            @if(\Auth::guard('cliants')->user()->isVerified == 1 && \Auth::guard('cliants')->user()->emailVerified == 1)
                                            <form action="{{ route('order_by_satrs', $cart) }}" method="post">                                        
                                                {{ csrf_field() }}
                                                {{ method_field('post') }}
                                                <button type="submit" class="button button-plain"
                                                >{{ $cart->stars *  $cart->quantity}} ⭐  @lang('home.pay_cart')</button>
                                            </form>
                                            @else
                                            <span style="color: black">verify your email and phone</span>

                                            @endif
                                            @endif
                                        </span>
                                    </a>
                                </li>
                                @else
                              <span style="color: black">not avialable</span>
                                @endif
                            </ul>
                            </a>
                            </div>
                        </div>
    
                    @endforeach
                    </div>
                </div>
            </div>
        </section> 
        <!--section_ticit_supp-->   
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