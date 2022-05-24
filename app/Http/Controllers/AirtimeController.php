<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\User;
use App\Models\AirtimeRate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class AirtimeController extends Controller
{
    public function indexRates(){
        try {
            $user = User::find(Auth::id()); 
            if($user && $user->role == 'admin'){
                $rates = AirtimeRate::all();
                return view('airtime.rates',['user'=>$user,'rates'=>$rates])->with('page','Airtime | Rates');

            }
            return redirect()->route('login');
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function airtimeTransactions(){
        try {
            $user = User::find(Auth::id()); 
            if($user){
                $transactions = $user->role == 'admin' ? Transaction::latest()->where('paymentable_type','App\Models\AirtimeRate')->get() 
                    :  $user->transactions::latest()->where('paymentable_type','App\Models\AirtimeRate')
                        ->get();
                return view('airtime.transactions',['user'=>$user,'transactions'=>$transactions])->with('page','Airtime | Rates');

            }
            return redirect()->route('login');
        } catch (\Throwable $th) {
            throw $th;
        }
    }
    

    public function storeRate(Request $request){
        try {
            $user = User::find(Auth::id()); 
            if($user && $user->role == 'admin'){
                $rate = new AirtimeRate();
                $rate->lower_limit = $request->lower_limit;
                $rate->upper_limit = $request->upper_limit;
                $rate->bonus = $request->bonus ? $request->bonus : 0 ;
                $rate->status = 'Active';
                $rate->save();
                return redirect()->back();

            }
            return redirect()->route('login');
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function updateRate(Request $request,$id){
        try {
            $user = User::find(Auth::id()); 
            if($user && $user->role == 'admin'){
                $rate = AirtimeRate::find($id);
                $rate->lower_limit = $request->lower_limit;
                $rate->upper_limit = $request->upper_limit;
                $rate->bonus = $request->bonus ? $request->bonus : 0 ;
                $rate->status = $request->status;
                $rate->save();
                return redirect()->back();

            }
            return redirect()->route('login');
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function buyAirtime(Request $request,$id){
        try {
            $user = User::find(Auth::id()); 
            if($user){
                $rate = AirtimeRate::find($id);
                $amount = $request->amount + $rate->bonus;
               
                return redirect()->back();

            }
            return redirect()->route('login');
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
