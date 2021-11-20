@extends('layouts.dashboard.app')

@section('content')

<div class="c-subheader justify-content-between px-3">

    <ol class="breadcrumb border-0 m-0 px-0 px-md-3">
        <li class="breadcrumb-item">@lang('dashboard.dashboard')</li>
        <li class="breadcrumb-item"><a href="{{ route('dashboard.carts.index') }}">@lang('dashboard.carts')</a></li>
        <li class="breadcrumb-item active">@lang('dashboard.add')</li>
    </ol>

</header>

<div class="c-body">
    <main class="c-main">

<div class="col-sm-12 col-md-12">
    <div class="card">
        <div class="card-header">
            <strong>@lang('dashboard.add')</strong>
        </div>
        <div class="card-body">
            
            <div class="row">

                <div class="col-sm-12">


                    <form action="{{ route('dashboard.carts.store') }}" method="post" enctype="multipart/form-data">

                        {{ csrf_field() }}
                        {{ method_field('post') }}

                        <div class="form-group">
                            <label>@lang('dashboard.carts_detail')</label>
                            <select name="cart_details_id" class="form-control">
                            @foreach ($carts_details as $carts_detail)
                                <option value="{{ $carts_detail->id }}" > {{ $carts_detail->cart_name }}</option>
                            @endforeach
                            </select>
                        </div>


                        <div class="form-group">
                            <label>@lang('dashboard.markets')</label>
                            <select name="market_id" class="form-control">
                                <option value="">@lang('dashboard.not-market')</option>
                            @foreach ($markets as $market)
                                <option value="{{ $market->id }}" > {{ $market->name }}</option>
                            @endforeach
                            </select>
                        </div>
                      

                        <div class="form-group">
                            <label>@lang('dashboard.sub_categories')</label>
                            <select name="sub_category_id" class="form-control">
                            @foreach ($sub_categorys as $sub_category)
                                <option value="{{ $sub_category->id }}"> {{ $sub_category->name }}</option>
                            @endforeach
                            </select>

                            @if ($errors->has('sub_category_id'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('sub_category_id') }}</strong>
                            </span>
                        @endif
                        </div>

    
                        <div class="form-group">
                            <label>@lang('dashboard.balance')</label>
                            <input type="text" name="balance" min="0" value="0" step="any" class="form-control{{ $errors->has('balance') ? ' is-invalid' : '' }}" value="{{ old('balance') }}">
                            @if ($errors->has('balance'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('balance') }}</strong>
                                </span>
                            @endif
                        </div>

                        <div class="form-group">
                            <label>@lang('dashboard.rating')</label>
                            <select name="rating" class="form-control">
                                @for ($i = 0; $i < 7; $i++)
                                    <option value="{{ $i }}">{{ $i }}</option>
                                @endfor
                            </select>
                        </div>

                        <div class="form-group">
                            <label>@lang('dashboard.stars')</label>
                            <input type="number" name="stars" min="0" value="0"  class="form-control{{ $errors->has('balance') ? ' is-invalid' : '' }}" value="{{ old('stars') }}">
                            @if ($errors->has('balance'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('stars') }}</strong>
                                </span>
                            @endif
                        </div>


                        <div class="form-group">
                            <label>@lang('dashboard.amrecan_price')</label>
                            <input type="number" name="amrecan_price" step="any" class="form-control{{ $errors->has('amrecan_price') ? ' is-invalid' : '' }}" value="{{ old('amrecan_price') }}">
                            @if ($errors->has('amrecan_price'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('amrecan_price') }}</strong>
                                </span>
                            @endif
                        </div>
                        
                        <div id="coupon_question" class="form-check form-switch my-4">
                          <input  class="form-check-input togglebtn" value="1" name="Subscrip_status" type="checkbox" id="flexSwitchCheckDefault">
                          <label class="form-check-label" for="flexSwitchCheckDefault">@lang('dashboard.Subscrip_status')</label>
                        </div>

                        <div class="form-group answer">
                            <label>@lang('dashboard.time_Subscrip')</label>
                            <input type="text" name="time_Subscrip" class="form-control{{ $errors->has('time_Subscrip') ? ' is-invalid' : '' }}" value="{{ old('time_Subscrip') }}">
                            @if ($errors->has('time_Subscrip'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('time_Subscrip') }}</strong>
                                </span>
                            @endif
                        </div>

                        <div class="form-group answer">
                            <label>@lang('dashboard.time_Subscrip_en')</label>
                            <input type="text" name="time_Subscrip_en" class="form-control{{ $errors->has('time_Subscrip_en') ? ' is-invalid' : '' }}" value="{{ old('time_Subscrip_en') }}">
                            @if ($errors->has('time_Subscrip_en'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('time_Subscrip_en') }}</strong>
                                </span>
                            @endif
                        </div>


                        <div class="form-group">
                            <button type="submit" class="btn btn-primary"><i class="fa fa-plus"></i> @lang('dashboard.add')</button>
                        </div>

                    </form><!-- end of form -->

                </div>

            </div>
            <!--/.row-->
        </div>

    </div>

</div>

@endsection
