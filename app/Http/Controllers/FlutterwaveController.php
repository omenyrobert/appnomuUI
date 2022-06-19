<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use KingFlamez\Rave\Facades\Rave as Flutterwave;
// use App\Http\Controllers\AuthenticationController as Auth;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Mail;
use App\Mail\ConfirmMail;
use App\Http\Controllers\SmsController;
use App\Models\SavingCategory;
use App\Models\SavingSubCategory;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Http\Traits\AccountsOperationsTrait;
use App\Http\Traits\FlutterwaveTrait;
use App\Models\Repayment;
use App\Models\Savingg;
use App\Models\Transaction;
use App\Models\Withdraw;

class FlutterwaveController extends BaseController
{
    use AccountsOperationsTrait, FlutterwaveTrait;
    //
    public function makeWithdraw(Request $request){
        try {
            $user = User::find(Auth::id());
            // dd($user);
            if($user){
                $fee_details = $this->getTransferFee($request->amount,$request->currency,$request->type);
                $fee_details = json_decode($fee_details,true);
                $fee = $fee_details['data'][0]['fee'];
                // dd($fee_details['data'][0]['fee']);
                $capable = $this->checkTransactionCapability($request,$user,$fee);
                if(!$capable){
                    return redirect()->back()->withErrors('Error','You have insuffiecient balance in your account to complete this transaction');
                }
                $reference =Flutterwave::generateReference().'_wd';
                $beneficiary = $request->beneficiary;
                switch ($request->destination) {
                    case 'withdraw':
                        $narration = 'Appnomu withdraws';
                        break;
                    case 'transfer':
                        $narration = 'Appnomu transfers';
                        break;                    
                   
                }   
                if($request->type == 'account'){
                    $bank = $request->bank;
    
                }else{
                    $operator = $request->operator;
                }       
                $trans_details = [
                    'account_bank'=> $request->account_bank,
                    'account_number'=> $request->account_number,
                    'amount'=>$request->amount,
                    "narration"=> $narration,
                    "currency"=> $request->currency,
                    "reference"=> $reference,
                    "beneficiary_name"=> $beneficiary
                ];
                // dd($trans_details);
                $response = $this->makeTransfer($trans_details);
                $response = json_decode($response,true);
                // dd($response);
                if($response['status'] == 'success'){
                    $this->storeWithdraw($request,$response['data'],$user);   
                }
                return redirect()->back()->withErrors('Error','Error processing transaction');
                // {
                //     "status": "success",
                //     "message": "Transfer Queued Successfully",
                //     "data": {
                //         "id": 127894,
                //         "account_number": "233542773934",
                //         "bank_code": "MPS",
                //         "full_name": "Flutterwave Developers",
                //         "created_at": "2020-06-25T14:39:16.000Z",
                //         "currency": "UGX",
                //         "amount": 50,
                //         "fee": 500,
                //         "status": "NEW",
                //         "reference": "ugx-momo-transfer",
                //         "meta": null,
                //         "narration": "UGX momo transfer",
                //         "complete_message": "",
                //         "requires_approval": 0,
                //         "is_approved": 1,
                //         "bank_name": "FA-BANK"
                //     }
                // }
            }
            return redirect()->route('login')->withErrors('Error','Unauthorized. You must login to perform this operation');
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function makeDeposit(Request $request){
        try {
            $user = User::find(Auth::id());
            if($user){
                
                $reference = Flutterwave::generateReference().'_sv';
                $saving = $this->storeSaving($request,$reference); 
                // dd($saving);
                if($saving){

                    $amount = $request->amount;
                    $currency = $request->currency;
                    $customer = [
                        'name'=> $user->name,
                        'email'=> $user->email,
                        'phonenumber'=>$user->telephone
                    ];
                    $redirect_url = route('savings.handle');
                    $meta = [
                        'user_id'=>$user->id,
                        'model' => 'saving'
                    ];
                    $customizations = [
                        'title'=> 'Make A Saving Deposit',
                        'logo'=>'',
                        'description'=>'Ma ke a deposit to your appNomu savings account and earn an interest at the end of the saving period'
                    ];
    
                    $details = [
                        'tx_ref'=>$reference,
                        'amount'=> $amount,
                        'currency'=>$currency,
                        'customer'=>$customer,
                        'redirect_url'=>$redirect_url,
                        'meta'=> $meta,
                        'customizations'=> $customizations,
                    ];
                    // dd($details);
                    //todo:send saving details to flutterwavepayment
                    $path_response = $this->collectPayment($details); 
                    $path_response = json_decode($path_response,true);
                    // dd($path_response);
                    if($path_response['status'] == 'success'){
                        // dd($path_response['data']['link']);
                        return redirect($path_response['data']['link']);
                    }
                }               

                return redirect()->back()->withErrors('Error','Something went wrong processing this transaction');
                
                

            }
        } catch (\Throwable $th) {
            throw $th;
        }
        
    }

    public function payLoanInstallment(Request $request){
        try {
            $user = User::find(Auth::id());
            if($user){
                $repayment = Repayment::findOrFail($request->id);
                $reference = Flutterwave::generateReference().'_ln';
                $amount = $request->amount;
                $currency = $request->currency;
                $customer = [
                    'name'=> $user->name,
                    'email'=> $user->email,
                    'phonenumber'=>$user->telephone
                ];
                $redirect_url = route('savings.handle');
                $meta = [
                    'user_id'=>$user->id,
                    'model' => 'repayment'
                ];
                $customizations = [
                    'title'=> "Loan Repayment",
                    'logo'=>'',
                    'description'=>'Complete your loan payment and stand a chance to have a higher loan limit'
                ];

                $details = [
                    'tx_ref'=>$reference,
                    'amount'=> $amount,
                    'currency'=>$currency,
                    'customer'=>$customer,
                    'redirect_url'=>$redirect_url,
                    'meta'=> $meta,
                    'customizations'=> $customizations,
                ];
                //todo:send saving details to flutterwavepayment
                $path_response = $this->collectPayment($details); 
                $path_response = json_decode($path_response,true);
                if($path_response['status'] == 'success'){
                    return redirect($path_response['data']['link']);
                }
            }
            return redirect()->back()->withErrors('Error','Something went wrong processing this transaction');

        } catch (\Throwable $th) {
            throw $th;
        }
    }

    

   

   
    public static function getTransactions($transid){
        $db = DB::table('transactions')
            ->where('Trans_id','=',$transid)
            ->get();
    
        $dbx = json_decode($db,true);

        return $dbx;
    }

    public static function getWithdrawTransactions($transid){
        $db = DB::table('withdraws')
            ->where('trans_id','=',$transid)
            ->get();
    
        $dbx = json_decode($db,true);

        return $dbx;
    }

}
