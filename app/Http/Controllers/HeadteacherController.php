<?php

namespace App\Http\Controllers;

use App\Models\District;
use App\Models\Headteacher;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HeadteacherController extends Controller
{
    public function create($id){
        $student = Student::findOrFail($id);
        $districts = District::orderBy('name','asc')->get();
        return view('headteacher.create',['student'=>$student,'districts'=>$districts]);
    }

    public function store(Request $request,$id){
        try {
            $student = Student::findOrFail($id);
            $user = User::find(Auth::id());
            $hm_district = District::find($request->hm_district);
            $headteacher = new Headteacher();
            $headteacher->fname = $request->hm_fname;
            $headteacher->lname = $request->hm_lname;
            $headteacher->school_name = $request->sch_name;
            $headteacher->phone = $request->hm_contact;
            $headteacher->student_admission_num = $student->sch_admission_num;
            $headteacher->district()->associate($hm_district);
            $headteacher->user()->associate($user);
            $headteacher->save();
            $headteacher->students()->attach($student->id,['student_admission_num'=>$student->sch_admission_num]);
            return redirect()->back()->with('success','HeadTeacher added successfully');
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function edit($id){
        try {
            $headteacher = Headteacher::findOrFail($id);
            return view('headteacher.edit',['headteacher'=>$headteacher])->with('page','Headteacher | Edit');
        } catch (\Throwable $th) {
            throw $th;
        }

    }

    public function update(Request $request,$id){
        try {
            $headteacher = Headteacher::findOrFail($id);
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
