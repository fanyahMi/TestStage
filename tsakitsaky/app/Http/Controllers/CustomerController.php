<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function showCustomer(){
        return view("template.Layout", [
            'title' => 'Clients',
            'page' => "customer.CustomerList",
            'customers' => Customer::all(),
        ]);
    }

    public function createCustomer(Request $request){
        $name = $request->input('name');
        $first_name = $request->input('first_name');
        $sex = $request->input('sex');
        $phone  = $request->input('phone');
        $email = $request->input('email');
        $address = $request->input('address');

        Customer::create([
            'name'=> $name,
            'first_name'=> $first_name,
            'sex'=> $sex,
            'phone'=> $phone,
            'email'=> $email,
            'address'=> $address
        ]);

        return redirect()->back();
    }
}
