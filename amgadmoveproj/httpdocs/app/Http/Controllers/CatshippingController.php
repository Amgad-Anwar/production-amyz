<?php

namespace App\Http\Controllers;

use App\Models\Catshipping;
use Illuminate\Http\Request;

class CatshippingController extends Controller
{
    public function showAll()
    {
        $cats =Catshipping::all();
            return view("catshipping" , ['catshippings'=>$cats ]);

    }


    public function store(Request $request)
    {

        Catshipping::create([
            'name'=>$request->name,
          //  'percent'=>$request->percent,
        ]);

        return back();
    }




    public function update($id, Request $request)
    {
        $cat = Catshipping::findOrFail($id);



        $cat->update([
            'name'=>$request->name,
           // 'percent'=>$request->percent,
        ]);

        return back();
    }


    public function delete($id)
    {
        $team = Catshipping::findOrFail($id);
        $team->delete();
        return back();
    }


}
