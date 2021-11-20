

<!DOCTYPE html>
<html>

<head>
	<meta content="text/html; charset=UTF-8" http-equiv="Content-Type" />
	<meta content="telephone=no" name="format-detection" />
	<meta content="width=mobile-width; initial-scale=1.0; maximum-scale=1.0; user-scalable=no;" name="viewport" />


	<meta content="IE=9; IE=8; IE=7; IE=EDGE;" http-equiv="X-UA-Compatible" />
	<title>Ingredients</title>

	<link href="https://fonts.googleapis.com/css?family=Cairo&display=swap" rel="stylesheet">

	
	<style>
	 
		@media only screen and (max-width: 991px) {
		  .latter, .box-latter {
			width: 100% !important;
			padding: 20px 0 !important;
		   }
		}
		
	</style>
</head>

<body style="background:#F0F2F7;font-family: Cairo !important">
    <div class="box-latter" style=" width:30%;margin: auto;background: #F0F2F7;padding: 20px;">
        <div style="text-align: center;">
            <style scoped>
                @media only screen and (max-width: 991px) {
        		  .latter, .box-latter {
        			width: 100% !important;
        			padding: 20px 0 !important;
        		   }
        		}
                }
            </style>
            <div class="logo-site">
                <a href=""><img src="{{ asset('home_file/images/logo.svg')}}" alt="" class="img-responsive"></a>
            </div>
            <h4 style=" text-align:center;font-size: 25px;margin: 10px 0 0;">مرحباً  <br>{{\Auth::guard('cliants')->user()->name}}</h4><br><br>

        </div>
        <div  style="background:#fff !important;padding:20px;border-radius:10px;">
        	<div class="content-ing" style="margin-bottom:40px;text-align: center;">
				<h2 style="text-align: center;font-size:19px;">عمليه شراء ناجحة بمبلغ اجمالي {{ Session::get('price_icon')}} {{Cart::subtotal()}} </h2>

				
					@foreach(\Cart::content() as $sess)
					<p style="text-align: center"><strong>  المنتج: </strong> {{$sess->model->cart_details->cart_name}}</p>
					<p  style="text-align: center"><strong>الكمية:</strong>{{$sess->qty}}</p>
					<strong  style="text-align: center">@lang('home.balance')<br> {{$sess->model->balance}}  
					    @if($sess->model->market_id == null)
						@if ($sess->model->Subscrip_status == 1)

						{{ $sess->model->time_Subscrip }}
						@endif
						@else
						@if ($sess->model->Subscrip_status == 1)

						{{ $sess->model->time_Subscrip }}
						@else
							{{ $sess->model->Market->balance_type }}
						@endif
						@endif</strong>
					<div class="list-ing" style=" border-bottom: 1px solid #ececec;margin-bottom: 10px; padding-bottom: 10px;">
						<p style="text-align: center;margin:0;">كود البطاقة</p>
                    @foreach(\App\Models\CartStore::where('products_id',$sess->id)->take($sess->qty)->get(['cart_code'])->pluck('cart_code')->toArray() as $val)
                    <a href="" style="overflow-wrap: break-word">{!! implode($val->cart_code,'<br>') !!}</a><br>
					</div>
                   
					@endforeach
				    @endforeach

            	<div class="list-ing" style=" border-bottom: 1px solid #ececec;margin-bottom: 10px; padding-bottom: 10px;">
            	</div>
            	<div>
            	    <p style="text-align:center;margin-bottom: 0; font-weight: 600">تحياتنا</p>
            	    <p style="text-align:center;margin-top: 0; font-weight: 600">فريق مجال ستور</p>
            	</div>
        	</div>
        </div>
    </div>


</body>

</html>
