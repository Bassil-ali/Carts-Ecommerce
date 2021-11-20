@extends('layouts.dashboard.app')

@section('content')

<div class="c-subheader justify-content-between px-3">

    <ol class="breadcrumb border-0 m-0 px-0 px-md-3">
        <li class="breadcrumb-item">@lang('dashboard.dashboard')</li>
        <li class="breadcrumb-item"><a href="{{ route('dashboard.social_links.index') }}">@lang('dashboard.social_links')</a></li>
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


                    <form action="{{ route('dashboard.social_links.store') }}" method="post">

                        {{ csrf_field() }}
                        {{ method_field('post') }}

                        @php
                            $social_sites = ['facebook','instagram','twitter','youtube','rss','phone','email','vedio'];
                        @endphp

                        @foreach ($social_sites as $social_site)

                            <div class="form-group">
                                <label>{{ $social_site }}_Line</label>
                                <input type="text" name="{{ $social_site }}_link" class="form-control" value="{{ setting( $social_site . '_link') }}">
                            </div>
                            
                        @endforeach

                        <div class="form-group">
                            <label>@lang('dashboard.location_ar')</label>
                            <input type="text" name="location_ar" class="form-control" value="{{ setting('location_ar') }}">
                        </div>

                        <div class="form-group">
                            <label>@lang('dashboard.location_ar')</label>
                            <input type="text" name="location_en" class="form-control" value="{{ setting('location_en') }}">
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
