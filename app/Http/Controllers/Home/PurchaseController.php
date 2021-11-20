<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Models\Market;
use App\Models\Parent_Category;
use App\Models\Product;
use App\Models\Sub_Category;
use App\Models\WalletDatabase;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Cliant;
use App\Models\Purchase;
use Illuminate\Support\Facades\Mail;
use Twilio\Rest\Client;
use App\Models\CartStore;


class PurchaseController extends Controller
{

    public function index()
    {

        if (session()->get('rate') == null) {

            session()->put('price_icon', '$');
            session()->put('rate', 'UST');

        }

        $parent_categories = Parent_Category::with('sub_category')->get();
        $sub_categorys     = sub_category::all();
        $markets           = Market::all();
        $products          = Product::all();

        return view('home.purchase', compact('parent_categories', 'sub_categorys', 'markets', 'products'));
    } //end of index

    public function parent_category($id)
    {
        // return "gooooooood";
        $sub_category = sub_category::where("parent_category_id", $id)->get();

        return response()->json($sub_category);
    } //end of create

    public function sub_categoryed($id)
    {
        $carts = Product::where('sub_category_id', $id)->select('market_id')->distinct()->get();

        $SubImage = Product::where('sub_category_id', $id)->first();

        $markets = [];

        foreach ($carts as $cart) {

            $markets[] = Market::where('id', $cart->market_id)->first();

        }


        if (!$markets[0]  == null) {


            return response()->json(['markets' => $markets]);

        } else {

            $products = Product::where('sub_category_id', $id)->with('cart_details')->get();
            // return response()->json($products,'products');

            return response()->json($products);
            // return response()->json(['markets' => $markets]);

        } //end of if

        // dd($markets);

    } //end of create

    public function makted($id)
    {
        // return "ggggggggggggg";
        $product = Product::where('market_id', $id)->with('cart_details')->get();

        return response()->json(['product' => $product]);

    } //end of create

    public function store(Request $request)
    {
        // dd($request->all());

        if (Auth::guard('cliants')->check()) {

            $walletdb = new WalletDatabase();

            $walletdb->cart_id         = $request->cart_id;
            $walletdb->cart_name       = $request->cart_name;
            $walletdb->short_descript  = $request->short_descript;
            $walletdb->cart_text       = $request->cart_text;
            $walletdb->image           = $request->image;
            $walletdb->users_id        = Auth::guard('cliants')->user()->id;
            $walletdb->market_id       = $request->market_id;
            $walletdb->sub_category_id = $request->sub_category_id;
            $walletdb->amrecan_price   = $request->amrecan_price;

            $walletdb->save();

            // dd($walletdb);

        } //end of if

        //  dd($carts);
        $duplicates = Cart::search(function ($cartItem, $rowId) use ($request) {
            return $cartItem->id === $request->cart_id;
        });

        if ($duplicates->isNotEmpty()) {
            return back()->with('success_message', 'Item is already in your carts!');
        }

        $carts = Cart::add($request->cart_id, $request->cart_name, 1, $request->amrecan_price)
            ->associate('App\Models\Product');
        // dd($carts);
        return redirect()->route('wallet.index');

        // return response()->json($carts);

    } //end of store

    public function balance_bay()
    {
         // try{


    $discount = session()->get('coupon')['discount'] ?? 0;
    $code = session()->get('coupon')['name'] ?? null;
    $tax = config('cart.tax') / 100;
    $number = Cart::subtotal();
    $convertNum = preg_replace('/,/', '', $number);

    $newSubtotal = ($convertNum - $discount);
    $newTotal = $newSubtotal;

    $newTax = $newSubtotal * $tax;
    $newTotalwithTax = $newTotal * (1 + $tax);


   if(\Auth::guard('cliants')->user()->account_balance >= $newTotalwithTax){

    $cliant =  Cliant::where('id' , \Auth::guard('cliants')->user()->id)->first();

    $oldBalance = $cliant->account_balance;

    $newBalance = $oldBalance - $newTotalwithTax;


    $cliant->update([

        'account_balance' => $newBalance,

    ]);

     $products = Cart::content();

     foreach($products as $product){

         $cart_category = Product::where('id',$product->id)->first();

        // validate quantity
        if($cart_category->quantity < $product->qty ) {

           

            Cart::destroy();

            $cartss = WalletDatabase::where('users_id', '=', \Auth::guard('cliants')->user()->id)->where('cart_id',$product->id)->first();

            if(!$cartss == null){

            $cartss->delete();

            }


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
}else{

    return redirect()->back()->withErrors('you dont have enough balance');
}
 // } catch (\Exception $e) {
 //     return redirect()->back()->withErrors(['error' => $e->getMessage()]);
 // }//end try

    } //end of show

    public function edit(Purchase $purchase)
    {
        //
    } //end of edit

    public function update(Request $request, Purchase $purchase)
    {
        //
    } //end of update

    public function destroy(Purchase $purchase)
    {
        //
    } //end of destroy

} //end of controller
