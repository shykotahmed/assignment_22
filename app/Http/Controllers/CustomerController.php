<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Exception;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    function CustomerPage(){
        return view('pages.dashboard.customer-page');
    }
    function GetCustomer(){
        return Customer::all();
    }
    function CreateCustomer(Request $request){
        try{
            return Customer::create([
                'name'=>$request->input('name'),
                'email'=>$request->input('email'),
                'address'=>$request->input('address'),
                'phone'=>$request->input('phone')
            ]);
            
        }catch(Exception $e){
            return response()->json([
                'status'=>'failled',
                'message'=>'unauthorized'
            ]);
        }
    }
    function UpdateCustomer(){
        //
    }
    function DeleteCustomer(){
        //
    }
}
