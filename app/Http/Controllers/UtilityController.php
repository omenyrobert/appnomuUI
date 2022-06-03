<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Traits\UtilityTrait;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UtilityController extends Controller
{
    use UtilityTrait;

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
                switch ($$pay_channel) {
                    case 'savings':
                        if($amount <= $account->available_balance){
                           $pay_result = $this->payBill(json_encode($pay_details));
                            break;
                        
                        }
                        return redirect()->back()->withErrors('Error','you do not have enough funds in your savings account to complete this transaction ');
                    
                    case 'loan':
                        if($amount <= $account->loan_balance){
                           $pay_result = $this->payBill(json_encode($pay_details));
                            break;
                        
                        }
                        return redirect()->back()->withErrors('Error','you do not have enough funds in your loan account to complete this transaction ');
                        
                }               
                
        
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
