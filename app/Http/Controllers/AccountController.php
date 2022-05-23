<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Loan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AccountController extends Controller
{
    //change loan limit
    public function updateLoanLimit(Request $request,$id){
        try {
            $logged = User::find(Auth::id());
            if($logged->role == 'admin'){
                $user = User::find($id);

                if($user){
                    $account = $user->account;
                    $account->loan_limit = (int)$request->loan_limit;
                    $account->save();
                }
                return response('user account does not exist',401);
            }
            return response('unathorized',401);
        } catch (\Throwable $th) {
            throw $th;
        }
        
    }

    //show an account
    public function show($id){
        $account = Account::findOrFail($id);
        $user = User::find(Auth::id());
        if($user ===$account->user || $user->role == 'admin'){
            $withdraws = $account->withdraws;
            $savings = $account->savings;
            $loans = $account->loans; 
            $soma_loans = $account->somaLoans;
            $business_loans = $account->businessLoans;
            return view('account.show',['account'=>$account,
            'withdraws'=>$withdraws,'savings'=>$savings,
            'loans'=>$loans,'soma_loans'=>$soma_loans,
            'business_loans'=>$business_loans])->with('page','Account | Show');
        }

    }

    
    // create a new account
    public function store($id){
        try {
            $user = User::findOrfail($id);
            $account = new Account();
            $account->user()->associate($user);
            $account->Loan_Limit = 20000;
            $account->Outstanding_Balance = 0;
            $account->Loan_Balance = 0;
            $account->available_balance = 0;
            $account->Ledger_Balance=0;
            $account->Total_Saved=0;
            $account->Amount_Withdrawn=0;
            $account->save();
            // if($account) return;
            return;
            
            
        } catch (\Throwable $th) {
            throw $th;
        }

    }

    public function changeLoanLimit(Request $request,$id){
        try {
            $user = User::find(Auth::id());
            if($user && $user->role == 'admin'){
                $account = Account::findOrFail($id);
                $account->Loan_Limit = (int)$request->loan_limit;
                $account->save();
                return redirect()->back()->with('success','loan limit reset successfully');
            }
            return redirect()->route('login')->withErrors('error','Unauthorised');
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    
}
