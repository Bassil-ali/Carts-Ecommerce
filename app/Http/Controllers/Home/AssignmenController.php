<?php

namespace App\Http\Controllers\home;

use App\Http\Controllers\Controller;
use App\Models\Cliant;
use App\Models\Parent_Category;
use App\Models\Product;

class AssignmenController extends Controller
{

    public function index($assign)
    {

        $parent_categories = Parent_Category::with('sub_category')->get();

        $carts = Product::orderBy('used')->paginate(10);

        if (session()->get('rate') == null) {

            session()->put('price_icon', '$');
            session()->put('rate', 'UST');

        } //end of if

        $parent_categories = Parent_Category::with('sub_category')->get();

        $carts = Product::orderBy('used')->paginate(10);

        if (!session()->get('rate') == null) {

            if (session()->get('rate') == 'UST') {

                $carts = Product::orderBy('used')->paginate(10);

            } else {

                $convert = Rate::select(session()->get('rate'))->value(session()->get('rate'));

                foreach ($carts as $cart) {

                    $amount = $cart->amrecan_price;

                    $newamount = $amount * $convert;

                    $cart->amrecan_price = $newamount;

                } //end of foreach

            } //end of if
        } //end of if

    

        

         if(\Auth::guard('cliants')->user()->check()){


            if(!\Auth::guard('cliants')->user()->another_assignmen_link == $assign){
            $cliant = Cliant::where('assignmen_link',$assign)->first();

            $cliant->assignmen_users = $cliant->assignmen_users + 1;
   
            $cliant->save();
            }

         $myacount =  Cliant::where('id',\Auth::guard('cliants')->user()->id)->first();

         $myacount->update([

            'another_assignmen_link' => $assign,
         ]);
         
         

         }else{

            return redirect()->route('/')->with('error_message', 'you should sign up to use referral link system!');
         }
         
         return redirect()->route('/')->with('success', 'sign up to referral link system success!');
    

    } //end of function index

    public function get()
    {

        if (!\Auth::guard('cliants')->user() == null) {
            $cliant = Cliant::where('id', \Auth::guard('cliants')->user()->id)->first();
        } else {

            $cliant = 0;

        }

        $parent_categories = Parent_Category::with('sub_category')->get();

        return view('home.referral-system', compact('parent_categories', 'cliant'));

    } //end pf get

} //end of controller
