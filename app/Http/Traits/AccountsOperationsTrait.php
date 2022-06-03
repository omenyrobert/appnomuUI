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
                            case 'withdraw':
                                $loan = Loan::findOrFail($id);
                                $account = $loan->account;
                                $account->Loan_Balance = $account->Loan_Balance + $loan->principal;
                                $account->Outstanding_Balance = $account->Outstanding_Balance + $loan->principal;
                                break;                        
                    }    
                    return;
                case 'debit':
                    switch ($type) {
                        case 'loan':
                            $withdraw = withdraw::findOrFail($id);
                            $account = $withdraw->user->account;
                            $account->available_balance = $account->available_balance - $details['data']['amount'];
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
    public function storeWithdraw(Request $request,$refference,$details){
        try {
            $user = User::findOrFail(Auth::id());
            $withdraw = new Withdraw();
            $withdraw->user()->associate($user);
            $withdraw->trans_id = $refference;
            $withdraw->accounts_number = $details->data->account_number;
            $withdraw->currency = $details->data->currency;
            $withdraw->amount = $details->data->amount;
            $withdraw->withdraw_from = $request->account;
            $withdraw->status = 'Successful';
            $withdraw->mode = $details->date->amount;
            $withdraw->save();
            $this->storeTransaction('Withdraw',$withdraw->id,$refference);
            $this->accountOperation('debit','saving',$withdraw->id,$details);
            return;
        } catch (\Throwable $th) {
            //throw $th;
        }

    }

    public function storeTransaction($type,$id,$refference,$details=null){
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
        $transaction->operation = $type;
        $transaction->amount = $operation->amount;
        $transaction->Trans_id = $refference;
        $transaction->status = 'Successful';
        if($details){
            $transaction->FLW_Id = $details->data->id;
            $transaction->FLW_txref = $details->data->reference;
            $transaction->mode = $details->data->bank_name;
            $transaction->flw_charge = $details->data->free;
        }
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

}