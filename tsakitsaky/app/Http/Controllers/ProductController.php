<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductUnit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    public function showProducts(Request $request){
        $viewData = DB::table('v_products_lib')->paginate(2);

        if ($request->ajax()) {
            return view('product.Products_table', ['products' => $viewData])->render();
        }

        return view("template.Layout", [
            'title' => 'Liste des produits',
            'page' => "product.ProductsList",
            'units' => ProductUnit::all(),
            'products' => $viewData,
        ]);
    }

  


    public function createProduct(Request $request){
        $rules = [
            'product' => 'required|string|max:255',
            'unitary_quantity' => 'required|numeric|min:0',
            'cost_price' => 'required|numeric|min:0',
            'unit_id' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()
                        ->withErrors($validator)
                        ->withInput();
        }

        $product = Product::create([
            'product' => $request->input('product'),
            'unitary_quantity' => $request->input('unitary_quantity'),
            'cost_price' => $request->input('cost_price'),
            'unit_id' => $request->input('unit_id'),
        ]);
        return redirect()->back();

    }
}
