<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\User;
use App\Models\AirtimeRate;
use App\Models\AirtimeOperator;
use Illuminate\Http\Request;
use App\Http\Traits\AirtimeTrait;
use App\Models\AirTime;
use App\Models\Payment;
use Illuminate\Support\Facades\Auth;
use App\Http\Traits\AccountsOperationsTrait;


class AirtimeController extends Controller
{
    use AirtimeTrait,AccountsOperationsTrait;

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
                // dd($request);
                $rate = $id ? AirtimeRate::find($request->rate_id) : AirtimeRate::find($request->select_rate_id) ;
                $amount = $request->amount + $request->amount *$rate->bonus/100;
                $fee = 0;
                $capable = $this->checkTransactionCapability($request,$user,$fee);
                if(!$capable){
                    return redirect()->back()->withErrors('Error','You have insuffiecient balance in your account to complete this transaction');
                }
                $operator = AirTimeOperator::where('operator_code',$request->operator)->first();
                if(!$operator){
                    $operator=$this->getTopupOperator($request->operator);
                    $new_operator = new AirtimeOperator();
                    $new_operator->name = $operator->name;
                    $new_operator->operator_code = $operator->operatorId;
                    $new_operator->country_id = $operator->country->isoName;
                    $new_operator->logo_url = $operator->logoUrls ?  $operator->logoUrls[0]: '';
                    $new_operator->save();
                    if($new_operator){
                        $operator= $new_operator;
                    }
                }
                $recipient = array("countryCode"=>$operator->country->ISO,
                                    'number'=>$request->phone
                                );
                $sender = array("countryCode"=>$user->country ? $user->country->ISO : 'UG',
                                'number'=>$user->telephone
                            );
                $top_data= array(
                        "operatorId" => $operator->operator_code,
                        "amount" => $amount,
                        "useLocalAmount"=> false, 
                        "customIdentifier"=> $user->id.'A'.rand(1000,9999),
                        "recipientPhone"=>$recipient,
                        "senderPhone"=>$sender
                            );
                $transaction_details=$this->makeTopUp($top_data);
                $transaction_details = json_decode($transaction_details,true);
                if($transaction_details['status']== 'SUCCESSFUL'){
                    $airtime = new AirTime();
                    $airtime->amount = $request->amount;
                    $airtime->airTimeRate()->associate($rate);
                    $airtime->user()->associate($user);
                    $airtime->bonus = $transaction_details['deliverdAmount']-$request->amount;
                    $airtime->status = 'Successful';
                    $airtime->save();
                    $this->storePayment($airtime,$request->account,$transaction_details);

                }

               
               
                return redirect()->back()->with('success',"Airtime top up of successfully made for $request->phone");

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
