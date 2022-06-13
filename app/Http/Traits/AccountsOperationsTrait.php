<?php


namespace App\Http\Traits;

use App\Models\AirTime;
use App\Models\Loan;
use App\Models\Payment;
use App\Models\Repayment;
use App\Models\Saving;
use App\Models\SavingSubCategory;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Withdraw;
use App\Notifications\AccountOperationNotification;
use App\Notifications\TransactionNotification;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

trait AccountsOperationsTrait{
    use SMSTrait;
    public function accountOperation($operation,$type,$id){
        //operation = credit or debit
        //type = loan,soma,business,saving,transaction
        try {
            switch ($operation) {
                case 'credit':
                    switch ($type) {
                        case 'loans':
                            $model = Loan::findOrFail($id);
                            //todo:create a transaction for loans credited to account
                            $account = $model->account;
                            $account->Loan_Balance +=$model->principal;
                            $account->Outstanding_Balance += $model->principal;
                            $account->save();

                            break;
                        case 'savings':
                            $model = Saving::findOrFail($id);
                            $account = $model->account;
                            $account->Ledger_Balance += $model->amount;
                            $account->Total_Saved += $model->amount;
                            $account->save();
                            break;   
                        case 'repayment':
                            $model = Repayment::findOrFail($id);
                            $loan = $model->repaymentable;
                            $loan->amount_paid +=$model->amount_paid;
                            $loan->save();
                            $account = $model->user->account;
                            $account->Outstanding_Balance -= $model->amount_paid;
                            $account->Total_Paid += $model->amount_paid;
                            $account->save();
                            break;                             
                    }    
                    break;
                case 'debit':
                    switch ($type) {
                        case 'loans':
                            $model = withdraw::findOrFail($id);
                            $account = $model->user->account;
                            if($model->user->telephone == $model->account_number){
                                $account->Loan_Balance -= $model->amount;
                                $account->amount_withdrawn += $model->amount;
                                $account->save();
                                break;
                            }
                            
                            $account->Loan_Balance -=$model->amount-$model->withdrawFee->fee;
                            $account->amount_withdrawn += $model->amount;
                            $account->save();
                            break;
                        case 'savings':
                            $model = Withdraw::findOrFail($id);
                            $account = $model->user->account;
                            $account->available_balance -= $model->amount-$model->withdrawFee->fee;
                            $account->amount_withdrawn += $model->amount ;
                            $account->save();
                            break;    
                        case 'payment':
                            $model = Payment::findOrFail($id);
                            dd($model);
                            $account = $model->user->account;
                            $account->available_balance -= $model->amount; //-$withdraw->withdrawFee->fee;
                            $account->save();
                            break;
                    }                    
                    break;  

            }
            $notif_data = $this->notificationData($model->transaction);
            $user = $model->user;
            $user->notify(new AccountOperationNotification($notif_data));
            $sms_status = $this->sendSMS($notif_data['message'],$user->telephone);
            // $user
            return true;
        } catch (\Throwable $th) {
            throw $th;
            // $log = [
            //     'name'=>'account operations',
            //     'operation'=>$operation,
            //     'type' =>$type,
            //     'model_id'=>$id,
            //     'message'=>$th->getMessage()
            // ];
            // logger($log);
            // return false;
        }

    }

