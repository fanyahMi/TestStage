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

    public function storePlace(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'place' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors(),
            ], 400);
        }

        $place = new Place();
        $place->name = $request->input('place');
        $place->save();

        return response()->json([
            'status' => 'success',
            'place' => $place,
        ]);
    }

    // Récupérer toutes les places pour les afficher
    public function getPlaces()
    {
        $places = Place::all();
        return response()->json($places);
    }
}
