<?php

namespace App\Http\Controllers;

use App\Models\Loan;
use App\Models\LoanCategory;
use App\Models\Repayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Response;
use App\Models\User;

class LoanController extends Controller
{
    


    public function create(){

    }
    public function requestLoan(Request $request){
        try {
            $validated = $request->validate([
                'category'=>'required'
            ]);
    
            $user = User::findOrFail(Auth::id());
            if ($user->NIN ==null || $user->card_no == null || $user->address == null) {
                return redirect()->route('profile')->withErrors(['Errors'=>'Your Profile Is not Set Up fully ']);
            }
    
            $outstanding_loan_count = $user->loans()->where('status',6)->orWhere('status',4)->count();
            $loan_cat = AuthenticationController::getLoanByCatID($request['category']);
    
            if ($outstanding_loan_count  > 0) {
                return redirect()->back()->withErrors(['Errors'=>'You Have an Outstanding Loan ']);
            }
            
            $ally_count = $user->alliances()->count();
            if ($ally_count < 2) {
                # code...
                return redirect()->back()->withErrors(['Errors'=>'You Dont Have enough Allianses to qualify For a loan,Add alliance and try again later']);
    
            }
            $uloan = 'LN-'.rand(11111,99999);
            $pay = 0;
            $status = 5;
            if($user->loan_limit < $loan_cat[0]['loan_amount']){
                return redirect()->back()->withErrors(['Errors'=>'Your loan limit is '.$user->loan_limit .'you cannot borrow above your loan limit']);
            }
    
            $loan = new Loan();
            $loan->user()->associate($user);
            $loan->ULoan_Id = $uloan;
            $loan->loan_amount = $loan_cat[0]['loan_amount'];
            $loan->amount_paid = $pay;
            $loan->status = $status;
            $loan->dueDate = time();
            $loan->save();
            
            if ($loan) {
                # code...
                return redirect()->back()->with('Success','Loan Request Sent');
            }else {
                # code...
                return redirect()->back()->withErrors(['Errors'=>'Loan Request Not Sent']);
            }
        } catch (\Throwable $th) {
            throw $th;
        }
        
    }


   //get all loans
   public function index(){
        try {
            $user = User::find(Auth::id());
            if($user){
                $categories = LoanCategory::all();
                $repayments = $user->role == 'admin' ? Repayment::where('repaymentable_type','App\Models\Loan')->where('status','pending')->latest()->limit(15)->get() :
                $user->repayments()->where('repaymentable_type','App\Models\Loan')->where('status','pending')->latest()->get();
                $loans = $user->role == 'admin' ? Loan::latest()->get() :
                    $user->loans()->latest()->get();
                return view('loans.index',['repayments'=> $repayments,'categories'=>$categories,'user'=>$user,'loans'=>$loans])->with('page','Loans | All Loans');
            }
            return redirect()->route('login');
        } catch (\Throwable $th) {
            throw $th;
        }
   }

   //get all pending loans
   public function pending(){
        try {
            $user = User::find(Auth::id());
            if($user){
                $loans = $user->role == 'admin' ? Loan::where('status','pending')->latest()->get() :
                    $user->loans()->where('status','pending')->latest()->get();
                return view('loans.index',['user'=>$user,'loans'=>$loans])->with('page','Soma Loans | Pending');
            }
            return redirect()->route('login');
        } catch (\Throwable $th) {
            throw $th;
        }
   }

   //get all ongoing loans
   public function approved(){
    try {
        $user = User::find(Auth::id());
        if($user){
            $loans = $user->role == 'admin' ? Loan::where('status','approved')->latest()->get() :
                $user->loans()->where('status','approved')->latest()->get();
            return view('loans.index',['user'=>$user,'loans'=>$loans])->with('page','Loans | Approved');
        }
        return redirect()->route('login');
    } catch (\Throwable $th) {
        throw $th;
    }
   }

   //get all overdue loans
   public function late(){
    try {
        $user = User::find(Auth::id());
        if($user){
            $loans = $user->role == 'admin' ? Loan::where('status','late')->latest()->get() :
                $user->loans()->where('status','late')->latest()->get();
            return view('loans.index',['user'=>$user,'loans'=>$loans])->with('page','Loans | Over Due');
        }
        return redirect()->route('login');
    } catch (\Throwable $th) {
        throw $th;
    }
   }

   //declined loans
   public function declined(){
    try {
        $user = User::find(Auth::id());
        if($user){
            $loans = $user->role == 'admin' ? Loan::where('status','declined')->latest()->get() :
                $user->loans()->where('status','declined')->latest()->get();
            return view('loans.index',['user'=>$user,'loans'=>$loans])->with('page','Loans | Denied');
        }
        return redirect()->route('login');
    } catch (\Throwable $th) {
        throw $th;
    }
   }

   //loans on hold
   public function held(){
    try {
        $user = User::find(Auth::id());
        if($user){
            $loans = $user->role == 'admin' ? Loan::where('status','held')->latest()->get() :
                $user->loans()->where('status','held')->latest()->get();
            return view('loans.index',['user'=>$user,'loans'=>$loans])->with('page','Loans | On Hold');
        }
        return redirect()->route('login');
    } catch (\Throwable $th) {
        throw $th;
    }
   }

   //get a users pending loans
   public function userPendingLoans($id){
       try {
           
        $user = User::find(Auth::id());
            if($user->role == 'admin' || $user->id = $id){
                $loans = $user->loans()->orderBy('created_at','DESC')->where('status','05')->get();
                return view('loans.user.index',['loans'=>$loans])->with('page','All Loans');
            }
        
       } catch (\Throwable $th) {
           throw $th;
       }
   }

   public function userIndex($id){
    try {
            $user = User::find(Auth::id());
                if($user->role == 'admin' || $user->id = $id){
                    $categories = LoanCategory::all();
                    $loans = $user->loans()->orderBy('created_at','desc')->paginate(10);
                    return view('loans.user.index',['loans'=>$loans,'user'=>$user,'categories'=>$categories])->with('page','All Loans');
                }            
           } catch (\Throwable $th) {
               throw $th;
           }
       }


}