    public function storeSaving(Request $request,$reference){
        try {
            $user = User::findOrFail(Auth::id());
            $category = SavingSubCategory::find((int) $request->category);
            $saving = new Saving();
            $saving->user()->associate($user);
            $saving->account()->associate($user->account);
            $saving->savingSubCategory()->associate($category);
            $saving->Interest = ((int)$request->amount)*($category->Interest/100);
            $saving->dueDate = Carbon::now()->addDays($category->Save_Period);
            $saving->processing_fees = 0;
            $saving->amount = (int)$request->amount;
            $saving->saving_id = 'Save-'.rand(11111,99999);
            $saving->status = 'Initiated';
            $saving->save();
            $data = [
                'data'=>[
                    'reference'=>$reference,
                    'fee'=>0,
                    'amount'=>$saving->amount,
                    'full_name'=> $user->name,
                    'id'=> ''
                ]
            ];
            $transaction = $this->storeTransaction('Saving',$saving->id,$data);
            if($transaction){

                return true;
            }
            // return redirect()->back()->with('success','Transaction completed successfully');
           
            return false;// redirect()->back()->with('success','Transaction completed successfully');

        } catch (\Throwable $th) {
            throw $th;
        }
    }
    public function storeWithdraw(Request $request,$details,User $user){
        try {
            $user = User::findOrFail(Auth::id());
            $withdraw = new Withdraw();
            $withdraw->withdraw_from = $request->source;
            $withdraw->user()->associate($user);
            $withdraw->amount = $request->amount;
            
            $withdraw->status = 'Pending';
            if($request->type == 'account'){
                $withdraw->account_number = 'Bank' ;
                $withdraw->mode = 'transfer';
            }else{
                $withdraw->account_number = $request->account_number;
                $withdraw->mode = $request->account_number == $user->telephone ? 'withdraw' : 'transfer';
            }
            $withdraw->currency = $request->currency;
            $withdraw->save();
            $transaction = $this->storeTransaction('Withdraw',$withdraw->id,$details);
            if($transaction){

                return true;
            }
            return false;
            // $this->accountOperation('debit','savings',$withdraw->id,$details);
        } catch (\Throwable $th) {
            throw $th;
            // $log = [
            //     'name'=>'account operations',
            //     'operation'=>$operation,
            //     'type' =>$type,
            //     'model_id'=>$id,
            //     'message'=>$th->getMessage()
            // ];
            // logger($log);
            // return false;
        }

    }


    public function storeTransaction($type,$id,$response=null){
        try {
            $transaction = new Transaction();
            switch ($type) {
                case 'Saving':
                    $operation = Saving::findOrFail($id);
                    break;                
                case 'Loan Installment':
                    $operation = Repayment::findOrFail($id);
                    break;
                case 'Withdraw':
                    $operation = Withdraw::findOrFail($id);
                    break;
                case 'Payment':
                    $operation = Payment::findOrFail($id);
                    break;
            }
            $transaction->transactionable()->associate($operation);
            $transaction->user()->associate($operation->user);
            if($response)
            {
                $transaction->Trans_id = $response['data']['reference'];
                $transaction->flw_charge =  $response['data']['fee'];
                $transaction->amount =  $response['data']['amount'];
                $transaction->FLW_Id = $response['data']['id'];
                $transaction->beneficiary = $response['data']['full_name'];
            }
            // $transaction->mode = $response['data']['full_name'];
            $transaction->operation =  $type;
            $transaction->status = 'Initiated';
            $transaction->save();
            // dd($transaction);
            if($transaction){
                $transaction->user->notify(new TransactionNotification($transaction));
                return true;
            }
            return false;
        } catch (\Throwable $th) {
            throw $th;
            // $log = [
            //     'name'=>'account operations',
            //     'operation'=>$operation,
            //     'type' =>$type,
            //     'model_id'=>$id,
            //     'message'=>$th->getMessage()
            // ];
            // logger($log);
            // return false;
        }


    }

  
    public function getRepayment(Request $request, $reference){
        try {
            $user = User::find(Auth::id());
            if($user){
                $repayment = Repayment::findOrFail($request->id);
                $data = [
                    'data'=>[
                        'reference'=>$reference,
                        'fee'=>0,
                        'amount'=>$repayment->amount,
                        'full_name'=> $user->name,
                        'id'=> ''
                    ]
                ];
                $this->storeTransaction('Loan Installment',$repayment->id,$data);
                return $repayment;

            }
        } catch (\Throwable $th) {
            throw $th;
        }

    }
    public function checkTransactionCapability(Request $request,User $user,$fee){
        // $validated = $request->validate([
        //     'source'=>'required',
        //     'amount'=>'required'
        // ]);
        // $account = $user->account;
        // if (!$user->sms_verified_at) {
        //     return redirect()->route('profile')->withErrors(['Errors'=>'No Verified Phone Number Has Been Found Please Verify Your Phone Number Before You Can Withdraw Your Money']);
        // }

        // if ($request->amount<1000) {
        //     # code...
        //     return redirect()->back()->withErrors(['Errors'=>'Your Minimum Value of Withdraw Should be UGX.1000']);
        // }

        // if($request->source=='savings'){
        //     if(($request->amount+$fee) > $account->available_balance){
        //         return  false;
            
        //     }
        // }elseif ($request->source=='loans'){
        //     if($request->account_number == $user->telephone){
        //         if(($request->amount)>$account->Loan_Balance ){
        //             return  false;
        //         }

        //     }
        //     if(($request->amount+$fee)>$account->Loan_Balance ||$request->amount == $account->Loan_Balance){
        //         return  false;
        //     }
        // }
       return true;
    }

