<?php

namespace App\Http\Controllers;

use App\Models\LoanCategory;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoanCategoryController extends Controller
{
    //get all loan categories
    public function index(){
        $categories = LoanCategory::all();
        $user = User::find(Auth::id());
        return view('view.loan-categories',['categories'=>$categories,'user'=>$user])->with('page','Loan Categories');
    }

    //create a new category

    public function store(Request $request){
        try {
            $user = User::find(Auth::id());
            if($user && $user->role == 'admin'){
                $category = new LoanCategory();
                $category->loan_amount = $request->amount;
                $category->loan_period = $request->period;
                $category->installments = $request->installments;
                $category->interest_rate = $request->interest;
                $category->processing_fees = $request->processing;
                $category->save();
            }
            return redirect()->route('login');
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    //  public function saveLoanCategory(Request $request){
    //     $validated = $request->validate([
    //         'amount'=>'required',
    //         'period'=>'required',
    //         'interest'=>'required',
    //         'processing'=>'required',
    //         'installements'=>'required'
    //     ]);

    //     $cate = 'LN-'.rand(1111,9999);

    //     if (!is_numeric($request['amount'])) {
    //         # code...
    //         return redirect()->back()->withErrors(['Error'=>'Sorry Amount Should Be a numeric Value']);
    //     }

    //     if (!is_numeric($request['period'])) {
    //         # code...
    //         return redirect()->back()->withErrors(['Error'=>'Sorry Period Should Be a numeric Value']);
    //     }

    //     if (!is_numeric($request['interest'])) {
    //         # code...
    //         return redirect()->back()->withErrors(['Error'=>'Sorry Interest Should Be a numeric Value']);
    //     }

    //     if (!is_numeric($request['processing'])) {
    //         # code...
    //         return redirect()->back()->withErrors(['Error'=>'Sorry Proccessing Fee Should Be a numeric Value']);
    //     }

    //     // Installement days 
    //     $install_days = intdiv($request['period'],$request['installements']);

    //     //Status
    //     $status = 7;

    //     $db = DB::table('loanchart')->insert([
    //         'loan_id'=>$cate,
    //         'loan_amount'=>$request['amount'],
    //         'loan_period'=>$request['period'],
    //         'interest_rate'=>$request['interest'],
    //         'processing_fees'=>$request['processing'],
    //         'status'=>$status,
    //         'installments'=>$request['installements'],
    //         'installement_period'=>$install_days,
    //         'created_at'=>date('Y-m-d H:i:s',time()),
            
    //     ]);

    //     if ($db) {
    //         # code...
    //         return redirect()->back()->with(['Success'=>'Loan Category Saved Successfully']);
    //     }else {
    //         # code...
    //         return redirect()->back()->withErrors(['Error'=>'Loan Category Not Saved Successfully']);
    //     }

    // }
}


