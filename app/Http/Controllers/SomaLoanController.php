<?php

namespace App\Http\Controllers;
use App\Http\Traits\SMSTrait;
use App\Http\Traits\RepaymentsTrait;
use App\Models\SomaLoan;
use App\Models\User;
use App\Models\District;
use App\Models\Headteacher;
use App\Models\LoanCategory;
use App\Models\ParentDetail;
use App\Models\Repayment;
use App\Models\Student;
use Carbon\Carbon;
use AfricasTalking\SDK\AfricasTalking;
use App\Http\Traits\LoanTrait;
use App\Models\Country;
use App\Models\Grade;
use App\Models\Identification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class SomaLoanController extends Controller
{
    use SMSTrait,RepaymentsTrait,LoanTrait;
    //dashboard access
    public function somaDashboard(){
        $user = User::findOrFail(Auth::id());
        if($user->role== 'admin'){
            return redirect()->route('soma.dashboard');
        }
        return redirect()->route('soma.borrower.index',['id'=>$user->id]);
    }
    //get all soma loans
    public function index(){
        try {
            $user = User::find(Auth::id());
            if($user){
                $loans = $user->role == 'admin' ? SomaLoan::latest()->get() :
                    $user->somaLoans()->latest()->get();
                return view('soma.index',['user'=>$user,'loans'=>$loans])->with('page','Soma Loans | All ');
            }
            return redirect()->route('login');
        } catch (\Throwable $th) {
            throw $th;
        }
        
    }
    //get an individuals soma loans
    public function borrowerIndex(){
        try {
            $borrower = User::findOrFail(Auth::id());
            $students = $borrower->students()->with('headteachers')->get();
            // dd($students);
            $headteachers = $borrower->headteachers;
            $loans = $borrower->somaLoans()->orderBy('created_at','DESC')->get();
            $repayments = $borrower->repayments()->where('repaymentable_type','App\Models\SomaLoan')->where('status','!=','Pending')->get();
            // $installments = $borrower->repayments()->where('status','pending')->orWhere('status','late')->get();
            $countries = Country::all();
            return view('soma.borrower_index',['countries'=>$countries,'loans'=>$loans,'repayments'=>$repayments,'headteachers'=>$headteachers,'user'=>$borrower,'students'=>$students])->with('page',$borrower->fname.' '.$borrower->lname.' Soma Loans');
        } catch (\Throwable $th) {
            throw $th;
            // return redirect()->back()->withErrors($th->getMessage());
        }
        
    }

    //pending soma loans
    public function pending(){
        try {
            $user = User::find(Auth::id());
            if($user){
                $loans = $user->role == 'admin' ? SomaLoan::where('status','pending')->latest()->get() :
                    $user->somaLoans()->where('status','Requested')->latest()->get();
                return view('soma.index',['user'=>$user,'loans'=>$loans])->with('page','Soma Loans | Pending');
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
                $loans = $user->role == 'admin' ? SomaLoan::where('status','late')->latest()->get() :
                    $user->somaLoans()->where('status','Over Due')->latest()->get();
                return view('soma.index',['user'=>$user,'loans'=>$loans])->with('page','Soma Loans | Late');
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
                $loans = $user->role == 'admin' ? SomaLoan::where('status','approved')->latest()->get() :
                    $user->somaLoans()->where('status','Approved')->latest()->get();
                return view('soma.index',['user'=>$user,'loans'=>$loans])->with('page','Soma Loans | Approved');
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
                $loans = $user->role == 'admin' ? SomaLoan::where('status','declined')->latest()->get() :
                    $user->somaLoans()->where('status','Denied')->latest()->get();
                return view('soma.index',['user'=>$user,'loans'=>$loans])->with('page','Soma Loans | Declined');
            }
            return redirect()->route('login');
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function somaOnHold(){
        try {
            $user = User::find(Auth::id());
            if($user){
                $loans = $user->role == 'admin' ? SomaLoan::where('status','on hold')->latest()->get() :
                    $user->somaLoans()->where('status','On Hold')->latest()->get();
                return view('soma.index',['user'=>$user,'loans'=>$loans])->with('page','Soma Loans | On Hold');
            }
            return redirect()->route('login');
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    //create a soma loan
    public function create(){
        try {
            $user  = User::findOrFail(Auth::id());
            // dd($users[0]['id']);
            $districts = District::all();
            $grades = Grade::all();
            $loan_categories = LoanCategory::where('loan_amount','>=',100000)->Where('loan_amount','<=',2000000)->get();
            $students = $user->students;
            return view('soma.create',['districts'=>$districts,'user'=>$user,'grades'=>$grades,'categories'=>$loan_categories,'students'=>$students])->with('page','Soma Loan Application');
        } catch (\Throwable $th) {
            throw $th;
        }
    }
    public function createStudent(){
        try {
            $user  = User::findOrFail(Auth::id());
            // dd($users[0]['id']);
            $districts = District::all();
            $grades = Grade::all();
            $loan_categories = LoanCategory::where('loan_amount','>=',100000)->Where('loan_amount','<=',2000000)->get();
            $students = $user->students;
            return view('soma.create_student',['districts'=>$districts,'user'=>$user,'grades'=>$grades,'categories'=>$loan_categories,'students'=>$students])->with('page','Soma Loan Application');
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    //store the soma loan
    public function storeSomaStudent(Request $request){
        try {
            // dd($request);
            $user = User::findOrFail(Auth::id());
            // dd($user);
            if($user){
                // $outstanding_loans = $user->loans()->where('status','approved')->orWhere('status','Over Due')->count();
                // $outstanding_soma = $user->somaLoans()->where('status','approved')->orWhere('status','Over Due')->count();
                // $outstanding_biz = $user->businessLoans()->where('status','approved')->orWhere('status','Over Due')->count();
                // if($outstanding_biz || $outstanding_loans || $outstanding_soma){
                //     return redirect()->back()->withErrors(['Errors'=>'you still have an outstanding loan']);
                // }
                // if($user->alliances()->count() < 2){
                //     return redirect()->back()->withErrors(['Errors'=>'You Dont Have enough Allianses to qualify For a loan,Add alliance and try again later']);
                // }
                $student = new Student();
                $student->fname = $request->student_fname;
                $student->lname = $request->student_lname;
                $student->user()->associate($user);
                $student->gender = $request->student_gender;
                $student->class_name = $request->student_class;
                $student->dob = $request->student_dob;
                $student->phone = $request->student_phone;
                $student->school_name = $request->sch_name;
                $student->school_id_card = $request->student_id_card;
                $student->sch_admission_num = $request->sch_admin_num;
                if($request->radio_receipt_report == 'report'){
                    $student->school_report = $request->receipt_report;
                }else{
                    $student->school_receipt = $request->receipt_report;

                }
                
                $student->class_name = Grade::where('id',(int)$request->student_class)->first()->name;
                $student->save();
                // dd($student);
                $student->STD_id = 'STD_'.$student->id.rand(0000,9999);
                $student->save();
                if($student){
                    $hm_district = District::find($request->hm_district);
                    $headteacher = new Headteacher();
                    $headteacher->fname = $request->hm_fname;
                    $headteacher->lname = $request->hm_lname;
                    $headteacher->school_name = $request->sch_name;
                    $headteacher->phone = $request->hm_contact;
                    $headteacher->district()->associate($hm_district);
                    $headteacher->user()->associate($user);
                    $headteacher->save();
                    $headteacher->students()->attach($student->id,['student_admission_num'=>$student->sch_admission_num ]);
                    // dd($headteacher);

                }
                // dd(typeof $request->chk_parent_applicant);
                if($request->chk_parent_applicant == true){
                    $parent = new ParentDetail();
                    $parent->name = $user->name;
                    $parent->phone = $user->telephone;
                    $parent->id_photo = $user->identification->front_face;
                    $parent->save();
                }else{
                    $parent = new ParentDetail();
                    $parent->name = $request->parent_name;
                    $parent->phone = $request->nok_contact;
                    $parent->id_photo = $request->parent_id_card;
                    $parent->save();
                }
                // dd($parent);
                $parent->students()->attach($student->id,['relationship'=>$request->relationship]);
                $this->store($request,$student->id);
                
            }
            return redirect()->route('login')->withErrors('you have to loggin to do this operation');
        } catch (\Throwable $th) {
            throw $th;
        }
        
    }

    public function store(Request $request,$id){
        try {
            $user = User::find(Auth::id());
            // dd($user);
            if($user){
                $student = Student::findOrFail($id);
                $loan_category = LoanCategory::find($request->loan_category);
                $this->checkLoanLegibilityStatus($loan_category,$user);
                $soma = new SomaLoan();
                $soma->user()->associate($user);
                $soma->student()->associate($student);
                $soma->loanCategory()->associate($loan_category);
                $soma->account()->associate($user->account);
                $soma->principal = $loan_category->loan_amount;
                $soma->interest_rate = $loan_category->interest_rate;
                $soma->payment_period = $loan_category->loan_period;
                $soma-> installments = $loan_category->installments;
                $soma->save();
                // dd($soma);
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
                if($soma){
                    // dd($soma);
                    
                    $message = 'Dear '. $user->name .', Your '.$soma->SLN_id .' of '.$soma->principal .' has been requested pending approval';
                    
                    $smsStatus = $this->sendSMS($message,$user->telephone,'Soma Loan Request',$user);
                    // $this->show($soma->id);
                    return redirect()->route('soma.show',['id'=>$soma->id]);
                }
                return redirect()->back();

            }
            return redirect()->route('login');
        } catch (\Throwable $th) {
            throw $th;
        }
    }

   
   

    public function loanStatusChange($action,$id){
        try {
            // dd($action);
            if(Auth::check()){
                $loan_n_string = $this->changeStatus($action,$id,'soma');
                if($loan_n_string){
                    $loan = $loan_n_string['loan'];
                    $user = $loan->user; 
                    $last_message_string = $loan_n_string['last_message_string'];
                    $message = 'Dear '. $user->name .', Your '.$loan->SLN_id .' of '.$loan->principal .' has been '.$loan->status .$last_message_string;                    
                    $sms_status = $this->sendSMS($message,$user->telephone,'Loan Processing',$user);
                    // for(;$sms_status != 'success';){
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

   

    public function payInstallment($id){

    }

    //retrieve a single soma loan
    public function show($id){
        try {
            $user = User::find(Auth::id());
            if($user){
                $loan = SomaLoan::findOrFail($id);
                $repayments = $loan->status=='Pending'?$loan->repayments()->where('status','pending')->get(): $loan->repayments;
                $student = $loan->student;
                $school = $loan->student->headteachers()->latest()->first();
                $countries = Country::all();
                return view('soma.show',['countries'=>$countries,'loan'=>$loan,'repayments'=>$repayments,'student'=>$student,'school'=>$school])
                    ->with('page','Soma Loan | Show');

            }
            return redirect()->back()->withErrors('Error','You must Login First To View This Loan');

            // return redirect()->route('login')->withErrors('Error','You must Login First To View This Loan');
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    

}
