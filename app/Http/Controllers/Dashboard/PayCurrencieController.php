<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Purchase;
use App\Models\PayCurrencie;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use App\Mail\PandingCart;
use Twilio\Rest\Client;



class PayCurrencieController extends Controller
{

    public function index()
    {
        $pay_currencie = PayCurrencie::all();
        return view('dashboard.pay_currencie.index', compact('pay_currencie'));
    } //endof index

    public function edit(PayCurrencie $payCurrencie)
    {
        return view('dashboard.pay_currencie.edit', compact('payCurrencie'));
    } //end of edit

    public function update(Request $request, PayCurrencie $payCurrencie)
    {
        $request->validate([
            'link'    => 'required',
        ]);

        try {

            if ($request->image) {

                $file_name = time() . '.' . $request->image->getClientOriginalExtension();

                $request->image->move('uploads/PayCurrencie_images/', $file_name);

                $payCurrencie->update([

                    'image' => $file_name,
                    'link'  => $request->link,
                ]);
                
            } else {

                $payCurrencie->update([
                    'link'  => $request->link,
                ]);
            }

            notify()->success(__('home.updated_successfully'));
            return redirect()->route('dashboard.pay_currencie.index');

        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    } //end of update

    public function pending_requests ()
    {
        $pending_requests =  Purchase::where('status' ,1)
        ->select('number','newTotalwithTax','purchases_status','date')
        ->distinct()->latest()->get();
        // dd($pending_requests);
        
        return view('dashboard.pending_requests.index',compact('pending_requests'));
    }

    public function pending_requests_edit ($number)
    {
        $pending_requests =  Purchase::where('status' ,1)->where('number',$number)->get();

        $email =  Purchase::where('status' ,1)->where('number',$number)->get();


        foreach($pending_requests as $request){

            $request->update([
                'status' => 0,
            ]);

          $carts =  Product::where('id', $request->cart_id)->first();


          $cliant =  Cliant::where('id' , $request->user_id)->first();

            if($carts > 0){

                
    
                $cliantStars =  $cliant->stars;
    
                $cartStars = $stars->stars ;
    
                $stars = $cartStars + $cartStars;
    
                $cliant->update([
                    'stars' => $stars,
                ]);
    
            }
            if($cliant->code_cart_email == 1 && $cliant->code_cart_email == 1){

                Mail::send(new PandingCart($pending_requests,$email));
        
                }
        
                if($cliant->isVerified == 1 && $cliant->code_cart_phone == 1){
        
                    $sid = getenv("TWILIO_ACCOUNT_SID");
                    $token = getenv("TWILIO_AUTH_TOKEN");
                    $twilio = new Client($sid, $token);
            
                            $message = $twilio->messages->create($cliant->phone_number, // to
                            [
                                "body" => 
                                 __('home.order_no') . $request->number."\r\n" .
                                 $request->cart_name.' '.
                                 $request->quantity . ' '. '-' .
                                 $request->balance . ' ' .
                                 $request->balance_type.'                           '."\r\n" . 
                                 $request->code
                                ,
                                "from" => "MjalStore",
                            ]);
                        }
        }

      

       
         




        notify()->success(__('home.updated_successfully'));
        return redirect()->route('dashboard.pending_requests');
    }

    public function not_exept($number){

        $pending_requests =  Purchase::where('status' ,1)->where('number',$number)->get();



        foreach($pending_requests as $request){

            $request->update([
                'status' => 0,
                'purchases_status' => 3,
            ]);
        }

        notify()->success(__('home.updated_successfully'));
        return redirect()->route('dashboard.pending_requests');

    }

    public function pay_currencie_details($number){

        $purchases =  Purchase::where('number',$number)->get();

        return view('dashboard..pending_requests.details',compact('purchases'));

    }

} //end of controller
