@extends('layouts.dashboard.app')

@section('content')

<div class="c-subheader justify-content-between px-3">

    <ol class="breadcrumb border-0 m-0 px-0 px-md-3">
        <li class="breadcrumb-item">@lang('dashboard.dashboard')</li>
        <li class="breadcrumb-item"><a href="{{ route('dashboard.carts_detail.index') }}">@lang('dashboard.carts_detail')</a></li>
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
            
            @if(Session::has('error'))
<div class="alert alert-danger">
  {{ Session::get('error')}}
</div>
@endif
            
            <div class="row">

                <div class="col-sm-12">


                    <form action="{{ route('dashboard.carts_detail.store') }}" method="post" enctype="multipart/form-data">

                        {{ csrf_field() }}
                        {{ method_field('post') }}


                        <div class="form-group">
                            <label>@lang('dashboard.cart_name')</label>
                            <input type="text" name="cart_name" class="form-control{{ $errors->has('cart_name') ? ' is-invalid' : '' }}" value="{{ old('cart_name') }}">
                            @if ($errors->has('cart_name'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('cart_name') }}</strong>
                                </span>
                            @endif
                        </div>


                        <div class="form-group">
                            <label>@lang('dashboard.cart_name_en')</label>
                            <input type="text" name="cart_name_en" class="form-control{{ $errors->has('cart_name_en') ? ' is-invalid' : '' }}" value="{{ old('cart_name_en') }}">
                            @if ($errors->has('cart_name_en'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('cart_name_en') }}</strong>
                                </span>
                            @endif
                        </div>

                      
                        <div class="form-group">
                            <label>@lang('dashboard.cart_text')</label>
                            <input type="text" name="cart_text" class="form-control{{ $errors->has('cart_text') ? ' is-invalid' : '' }}" value="{{ old('cart_text') }}">
                            @if ($errors->has('cart_text'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('cart_text') }}</strong>
                                </span>
                            @endif
                        </div>

                        <div class="form-group">
                            <label>@lang('dashboard.cart_text_en')</label>
                            <input type="text" name="cart_text_en" class="form-control{{ $errors->has('cart_text_en') ? ' is-invalid' : '' }}" value="{{ old('cart_text_en') }}">
                            @if ($errors->has('cart_text_en'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('cart_text_en') }}</strong>
                                </span>
                            @endif
                        </div>


                        <div class="form-group">
                            <label>@lang('dashboard.short_descript')</label>
                            <input type="text" name="short_descript" class="form-control{{ $errors->has('short_descript') ? ' is-invalid' : '' }}" value="{{ old('short_descript') }}">
                            @if ($errors->has('short_descript'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('short_descript') }}</strong>
                                </span>
                            @endif
                        </div>

                        <div class="form-group">
                            <label>@lang('dashboard.short_descript_en')</label>
                            <input type="text" name="short_descript_en" class="form-control{{ $errors->has('short_descript_en') ? ' is-invalid' : '' }}" value="{{ old('short_descript_en') }}">
                            @if ($errors->has('short_descript_en'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('short_descript_en') }}</strong>
                                </span>
                            @endif
                        </div>

                     


                        <div class="form-group">
                            <label>@lang('dashboard.image')</label>
                            <input type="file" name="image" class="form-control{{ $errors->has('image') ? ' is-invalid' : '' }} image" value="{{ old('image') }}">
                            @if ($errors->has('image'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('image') }}</strong>
                                </span>
                            @endif
                        </div>

                        <div class="form-group">
                            <img src="{{ asset('uploads/cart_images/default.png') }}"  style="width: 100px" class="img-thumbnail image-preview" alt="">
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
