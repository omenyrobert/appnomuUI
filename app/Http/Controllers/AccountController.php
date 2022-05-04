<?php

namespace App\Http\Controllers;

use App\Models\Account;
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

    
}
