<?php

namespace App\Http\Controllers;

use App\Http\Traits\AccountsOperationsTrait;
use App\Models\User;
use App\Models\Electricity;
use Illuminate\Http\Request;
use App\Jobs\UtilityPaymentJob;
use App\Http\Traits\UtilityTrait;
use App\Models\ElectricityRate;
use Illuminate\Support\Facades\Auth;

class UtilityController extends Controller
{
    use UtilityTrait,AccountsOperationsTrait;

    public function index(){
        try {
            $user = User::find(Auth::id());
            if($user){
                $billers = $this->getUtilityBillers();
                $billers = json_decode($billers,true);
                // dd($billers);
                $e_rates = ElectricityRate::all();
                return view('payments.utilities.index',[
                    'user'=>$user,
                    'billers'=>$billers,
                    'e_rates'=>$e_rates
                ])->with('page','Utilities');
            }
            return redirect()->route('login')->withErrors('Error','You need to Login to access this resource');
        } catch (\Throwable $th) {
            throw $th;
        }


        
    }

    public function getElectricityRates(){
        $user = User::find(Auth::id());
        if($user && $user->role == 'admin'){
            $rates = ElectricityRate::paginate(10);
            
            return view('payments.utilities.rates',['rates'=>$rates,'user'=>$user,'billers'=>$billers])->with('page','Elctricity| Rates');
        }
        return redirect()->back()->withErrors('Error','You do not have permission to access this resource');
    
       
    }

    public function storeElectricityRate(Request $request){
        try {
            $user = User::find(Auth::id());
            dd($user);
            if($user && $user->role == 'admin'){
                $rate = new ElectricityRate();
                $rate->lower_limit = $request->lower_limit;
                $rate->upper_limit = $request->upper_limit;
                $rate->bonus = $request->bonus;
                $rate->save();
                return redirect()->back()->with('success','New Electricity rate created successfully');
            }
            return redirect()->back()->withErrors('Error','You do not have permission to access this resource');
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function updateElectricityRate(Request $request){
        try {
            $user = User::find(Auth::id());
            if($user && $user->role == 'admin'){
                $rate = ElectricityRate::find($request->id);
                if($rate){
                    $rate->lower_limit = $request->lower_limit;
                    $rate->upper_limit = $request->upper_limit;
                    $rate->bonus = $request->bonus;
                    $rate->save();
                    return redirect()->back()->with('success','Electricity rate edited successfully');

                }
                return redirect()->back()->withErrors('Error','Electricity not found');
            }
            return redirect()->back()->withErrors('Error','You do not have permission to access this resource');
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function payUtility(Request $request){
        try {
            // dd($request);
            $user = User::find(Auth::id());
            if($user){
                $account = $user->account;
                $pay_channel = $request->mode;
                $subscriber_number = $request->subscriber_account;
                $biller_id = $request->biller_id;
                $amount = $request->amount;
                $pay_details = array(
                    "subscriberAccountNumber"=>$subscriber_number,
                    "amount"=>$amount,
                    "billerId"=>$biller_id,
                    "useLocalAmount"=> false
                );
                $pay = $this->checkTransactionCapability($request,$user,0);
                // dd($pay);
                if($pay){
                    $pay_result = $this->payBill(json_encode($pay_details));
                    $pay_result = json_decode($pay_result,true);
                    // dd($pay_result['status'] == 'PROCESSING');
                    if($pay_result['status'] == 'PROCESSING'){
                        $rate = ElectricityRate::find($request->rate_id);
                        // dd($rate);
                        $umeme = new Electricity();
                        $umeme->electricityRate()->associate($rate);
                        $umeme->user()->associate($user);
                        $umeme->amount = $request->amount;
                        $umeme->bonus = $rate->bonus * $request->amount/100;
                        $umeme->status = 'Initiated';
                        $umeme->save();
                        // dd($umeme);
                        $this->storePayment($umeme,$request->source,$pay_result);
                        UtilityPaymentJob::dispatch($pay_result['id'],$pay_channel,$umeme,$request->source)->delay(now()->addSeconds(5));
                        return redirect()->back();
                    }


                }
                return redirect()->back()->withErrors('Error','you do not have enough funds in your savings account to complete this transaction ');
               
                
        
                // '{
                //     "subscriberAccountNumber": "04223568280",
                //     "amount": 1000,
                //     "billerId": 5,
                //     "useLocalAmount": false
                //   }'

            }
        } catch (\Throwable $th) {
            throw $th;
        }
    }
    
    
}
