<?php

namespace App\Http\Controllers;

use App\Models\SomaLoan;
use App\Models\User;
use App\Models\District;
use App\Models\Headteacher;
use App\Models\LoanCategory;
use App\Models\ParentDetail;
use App\Models\Repayment;
use App\Models\Student;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class SomaLoanController extends Controller
{
    //dashboard access
    public function somaDashboard(){
        $users = AuthenticationController::getUserById(session('user_id'));
        $user = User::findOrFail($users[0]['id']);
        if($user->role== 'Admin'){
            return redirect()->route('soma.index');
        }
        return redirect()->route('soma.borrower.index',['id'=>$user->id]);
    }
    //get all soma loans
    public function index(){
        try {
            $pending = SomaLoan::orderBy('created_at','DESC')->where('status','pending')->get();
            $approved = SomaLoan::orderBy('created_at','DESC')->where('status','approved')->get();
            $denied = SomaLoan::orderBy('created_at','DESC')->where('status','declined')->get();
            $held = SomaLoan::orderBy('created_at','DESC')->where('status','held')->get();
            $late = SomaLoan::orderBy('created_at','DESC')->where('status','late')->get();
            return view('soma.index',['pending'=>$pending,'approved'=>$approved,'denied'=>$denied,'hold'=>$held,'late'=>$late])->with('title','Soma Loans');
        } catch (\Throwable $th) {
            //throw $th;
            return redirect()->back()->withErrors($th->getMessage());
        }
        
    }
    //get an individuals soma loans
    public function borrowerIndex($id){
        try {
            $borrower = User::findOrFail($id);
            $loans = $borrower->soma_loans()->orderBy('created_at','DESC')->get();
            $installments = $borrower->repayments()->where('status','pending')->orWhere('status','late')->get();
            return view('soma.borrower_index',['loans'=>$loans,'installments'=>$installments])->with('page',$borrower->fname.' '.$borrower->lname.' Soma Loans');
        } catch (\Throwable $th) {
            //throw $th;
            return redirect()->back()->withErrors($th->getMessage());
        }
        
    }

    //create a soma loan
    public function create(){
        try {
            $users = AuthenticationController::getUserById(session('user_id'));
            $districts = District::all();
            $user  = User::findOfFail($users[0]['id']);
            return view('soma.create',['districts'=>$districts,'user'=>$user])->with('title','Soma Loan Application');
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    //store the soma loan
    public function store(Request $request){
        try {
            // $user = Auth::user();
            $users = AuthenticationController::getUserById(session('user_id'));
            $user = User::findOrFail($users[0]['id']);
            if($user){
                $outstanding_loans = $user->loans()->where('status',6)->orWhere('status',4)->count();
                $outstanding_soma = $user->soma_loans()->where('status',6)->orWhere('status',4)->count();
                $outstanding_biz = $user->biz_loans()->where('status',6)->orWhere('status',4)->count();
                if($outstanding_biz || $outstanding_loans || $outstanding_soma){
                    return redirect()->back()->withErrors(['Errors'=>'you still have an outstanding loan']);
                }
                if($user->alliances()->count() < 2){
                    return redirect()->back()->withErrors(['Errors'=>'You Dont Have enough Allianses to qualify For a loan,Add alliance and try again later']);
                }
                $student = new Student();
                $student->fname = $request->student_fname;
                $student->lname = $request->student_lname;
                $student->gender = $request->student_gender;
                $student->class_name = $request->student_class;
                $student->dob = $request->student_dob;
                $student->phone = $request->student_phone;
                $student->school_name = $request->student_school_name;
                $student->school_id_card = $request->student_id_card;
                $student->school_report = $request->student_report;
                $student->school_receipt = $request->student_receipt;
                $student->save();
                if($student){
                    $hm_district = District::find($request->hm_district);
                    $headteacher = new Headteacher();
                    $headteacher->fname = $request->hm_fname;
                    $headteacher->lname = $request->hm_lname;
                    $headteacher->school_name = $request->hm_school_name;
                    $headteacher->phone = $request->hm_phone;
                    $headteacher->district()->associate($hm_district);
                    $headteacher->student()->associate($student);
                    $headteacher->save();

                }
                if($request->same_parent == 1){
                    $parent = new ParentDetail();
                    $parent->name = $user->name;
                    $parent->phone = $user->telephone;
                    $parent->id_photo = $user->identification->front_face;
                    $parent->save();
                }else{
                    $parent = new ParentDetail();
                    $parent->name = $request->parent_name;
                    $parent->phone = $request->parent_phone;
                    $parent->id_photo = $request->parent_id_card;
                    $parent->save();
                }
                $parent->students()->attach($student->id,['relationship'=>$request->parent_relationship]);
                $loan_category = LoanCategory::find($request->loan_chart);
                $soma = new SomaLoan();
                $soma->user()->associate($user);
                $soma->student()->associate($student);
                $soma->principal = $loan_category->loan_amount;
                $soma->interest_rate = $loan_category->interest_rate;
                $soma->payment_period = $loan_category->loan_period;
                $soma -> installments = $loan_category->installments;
                $soma->save();
                switch (strlen($soma->id)) {
                    case 1:
                        $soma->SLN_id = 'SLN_'.$soma->id.'00'.rand(111,999);
                        break;
                    case 2:
                        $soma->SLN_id = 'SLN_'.$soma->id.'0'.rand(111,999);
                        break;
                    case 3:
                        $soma->SLN_id = 'SLN_'.$soma->id.rand(111,999);
                        break;
                        
                    default:
                        $soma->SLN_id = 'SLN_'.$soma->id.rand(1111,9999);
                        break;
                }
                $soma->save();
                return redirect()->route('soma.show');
            }
            return redirect()->back()->withErrors('you have to loggin to do this operation');
        } catch (\Throwable $th) {
            throw $th;
        }
        
    }

    public function approveSomaLoan(Request $request,$id){
        try {
            $users = AuthenticationController::getUserById(session('user_id'));
            // $user = Auth::user();
            $user = User::findOrFail($users[0]['id']);
            if($user && $user->role == 'Admin'){
                $soma = SomaLoan::findOrFail($id);
                $soma->approved_at = Carbon::now();
                $soma->due_date = Carbon::now()->addDays($soma->payment_period);                
                $soma->status = 'approved';
                $soma->interest = $soma->principal /$soma->interest_rate;
                $soma->payment_amount = $soma->interest + $soma->principal + $soma->loan_category->processing_fees;
                $soma->save();
                $installment_amount = $soma->payment_amount /$soma->installments;
                $installment_period = $soma->payment_period/$soma->installments;
                for ($i=1; $i <= $soma->installments ; $i++) { 
                   $installment = new Repayment();
                   $installment->repaymentable_id = $soma->id;
                   $installment->repaymentable_type = 'App/Models/SomaLoan';
                   $installment->amount = $installment_amount;
                   $last_date = $soma->repayments()->latest()->get()->due_date;
                   $installment->due_date = $last_date ? $last_date->addDays($installment_period) : $soma->approved_at->addDays($installment_period);
                   $installment->save();

                }

                return redirect()->back();

            }
            return redirect()->back()->withErrors('you are unathorised to do this operation!');
        } catch (\Throwable $th) {
            throw $th;
            // return redirect()->back()->withErrors($th->getMessage());
        }
    }

    public function payInstallment($id){

    }

}
