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


        
        $billers = $this->getUtilityBillers();
    }

    public function payUtility(Request $request){
        try {
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
                if($pay){
                    $pay_result = $this->payBill(json_encode($pay_details));
                    $pay_result = json_decode($pay_result,true);
                    if($pay_result['status'] == 'PROCESSING'){
                        $rate = ElectricityRate::find($request->rate->id);
                        $umeme = new Electricity();
                        $umeme->electricityRate()->associate($rate);
                        $umeme->amount = $request->amount;
                        $umeme->bonus = $rate->bonus * $request->amount/100;
                        $umeme->status = 'Initiated';
                        $umeme->save();
                        UtilityPaymentJob::dispatch($pay_result['id'],$pay_channel,$umeme,$request->source)->delay(now()->addSeconds(5));
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
            //throw $th;
        }
    }
    
    
}
