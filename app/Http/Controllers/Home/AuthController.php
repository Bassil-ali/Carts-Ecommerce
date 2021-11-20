<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Mail\EmailVerify;
use App\Models\Cliant;
use App\Models\WalletDatabase;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Socialite;
use Twilio\Rest\Client;
use TimeHunter\LaravelGoogleReCaptchaV3\Validations\GoogleReCaptchaV3ValidationRule;

use Auth;

class AuthController extends Controller
{

    public function verifyindex()
    {
        return view('auth.verify');
    }

    public function create(Request $request)
    {
        $data = $request->validate([
            'name'         => ['required', 'string', 'max:255'],
            'email'        => ['required', 'string', 'email', 'max:255', 'unique:cliants'],
            'phone_number' => ['required'],
            'password'     => ['required', 'string', 'min:8'],
            // 'recaptcha' => 'required|captcha'
        ]);
        
    //   $rule = [
    //         'g-recaptcha-response' => [new GoogleReCaptchaV3ValidationRule('action_name')]
    //     ];

        $digits = 4;
        $code   = rand(pow(10, $digits - 1), pow(10, $digits) - 1);

        $cliant = Cliant::create([
            'email'        => $data['email'],
            'name'         => $data['name'],
            'phone_number' => $data['phone_number'],
             'code_phone'   => $code,
            'password'     => Hash::make($data['password']),
        ]);

        // $sid    = getenv("TWILIO_ACCOUNT_SID");
        // $token  = getenv("TWILIO_AUTH_TOKEN");
        // $twilio = new Client($sid, $token);

        // $message = $twilio->messages->create($data['phone_number'], // to
        //     [
        //         "body" => 'your verify code is :' . $code,
        //         "from" => "MjalStore",
        //     ]
        // );

        $emailverify = bin2hex(openssl_random_pseudo_bytes(10));

        $cliant->update([
            'code_email' => $emailverify,
        ]);
        
        

         $mail = Mail::send(new EmailVerify($cliant));
         
       
        $remember_me = $request->has('remember_me') ? true : false;

        if (auth()->guard('cliants')->attempt(
            [
                'email'    => $request->input("email"),
                'password' => $request->input('password'),
            ], $remember_me));

        $sessions = Cart::content();

     if ($sessions) {
                
            foreach ($sessions as $session) {


                $walletdb                  = new WalletDatabase();
                $walletdb->cart_id         = $session->id;
                $walletdb->cart_name       = $session->model->cart_details->cart_name;
                $walletdb->short_descript  = $session->model->cart_details->short_descript;
                $walletdb->cart_text       = $session->model->cart_details->cart_text;
                $walletdb->users_id        = Auth::guard('cliants')->user()->id;
                $walletdb->market_id       = $session->model->market_id;
                $walletdb->image           = $session->model->cart_details->image;
                $walletdb->sub_category_id = $session->model->sub_category_id;
                $walletdb->amrecan_price   = $session->model->amrecan_price;

                $walletdb->save();

            }
        }
        return response(['success' => true]);

        // } catch (\Exception $e) {
        //     redirect()->route('/')->withErrors(['error' => $e->getMessage()]);
        // } //end try

    } //end of create

    public function verify(Request $request)
    {

        return view('home.verify_phone');

    } //end of verify

    public function isverify(Request $request)
    {
        $request->validate([
            'code' => ['required', 'numeric'],
            // 'phone_number' => ['required', 'string'],
        ]);
        
        $cliant = Cliant::where('code_phone', $request->code)->first();


        if (!$cliant == null) {

            $cliant->update([

                'isVerified' => 1,
            ]);

            return response(['success' => true]);        

        } else {

            return response(['success' => false]);

        }

    } //end of isverify

    public function emailverify($id, $code)
    {

        $cliant = Cliant::where('id', $id)->first();

        if ($cliant->code_email == $code) {

            $cliant->update([
                'emailVerified' => 1,
            ]);

            return redirect()->route('/');

        } else {

            sesstion()->flush();

            return redirect()->route('/');
        }

    } //end of emailverify

    public function redirect($provider)
    {

        return Socialite::driver($provider)->redirect();
        
    } //end ofredirect

    public function Callback($provider)
    {
        
        try {

            $social_user = Socialite::driver($provider)->stateless()->user();
            
            if ($social_user->getEmail() == null) {

                return redirect('/');

            }

           

        } catch (Exception $e) {

            return redirect('/');
        }

        // $user = Cliant::where('provider', $provider)
        //     ->where('provider_id', $social_user->getId())
        //     ->first();
            
        $user = Cliant::where('email', $social_user->getEmail())->first();
        

        if (!$user) {

            $user = Cliant::create([
                'name' => $social_user->getName(),
                'email' => $social_user->getEmail(),
                'image'        => $social_user->getAvatar(),
                'password' => bcrypt('123456'),
                'provider' => $provider,
                'provider_id' => $social_user->getId(),
                'emailVerified' => 1,
            ]);

         }
        

          $login =   auth()->guard('cliants')->attempt(
            [
                'email'    => $social_user->getEmail(),
                'password' => '123456',
            ]);
            
                    $sessions = Cart::content();

         if ($sessions) {
                
            foreach ($sessions as $session) {


                $walletdb                  = new WalletDatabase();
                $walletdb->cart_id         = $session->id;
                $walletdb->cart_name       = $session->model->cart_details->cart_name;
                $walletdb->short_descript  = $session->model->cart_details->short_descript;
                $walletdb->cart_text       = $session->model->cart_details->cart_text;
                $walletdb->users_id        = Auth::guard('cliants')->user()->id;
                $walletdb->market_id       = $session->model->market_id;
                $walletdb->image           = $session->model->cart_details->image;
                $walletdb->sub_category_id = $session->model->sub_category_id;
                $walletdb->amrecan_price   = $session->model->amrecan_price;

                $walletdb->save();

            }
        }
            


      

        return redirect()->route('/');
        
    } //end of returnverify
    
        public function returnverify()
    {

            $id     = \Auth::guard('cliants')->user()->id;
            $cliant = Cliant::where('id', $id)->first();

            $code = mt_srand(4);

            $cliants->update([
                'code_phone' => $code,
            ]);
            
            
             // $sid    = getenv("TWILIO_ACCOUNT_SID");
        // $token  = getenv("TWILIO_AUTH_TOKEN");
        // $twilio = new Client($sid, $token);

        // $message = $twilio->messages->create($data['phone_number'], // to
        //     [
        //         "body" => 'your verify code is :' . $code,
        //         "from" => "MjalStore",
        //     ]
        // );

           
            return back();
        }

} //end of controller
