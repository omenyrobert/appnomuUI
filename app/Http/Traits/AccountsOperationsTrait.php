<?php


namespace App\Http\Traits;

use App\Models\Loan;
use App\Models\Payment;
use App\Models\Repayment;
use App\Models\Savingg;
use App\Models\SavingSubCategory;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Withdraw;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

trait AccountsOperationsTrait{

    public function accountOperation($operation,$type,$id,$details=null){
        //operation = credit or debit
        //type = loan,soma,business,saving,transaction
        try {
            //code...
            switch ($operation) {
                case 'credit':
                    switch ($type) {
                        case 'loan':
                            $loan = Loan::findOrFail($id);
                            $account = $loan->account;
                            $account->Loan_Balance = $account->Loan_Balance + $loan->principal;
                            $account->Outstanding_Balance = $account->Outstanding_Balance + $loan->principal;
                            $account->save();
                            break;
                        // case 'withdraw':
                        //     $loan = Loan::findOrFail($id);
                        //     $account = $loan->account;
                        //     $account->Loan_Balance = $account->Loan_Balance + $loan->principal;
                        //     $account->Outstanding_Balance = $account->Outstanding_Balance + $loan->principal;
                        //     break;                        
                    }    
                    return;
                case 'debit':
                    switch ($type) {
                        case 'loans':
                            $withdraw = withdraw::findOrFail($id);
                            $account = $withdraw->user->account;
                            if($withdraw->user->telephone == $withdraw->account_number){
                                $account->Loan_Balance = $account->Loan_Balance  - $details['data']['amount'];
                                $account->amount_withdrawn = $account->amount_withdrawn  + $details['data']['amount'];
                                $account->save();
                                break;
                            }
                            $account->Loan_Balance = $account->Loan_Balance  - $details['data']['amount'];
                            $account->amount_withdrawn = $account->amount_withdrawn  + $details['data']['amount'] + $details['data']['fee'];
                            $account->save();
                            break;
                        case 'savings':
                            $withdraw = Withdraw::findOrFail($id);
                            $account = $withdraw->user->account;
                            $account->available_balance = $account->available_balance - $details['data']['amount']-$details['data']['fee'];
                            $account->amount_withdrawn = $account->amount_withdrawn  + $details['data']['amount'] + $details['data']['fee'];
                            $account->save();
                            break;                        
                    }                    
                    return;  

            }
        } catch (\Throwable $th) {
            throw $th;
        }

    }

    public function storeSaving(Request $request,$refference){
        try {
            $user = User::findOrFail(Auth::id());
            $category = SavingSubCategory::find((int) $request->category);
            $saving = new Savingg();
            $saving->user()->associate($user);
            $saving->account()->associate($user->account);
            $saving->savingSubCategory()->associate($category);
            $saving->Uuser_id = $user->id;
            $saving->Interest = ((int)$request->amount)*($category->Interest/100);
            $saving->dueDate = Carbon::now()->addDays($category->Save_Period);
            $saving->procesing_fees = 0;
            $saving->amount = (int)$request->amount;
            $saving->saving_id = 'Save-'.rand(11111,99999);
            $saving->save();
            $this->storeTransaction('Saving',$saving->id,$refference);
            // return redirect()->back()->with('success','Transaction completed successfully');

            return;// redirect()->back()->with('success','Transaction completed successfully');

        } catch (\Throwable $th) {
            //throw $th;
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
            $this->storeTransaction('Withdraw',$withdraw->id,$details);
            // $this->accountOperation('debit','savings',$withdraw->id,$details);
            return;
        } catch (\Throwable $th) {
            throw $th;
        }

    }


    public function storeTransaction($type,$id,$response=null){
        $transaction = new Transaction();
        switch ($type) {
            case 'Saving':
                $operation = Savingg::findOrFail($id);
                $transaction->savingg()->associate($operation);
                break;                
            case 'Loan Installment':
                $operation = Repayment::findOrFail($id);
                $transaction->repayment()->associate($operation);
                break;
            case 'Withdraw':
                $operation = Withdraw::findOrFail($id);
                $transaction->withdraw()->associate($operation);
                break;
            case 'Payment':
                $operation = Payment::findOrFail($id);
                $transaction->payment()->associate($operation);
                break;
        }

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
        return;


    }

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
    //     'Interest'=>(request()->amount)*($cate[0]['Interest']/100),
    //     'duedate'=>time(),
    //     'savingdate'=>time(),
    //     'processing_fees'=>$processing,
    //     'created_at'=> Carbon::now()
    // ]);

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