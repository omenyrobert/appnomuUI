<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\User;
use App\Models\AirtimeRate;
use App\Models\AirtimeOperator;
use Illuminate\Http\Request;
use App\Http\Traits\AirtimeTrait;
use Illuminate\Support\Facades\Auth;


class AirtimeController extends Controller
{
    use AirtimeTrait;

    public function index(){
        try {
            $user = User::find(Auth::id());
            if($user){
                //todo:get the users country location and pick providers from that country
                $airtime_providers = $this->getTopupOperatorByIso('UG');
                $airtime_providers = json_decode($airtime_providers);
                $airtime_rates = AirtimeRate::paginate(10);

                return view('payments.airtime.index',['providers'=>$airtime_providers,'rates'=>$airtime_rates,'user'=>$user])->with('page','Airtime');
            }
            return redirect()->route('login');
        } catch (\Throwable $th) {
            throw $th;
        }
    }
    public function indexRates(){
        try {
            $user = User::find(Auth::id()); 
            if($user && $user->role == 'admin'){
                $rates = AirtimeRate::paginate(10);
                return view('payments.airtime.rates',['user'=>$user,'rates'=>$rates])->with('page','Airtime | Rates');

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

    public function getRate($id){
        $rate = AirtimeRate::find($id);
        return json_encode($rate);
    }

    public function buyAirtime(Request $request,$id=null){
        try {
            $user = User::find(Auth::id()); 
            if($user){
                $rate = $id ? AirtimeRate::find($request->rate_id) : AirtimeRate::find($request->select_rate_id) ;
                $amount = $request->amount + $request->amount *$rate->bonus/100;
                $operator = AirTimeOperator::find($request->operator);
                $recipient = array("countryCode"=>$operator->country->ISO,
                                    'number'=>$request->phone
                                );
                $sender = array("countryCode"=>$user->country ? $user->country->ISO : 'UG',
                                'number'=>$user->telephone
                            );
                $top_data= array(
                        "operatorId" => $operator,
                        "amount" => $amount,
                        "useLocalAmount"=> false, 
                        "customIdentifier"=> $user->id,
                        "recipientPhone"=>$recipient,
                        "senderPhone"=>$sender
                            );
                $transaction_details=$this->makeTopUp($top_data);
                dd($transaction_details);

               
               
                return redirect()->back();

            }
            return redirect()->route('login');
        } catch (\Throwable $th) {
            throw $th;
        }
    }
    public function getCountryOperators($iso){
        $operators = $this->getTopupOperatorByIso($iso);
        return $operators;
    }
}
