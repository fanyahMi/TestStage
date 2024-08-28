<?php

namespace App\Http\Controllers;

use App\Models\DetailPack;
use App\Models\Pack;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;

class PackController extends Controller
{
    public function showPacks(){
        return view("template.Layout", [
            'title' => 'Liste des packs',
            'page' => "pack.ListPacks",
            'packs' => Pack::where('state', 10)->get(),
        ]);
    }

    public function showStatePacks(){
        $datas = DB::table('v_ticket_packs')
                                    ->get();
        return view("template.Layout", [
            'title' => 'Statistique des packs',
            'page' => "pack.Statistique",
            'data' => $datas,
        ]);
    }


    public function createPack(Request $request){
        $rules = [
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()
                        ->withErrors($validator)
                        ->withInput();
        }

        $name = $request->input('name');
        $price = $request->input('price');
        $picture = $request->file('images');

        if ($request->hasFile('images')) {
            Pack::createPack($name, $price, $picture);
        }
        return redirect()->back();
    }

    public function showDetailPack(Request $request, $pack)
    {
        $viewData = DB::table('v_detail_packs_lib')
                                            ->where('id_pack', $pack)
                                            ->get();

                                            return view("template.Layout", [
                                                'title' => 'Detail pack',
                                                'page' => "pack.DetailPack",
                                                'details' => $viewData,
                                                'pack' => $pack,
                                                'products' => Product::all(),
                                            ]);

    }

    public function createDetailPack(Request $request){
        $rules = [
            'pack_id' => 'required|string|max:255',
            'quantity_product' => 'required|numeric|min:0',
            'product_id' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()
                        ->withErrors($validator)
                        ->withInput();
        }
        DetailPack::create($request->all());
        return redirect()->back();
    }

    public function deleteDetailPack($id){
        DetailPack::where('id_detail_pack', $id)->delete();
        return redirect()->back();
    }


    public function getDetailPack($id)
    {
        $detailPack = DetailPack::findOrFail($id);
        return response()->json($detailPack);
    }
    public function Pack($id)
    {
        $Pack = Pack::findOrFail($id);
        return response()->json($Pack);
    }

    /*****Update  *****/

    public function updateDetailPack(Request $request)
    {
        // Règles de validation
        $rules = [
            'id_detail_pack' => 'exists:detail_packs,id_detail_pack',
            'product_id' => 'exists:products,id_product',
            'quantity_product' => 'required|numeric|min:1',
        ];

        // Messages d'erreur personnalisés
        $messages = [
            'id_detail_pack.exists' => 'Le détail du pack spécifié n\'existe pas.',
            'product_id.exists' => 'Le produit spécifié n\'existe pas.',
            'quantity_product.required' => 'Le champ quantité du produit est requis.',
            'quantity_product.numeric' => 'La quantité du produit doit être un nombre.',
            'quantity_product.min' => 'La quantité du produit doit être d\'au moins :min.',
        ];

        // Validation des données
        $validator = Validator::make($request->all(), $rules, $messages);

        // Vérifier si la validation a échoué
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Si la validation réussit, mettre à jour le détail du pack
        $id_detail_pack = $request->input('id_detail_pack');
        $product_id = $request->input('product_id');
        $quantity_product = $request->input('quantity_product');

        $detailPack = DetailPack::findOrFail($id_detail_pack);

        $detailPack->product_id = $product_id;
        $detailPack->quantity_product = $quantity_product;
        $detailPack->save();

        return response()->json(['success' => 'Détail mis à jour avec succès.']);
    }

    public function updatePack(Request $request){
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $id = $request->input('id_pack');
        $name = $request->input('name');
        $price = $request->input('price');
        $picture = $request->file('image');

        $result = Pack::updatePack($id, $name, $price, $picture);

        if ($result) {
            return redirect()->back()->with('success', 'Pack mis à jour avec succès.');
        } else {
            return redirect()->back()->with('error', 'Échec de la mise à jour du pack. Le pack n\'existe pas.');
        }
    }


    public function deletePack( $id){
        $pack = Pack::find($id);
        if (!$pack) {
            return redirect()->back()->with('error', 'Ce pack n\'existe pas.');
        }
        $pack->state = 0;
        $pack->save();
        return redirect()->back()->with('success', 'Pack supprimé avec succès.');
    }


}
