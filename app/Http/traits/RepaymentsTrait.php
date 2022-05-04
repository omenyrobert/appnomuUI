<?php
namespace App\Http\Traits;

use App\Models\BusinessLoan;
use App\Models\Loan;
use App\Models\Repayment;
use App\Models\SomaLoan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

trait RepaymentsTrait{

    public function createInstallments($id,$type){
        try {
            $loan = $this->findLoan($id,$type);
            if ($loan->status == 'approved') {
                $installment_amount = $loan->payment_amount / $loan->installments;
                $installment_period = $loan->payment_period /$loan->installments;
                for ($i=1; $i <= $loan->installments ; $i++) { 
                    $installment = new Repayment();
                    $installment->repaymentable->associate($loan);
                    $installment->amount = $installment_amount;
                    $last_date = $loan->latestRepayment->due_date;
                    $installment->due_date = $last_date ? $last_date->addDays($installment_period) : $loan->approved_at->addDays($installment_period);
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
                    return $loan;
                case 'business':
                    $loan = BusinessLoan::find($id);
                    return $loan;
                case 'soma':
                    $loan = SomaLoan::find($id);
                    return $loan;
                
            }
        } catch (\Throwable $th) {
            throw $th;
        }
        
    }
    public function changeStatus(Request $request,$id,$type){
        try {
            $loan = $this->findLoan($id,$type);
            if (Auth::check()) {
                $user = Auth::user();
                $loan = BusinessLoan::findOrFail($id);  
                $status = $request->status;             
                switch ($status) {
                    case 'approve':
                        if($user->role == 'admin'){
                            $loan->status = 'approved';
                            $loan->approved_by = Auth::id();
                            $loan->approved_at = Carbon::now('GMT+3');
                            $loan->due_date = $loan->approved_at->addDays($loan->payment_period);
                            $loan->save();
                            $this->createRepayments($loan->id,'business');
                            $last_message_string = ' and credited to your appnomu account';
                            break;
                        }
                    case 'decline':
                        if($user->role == 'admin'){
                            $loan->status = 'declined';
                            $loan->declined_by = Auth::id();
                            $loan->declined_at = Carbon::now('GMT+3');
                            $loan->decline_reason = $request->reason;
                            $last_message_string = $loan->decline_reason;
                            break;
                        }
                    case 'hold':
                        if($user->role == 'admin'){
                            $loan->status = 'on hold';
                            $loan->held_by = Auth::id();
                            $loan->held_at = Carbon::now('GMT+3');
                            $loan->hold_reason = $request->reason;
                            $last_message_string = $loan->hold_reason;
                            break;
                        }
                    case 'cancel':
                        $loan->status = 'cancelled';
                        $loan->cancelled_by = Auth::id();
                        $loan->cancel_reason = $request->reason;
                        $loan->cancelled_at = Carbon::now('GMT+3');
                        $last_message_string = $loan->cancel_reason;
                        break;
                    case 're-submit':
                        $loan->status = 'pending';
                        $last_message_string = 'please wait while our personnel assese your application';
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