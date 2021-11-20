<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Mail\StarsOrder;
use App\Models\CartStore;
use App\Models\Cliant;
use App\Models\Notify;
use App\Models\Parent_Category;
use App\Models\Product;
use App\Models\Purchase;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Twilio\Rest\Client;


class StarsController extends Controller
{

    public function index()
    {

        if (session()->get('rate') == null) {

            session()->put('price_icon', '$');
            session()->put('rate', 'UST');

        }

        $parent_categories = Parent_Category::with('sub_category')->get();


        $carts =   Product::where('stars','>', 0)->where('Subscrip_status','=',null)->inRandomOrder()->take(4)->get();


        return view('home.stars',compact('parent_categories','carts'));
    }

 



    public function order_by_satrs(Request $request, $cart)
    {

        try {

            $cliant = Cliant::where('id', \Auth::guard('cliants')->user()->id)->first();

            if ($cliant->stars >= $request->stars) {

                $cliantStars = $cliant->stars;

                $cartStars = $request->stars;

                $stars = $cliantStars - $cartStars;

                DB::beginTransaction();

                $cliant->update([
                    'stars' => $stars,
                ]);

                $cart_category = Product::where('id', $cart)->first();

                $result = $cart_category->quantity - 1;
                $count  = $cart_category->count_of_buy + 1;
                Product::where('id', $product->id)->update([
                    'quantity'     => $result,
                    'count_of_buy' => $count,

                ]);

        $discount = session()->get('coupon')['discount'] ?? 0;
        $code = session()->get('coupon')['name'] ?? null;
        $tax = config('cart.tax') / 100;
        $number = Cart::subtotal();
        $convertNum = preg_replace('/,/', '', $number);
        $newSubtotal = ($convertNum - $discount);
        $newTotal = $newSubtotal;
        $newTax = $newSubtotal * $tax;
        $newTotalwithTax = $newTotal * (1 + $tax);

        $date = date("Y-M-D");

        $digits = 6;

        $coderandome =rand(pow(10, $digits-1), pow(10, $digits)-1);

        $carts =  CartStore::where('products_id',$cart)->take(1)->get(['cart_code'])->pluck('cart_code')->toArray();


                CartStore::where('products_id', $cart)->take(1)->update([
                    'used'      => 1,
                    'user_name' => \Auth::guard('cliants')->user()->name,
                    'cliant_id' => \Auth::guard('cliants')->user()->id,
                ]);

            $purchases = new Purchase();
            $purchases->number          = $coderandome;
            $purchases->cart_id         = $cart_category->id;
            $purchases->cart_name       = ['ar' =>$cart_category->cart_details->getTranslation('cart_name', 'ar'),'en' =>$cart_category->cart_details->getTranslation('cart_name', 'en')];
            $purchases->short_descript  = ['ar' =>$cart_category->cart_details->getTranslation('short_descript', 'ar'),'en' =>$cart_category->cart_details->getTranslation('short_descript', 'en')];
            $purchases->cart_text       = ['ar' =>$cart_category->cart_details->getTranslation('cart_text', 'ar'),'en' =>$cart_category->cart_details->getTranslation('cart_text', 'en')];
            $purchases->sub_category_id = $cart_category->sub_category_id;
            $purchases->purchases_status= 1;
            $purchases->users_id        = Auth::guard('cliants')->user()->id;
            $purchases->price           = $request->stars;
            $purchases->date            = $date;
            $purchases->code            = $carts;
            $purchases->quantity        = 1;
            if($cart_category->market_id == null){
                $purchases->market =   "none";
                }else{
                    $purchases->market =     ['ar' => $cart_category->getTranslation('name', 'ar'),'en' =>$cart_category->Market->getTranslation('name', 'en')];
                }
                $purchases->balance         = $cart_category->balance;
            if($cart_category->market_id == null){
                $purchases->balance_type =            'none';                     
                }else{
                    $purchases->balance_type =    ['ar' =>$cart_category->Market->getTranslation('balance_type', 'ar'),'en' =>$cart_category->Market->getTranslation('balance_type', 'en')];

                }

                $purchases->image           = $cart_category->cart_details->image;

                $purchases->totalprice      = $request->stars;
                $purchases->totaltax        = 0;
                $purchases->rate            = "⭐";
                $purchases->newTotalwithTax = $request->stars;

                $purchases->save();

                //send cart order to email
                if (\Auth::guard('cliants')->user()->code_cart_email == 1) {

                    Mail::send(new StarsOrder($purchases));

                }

                $cliant = Cliant::where('id', \Auth::guard('cliants')->user()->id)->first();

        //send cart order to email
        if(\Auth::guard('cliants')->user()->code_cart_email == 1 &&  \Auth::guard('cliants')->user()->emailVerified == 1){

            Mail::send(new StarsOrder($purchases));

        }

        //send cart order to sms
        if(\Auth::guard('cliants')->user()->isVerified == 1 && \Auth::guard('cliants')->user()->code_cart_phone == 1){

            $sid = getenv("TWILIO_ACCOUNT_SID");
            $token = getenv("TWILIO_AUTH_TOKEN");
            $twilio = new Client($sid, $token);
    

            if($cart_category->market_id == null){

                if($cart_category->Subscrip_status == 1){
                  $cion =   $cart_category->time_Subscrip;
                    }
                }else{
                    if ($cart_category->Subscrip_status == 1){
                 $cion =    $cart_category->time_Subscrip; 
                    }else{
                 $cion =    $cart_category->Market->balance_type;
                    }
                }

                //get code cart
                $val =    CartStore::where('products_id',$cart_category->id)->take(1)->get(['cart_code'])->pluck('cart_code')->toArray();
    
               $codeCart =  implode($val,"\r\n");
    
    
                    $message = $twilio->messages->create(\Auth::guard('cliants')->user()->phone_number, // to
                    [
                        "body" => 
                         __('home.order_no') . $coderandome."\r\n" .
                         $cart_category->cart_details->cart_name.' '.
                         '1' . ' '. '-' .
                         $cart_category->balance . ' ' .
                         $cion.'                           '."\r\n" . 
                         $codeCart
                        ,
                        "from" => "MjalStore",
                    ]);
                }
            

       $cliant =  Cliant::where('id' , \Auth::guard('cliants')->user()->id)->first();

       if(!$cliant->another_assignmen_link == null){

       $balance = Cliant::where('assignmen_link' , $cliant->another_assignmen_link)->first();

       $tax = config('cart.tax') / 100;

       $total = Cart::subtotal();

       $number = ($total * $tax + $total) * 1 / 100;


                    $balance = Cliant::where('assignmen_link', $cliant->another_assignmen_link)->first();

                    $tax = config('cart.tax') / 100;

                    $total = Cart::subtotal();

                    $number = ($total * $tax + $total) * 1 / 100;

                    $balance->update([
                        'account_balance' => $number,
                    ]);

                }

      

                return redirect()->route('complete');

            } else {

                return back()->withErrors('You dont have enough stars');

            }
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
         } //end try

    }


//end of order_by_satrs



    public function ConvertStars()
    {
        $cliant = Cliant::where('id', \Auth::guard('cliants')->user()->id)->first();

        $convert = $cliant->stars / 100;

        $balance = $cliant->account_balance;

        $total = $convert + $balance;

        $cliant->update([

            'account_balance' => $total,
            'stars'           => 0,

        ]);

        return back()->with('success', 'You  stars are converted to the balance ');

    }//end of ConvertStars



    public function notify(Request $request)
    {

        if (Notify::where('cliant_id', $request->cliant_id)->where('cart_id', $request->cart_id)->exists()) {

            return response(['success' => true]);

        } else {

            Notify::create($request->all());

            return response(['success' => true]);
        }

    } //end fo notify

}//end of  controller