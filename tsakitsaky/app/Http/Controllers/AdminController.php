<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Place;
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
{
    public function index(){
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
    $place->place = $request->input('place');
    $place->save();

    return response()->json([
        'status' => 'success',
        'place' => $place,  // Return only the newly created place with its ID
    ]);
}


    // Récupérer toutes les places pour les afficher
    public function getPlaces()
    {
        $places = Place::all();
        return response()->json($places);
    }

    public function dropPlaces($id)
    {
        $place = Place::findOrFail($id);
        if ($place) {
            $place->delete();
            return response()->json([
                'status' => 'success',
                'message' => 'Place supprimée avec succès.',
            ]);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Place non trouvée.',
        ], 404);
    }

    public function update(Request $request, $id)
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

        $place = Place::find($id);

        if (!$place) {
            return response()->json([
                'status' => 'error',
                'message' => 'Place not found.',
            ], 404);
        }

        $place->place = $request->input('place');
        $place->save();

        return response()->json([
            'status' => 'success',
            'place' => $place,
        ]);
    }
}
