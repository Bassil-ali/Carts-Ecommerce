<?php

namespace App\Http\Controllers\Home;

use App\Models\Order;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Parent_Category;
use Gloudemans\Shoppingcart\Facades\Cart;
use App\Models\Product;
use App\Models\CartStore;
use App\Models\PayCurrencie;
use Illuminate\Support\Facades\DB;
use App\Mail\BayCart;
use App\Models\Cliant;
use App\Models\Purchase;
use Illuminate\Support\Facades\Mail;
use Twilio\Rest\Client;
use App\Models\WalletDatabase;



class OrderController extends Controller
{

   

    public function paystore(Request $request)
    {

      dd($request->all()); 
    // try{
          
        

        // if(Cart::count() == 0){

           

        //     return redirect()->route('/')->with('error_message','product in your cart not fount try again');
        // }


     $products = Cart::content();

        //proccess product
        foreach($products as $product){

            $cart_category = Product::where('id',$product->id)->first();

            //validate quantity
            if( $product->qty <  $cart_category->quantity) {

                $cartss = WalletDatabase::where('users_id', '=', \Auth::guard('cliants')->user()->id)->where('cart_id',$product->id)->first();

                $cartss->delete();

                Cart::destroy();

                return redirect()->route('/')->with('error_message','product in your cart not fount try again');
            }

            $result = $cart_category->quantity - $product->qty;
            $count = $cart_category->count_of_buy + 1;

         

            Product::where('id', $product->id)->update([
                'quantity' => $result,
                'count_of_buy' => $count,

            ]);
        }

        if($product->model->stars > 0){

            $cliant =  Cliant::where('id' , \Auth::guard('cliants')->user()->id)->first();

            $cliantStars =  $cliant->stars;

            $cartStars = $product->model->stars * $product->qty ;

            $stars = $cliantStars + $cartStars;

            $cliant->update([
                'stars' => $stars,
            ]);

        }

        //get order from DB
        foreach($products as $product){

          CartStore::where('products_id',$product->id)->take($product->qty)->update([
              'used'=>1,
              'user_name' => \Auth::guard('cliants')->user()->name,
              ]);

          $carts[] =  CartStore::where('products_id',$product->id)->take($product->qty)->get();

        }


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

        $file_name = time() . '.' . $request->image->getClientOriginalExtension();
        $request->image->move(public_path('uploads/sub_category_images/') , $file_name);

        $code =rand(pow(10, $digits-1), pow(10, $digits)-1);


            foreach($products as $cart){

            $carts =  CartStore::where('products_id',$cart->id)->take($cart->qty)->get(['cart_code'])->pluck('cart_code')->toArray();
            $purchases = new Purchase();
            $purchases->number          = $code;
            $purchases->cart_id         = $cart->id;
            $purchases->cart_name       = ['ar' =>$cart->model->cart_details->getTranslation('cart_name', 'ar'),'en' =>$cart->model->cart_details->getTranslation('cart_name', 'en')];
            $purchases->short_descript  = ['ar' =>$cart->model->cart_details->getTranslation('short_descript', 'ar'),'en' =>$cart->model->cart_details->getTranslation('short_descript', 'en')];
            $purchases->cart_text       = ['ar' =>$cart->model->cart_details->getTranslation('cart_text', 'ar'),'en' =>$cart->model->cart_details->getTranslation('cart_text', 'en')];
            $purchases->sub_category_id = $cart->model->sub_category_id;
            $purchases->purchases_status= 2;
            $purchases->users_id        = \Auth::guard('cliants')->user()->id;
            $purchases->price           = $cart->price;
            $purchases->date            = $date;
            $purchases->status          = '1';
            $purchases->code            = implode($carts, '<br>');
            // dd($purchases->code);
            $purchases->quantity        = $cart->qty;
            if($cart->model->market_id == null){
                $purchases->market  =   "none";
                }else{
                    $purchases->market  =  ['ar' => $cart->model->Market->getTranslation('name', 'ar'),'en' =>$cart->model->Market->getTranslation('name', 'en')];
                }
                $purchases->balance         = $cart->model->balance;
                if($cart->model->market_id == null){
                if ($cart->model->Subscrip_status == 1){

                    $purchases->balance_type = ['ar' =>$cart->model->getTranslation('time_Subscrip', 'ar'),'en' =>$cart->model->getTranslation('time_Subscrip', 'en')];
                }else{
                $purchases->balance_type =           'none';                     
                }
               }
                if(!$cart->model->market_id == null){
                    $purchases->balance_type = ['ar' =>$cart->model->Market->getTranslation('balance_type', 'ar'),'en' =>$cart->model->Market->getTranslation('balance_type', 'en')];
                }
    

            $purchases->image           = $file_name;
            $purchases->totalprice      = $newTotal;
            $purchases->totaltax        = number_format($newTax,2,".",",");
            $purchases->rate            = session()->get('price_icon');
            $purchases->newTotalwithTax = number_format($newTotalwithTax,2,".",",");
   
            $purchases->save();

            }




       


           $cliant =  Cliant::where('id' , \Auth::guard('cliants')->user()->id)->first();

           if(!$cliant->another_assignmen_link == null){

           $balance = Cliant::where('assignmen_link' , $cliant->another_assignmen_link)->first();

           $tax = config('cart.tax') / 100;

           $total = Cart::subtotal();

           $number = ($total * $tax + $total) * 1 / 100;

           $balance->update([
               'account_balance' => $number,
           ]);

     

    }

   


       return redirect()->route('complete');
    // } catch (\Exception $e) {
    //     return redirect()->back()->withErrors(['error' => $e->getMessage()]);
    // }//end try

    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        if (session()->get('rate') == null) {

            session()->put('price_icon', '$');
            session()->put('rate', 'UST');

        }

        $parent_categories = Parent_Category::with('sub_category')->get();

        $pay_currencie = PayCurrencie::all();

        $discount = session()->get('coupon')['discount'] ?? 0;
        $code = session()->get('coupon')['name'] ?? null;
        $tax = config('cart.tax') / 100;
        $number = Cart::subtotal();
        $convertNum = preg_replace('/,/', '', $number);
        $newSubtotal = ($convertNum - $discount);
        $newTotal = $newSubtotal;
        $newTax = $newSubtotal * $tax;
        $newTotalwithTax = $newTotal * (1 + $tax);
    // dd($newTotalwithTax);



        return view('home.payment',compact('parent_categories','pay_currencie'))->with([
            'newTotal' =>$newTotal,
            'newTax' => $newTax,
            'newTotalwithTax' => $newTotalwithTax,

        ]);
    }

