<?php

namespace App\Http\Controllers;

use App\Models\Grade;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentController extends Controller
{
    // get all students belonging to a particular user
    public function index(){
        try {
            $user = User::findOrFail(Auth::id());
            $students = $user->students;
            return view('student.index',['students'=>$students,'user'=>$user])->with('page','Students | All');
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    //create a student
    public function create(){
        $classes = Grade::all();
        return view('student.create',['classes'=>$classes])->with('page','Student | Show');
    }
    // store a student
    public function store(Request $request){
        try {
            $user = User::findOrFail(Auth::id());
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
                return redirect()->back()->with('success','Student Created Successfully');
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function show($id){
        try {
            $student = Student::findOrFail($id);
            return view('student.show',['student'=>$student])->with('page','Student | Show');
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function edit($id){
        try {
            $student = Student::findOrFail($id);
            $classes = Grade::all();
            return view('student.edit',['classes'=>$classes,'student'=>$student])->with('page','Student | Edit');
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function update(Request $request,$id){
        try {
            $student = Student::findOrFail($id);
            $student->fname = $request->student_fname;
                $student->lname = $request->student_lname;
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
                return redirect()->back()->with('success','Student data Updated successfully');
        } catch (\Throwable $th) {
            throw $th;
        }
    }


}
