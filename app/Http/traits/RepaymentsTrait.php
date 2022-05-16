<?php
namespace App\Http\Traits;

use App\Models\BusinessLoan;
use App\Models\Loan;
use App\Models\Repayment;
use App\Models\SomaLoan;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

trait RepaymentsTrait{

    public function createInstallments($id,$type){
        try {
            $details = $this->findLoan($id,$type);
            $loan = $details['loan'];
            $user = User::find(Auth::id());
            if ($loan->status == 'Approved') {
                $installment_amount = $loan->payment_amount / $loan->loanCategory->installments;
                $installment_period = $loan->payment_period /$loan->loanCategory->installments;
                for ($i=1; $i <= $loan->loanCategory->installments ; $i++) { 
                    $installment = new Repayment();
                    $installment->repaymentable()->associate($loan);
                    $installment->user()->associate($user);
                    $installment->amount = $installment_amount;
                    $installment->loan_id = $details['loan_id'];
                    $last_installment = $loan->latestRepayment;
                    $last_date = $last_installment ? $last_installment->due_date : null;
                    $installment->due_date = $last_date ? Carbon::createFromTimestamp($last_date)->addDays($installment_period) : Carbon::createFromTimestamp($loan->approved_at)->addDays($installment_period);
                    $installment->save();        
                }
            }
            return;
        } catch (\Throwable $th) {
            throw $th;
        }
    }
    public function findLoan($id,$type){
        try {
            switch ($type) {
                case 'loan':
                    $loan = Loan::find($id);
                    $loan_id =$loan->ULoan_Id;
                    return ['loan'=>$loan,'loan_id'=>$loan_id];
                case 'business':
                    $loan = BusinessLoan::find($id);
                    $loan_id =$loan->BLN_id;
                    return ['loan'=>$loan,'loan_id'=>$loan_id];
                    return $loan;
                case 'soma':
                    $loan = SomaLoan::find($id);
                    $loan_id =$loan->SLN_id;
                    return ['loan'=>$loan,'loan_id'=>$loan_id];
                    return $loan;
                
            }
        } catch (\Throwable $th) {
            throw $th;
        }
        
    }
    public function changeStatus($status,$id,$type){
        try {
            $loan_n_id = $this->findLoan($id,$type);
            if (Auth::check()) {
                $user = Auth::user();
                $loan = $loan_n_id['loan']; 
                switch ($status) {
                    case 'Approve':
                        if($user->role == 'admin'){
                            $category = $loan->loanCategory;
                            $loan->status = 'Approved';
                            $loan->approved_by = Auth::id();
                            $loan->approved_at = Carbon::now();
                            $loan->payment_amount = $loan->principal + $loan->principal*$category->interest_rate/100 + $category->processing_fees;
                            $loan->payment_period = $category->loan_period;
                            $loan->due_date = $loan->approved_at->addDays($loan->payment_period );
                            $loan->save();
                            // dd($loan->approved_at);
                            $this->createInstallments($loan->id,$type);
                            $last_message_string = ' and credited to your appnomu account';
                            break;
                        }
                    case 'Deny':
                        if($user->role == 'admin'){
                            $loan->status = 'Denied';
                            $loan->declined_by = Auth::id();
                            $loan->declined_at = Carbon::now();
                            // $loan->decline_reason = $request->reason;
                            $last_message_string = $loan->decline_reason;
                            break;
                        }
                    case 'Hold':
                        if($user->role == 'admin'){
                            $loan->status = 'On Hold';
                            $loan->held_by = Auth::id();
                            $loan->held_at = Carbon::now();
                            // $loan->hold_reason = $request->reason;
                            $last_message_string = $loan->hold_reason;
                            break;
                        }
                    case 'Cancel':
                        $loan->status = 'Cancelled';
                        $loan->cancelled_by = Auth::id();
                        // $loan->cancel_reason = $request->reason;
                        $loan->cancelled_at = Carbon::now();
                        $last_message_string = $loan->cancel_reason;
                        break;
                    case 'Re-submit':
                        $loan->status = 'Requested';
                        $last_message_string = 'please wait while our personnel assess your application';
                        break;
                }
                $loan->save();
                if($loan) return ['loan'=>$loan,'last_message_string'=>$last_message_string];
                return false;
                
            }
        } catch (\Throwable $th) {
            throw $th;
        }
    }
    
}