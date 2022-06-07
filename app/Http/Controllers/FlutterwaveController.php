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
                $capable = $this->checkWithdrawCapability($request,$user,$fee);
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
                    'account_bank'=> $request->type,
                    'account_number'=> $request->account_number,
                    'amount'=>$request->amount,
                    "narration"=> $narration,
                    "currency"=> $request->currency,
                    "reference"=> $reference,
                    "beneficiary_name"=> $beneficiary
                ];
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
                $category = SavingSubCategory::find($requset->category);
                $saving = new Savingg();
                $saving->user()->associate($user);
                $saving->savingSubCategory()->associate($category);
                $saving->amount = $request->amount;
                $saving->status = 'Initiated';
                $saving->duedate = Carbon::now()->addDays($category->Saving_Period);
                $saving->Interest = $request->amount * $category->Interest /100;
                $saving->processing_fees = 0;
                $saving->save();
                $reference =Flutterwave::generateReference().'_sv';
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
                    'saving_id' => $saving->id
                ];
                $customizations = [
                    'title'=> 'Make A Saving Deposit',
                    'logo'=>'',
                    'description'=>'Ma ke a deposit to your appNomu savings account and earn an interest at the end of the saving period'
                ];

                $saving_details = [
                    'tx_ref'=>$reference,
                    'amount'=> $amount,
                    'currency'=>$currency,
                    'customer'=>$customer,
                    'redirect_url'=>$redirect_url,
                    'meta'=> $meta,
                    'customizations'=> $customizations,
                ];

                //todo:send saving details to flutterwavepayment

            }
        } catch (\Throwable $th) {
            throw $th;
        }
        
    }

    public function flwWebhook(Request $request){
        $trans_update = $request; 
    }
    public function initialize(Request $request)
    {
        $user = User::find(Auth::id());
        //Getting the saving category
        $category = SavingCategory::find((int)$request->category);
        if($category){
            if($request->amount < $category->savingCategory->lowerlimit || $request->amount > $category->savingCategory->upperlimit ){

                return redirect()->back()->withErrors(['Errors'=>'Amount to be saved doesnot fall under this category choose supported category...']);
            }
        }
        //This generates a payment reference
        $reference =Flutterwave::generateReference().'_sv';

        // Enter the details of the payment
        $data = [
            'payment_options' => 'card,banktransfer,mobile_money_ug',
            'amount' => $request->amount,
            'email' => $user->email,
            'tx_ref' => $reference,
            'currency' => "UGX",
            'redirect_url' => route('callback'),
            'customer' => [
                'email' => $user->email,
                "phone_number" => $user->telephone,
                "name" => $user->name
            ],

            "customizations" => [
                "title" => 'Appnomu Savings'
            ]
        ];

        $payment = Flutterwave::initializePayment($data);
        if ($payment['status'] !== 'success') {
            return redirect()->back()->withErrors(['Errors'=>'Transaction Failed To Start']);
        }elseif ($payment['status'] == 'success') {
            $this->storeSaving($request,$reference);
            # code...
            // $status = '06';
            // $op_id = 'Save-'.rand(11111,99999);
            // $db_transactions = DB::table('transactions')->insert([
            //     'user_id'=>$user->user_id,
            //     'Trans_id'=>$reference,
            //     'amount'=>request()->amount,
            //     'operation'=>'Saving',
            //     'op_id'=>$op_id,
            //     'email'=>$user->email,
            //     'name'=>$user->name,
            //     'status'=>$status,
            //     'created_at'=>Carbon::now()
            // ]);
            // $status2 = 6;
            // $processing = 0;
            // $db_saving = DB::table('savings')->insert([
            //     'SubCateId'=>request()->category,
            //     'saving_id'=>$op_id,
            //     'user_id'=>session('user_id'),
            //     'amount'=>request()->amount,
            //     'status'=>$status2,
            //     'Interest'=>($request->amount)*($category->Interest/100),
            //     'duedate'=>time(),
            //     'savingdate'=>time(),
            //     'processing_fees'=>$processing,
            //     'created_at'=> Carbon::now()
            // ]);

        }

        return redirect($payment['data']['link']);
    }

    
    /**
     * Obtain Rave callback information
     * @return void
     */
    public function callback()
    {
        
        $status = request()->status;

        //if payment is successful
        if ($status ==  'successful') {
        
            $transactionID = Flutterwave::getTransactionIDFromCallback();
            $data = Flutterwave::verifyTransaction($transactionID);

            // dd($data);
            $db_trans_update = DB::table('transactions')
                ->where('Trans_id','=',request()->tx_ref)
                ->get();

            $dbx = json_decode($db_trans_update,true);
            $num = sizeof($dbx);

            if ($num<1) {
                return redirect()->route('all_savings')->withErrors(['Error'=>'Unknown Transaction Reference']);
            }

            if($dbx[0]['status']!='06'){
                return redirect()->route('all_savings')->withErrors(['Error'=>'Unknown Operation On Transaction']);
            }

            if ($data['status']=='success' && $status=='successful' && $dbx[0]['status']=='06' ) {
                # code...
                $db_up = DB::table('transactions')
                    ->where('Trans_id','=',request()->tx_ref)
                    ->update([
                        'status'=>'07',
                        'FLW_Id'=>$data['data']['id'],
                        'flw_charge'=>$data['data']['app_fee'],
                        'mode'=>$data['data']['auth_model'],
                        'updated_at'=>date('Y-m-d H:i:s',time())
                    ]);
                $rt = 7;
                if(1){
                    $savings = Auth::getSavingById($dbx[0]['op_id']);
                    $getting_sub_Cat = Auth::getSavingSubCatByCatId($savings[0]['SubCateId']);

                    $witdraw_date = time()+($getting_sub_Cat[0]['Saving_Period']*24*60*60);
                    $db_pg = DB::table('savings')
                        ->where('saving_id','=',$dbx[0]['op_id'])
                        ->update([
                            'status'=>$rt,
                            'duedate'=>$witdraw_date,
                            'savingdate'=>time(),
                            'updated_at'=>date('Y-m-d H:i:s',time())
                        ]);

                    if ($db_pg) {
                        # code...
                        $user_acc = Auth::getAccount($dbx[0]['user_id']);
                        $userxc = Auth::getUserById($dbx[0]['user_id']);
                        //refferal Commission
                        if ($savings[0]['amount']<50000) {
                            $amount = 0;
                        } elseif ($savings[0]['amount']>50000 && $savings[0]['amount']<300000) {
                            $amount = 0;
                        } elseif($savings[0]['amount']>300000) {
                            $amount = 0;
                        }
                        
                        $reff = Auth::creditRefferer($userxc[0]['refferer'],$dbx[0]['user_id'], $amount);
                        //Refferal Commission Finish
                        $dbop = DB::table('user_account')
                            ->where('user_id','=',$dbx[0]['user_id'])
                            ->update([
                                'Ledger_Balance'=>$user_acc[0]['Ledger_Balance'] + ($savings[0]['amount'] + $savings[0]['Interest'] ),
                                'Total_Saved'=>$user_acc[0]['Total_Saved'] + $savings[0]['amount'] 
                            ]);

                        if ($dbop) {
                            return redirect()->route('all_savings')->with(['Success'=>'Saving Succesful']);
                        }
                        
                    }
                }
            }else {
                # code..
                return redirect()->route('all_savings')->withErrors(['Error'=>'Unknown Operation On Transaction']);
            }
        }
        elseif ($status ==  'cancelled'){
            //Put desired action/code after transaction has been cancelled here
            $db_trans_update = DB::table('transactions')
                ->where('Trans_id','=',request()->tx_ref)
                ->get();

            $dbx = json_decode($db_trans_update,true);
            $num = sizeof($dbx);

            if ($num<1) {
                # code...
                return redirect()->route('all_savings')->withErrors(['Error'=>'Unknown Transaction Reference']);
            }

            if($dbx[0]['status']!='06'){
                return redirect()->route('all_savings')->withErrors(['Error'=>'Unknown Operation On Transaction']);
            }

            if ($dbx[0]['status']=='06' && request()->status=='cancelled') {
                # code...
                $db_up = DB::table('transactions')
                    ->where('Trans_id','=',request()->tx_ref)
                    ->update([
                        'status'=>'05',
                        'updated_at'=>date('Y-m-d H:i:s',time())
                    ]);
                $rt = 9;
                if($db_up){
                    $db_pg = DB::table('savings')
                        ->where('saving_id','=',$dbx[0]['op_id'])
                        ->update([
                            'status'=>$rt,
                            'updated_at'=>date('Y-m-d H:i:s',time())
                        ]);

                    if ($db_pg) {
                        # code...
                        return redirect()->route('all_savings')->withErrors(['Error'=>'Transaction Failed']);
                    }
                }
            }else {
                # code...
                return redirect()->route('all_savings')->withErrors(['Error'=>'Unknown Operation On Transaction']);
            }
        }
        else{
            //Put desired action/code after transaction has failed here
            $db_trans_update = DB::table('transactions')
                ->where('Trans_id','=',request()->tx_ref)
                ->get();

            $dbx = json_decode($db_trans_update,true);
            $num = sizeof($dbx);

            if ($num<1) {
                # code...
                return redirect()->route('all_savings')->withErrors(['Error'=>'Unknown Transaction Reference']);
            }

            if($dbx[0]['status']!='06'){
                return redirect()->route('all_savings')->withErrors(['Error'=>'Unknown Operation On Transaction']);
            }

            if ($dbx[0]['status']=='06' && request()->status=='failed') {
                # code...
                $db_up = DB::table('transactions')
                    ->where('Trans_id','=',request()->tx_ref)
                    ->update([
                        'status'=>'05',
                        'updated_at'=>date('Y-m-d H:i:s',time())
                    ]);
                $rt = 9;
                if($db_up){
                    $db_pg = DB::table('savings')
                        ->where('saving_id','=',$dbx[0]['op_id'])
                        ->update([
                            'status'=>$rt,
                            'updated_at'=>date('Y-m-d H:i:s',time())
                        ]);

                    if ($db_pg) {
                        # code...
                        return redirect()->route('all_savings')->withErrors(['Error'=>'Transaction Failed']);
                    }
                }
            }else {
                # code...
                return redirect()->route('all_savings')->withErrors(['Error'=>'Unknown Operation On Transaction']);
            }


        }

    }

    public static function pay_loan($id)
    {
        $user = User::find(Auth::id());
        $installment = Repayment::find($id); 
        $loan =  $installment->repaymentable;
        $category = $loan->loanCategory;

        

        // if ($cate[0]['status']!=6) {
        //     return 2;
        // }

        //This generates a payment reference
        $reference =Flutterwave::generateReference().'_ln';

        // Enter the details of the payment
        $data = [
            'payment_options' => 'card,banktransfer,mobile_money_ug',
            'amount' => $installment->amount,
            'email' => $user->email,
            'tx_ref' => $reference,
            'currency' => "UGX",
            'redirect_url' => route('callback_loans'),
            'customer' => [
                'email' => $user->email,
                "phone_number" => $user->telephone,
                "name" => $user->name
            ],

            "customizations" => [
                "title" => 'Appnomu Loan Repayment'
            ]
        ];



        $payment = Flutterwave::initializePayment($data);
        if ($payment['status'] !== 'success') {
            return 3;
        }elseif ($payment['status'] == 'success') {
            $operation ='Loan Installment';
            $this->storeTransaction($operation,$installment->id,$reference);
           
        }
        return $payment['data']['link'];
    }

    public function loan_callback()
    {
        
        $status = request()->status;

        //if payment is successful
        if ($status ==  'successful') {
        
            $transactionID = Flutterwave::getTransactionIDFromCallback();
            $data = Flutterwave::verifyTransaction($transactionID);

            // dd($data);
            $db_trans_update = DB::table('transactions')
                ->where('Trans_id','=',request()->tx_ref)
                ->get();

            $dbx = json_decode($db_trans_update,true);
            $num = sizeof($dbx);

            if ($num<1) {
                return redirect()->route('loan-installments')->withErrors(['Error'=>'Unknown Transaction Reference']);
            }

            if($dbx[0]['status']!='06'){
                return redirect()->route('loan-installments')->withErrors(['Error'=>'Unknown Operation On Transaction']);
            }

            if ($data['status']=='success' && $status=='successful' && $dbx[0]['status']=='06' ) {
                # code...
                $db_up = DB::table('transactions')
                    ->where('Trans_id','=',request()->tx_ref)
                    ->update([
                        'status'=>'07',
                        'FLW_Id'=>$data['data']['id'],
                        'flw_charge'=>$data['data']['app_fee'],
                        'mode'=>$data['data']['auth_model'],
                        'updated_at'=>date('Y-m-d H:i:s',time())
                    ]);
                $rt = 7;

                if($db_up){
                    $install = Auth::getLoanInstallmentById($dbx[0]['op_id']);
                    // if ( $install[0]['Amount_Paid'] !==  $dbx[0]['amount']) {
                    //     return redirect()->back()->withErrors(['Errors'=>'Loan Amount is less']);
                    // }

                    $rt =7;
                    $db_pg = DB::table('loanpaymentsinstallments')
                        ->where('id','=',$dbx[0]['op_id'])
                        ->update([
                            'status'=>$rt,
                            'updated_at'=>date('Y-m-d H:i:s',time())
                        ]);

                    if ($db_pg) {
                        # get loan and edit it 
                        $user_loan = Auth::getLoanByCatID2($install[0]['ULoan_Id']);
                        $num_lns = sizeof($user_loan);
                        
                        $lonx = Auth::getLoanCatID($user_loan[0]['loan_amount']);

                        if ($num_lns<1) {
                            return 6;
                        }
                        
                        $total_loan = $user_loan[0]['loan_amount'] + $lonx[0]['processing_fees'] +(($lonx[0]['interest_rate']/100)*$user_loan[0]['loan_amount']);

                        $paid = $user_loan[0]['amount_paid'] + $dbx[0]['amount'];

                        if ($paid > $total_loan || $paid == $total_loan ) {
                            
                            $statusdf = 7;
                        } else {
                            # code...
                            $statusdf =  $user_loan[0]['status'];
                        }
                        
                        $dbop = DB::table('userloans')
                            ->where('ULoan_Id','=',$install[0]['ULoan_Id'])
                            ->update([
                                'status'=>$statusdf,
                                'amount_paid'=> $user_loan[0]['amount_paid'] + $dbx[0]['amount'],
                                'updated_at'=>date('Y-m-d H:i:s',time())
                            ]);
                        

                        if ($dbop) {
                            $user_accv= Auth::getAccount($dbx[0]['user_id']);
                            $userxc = Auth::getUserById($dbx[0]['user_id']);
                            //refferal Commission
                            if ($dbx[0]['amount']<50000) {
                                $amount = 500;
                            } elseif ($dbx[0]['amount']>50000 && $dbx[0]['amount']) {
                                $amount = 500;
                            } elseif($dbx[0]['amount']>300000) {
                                $amount = 500;
                            }
                            
                            $reff = Auth::creditRefferer($userxc[0]['refferer'],$dbx[0]['user_id'], $amount);
                            //Refferal Commission Finish

                            //Edit the user account

                            $user_acc = Auth::getAccount($dbx[0]['user_id']);

                            $db_acg = DB::table('user_account')
                                ->where('user_id','=',$dbx[0]['user_id'])
                                ->update([
                                    'Outstanding_Balance'=>$user_acc[0]['Outstanding_Balance'] - $dbx[0]['amount'],
                                    'Total_Paid'=>$user_acc[0]['Total_Paid'] + $dbx[0]['amount']
                                ]);
                            //finish edititng user account 
                            return redirect()->route('loan-installments')->with('Success','Loan Payment Succesful');
                        }
                        
                    }
                }
            }else {
                return redirect()->route('loan-installments')->withErrors(['Error'=>'Unknown Operation On Transaction']);
            }
        }
        elseif ($status ==  'cancelled'){
            //Put desired action/code after transaction has been cancelled here
            $db_trans_update = DB::table('transactions')
                ->where('Trans_id','=',request()->tx_ref)
                ->get();

            $dbx = json_decode($db_trans_update,true);
            $num = sizeof($dbx);

            if ($num<1) {
                # code...
                return redirect()->route('loan-installments')->withErrors(['Error'=>'Unknown Transaction Reference']);
            }

            if($dbx[0]['status']!='06'){
                return redirect()->route('loan-installments')->withErrors(['Error'=>'Unknown Operation On Transaction']);
            }

            if ($dbx[0]['status']=='06' && request()->status=='cancelled') {
                # code...
                $db_up = DB::table('transactions')
                    ->where('Trans_id','=',request()->tx_ref)
                    ->update([
                        'status'=>'05',
                        'updated_at'=>date('Y-m-d H:i:s',time())
                    ]);
                $rt = 9;
                if($db_up){
                     return redirect()->route('loan-installments')->withErrors(['Error'=>'Transaction Failed']);
                    
                }
            }else {
                # code...
                return redirect()->route('loan-installments')->withErrors(['Error'=>'Unknown Operation On Transaction']);
            }
        }
        else{
            //Put desired action/code after transaction has failed here
            $db_trans_update = DB::table('transactions')
                ->where('Trans_id','=',request()->tx_ref)
                ->get();

            $dbx = json_decode($db_trans_update,true);
            $num = sizeof($dbx);

            if ($num<1) {
                # code...
                return redirect()->route('loan-installments')->withErrors(['Error'=>'Unknown Transaction Reference']);
            }

            if($dbx[0]['status']!='05'){
                return redirect()->route('loan-installments')->withErrors(['Error'=>'Unknown Operation On Transaction']);
            }

            if ($dbx[0]['status']=='05' && request()->status=='failed') {
                # code...
                $db_up = DB::table('transactions')
                    ->where('Trans_id','=',request()->tx_ref)
                    ->update([
                        'status'=>'08',
                        'updated_at'=>date('Y-m-d H:i:s',time())
                    ]);
                $rt = 9;
                if($db_up){
                
                    return redirect()->route('loan-installments')->withErrors(['Error'=>'Transaction Failed']); 
                }
            }else {
                # code...
                return redirect()->route('loan-installments')->withErrors(['Error'=>'Unknown Operation On Transaction']);
            }


        }

    }

    public function webhook(Request $request){
        //This verifies the webhook is sent from Flutterwave
        $verified = Flutterwave::verifyWebhook();

        // if it is a charge event, verify and confirm it is a successful transaction
        if ($verified && $request->event == 'charge.completed' && $request->data->status == 'successful') {
            $verificationData = Flutterwave::verifyPayment($request->data['id']);
            if ($verificationData['status'] === 'success') {
            // process for successful charge

            }

        }

        // if it is a transfer event, verify and confirm it is a successful transfer
        if ($verified && $request->event == 'transfer.completed') {

            $transfer = Flutterwave::transfers()->fetch($request->data['id']);

            if($transfer['data']['status'] === 'SUCCESSFUL') {
                // update transfer status to successful in your db
                $tran = FlutterwaveController::getTransactions($transfer['data']['reference']);

                if ($tran[0]['Trans_id']=='06') {
                    # code...
                    $db = DB::table('transactions')
                        ->where('Trans_id','=',$transfer['data']['reference'])
                        ->update([
                            'status'=>'07',
                            'updated_at'=>date('Y-m-d H:i:s',time())
                        ]);
                }


            } else if ($transfer['data']['status'] === 'FAILED') {
                // update transfer status to failed in your db
                $tran = FlutterwaveController::getTransactions($transfer['data']['reference']);

                if ($tran[0]['Trans_id']=='06') {
                    # code...
                    $db = DB::table('transactions')
                        ->where('Trans_id','=',$transfer['data']['reference'])
                        ->update([
                            'status'=>'05',
                            'updated_at'=>date('Y-m-d H:i:s',time())
                        ]);

                    if ($db) {
                        # Take Back the money
                        $withdraws = FlutterwaveController::getWithdrawTransactions($transfer['data']['reference']);

                        $user_acc = Auth::getAccount($withdraws[0]['user_id']);

                        if ($withdraws[0]['withdraw_from']=='savings') {
                            # code...
                            $db_up = DB::table('user_account')
                                ->where('user_id','=',$withdraws[0]['user_id'])
                                ->update([
                                    'available_balance'=> $user_acc[0]['available_balance'] + ($transfer['data']['amount'] + $transfer['data']['fee'] ),
                                    'Amount_Withdrawn'=> $user_acc[0]['Amount_Withdrawn'] - ($transfer['data']['amount'] + $transfer['data']['fee'] ),
                                    
                                ]);
                        }elseif ($withdraws[0]['withdraw_from']=='loans') {
                            # code...
                            $db_up = DB::table('user_account')
                                ->where('user_id','=',$withdraws[0]['user_id'])
                                ->update([
                                    'Loan_Balance'=> $user_acc[0]['Loan_Balance'] + ($transfer['data']['amount'] + $transfer['data']['fee'] ),
                                    'Amount_Withdrawn'=> $user_acc[0]['Amount_Withdrawn'] - ($transfer['data']['amount'] + $transfer['data']['fee'] ),
                                    
                                ]);
                        }

                        $db_with = DB::table('withdraws')
                            ->where('trans_id','=',$transfer['data']['reference'])
                            ->update([
                                'status'=>'05'
                            ]);
                    }
                }
                // revert customer balance back
            } else if ($transfer['data']['status'] === 'PENDING') {
                // update transfer status to pending in your db
            }

        }
    }

    public function withdraw(Request $request){
        $validated = $request->validate([
            'account'=>'required',
            'amount'=>'required'
        ]);

        $user = User::findOrFail(Auth::id());
        $user_acc = $user->account;

        // if (sizeof($user)<1) {
        //     return redirect()->back()->withErrors(['Errors'=>'User Account Error Contact Administration']);
        // }

        // if (sizeof($user_acc)<1) {
        //     return redirect()->back()->withErrors(['Errors'=>'User Account Error Contact Administration']);
        // }

        // todo:allow withdraws on savings that are ready for wihdrawall
        // todo:connect this data to account after transaction 

        if ($user->sms_verified_at==null) {
            return redirect()->route('profile')->withErrors(['Errors'=>'No Verified Phone Number Has Been Found Please Verify Your Phone Number Before You Can Withdraw Your Money']);
        }

        if ($request['amount']<1000) {
            # code...
            return redirect()->back()->withErrors(['Errors'=>'Your Minimum Value of Withdraw Should be UGX.1000']);
        }

        if($request['account']=='savings'){
            if(($request['amount']+500)>$user_acc[0]['available_balance'] ){
                $stat = '09';
            }else {
                $stat = '07';
            }
        }elseif ($request['account']=='loans'){
            if(($request['amount']+500)>$user_acc[0]['Loan_Balance'] || $request['amount']==$user_acc[0]['Loan_Balance']){
                $stat = '09';
            }else {
                $stat = '07';
            }
        }

        if ($stat=='09') {
            return redirect()->back()->withErrors(['Errors'=>'You Dont have sufficient Funds on Your Account']);
        }
        

        $reference = Flutterwave::generateReference().'_wd';

        $data = [
            "account_bank" => "MPS",
            "account_number" => $user->telephone,
            "amount" => $request['amount'],
            "narration" => "Appnomu Withdraws",
            "currency" => "UGX",
            "reference" => $reference,
            "beneficiary_name" => $user->name
        ];
        
        $transfer = Flutterwave::transfers()->initiate($data);

        if ($transfer['status']!=='success') {
            return redirect()->back()->withErrors(['Errors'=>'Transaction Failed To Start']);
        } elseif ($transfer['status']=='success') {
            $status = '06';
            $op_id = 'Withdraws-'.rand(11111,99999);
            $this->storeWithdraw($request,$reference,$transfer);

           
            
            return redirect()->back()->with('Success','Withdraw Initiated Successfully');

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
