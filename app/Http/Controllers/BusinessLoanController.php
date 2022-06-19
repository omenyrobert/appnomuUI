<?php

namespace App\Http\Controllers;

use App\Http\Traits\LoanTrait;
use App\Http\Traits\SMSTrait;
use App\Http\Traits\RepaymentsTrait;
use App\Models\Business;
use App\Models\BusinessCredential;
use App\Models\BusinessLoan;
use App\Models\Country;
use App\Models\District;
use App\Models\LoanCategory;
use App\Models\Repayment;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class BusinessLoanController extends Controller
{
    use SMSTrait,RepaymentsTrait,LoanTrait;
    //show all business loans
    public function index(){
        try {
            $user = User::findOrFail(Auth::id());
            if($user->role == 'admin'){
                $loans = BusinessLoan::latest()->get();
                return view('business_loans.index',['loans'=>$loans,'user'=>$user])->with('page','Business | Loans');
            }else{
                return redirect()->route('loan.business.client');
                // $loans = $user->businessLoans()->latest()->get();
                // return view('business_loans.borrower_index',['loans'=>$loans,'user'=>$user])->with('page','Business | Loans');

            }

        } catch (\Throwable $th) {
            throw $th;
        }
    }

    //get an individuals business loans
    public function borrowerIndex(){
        try {
            $borrower = User::findOrFail(Auth::id());
            $businesses = $borrower->businesses()->get();
            // dd($students);
            $loans = $borrower->businessLoans()->latest()->get();
            $countries = Country::all();
            $repayments = $borrower->repayments()->where('repaymentable_type','App\Models\BusinessLoan')->where('status','!=','Paid')->get();
            // $installments = $borrower->repayments()->where('status','pending')->orWhere('status','late')->get();
            return view('business_loans.borrower_index',['countries'=>$countries,'businesses'=>$businesses,'loans'=>$loans,'repayments'=>$repayments,'user'=>$borrower])->with('page',$borrower->fname.' '.$borrower->lname.' Business Loans');
        } catch (\Throwable $th) {
            throw $th;
            // return redirect()->back()->withErrors($th->getMessage());
        }
        
    }

    //pending business loans
    public function pending(){
        try {
            $user = User::find(Auth::id());
            if($user){
                $loans = $user->role == 'admin' ? BusinessLoan::where('status','pending')->latest()->get() :
                    $user->businessLoans()->where('status','Requested')->latest()->get();
                return view('business_loans.index',['user'=>$user,'loans'=>$loans])->with('page','Business Loans | Pending');
            }
            return redirect()->route('login');
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function late(){
        try {
            $user = User::find(Auth::id());
            if($user){
                $loans = $user->role == 'admin' ? BusinessLoan::where('status','late')->latest()->get() :
                    $user->businessLoans()->where('status','Over Due')->latest()->get();
                return view('business_loans.index',['user'=>$user,'loans'=>$loans])->with('page','Business Loans | Late');
            }
            return redirect()->route('login');
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function approved(){
        try {
            $user = User::find(Auth::id());
            if($user){
                $loans = $user->role == 'admin' ? BusinessLoan::where('status','approved')->latest()->get() :
                    $user->businessLoans()->where('status','Approved')->latest()->get();
                return view('business_loans.index',['user'=>$user,'loans'=>$loans])->with('page','Business Loans | Approved');
            }
            return redirect()->route('login');
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function declined(){
        try {
            $user = User::find(Auth::id());
            if($user){
                $loans = $user->role == 'admin' ? BusinessLoan::where('status','declined')->latest()->get() :
                    $user->businessLoans()->where('status','Denied')->latest()->get();
                return view('business_loans.index',['user'=>$user,'loans'=>$loans])->with('page','Business Loans | Declined');
            }
            return redirect()->route('login');
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function onHold(){
        try {
            $user = User::find(Auth::id());
            if($user){
                $loans = $user->role == 'admin' ? BusinessLoan::where('status','on hold')->latest()->get() :
                    $user->businessLoans()->where('status','On Hold')->latest()->get();
                return view('business_loans.index',['user'=>$user,'loans'=>$loans])->with('page','Business Loans | On Hold');
            }
            return redirect()->route('login');
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    //create
    public function create(){
        $user = User::findOrFail(Auth::id());
        $businesses = $user->businesses()->latest()->get();
        return view('business_loans.create',['businesses'=>$businesses,'user'=>$user])->with('page','Business Loan | Create');
    }

    public function requestLoan($id=null){
        if($id == null) {
            $business = null;
        }else{
            $business = Business::find($id);
        }
        $districts = District::all();
        $user = User::findOrFail(Auth::id());
        $categories = LoanCategory::all();
        // $businesses = $user->businesses()->latest()->get();
        return view('business_loans.request',['districts'=>$districts,'user'=>$user,'categories'=>$categories,'business'=>$business])->with('page','Business Loan | Request');
    }
    public function storeBusiness(Request $request){
        try {
            // dd($request);
            $user= User::find(Auth::id());
            $district = District::find((int)$request->district);
            if($user){
                if($request->business_id == 0){
                    $business = new Business();
                    $business->name = $request->name;
                    $business->location = $request->location;
                    $business->district()->associate($district);
                    $business->user()->associate($user);
                    $business->license_photo = $request->license;
                    $business->premises_photo = $request->premises;
                    $business->business_photo = $request->business_owner_photo;
                    $business->save();
                    $business->BIZ_id = 'BLN-'.$business->id.rand(0000,9999);
                    $business->save();
                    // dd($business);
                    
                }else{
                    $business = Business::findOrFail((int)$request->business_id);
                    $business->name = $request->name;
                    $business->location = $request->location;
                    $business->district()->associate($district);
                    $business->user()->associate($user);
                    $business->license_photo = $request->license;
                    $business->premises_photo = $request->premises;
                    $business->business_photo = $request->business_owner_photo;
                    $business->save();
                    
                }
                if($business){
                    $this->store($request,$business->id);
                    // return redirect()->route('loan.business.store',['id'=>$business->id]);

                }
                return redirect()->back()->withErrors('error','problem saving business details');

            }
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    //store a business loan
    public function store(Request $request,$id){
        // dd('store function');
        try {
            $user = User::findOrFail(Auth::id());
            if($user){
                // dd($user->created_at->addDays(5) < Carbon::now());
                if ($user->created_at->addDays(90) < Carbon::now()) {
                    $business = Business::find($id);
                    $loan_category = LoanCategory::find($request->loan_category);
                    $this->checkLoanLegibilityStatus($loan_category,$user);
                    $loan = new BusinessLoan();
                    $loan->user()->associate($user);
                    $loan->business()->associate($business);
                    $loan->loanCategory()->associate($loan_category);
                    $loan->account()->associate($user->account);
                    $loan->principal = $loan_category->loan_amount;
                    $loan->interest_rate = $loan_category->interest_rate;
                    $loan-> installments = $loan_category->installments;
                    $loan->payment_period = $loan_category->loan_period;
                    $loan->status = 'Requested';
                    $loan->payment_amount = $loan->principal + ($loan->principle * $loan->interest_rate/100);
                    $loan->save();
                    $loan->BLN_id = 'BLN-'.$loan->id.rand(11111,999999); 
                    $loan->save();
                  
                    if($loan){
                        //save business credentials
                        $credential = new BusinessCredential();
                        $credential->business()->associate($business);
                        $credential->businessLoan()->associate($loan);
                        $credential->name = $request->name;
                        $credential->location = $request->location;
                        $credential->district()->associate($business->district);
                        $credential->user()->associate($user);
                        $credential->license_photo = $request->license;
                        $credential->premises_photo = $request->premises;
                        $credential->business_photo = $request->business_owner_photo;
                        $credential->save();
                        // dd($credential);
                    }
                    //  return redirect()->route('loan.business.show',['id'=>$loan->id]);
                    $this->show($loan->id);
                }else{

                    return redirect()->back()->with('warning','You are only eligible after you have been with us for atleast 90 days');
                }
            }
            return redirect()->route('login');
        } catch (\Throwable $th) {
            throw $th;
        }      

    }

     //show a particular business loan
    public function show($id){
        // dd($id);
         try {
             if(Auth::check()){
                 $loan = BusinessLoan::findOrFail($id);
                 $repayments = $loan->repayments;
                 $business = $loan->business;
                 $credential = $loan->credential;
                 $user = $loan->user;
                 $countries = Country::all();
                //  dd($user);
                 return view('business_loans.show',[
                     'loan'=>$loan,
                     'repayments'=>$repayments,
                     'business'=>$business,
                     'user' =>$user,
                     'credential'=>$credential,
                     'countries'=>$countries
                 ])->with('page','Business Loan | '.$loan->BLN_id);
             }
         } catch (\Throwable $th) {
             throw $th;
         }
            
    }

    public function loanStatusChange($action,$id){
        try {
            if(Auth::check()){
                $loan_n_string = $this->changeStatus($action,$id,'business');
                if($loan_n_string){
                    $loan = $loan_n_string['loan'];
                    $user = $loan->user; 
                    $last_message_string = $loan_n_string['last_message_string'];
                    $message = 'Dear '. $user->name .', Your '.$loan->BLN_id .' of '.$loan->principal .' has been '.$loan->status .$last_message_string;
                    $sms_status = $this->sendSMS($message,$loan->user->telephone);
                    // for(;$sms_status != 'success';){
                    //     $sms_status = $this->sendSMS($message,$user->telephone);
                    // }
                    return redirect()->route('loan.business.index');
                }               
            }
            return redirect()->route('login')->withErrors('Errors','you are unathorised to do this operation!');
        } catch (\Throwable $th) {
            throw $th;
            // return redirect()->back()->withErrors($th->getMessage());
        }
    }

    
}
