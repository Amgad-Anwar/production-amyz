<?php

namespace App\Http\Controllers;

use App\Models\Catshipping;
use App\Models\City;
use App\Models\Shippingprice;
use App\Models\State;
use Illuminate\Http\Request;

class ShippingpriceController extends Controller
{
    public function showAll()
    {
         $shippingprices =Shippingprice::all();
         $states =State::where('country_id' , '64')->get();;
         $cats =Catshipping::all();
            return view("shippingprice" , ['shippingprices'=>$shippingprices ,'states'=>$states,'cats'=>$cats ]);

    }


    public function store(Request $request)
    {

        Shippingprice::create([
            'state_id'=>$request->state_id,
            'catshipping_id'=>$request->cat_id,
            'price'=>$request->price,
        ]);

        return back();
    }




    public function update($id, Request $request)
    {
        $cat = Shippingprice::findOrFail($id);

        $cat->update([
            'state_id'=>$request->state_id,
            'catshipping_id'=>$request->cat_id,
            'price'=>$request->price,
        ]);

        return back();
    }


    public function delete($id)
    {
        $team = Shippingprice::findOrFail($id);
        $team->delete();
        return back();
    }


}
