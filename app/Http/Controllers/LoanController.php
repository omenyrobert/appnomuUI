<?php

namespace App\Http\Controllers;

use App\Http\Traits\LoanTrait;
use App\Models\Loan;
use App\Models\LoanCategory;
use App\Models\Repayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Response;
use App\Models\User;
use App\Http\Traits\RepaymentsTrait;
use App\Http\Traits\SMSTrait;
use App\Models\Country;

class LoanController extends Controller
{
    
    use RepaymentsTrait,SMSTrait,LoanTrait;

    public function create(){

    }
    public function requestLoan(Request $request){
        try {
            $validated = $request->validate([
                'category'=>'required'
            ]);
    
            $user = User::findOrFail(Auth::id());
            $category = LoanCategory::find($request->category);
            $this->checkLoanLegibilityStatus($category,$user);
            $uloan = 'LN-'.rand(11111,99999);
            $pay = 0;
            $status = 5;
    
            $loan = new Loan();
            $loan->user()->associate($user);
            $loan->loanCategory()->associate($category);
            $loan->Uuser_id = $user->user_id;
            $loan->ULoan_Id = $uloan;
            $loan->principal = $category->loan_amount;
            $loan->amount_paid = 0;
            $loan->status = $status;
            // $loan->due_date = tim;
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
                $repayments = $user->role == 'admin' ? Repayment::where('repaymentable_type','App\Models\Loan')->where('status','Pending')->latest()->paginate(10) :
                $user->repayments()->where('repaymentable_type','App\Models\Loan')->where('status','Pending')->latest()->paginate(10);
                $loans = $user->role == 'admin' ? Loan::latest()->paginate(10) :
                    $user->loans()->latest()->paginate(10);
                $countries = Country::all();
                return view('loans.index',['countries'=>$countries,'repayments'=> $repayments,'categories'=>$categories,'user'=>$user,'loans'=>$loans])->with('page','Loans | All Loans');
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
                $categories = LoanCategory::all();
                $loans = $user->role == 'admin' ? Loan::where('status','Requested')->latest()->get() :
                    $user->loans()->where('status','Requested')->latest()->get();
                $repayments = $user->role == 'admin' ? Repayment::where('repaymentable_type','App\Models\Loan')->where('status','pending')->latest()->paginate(10) :
                    $user->repayments()->where('repaymentable_type','App\Models\Loan')->where('status','pending')->latest()->paginate(10);
                    return view('loans.index',['categories'=>$categories,'user'=>$user,'repayments'=>$repayments,'loans'=>$loans])->with('page','Soma Loans | Pending');
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
            $loans = $user->role == 'admin' ? Loan::where('status','Approved')->latest()->get() :
                $user->loans()->where('status','Approved')->latest()->get();
                $repayments = $user->role == 'admin' ? Repayment::where('repaymentable_type','App\Models\Loan')->where('status','pending')->latest()->paginate(10) :
                $user->repayments()->where('repaymentable_type','App\Models\Loan')->where('status','pending')->latest()->paginate(10);
            return view('loans.index',['user'=>$user,'repayments'=>$repayments,'loans'=>$loans])->with('page','Loans | Approved');
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
            $loans = $user->role == 'admin' ? Loan::where('status','Over Due')->latest()->get() :
                $user->loans()->where('status','Over Due')->latest()->get();
            $repayments = $user->role == 'admin' ? Repayment::where('repaymentable_type','App\Models\Loan')->where('status','pending')->latest()->paginate(10) :
                $user->repayments()->where('repaymentable_type','App\Models\Loan')->where('status','pending')->latest()->paginate(10);
            return view('loans.index',['user'=>$user,'repayments'=>$repayments,'loans'=>$loans])->with('page','Loans | Over Due');
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
            $loans = $user->role == 'admin' ? Loan::where('status','Denied')->latest()->get() :
                $user->loans()->where('status','Denied')->latest()->get();
            $repayments = $user->role == 'admin' ? Repayment::where('repaymentable_type','App\Models\Loan')->where('status','pending')->latest()->paginate(10) :
                $user->repayments()->where('repaymentable_type','App\Models\Loan')->where('status','pending')->latest()->paginate(10);
            return view('loans.index',['user'=>$user,'repayments'=>$repayments,'loans'=>$loans])->with('page','Loans | Denied');
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
            $loans = $user->role == 'admin' ? Loan::where('status','On Hold')->latest()->get() :
                $user->loans()->where('status','On Hold')->latest()->get();
            $repayments = $user->role == 'admin' ? Repayment::where('repaymentable_type','App\Models\Loan')->where('status','pending')->latest()->paginate(10) :
                $user->repayments()->where('repaymentable_type','App\Models\Loan')->where('status','pending')->latest()->paginate(10);
            return view('loans.index',['user'=>$user,'repayments'=>$repayments,'loans'=>$loans])->with('page','Loans | On Hold');
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

    //show a loan
    public function show($id){
        try {
            $loan = Loan::findOrFail($id);
            $user = User::find(Auth::id());
            // dd($loan->user == $user);
            if($user){
                if($loan->user == $user || $user->role == 'admin'){
                    $repayments = $loan->repayments;
                    if(count($repayments)== 0){
                        $this->createInstallments($loan->id,'loan');
                        $repayments = $loan->repayments;
                    }
                    $countries = Country::all();
                    // $identifications = $user->identifications;
                    return view('loans.show',['countries'=>$countries,'loan'=>$loan,'user'=>$user,'repayments'=>$repayments])->with('page','View | '.$loan->ULoan_Id);
                }
            }
            return redirect()->route('login');
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    //approve,decline or change the status of a loan
    public function loanStatusChange($action,$id){
        try {
            if(Auth::check()){
                $loan_n_string = $this->changeStatus($action,$id,'loan');
                if($loan_n_string){
                    $loan = $loan_n_string['loan'];
                    $user = $loan->user; 
                    $last_message_string = $loan_n_string['last_message_string'];
                    $message = 'Dear '. $user->name .', Your loan '.$loan->ULoan_Id .' of '.$loan->principal .' has been '.$loan->status .$last_message_string;                    
                    // dd($message);
        //   todo          // $sms_status = $this->sendSMS($message,$user->telephone);
                    // for($i=5;$sms_status != 'success';$i++){
                    //     $sms_status = $this->sendSMS($message,$user->telephone);
                    // }
                }

                return redirect()->back();

            }
            return redirect()->route('login')->withErrors('Errors','you are unathorised to do this operation!');
        } catch (\Throwable $th) {
            throw $th;
            // return redirect()->back()->withErrors($th->getMessage());
        }
    }


}
