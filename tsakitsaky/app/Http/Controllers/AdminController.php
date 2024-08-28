<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Place;
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
{
    public function index(){
        $donutData = [10, 20, 30, 40, 50];

        // Données pour le graphique Barre
        $barData = [20, 30, 40, 50, 60];

        // Données pour le graphique Courbe
        $lineData = [30, 40, 50, 60, 70];

        return view('admin.Acceuil');
    }

    public function getPlace(){
        $place = Place::all();
        return response()->json(['data' => $place]);
    }

    public function addPlace(Request $request){
        $rules = [
            'place' => 'required|string|max:400',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()
                        ->withErrors($validator)
                        ->withInput();
        }

        $place = new Place();
        $place -> place = $request->input('place');
        $place -> save();

        return response()->json(['success' => ' ajouté avec success']);

    }
}
