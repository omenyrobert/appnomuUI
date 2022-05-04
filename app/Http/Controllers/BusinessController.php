<?php

namespace App\Http\Controllers;

use App\Models\Business;
use App\Models\District;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BusinessController extends Controller
{
    //get all businesses
    public function index(){
        $user = User::find(Auth::id());
        if($user->role == 'admin'){

            $businesses = Business::orderBy('created_at','desc')->get();
        }else{
            $businesses = $user->businesses()->latest()->get();
        }
        return view('bussiness.index',['businesses'=>$businesses])->with('page','Bussinesses');

    }

    //create a business
    public function create(){
        return view('bussiness.create')->with('page','Create Bussiness');
    }

    public function store(Request $request,$id){
        try {
            
            $user = User::findOrFail($id);
            $district = District::find($request->district);
            if($user && $district){
                $business = new Business();
                $business->user()->associate($user);
                $business->district()->associate($district);
                $business->name = $request->name;
                $business->location = $request->location;
                $business->premises_photo = $request->premises;
                $business->license_photo = $request->license;
                $business->business_photo = $request->business_photo;
                $business->save();
                return redirect()->back()->with('');
            }

            return redirect()->back()->withErrors('Errors','please try again');

        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function show($id){
        try {
            $business = Business::findOrFail($id);
            return view('business.show',['business'=>$business])->with('page','Business | Show');

        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
