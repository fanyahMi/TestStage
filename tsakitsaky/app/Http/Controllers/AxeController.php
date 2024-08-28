<?php

namespace App\Http\Controllers;

use App\Models\Axe;
use App\Models\Place;
use App\Models\PlaceAxe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Validator;

class AxeController extends Controller
{
    public function showPlaceAxe(){
        return view("template.Layout", [
            'title' => 'Axe et Place',
            'page' => "axe.AxePlace",
            'axes'=> Axe::all(),
            'places'=> Place::all(),
        ]);
    }

    public function createPlace(Request $request){
        $rules = [
            'place' => 'required|string|max:255',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()
                        ->withErrors($validator)
                        ->withInput();
        }
        Place::create([
            'place' => $request->input('place'),
        ]);
        return redirect()->back();
    }

    public function createPlaceAxe(Request $request){
        PlaceAxe::create([
            'axe_id' => $request->input('axe_id'),
            'place_id' => $request->input('place_id'),
        ]);
        return redirect()->back();
    }

    public function createAxe(Request $request){
        $rules = [
            'desc' => 'required|string|max:255',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()
                        ->withErrors($validator)
                        ->withInput();
        }
        Axe::create([
            'desc' => $request->input('desc'),
        ]);
        return redirect()->back();
    }

    public function detailsAxe($id){
        $detailAxe = DB::table('v_axe_place_complet')->where('id_axe','=', $id)->get();

        return view("template.Layout", [
            'title' => 'Axe et Place',
            'page' => "axe.DetailAxePlace",
            'detailAxes'=>  $detailAxe,
        ]);


    }




}
