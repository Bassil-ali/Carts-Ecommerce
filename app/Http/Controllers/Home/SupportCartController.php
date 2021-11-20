<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Mail\Ticit;
use App\Models\Parent_Category;
use App\Models\SupportCart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class SupportCartController extends Controller
{

    public function test(Request $request)
    {
        // return 'ddddddddddd';
        dd($request->all());
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

        $ticits = SupportCart::where('claint_id', \Auth::guard('cliants')->user()->id)->get();
        return view('home.ticit-list-supports', compact('parent_categories', 'ticits'));
    }

    public function get(Request $request)
    {

        $ticits = SupportCart::latest()->when($request->search, function ($q) use ($request) {

            return $q->where('cliant_email', 'like', '%' . $request->search . '%')
                ->orWhere('ticit_answer', 'like', '%' . $request->search . '%')
                ->orWhere('ticit_reply', 'like', '%' . $request->search . '%')
                ->orWhere('ticit_address', 'like', '%' . $request->search . '%')
                ->orWhere('number_ticit', 'like', '%' . $request->search . '%')
                ->orWhere('details_ticit', 'like', '%' . $request->search . '%');
            // ->orWhere('address', 'like', '%' . $request->search . '%');

        })->latest()->paginate(15);

        return view('dashboard.ticit-list.index', compact('ticits'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
      public function store(Request $request)
    {

        $request->validate([
          'file' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:5000',
        ]);

        //$image = new Image;

        if ($request->file('images')) {
            $imagePath = $request->file('images');
            $imageName = $imagePath->getClientOriginalName();

            $path = $request->file('images')->storeAs('uploads', $imageName, 'public');
        }
        dd(path);

        //$image->name = $imageName;
        //$image->path = '/storage/'.$path;
        //image->save();

        return response()->json('Image uploaded successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\SupportCart  $supportCart
     * @return \Illuminate\Http\Response
     */
    public function show(SupportCart $supportCart)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\SupportCart  $supportCart
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $ticits = SupportCart::find($id);
        // dd($ticits);

        return view('dashboard.ticit-list.edit', compact('ticits'));

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\SupportCart  $supportCart
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        try {
            $ticit = SupportCart::find($id);

            if ($ticit) {

                $ticit->update([

                    'ticit_reply' => $request->details_ticit,
                ]);
            }

            if ($ticit) {

                $mail = Mail::send(new Ticit($ticit));

            }

            $ticit->update([

                'ticit_answer' => 1,
            ]);

            notify()->success('Laravel Notify is awesome!');
            return back();

        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\SupportCart  $supportCart
     * @return \Illuminate\Http\Response
     */
    public function destroy(SupportCart $supportCart)
    {
        //
    }
}
