<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\CartDetail;
use Illuminate\Http\Request;

class CartDetailControlller extends Controller
{

    public function index(Request $request)
    {
        $carts_detail  = CartDetail::when($request->search, function ($q) use ($request) {

            // return $q->HasTranslations('name', '%' . $request->search . '%');
            return $q->where('users_id', 'like', '%' . $request->search . '%')
                ->orWhere('cart_name->ar', 'like', '%' . $request->search . '%')
                ->orWhere('cart_name->en', 'like', '%' . $request->search . '%')
                ->orWhere('short_descript->ar', 'like', '%' . $request->search . '%')
                ->orWhere('short_descript->en', 'like', '%' . $request->search . '%');

        })->latest()->get();

        return view('dashboard.carts_detail.index', compact('carts_detail'));
    } //end of index

    public function create()
    {
        return view('dashboard.carts_detail.create');
    } //end of create

    public function store(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'cart_name'         => 'required',
            'cart_name_en'      => 'required',
            'cart_text'         => 'required',
            'cart_text_en'      => 'required',
            'short_descript_en' => 'required',
            'short_descript'    => 'required',
            'image'             => 'required',
        ]);

        // try {

            $request_all = $request->all();

            $file_name = time() . '.' . $request->image->getClientOriginalExtension();

            $request->image->move('uploads/cart_images/', $file_name);

            $carts = new CartDetail();

            $carts->cart_name      = ['ar' => $request_all['cart_name'], 'en' => $request_all['cart_name_en']];
            $carts->short_descript = ['ar' => $request_all['short_descript'], 'en' => $request_all['short_descript_en']];
            $carts->cart_text      = ['ar' => $request_all['cart_text'], 'en' => $request_all['cart_text_en']];

            $carts->users_id       = auth()->user()->id;
            $carts->image          = $file_name;

            $carts->save();

            notify()->success(__('home.added_successfully'));
            return redirect()->route('dashboard.carts_detail.index');

        // } catch (\Exception $e) {
        //     return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        // } //end try

    } //end of store

    public function edit(CartDetail $cartDetail,$id)
    {
        $cartDetail = CartDetail::find($id); 
        return view('dashboard.carts_detail.edit', compact('cartDetail'));
    } //end of edit

    public function update(Request $request, CartDetail $cartDetail,$id)
    {
        $cartDetail = CartDetail::find($id);
        $request->validate([
            'cart_name'         => 'required',
            'cart_name_en'      => 'required',
            'cart_text'         => 'required',
            'cart_text_en'      => 'required',
            'short_descript_en' => 'required',
            'short_descript'    => 'required',
            'image'             => 'image',
        ]);

        try {

            if ($request->image) {

                $file_name = time() . '.' . $request->image->getClientOriginalExtension();

                $request->image->move(public_path('uploads/cart_images/'), $file_name);

                $cartDetail->update([
                    'cart_name'      => ['ar' => $request->cart_name,      'en' => $request->cart_name_en],
                    'short_descript' => ['ar' => $request->short_descript, 'en' => $request->short_descript_en],
                    'cart_text'      => ['ar' => $request->cart_text,      'en' => $request->cart_text_en],

                    'users_id'       => auth()->user()->id,
                    'image'          => $file_name,
                ]);

            } else {

                $cartDetail->update([
                    'cart_name'      => ['ar' => $request->cart_name,      'en' => $request->cart_name_en],
                    'short_descript' => ['ar' => $request->short_descript, 'en' => $request->short_descript_en],
                    'cart_text'      => ['ar' => $request->cart_text,      'en' => $request->cart_text_en],
                    'users_id'       => auth()->user()->id,

                ]);

            } //end of if

            notify()->success(__('home.updated_successfully'));
            return redirect()->route('dashboard.carts_detail.index');

        } catch (\Exception $e) {

            return redirect()->back()->withErrors(['error' => $e->getMessage()]);

        } //end try

    } //end of update

    public function destroy(CartDetail $cartDetail,$id)
    {
        $cartDetail = CartDetail::find($id); 

        $cartDetail->delete();
        notify()->success(__('home.deleted_successfully'));
        return redirect()->route('dashboard.carts_detail.index');
    } //end of destroy

} //end of controller
