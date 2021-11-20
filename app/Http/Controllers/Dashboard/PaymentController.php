<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;


class PaymentController extends Controller
{

    public function index()
    {
        $payments = Payment::all();
        return view('dashboard.settings.payment.index',compact('payments'));
    } //end of index

    public function create()
    {
        return view('dashboard.settings.payment.create');
    } //end of create

    public function store(Request $request)
    {
        $request->validate([
            'name'  => 'required',
            'image' => 'required',
        ]);

        try {

            $file_name = time() . '.' . $request->image->getClientOriginalExtension();

            $request->image->move('uploads/payment_images/', $file_name);

            Payment::create([
                'name'  => $request->name,
                'image' => $file_name,
            ]);

            notify()->success(__('home.added_successfully'));
            return redirect()->route('dashboard.payment.index');

        } catch (\Exception $e) {

            return redirect()->back()->withErrors(['error' => $e->getMessage()]);

        } //end try

    }//end of store

    public function edit(Payment $payment)
    {
        return view('dashboard.settings.payment.edit',compact('payment'));
    }

    public function update(Request $request, Payment $payment)
    {
        try {

            if ($request->image) {

                $file_name = time() . '.' . $request->image->getClientOriginalExtension();

                $request->image->move('uploads/payment_images/', $file_name);

                $payment->update([
                    'name'      => $request->name,
                    'image'     => $file_name,
                ]);


            } else {

                $payment->update([
                    'name'      => $request->name,
                ]);

            } //end of if

            notify()->success(__('home.updated_successfully'));
            return redirect()->route('dashboard.payment.index');

        } catch (\Exception $e) {

            return redirect()->back()->withErrors(['error' => $e->getMessage()]);

        } //end try

    }//end ofupdate

    public function destroy(Payment $payment)
    {
        $payment->delete();
        notify()->success(__('home.deleted_successfully'));
        return redirect()->route('dashboard.payment.index');
    }//end of destroy

}//en of controller
