<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Loan;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AccountController extends Controller
{
    public function index(){
        try {
            $user=User::find(Auth::id());
            if($user && $user->role == 'admin'){
                $accounts = Account::paginate(10);
                return view('accounts.index',['user'=>$user,'accounts'=>$accounts])->with('page','Accounts | All');
            }
            return redirect()->back()->withErrors('error','Unauthorized');

            
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function suspendUser(Request $request){
        try {
            $user = User::find(Auth::id());
            if($user && $user->role == 'admin'){
                $culprit = Account::findOrFail($request->id);
                $culprit->status = 'suspended';
                $culprit->suspended_at = Carbon::now();
                switch($request->duration){
                    case 'days':
                        $culprit->unsuspended_at = Carbon::now()->addDays($request->period);
                        break;
                    case 'weeks':
                        $culprit->unsuspended_at = Carbon::now()->addWeeks($request->period);
                        break;
                    case 'months':
                        $culprit->unsuspended_at = Carbon::now()->addMonths($request->period);
                        break;
                    }
                $culprit->suspend_reason = $request->reason;
                $culprit->save();
                return redirect()->back()->with('success','User suspended successfully');
            }
            return redirect()->back()->withErrors('Error','you do not have permission to perform this action');

        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function unSuspendUser(Request $request,$id){
        try {
            $user = User::find(Auth::id());
            if($user && $user->role == 'admin'){
                $culprit = Account::findOrFail($request->id);
                $culprit->status = 'inactive';
                $culprit->unsuspended_at = Carbon::now();
                $culprit->unsuspend_reason = $request->reason;
                $culprit->save();
                return redirect()->back()->with('success','User unsuspended successfully');
            }
            return redirect()->back()->withErrors('Error','you do not have permission to perform this action');

        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function blacklistUser(Request $request){
        try {
            $user = User::find(Auth::id());
            if($user && $user->role == 'admin'){
                $culprit = Account::findOrFail($request->id);
                $culprit->status = 'blacklisted';
                $culprit->blacklisted_at = Carbon::now();
                $culprit->blacklist_reason = $request->reason;
                $culprit->save();
                return redirect()->back()->with('success','User Account blacklisted successfully');
            }
            return redirect()->back()->withErrors('Error','you do not have permission to perform this action');

        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function unBlacklistUser(Request $request){
        try {
            $user = User::find(Auth::id());
            if($user && $user->role == 'admin'){
                $culprit = Account::findOrFail($request->id);
                $culprit->status = 'inactive';
                $culprit->unblacklisted_at = Carbon::now();
                $culprit->unblacklist_reason = $request->reason;
                $culprit->save();
                return redirect()->back()->with('success','User unblacklisted successfully');
            }
            return redirect()->back()->withErrors('Error','you do not have permission to perform this action');

        } catch (\Throwable $th) {
            //throw $th;
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

    public function changeLoanLimit(Request $request){
        try {
            $user = User::find(Auth::id());
            if($user && $user->role == 'admin'){
                $account = Account::findOrFail($request->id);
                $account->Loan_Limit = (int)$request->new_limit;
                $account->save();
                return redirect()->back()->with('success','loan limit reset successfully');
            }
            return redirect()->route('login')->withErrors('error','Unauthorised');
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    
}