    public function updateStatus($reference){
        $transaction = Transaction::where('Trans_Id',$reference)->first();
        if($transaction){

        }

    }

    public function storePayment($type,$pay_account,$details){
    //    dd($details);
        $payment = new Payment();
        $payment->paymentable()->associate($type);
        $payment->user()->associate($type->user);
        $payment->amount = $type->amount;
        $payment->status = 'Initiated';
        $payment->source = $pay_account; // == 'savings'? 'savings': 'loans';
        $payment->save();
        $data = [
            'data'=>[
                'reference'=> in_array('customIdentifier',$details) ? $details['customIdentifier'] : $details['id'] ,
                'fee'=>0,
                'amount'=>in_array('requestedAmount',$details) ?$details['requestedAmount'] :$payment->amount,
                'full_name'=> in_array('recipientPhone',$details) ?$details['recipientPhone'] :$payment->user->name,
                'id'=> in_array('transactionId',$details) ? $details['transactionId'] : $details['id']
            ]
        ];
        $transaction = $this->storeTransaction('Payment',$payment->id,$data);
        if($transaction){
            $this->accountOperation('debit','payment',$payment->id);
        }
    }

    public function notificationData($transaction){
        // dd($transaction);
        $model = $transaction->transactionable;
        // dd($model);
        switch ($transaction->status) {
            case 'Successfull':
                $status = 'has been successfully processed on your account';
                break;
            
            case 'Failed':
                $status = 'has failed. please try again';
                break;
        }
        switch($transaction->operation){
            case 'Saving':
                $title = 'Saving';
                $message = "Your transaction $transaction->Trans_id for saving $transaction->transactionable->saving_id $status";
                break;
            case 'Withdraw':
                $title = 'Withdraw';
                $message = "Dear Customer, your account has been debited $model->amount at a fee of $model->withdrawFee->fee";
                break;
            case 'Loan Installment':
                switch ($model->repaymentable_type) {
                    case 'App\models\Loan':
                        $loan = $model->repaymentable->ULoan_Id;
                        break;
                    case 'App\models\SomaLoan':
                        $loan = $model->repaymentable->SLN_id;
                        break;
                    case 'App\models\Loan':
                        $loan = $model->repaymentable->BLN_id;
                        break;
                    
                    
                }
                
                $title = 'Loan Installment';
                $message = "Dear Customer, your payment for loan installment of $model->amount for loan $loan $status.";
                break;
            case 'Referal':
                $title = 'Refferal bonus';
                $message = "Dear Customer, your account has been credited $model->amount.thank you for refering more clients to appnomu";
                break;
            case 'Payment':
                
                switch ($model->paymentable_type) {
                    case 'App\Models\Airtime':
                        $payment = 'airtime';
                        $title = 'Airtime payment';
                        break;
                    case 'App\Models\Electricity':
                        $payment = 'electricity';
                        $title = 'Utility payment';
                        break;
                    // case 'App\models\':
                    //     $loan = $model->repaymentable->BLN_id;
                    //     break;
                    
                    
                }
                
                $message = "Dear Customer, $payment transaction of $model->amount $status";
                break;
        }
        return [
            'id'=>$transaction->id,
            'title'=>$title,
            'message'=>$message
        ];
    }

}