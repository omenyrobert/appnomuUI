<?php


namespace App\Http\Traits;

use App\Models\Loan;
use App\Models\Payment;
use App\Models\Repayment;
use App\Models\Saving;
use App\Models\SavingSubCategory;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Withdraw;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

trait AccountsOperationsTrait{

    public function accountOperation($operation,$type,$id){
        //operation = credit or debit
        //type = loan,soma,business,saving,transaction
        try {
            switch ($operation) {
                case 'credit':
                    switch ($type) {
                        case 'loans':
                            $loan = Loan::findOrFail($id);
                            $account = $loan->account;
                            $account->Loan_Balance +=$loan->principal;
                            $account->Outstanding_Balance += $loan->principal;
                            $account->save();

                            break;
                        case 'savings':
                            $saving = Saving::findOrFail($id);
                            $account = $saving->account;
                            $account->Ledger_Balance += $saving->amount;
                            $account->Total_Saved += $saving->amount;
                            $account->save();
                            break;   
                        case 'repayment':
                            $repayment = Repayment::findOrFail($id);
                            $account = $repayment->user->account;
                            $account->Outstanding_Balance -= $repayment->amount_paid;
                            $account->Total_Paid += $repayment->amount_paid;
                            $account->save();
                            break;                             
                    }    
                    break;
                case 'debit':
                    switch ($type) {
                        case 'loans':
                            $withdraw = withdraw::findOrFail($id);
                            $account = $withdraw->user->account;
                            if($withdraw->user->telephone == $withdraw->account_number){
                                $account->Loan_Balance -= $withdraw->amount;
                                $account->amount_withdrawn += $withdraw->amount;
                                $account->save();
                                break;
                            }
                            
                            $account->Loan_Balance -=$withdraw->amount-$withdraw->withdrawFee->fee;
                            $account->amount_withdrawn += $withdraw->amount;
                            $account->save();
                            break;
                        case 'savings':
                            $withdraw = Withdraw::findOrFail($id);
                            $account = $withdraw->user->account;
                            $account->available_balance -= $withdraw->amount-$withdraw->withdrawFee->fee;
                            $account->amount_withdrawn += $withdraw->amount ;
                            $account->save();
                            break;                        
                    }                    
                    break;  

            }
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
                $transaction->name = $response['data']['full_name'];
            }
            // $transaction->mode = $response['data']['full_name'];
            $transaction->operation =  'Withdraw';
            $transaction->status = 'Pending';
            $transaction->save();
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
    public function checkWithdrawCapability(Request $request,User $user,$fee){
        $validated = $request->validate([
            'source'=>'required',
            'amount'=>'required'
        ]);
        $account = $user->account;
        if (!$user->sms_verified_at) {
            return redirect()->route('profile')->withErrors(['Errors'=>'No Verified Phone Number Has Been Found Please Verify Your Phone Number Before You Can Withdraw Your Money']);
        }

        if ($request->amount<1000) {
            # code...
            return redirect()->back()->withErrors(['Errors'=>'Your Minimum Value of Withdraw Should be UGX.1000']);
        }

        if($request->source=='savings'){
            if(($request->amount+$fee) > $account->available_balance){
                return  false;
            
            }
        }elseif ($request->source=='loans'){
            if($request->account_number == $user->telephone){
                if(($request->amount)>$account->Loan_Balance ){
                    return  false;
                }

            }
            if(($request->amount+$fee)>$account->Loan_Balance ||$request->amount == $account->Loan_Balance){
                return  false;
            }
        }
       return true;
    }

    public function updateStatus($reference){
        $transaction = Transaction::where('Trans_Id',$reference)->first();
        if($transaction){

        }

    }

}