<?php

namespace App\Http\Controllers;

use App\Http\Traits\SMSTrait;
use App\Http\Traits\RepaymentsTrait;
use App\Models\Business;
use App\Models\BusinessLoan;
use App\Models\LoanCategory;
use App\Models\Repayment;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class BusinessLoanController extends Controller
{
    use SMSTrait,RepaymentsTrait;
    //show all business loans
    public function index(){
        try {
            $user = User::findOrFail(Auth::id());
            if($user->role == 'admin'){
                $loans = BusinessLoan::latest()->get();
            }else{
                $loans = $user->businessLoans()->latest()->get();
            }
            return view('business_loans.index')->with('page','Business | Loans');

        } catch (\Throwable $th) {
            throw $th;
        }
    }

    //create
    public function create(){
        $user = User::findOrFail(Auth::id());
        $businesses = $user->businesses()->latest()->get();
        return view('business_loans.create',['businesses'=>$businesses])->with('page','Business Loan | Create');
    }

    //store a business loan
    public function store(Request $request){
        try {
            $user = User::findOrFail(Auth::id());
            if ($user->created_at->addDays(90) < Carbon::now()) {
                $business = Business::find($request->business);
                $loan_category = LoanCategory::find($request->loan_category);
                $loan = new BusinessLoan();
                $loan->user()->associate($user);
                $loan->business()->associate($business);
                $loan->loanCategory()->associate($loan_category);
                $loan->account()->associate($user->account);
                $loan->principal = $loan_category->loan_amount;
                $loan->interest_rate = $loan_category->interest_rate;
                $loan-> installments = $loan_category->installments;
                $loan->payment_period = $loan_category->loan_period;
                $loan->BLN_id = 'BLN-'.rand(11111,999999); 
                $loan->status = 'pending';
                $loan->payment_amount = $loan->principal + ($loan->principle * $loan->interest_rate/100);
                $loan->save();
                return redirect()->route('business.loan.index');
            }else{
                return redirect()->back()->with('warning','You are only eligible after you have been with us for atleast 90 days');
            }
        } catch (\Throwable $th) {
            throw $th;
        }

       

    }

     //show a particular business loan
    public function show($id){
         try {
             if(Auth::check()){
                 $loan = BusinessLoan::findOrFail($id);
                 $repayments = $loan->repayments;
                 $business = $loan->business;
                 $user = $loan->user;
                 return view('business_loan.show',[
                     'loan'=>$loan,
                     'repayments'=>$repayments,
                     'business'=>$business,
                     'user' =>$user
                 ])->with('page','Business Loan | '.$loan->BLN_id);
             }
         } catch (\Throwable $th) {
             throw $th;
         }
            
    }

    public function loanStatusChange(Request $request,$id){
        try {
            if(Auth::check()){
                $loan_n_string = $this->changeStatus($request,$id,'business');
                if($loan_n_string){
                    $loan = $loan_n_string['loan'];
                    $user = $loan->user; 
                    $last_message_string = $loan_n_string['last_message_string'];
                    $message = 'Dear '. $user->name .', Your '.$loan->BLN_id .' of '.$loan->principal .' has been '.$loan->status .$last_message_string;
                    $sms_status = $this->sendSMS($message,$loan->user->telephone);
                    for(;$sms_status != 'success';){
                        $sms_status = $this->sendSMS($message,$user->telephone);
                    }
                    return redirect()->route('business.loan.index');
                }               
            }
            return redirect()->route('login')->withErrors('Errors','you are unathorised to do this operation!');
        } catch (\Throwable $th) {
            throw $th;
            // return redirect()->back()->withErrors($th->getMessage());
        }
    }

    
}
