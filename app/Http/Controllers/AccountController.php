<?php

namespace App\Http\Controllers;

use App\Http\Traits\SMSTrait;
use App\Mail\AccountUpdateMail;
use App\Models\Account;
use App\Models\Loan;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class AccountController extends Controller
{
    use SMSTrait;
    public function index(){
        try {
            $user=User::find(Auth::id());
            if($user && $user->role == 'admin'){
                $accounts = Account::paginate(10);
                return view('accounts.index',['user'=>$user,'accounts'=>$accounts])->with('page','Accounts | All');
            }
            return redirect()->back()->withErrors('error','Unauthorized');

            
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function suspendUser(Request $request){
        try {
            $user = User::find(Auth::id());
            if($user && $user->role == 'admin'){
                $account = Account::findOrFail($request->id);
                $account->status = 'suspended';
                $account->suspended_at = Carbon::now();
                switch($request->duration){
                    case 'days':
                        $account->unsuspended_at = Carbon::now()->addDays($request->period);
                        break;
                    case 'weeks':
                        $account->unsuspended_at = Carbon::now()->addWeeks($request->period);
                        break;
                    case 'months':
                        $account->unsuspended_at = Carbon::now()->addMonths($request->period);
                        break;
                    }
                $account->suspend_reason = $request->reason;
                $account->save();
                $suspend_date = Carbon::parse($account->unsuspended_at)->toFormattedDateString();
                $message = "Dear $account->user->name your account at Appnomu has been suspended for $request->period $request->duration until $suspend_date due to $account->suspend_reason";
                $this->sendSMS($message,$account->user->phone,'Account Suspension',$account->user);
                return redirect()->back()->with('success','User suspended successfully');
            }
            return redirect()->back()->withErrors('Error','you do not have permission to perform this action');

        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function unSuspendUser(Request $request,$id){
        try {
            $user = User::find(Auth::id());
            if($user && $user->role == 'admin'){
                $account = Account::findOrFail($request->id);
                $account->status = 'inactive';
                $account->unsuspended_at = Carbon::now();
                $account->unsuspend_reason = $request->reason;
                $account->save();
                $message = "Dear $account->user->name suspension of your account at Appnomu has been lifted. You can now transact normally";
                $this->sendSMS($message,$account->user->phone,'Account Unsuspension',$account->user);
                return redirect()->back()->with('success','User unsuspended successfully');
            }
            return redirect()->back()->withErrors('Error','you do not have permission to perform this action');

        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function blacklistUser(Request $request){
        try {
            $user = User::find(Auth::id());
            if($user && $user->role == 'admin'){
                $account = Account::findOrFail($request->id);
                $account->status = 'blacklisted';
                $account->blacklisted_at = Carbon::now();
                $account->blacklist_reason = $request->reason;
                $account->save();
                $message = "Dear $account->user->name your account at Appnomu has been blacklisted due to $account->blacklist_reason .Contact admin for any petitions";
                $this->sendSMS($message,$account->user->phone,'Account Blacklisting',$account->user);
                Mail::to($account->user)->send(new AccountUpdateMail($message));
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
                $account = Account::findOrFail($request->id);
                $account->status = 'inactive';
                $account->unblacklisted_at = Carbon::now();
                $account->unblacklist_reason = $request->reason;
                $account->save();
                $message = "Dear $account->user->name your account at Appnomu has been unblacklisted. You can now resume transactions normally";
                $this->sendSMS($message,$account->user->phone,'Account Unblacklisting',$account->user);
                Mail::to($account->user)->send(new AccountUpdateMail($message));
                return redirect()->back()->with('success','User unblacklisted successfully');
            }
            return redirect()->back()->withErrors('Error','you do not have permission to perform this action');

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

    public function changeLoanLimit(Request $request){
        try {
            $user = User::find(Auth::id());
            if($user && $user->role == 'admin'){
                $account = Account::findOrFail($request->id);
                $account->Loan_Limit = (int)$request->new_limit;
                $account->save();
                $country = $account->user->country;
                $message = "Dear $account->user->name your account at Appnomu has received a new loan limit of $account->Loan_Limit $country->currency_code. You can now borrow loan to a maximum tune of $account->Loan_Limit $country->currency_code";
                $this->sendSMS($message,$account->user->phone,'New Loan Limit',$account->user);
                Mail::to($account->user)->send(new AccountUpdateMail($message));
                return redirect()->back()->with('success','loan limit reset successfully');
            }
            return redirect()->route('login')->withErrors('error','Unauthorised');
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    
}