    public function search(Request $request){

        $carts = CartStore::when($request->search, function ($q) use ($request) {

            // return $q->HasTranslations('name', '%' . $request->search . '%');
            return $q->where('users_id', 'like', '%' . $request->search . '%')
                ->orWhere('products_id', 'like', '%' . $request->search . '%');

        })->latest()->paginate(10);

        return view('dashboard.orders.index', compact('carts'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

       
    // try{


        // if(Cart::count() == 0){

        //     return redirect()->route('/')->with('error_message','product in your cart not fount try again');
        // }


     $products = Cart::content();

        foreach($products as $product){

            $cart_category = Product::where('id',$product->id)->first();

           // validate quantity
           if( $product->qty <  $cart_category->quantity) {

            $cartss = WalletDatabase::where('users_id', '=', \Auth::guard('cliants')->user()->id)->where('cart_id',$product->id)->first();

            $cartss->delete();
        
                Cart::destroy();

                return redirect()->route('/')->with('error_message','product in your cart not fount try again');
            }


            if($product->model->stars > 0){

                $cliant =  Cliant::where('id' , \Auth::guard('cliants')->user()->id)->first();

                $cliantStars =  $cliant->stars;

                $cartStars = $product->model->stars * $product->qty ;

                $stars = $cliantStars + $cartStars;

                $cliant->update([
                    'stars' => $stars,
                ]);
            }

            $result = $cart_category->quantity - $product->qty;
            $count = $cart_category->count_of_buy + 1;

                Product::where('id', $product->id)->update([
                'quantity' => $result,
                'count_of_buy' => $count,

            ]);   
        }

        //get order from DB
        foreach($products as $product){

          CartStore::where('products_id',$product->id)->take($product->qty)->update([
              'used'=>1,
              'user_name' => \Auth::guard('cliants')->user()->name,
              'cliant_id' => \Auth::guard('cliants')->user()->id
              ]);
        }

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

        $coderandome=rand(pow(10, $digits-1), pow(10, $digits)-1);

            foreach($products as $cart){

            $carts =  CartStore::where('products_id',$cart->id)->take($cart->qty)->get(['cart_code'])->pluck('cart_code')->toArray();

            $purchases = new Purchase();
            $purchases->number          = $coderandome;
            $purchases->cart_id         = $cart->id;
            $purchases->cart_name       = ['ar' =>$cart->model->cart_details->getTranslation('cart_name', 'ar'),'en' =>$cart->model->cart_details->getTranslation('cart_name', 'en')];
            $purchases->short_descript  = ['ar' =>$cart->model->cart_details->getTranslation('short_descript', 'ar'),'en' =>$cart->model->cart_details->getTranslation('short_descript', 'en')];
            $purchases->cart_text       = ['ar' =>$cart->model->cart_details->getTranslation('cart_text', 'ar'),'en' =>$cart->model->cart_details->getTranslation('cart_text', 'en')];
            $purchases->sub_category_id = $cart->model->sub_category_id;
            $purchases->purchases_status= 1;
            $purchases->users_id        = \Auth::guard('cliants')->user()->id;
            $purchases->price           = $cart->price;
            $purchases->date            = $date;
            $purchases->code            = implode($carts, '<br>');
            $purchases->quantity        = $cart->qty;
            if($cart->model->market_id == null){
                $purchases->market  =   "none";
                }else{
                    $purchases->market  =  ['ar' => $cart->model->Market->getTranslation('name', 'ar'),'en' =>$cart->model->Market->getTranslation('name', 'en')];
                }
                $purchases->balance         = $cart->model->balance;
                if($cart->model->market_id == null){
                if ($cart->model->Subscrip_status == 1){

                    $purchases->balance_type = ['ar' =>$cart->model->getTranslation('time_Subscrip', 'ar'),'en' =>$cart->model->getTranslation('time_Subscrip', 'en')];
                }else{
                $purchases->balance_type =           'none';                     
                }
              }
                if(!$cart->model->market_id == null){
                    $purchases->balance_type = ['ar' =>$cart->model->Market->getTranslation('balance_type', 'ar'),'en' =>$cart->model->Market->getTranslation('balance_type', 'en')];
                }
    

            $purchases->image           = $cart->model->cart_details->image;
            $purchases->totalprice      = $newTotal;
            $purchases->totaltax        = number_format($newTax,2,".",",");
            $purchases->rate            = session()->get('price_icon');
            $purchases->newTotalwithTax = number_format($newTotalwithTax,2,".",",");
   
            $purchases->save();

           
            }

          //send cart order to email
        if(\Auth::guard('cliants')->user()->code_cart_email == 1 &&  \Auth::guard('cliants')->user()->emailVerified == 1){

            Mail::send(new BayCart($carts));

        }

        
            //send order to sms 
        if(\Auth::guard('cliants')->user()->isVerified == 1 && \Auth::guard('cliants')->user()->code_cart_phone == 1){

        $sid = getenv("TWILIO_ACCOUNT_SID");
        $token = getenv("TWILIO_AUTH_TOKEN");
        $twilio = new Client($sid, $token);

        //get order from session
        foreach(\Cart::content() as $sess){

          $sess->model->cart_details->cart_name.' ';
          $sess->qty . ' ';
          $sess->model->balance . ' '; 

          if($sess->model->market_id == null){

            if($sess->model->Subscrip_status == 1){
              $cion =   $sess->model->time_Subscrip;
                }
            }else{
                if ($sess->model->Subscrip_status == 1){
             $cion =    $sess->model->time_Subscrip; 
                }else{
             $cion =    $sess->model->Market->balance_type;
                }
            }

            //get code cart
            $val =    CartStore::where('products_id',$sess->id)->take($sess->qty)->get(['cart_code'])->pluck('cart_code')->toArray();

           $codeCart =  implode($val,"\r\n");


        //         $message = $twilio->messages->create(\Auth::guard('cliants')->user()->phone_number, // to
        //         [
        //             "body" => 
        //              __('home.order_no') . $coderandome."\r\n" .
        //              $sess->model->cart_details->cart_name.' '.
        //              $sess->qty . ' '. '-' .
        //              $sess->model->balance . ' ' .
        //              $cion.'                           '."\r\n" . 
        //              $codeCart
        //             ,
        //             "from" => "MjalStore",
        //         ]);
             }
         }
      

       $cliant =  Cliant::where('id' , \Auth::guard('cliants')->user()->id)->first();

       if(!$cliant->another_assignmen_link == null){

       $balance = Cliant::where('assignmen_link' , $cliant->another_assignmen_link)->first();

       $tax = config('cart.tax') / 100;

       $total = Cart::subtotal();

       $number = ($total * $tax + $total) * 1 / 100;

       $balance->update([
           'account_balance' => $number,
       ]);

       }

       return redirect()->route('complete');
    // } catch (\Exception $e) {
    //     return redirect()->back()->withErrors(['error' => $e->getMessage()]);
    // }//end try


    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     *
     */

    public function complete(){


       $random_carts = Product::inRandomOrder()->paginate(10);

       $parent_categories = Parent_Category::with('sub_category')->get();

       return view('home.complete',compact('parent_categories','random_carts'));


     }

     public function my_purchase(){

        $parent_categories = Parent_Category::with('sub_category')->get();

        $purchases =  Purchase::where('users_id',\Auth::guard('cliants')->user()->id)->select('number','newTotalwithTax','purchases_status','date')->distinct()->latest()->get();


        return view('home.purchases',compact('purchases','parent_categories'));

     }

     public function purchase_details($number){

        $parent_categories = Parent_Category::with('sub_category')->get();

        $purchases_first =  Purchase::where('number',$number)->first();


        $purchases =  Purchase::where('number',$number)->get();

        if($purchases == null && $purchases_first == null){

            return view('/');
        }

        // dd($purchases);

        return view('home.purchases-details',compact('parent_categories','purchases','purchases_first'));

     }

     public function purchase_invoices($number){

        $purchases =  Purchase::where('number',$number)->get();

        $purchases_first =  Purchase::where('number',$number)->first();


        if($purchases == null && $purchases_first == null){

            return view('/');
        }
        //  dd($purchases);

        return view('home.invoices',compact('purchases','purchases_first'));

     }

     public function purchase_admin(Request $request){


        $purchases =  Purchase::latest()->when($request->search, function ($q) use ($request) {

        return $q->where('numberr', 'like', '%' . $request->search . '%')
                ->orWhere('date', 'like', '%' . $request->search . '%')
                ->orWhere('crate', 'like', '%' . $request->search . '%')
                ->orWhere('price', 'like', '%' . $request->search . '%')
                ->orWhere('quantity', 'like', '%' . $request->search . '%')
                ->orWhere('totalprice', 'like', '%' . $request->search . '%')
                ->orWhere('short_descript->ar', 'like', '%' . $request->search . '%')
                ->orWhere('short_descript->en', 'like', '%' . $request->search . '%');

        })->latest()->paginate(10);

        // $purchases =  Purchase::latest()->get();

        return view('dashboard.orders.purchase_admin',compact('purchases'));


     }

     public function purchases_search(Request $request){


        $purchases = Purchase::when($request->search, function ($q) use ($request) {

            // return $q->HasTranslations('name', '%' . $request->search . '%');
            return $q->where('number', 'like', '%' . $request->search . '%');

        })->latest()->paginate(10);

        return view('dashboard.orders.purchase_admin', compact('purchases'));

     }

     public function purchase_delete(Purchase $order){

        $order->delete();
        notify()->success(__('home.deleted_successfully'));
        return redirect()->route('purchase_admin');

     }


    public function show(Request $request)
    {

       $carts = CartStore::latest()->when($request->search, function ($q) use ($request) {

        return $q->where('cart_name->ar', 'like', '%' . $request->search . '%')
                ->orWhere('cart_name->en', 'like', '%' . $request->search . '%')
                ->orWhere('short_descript->ar', 'like', '%' . $request->search . '%')
                ->orWhere('short_descript->en', 'like', '%' . $request->search . '%');

        })->latest()->paginate(10);



       return view('dashboard.orders.index', compact('carts'));

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function edit(Order $order)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Order $order)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function destroy(CartStore $cartStore)
    {
        $cartStore->delete();
        notify()->success(__('home.deleted_successfully'));
        return redirect()->route('payment-show');
    } //end of destroy
}
