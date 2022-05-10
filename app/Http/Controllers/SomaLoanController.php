<?php

namespace App\Http\Controllers;
use App\Http\traits\SMSTrait;
use App\Http\traits\RepaymentsTrait;
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
use App\Models\Grade;
use App\Models\Identification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class SomaLoanController extends Controller
{
    use SMSTrait,RepaymentsTrait;
    //dashboard access
    public function somaDashboard(){
        $user = User::findOrFail(Auth::id());
        if($user->role== 'admin'){
            return redirect()->route('soma.index');
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
            $repayments = $borrower->repayments()->where('repaymentable_type','App\Models\SomaLoan')->where('status','!=','Paid')->get();
            $installments = $borrower->repayments()->where('status','pending')->orWhere('status','late')->get();
            return view('soma.borrower_index',['loans'=>$loans,'repayments'=>$repayments,'headteachers'=>$headteachers,'user'=>$borrower,'students'=>$students,'installments'=>$installments])->with('page',$borrower->fname.' '.$borrower->lname.' Soma Loans');
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
                    $user->somaLoans()->where('status','pending')->latest()->get();
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
                    $user->somaLoans()->where('status','late')->latest()->get();
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
                    $user->somaLoans()->where('status','approved')->latest()->get();
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
                    $user->somaLoans()->where('status','declined')->latest()->get();
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
                    $user->somaLoans()->where('status','on hold')->latest()->get();
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
            $user = User::findOrFail(Auth::id());
            if($user){
                $outstanding_loans = $user->loans()->where('status','approved')->orWhere('status','Over Due')->count();
                $outstanding_soma = $user->soma_loans()->where('status','approved')->orWhere('status','Over Due')->count();
                $outstanding_biz = $user->biz_loans()->where('status','approved')->orWhere('status','Over Due')->count();
                if($outstanding_biz || $outstanding_loans || $outstanding_soma){
                    return redirect()->back()->withErrors(['Errors'=>'you still have an outstanding loan']);
                }
                if($user->alliances()->count() < 2){
                    return redirect()->back()->withErrors(['Errors'=>'You Dont Have enough Allianses to qualify For a loan,Add alliance and try again later']);
                }
                $student = new Student();
                $student->fname = $request->student_fname;
                $student->lname = $request->student_lname;
                $student->user()->associate($user);
                $student->gender = $request->student_gender;
                $student->class_name = $request->student_class;
                $student->dob = $request->student_dob;
                $student->phone = $request->student_phone;
                $student->school_name = $request->student_school_name;
                $student->school_id_card = $request->student_id_card;
                $student->sch_admission_num = $request->sch_admin_num;
                if($request->radio_receipt_report == 'report'){
                    $student->school_report = $request->receipt_report;
                }else{
                    $student->school_receipt = $request->receipt_report;

                }
                
                $student->class_name = $request->student_class;
                $student->save();
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
                    // $headteacher->student()->associate($student);
                    $headteacher->save();
                    $headteacher->students()->attach($student->id);

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
            if($user){
                $student = Student::findOrFail($id);
                $loan_category = LoanCategory::find($request->loan_category);
                $soma = new SomaLoan();
                $soma->user()->associate($user);
                $soma->student()->associate($student);
                $soma->loanCategory()->associate($loan_category);
                $soma->principal = $loan_category->loan_amount;
                $soma->interest_rate = $loan_category->interest_rate;
                $soma->payment_period = $loan_category->loan_period;
                $soma-> installments = $loan_category->installments;
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
                // dd($soma);
                if($soma){
                    
                    // $message = 'Dear '. $user->name .', Your '.$soma->SLN_id .' of '.$soma->principal .' has been requested pending approval';
                    
                    // $smsStatus = $this->sendSMS($message,$user->telephone);
                    return redirect()->route('soma.show',['id'=>$soma->id]);
                }
                return redirect()->back();

            }
            return redirect()->route('login');
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function loanStatusChange(Request $request,$id){
        try {
            if(Auth::check()){
                $loan_n_string = $this->changeStatus($request,$id,'soma');
                if($loan_n_string){
                    $loan = $loan_n_string['loan'];
                    $user = $loan->user; 
                    $last_message_string = $loan_n_string['last_message_string'];
                    $message = 'Dear '. $user->name .', Your '.$loan->SLN_id .' of '.$loan->principal .' has been '.$loan->status .$last_message_string;                    
                    $sms_status = $this->sendSMS($message,$user->telephone);
                    for(;$sms_status != 'success';){
                        $sms_status = $this->sendSMS($message,$user->telephone);
                    }
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

    //show a particular soma loan
    public function show($id){
        try {
            $loan = SomaLoan::findOrFail($id);
            $repayments = $loan->repayments;
            $student = $loan->student;
            $school = $loan->headTeacher;
            return view('soma.show',['loan'=>$loan,'repayments'=>$repayments,'student'=>$student,'school'=>$school])
                ->with('page','Soma Loan | Show');

        } catch (\Throwable $th) {
            throw $th;
        }
    }

    

}
