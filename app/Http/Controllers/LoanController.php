<?php

namespace App\Http\Controllers;

use App\Models\Loan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Response;
use App\Models\User;

class LoanController extends Controller
{
    //change loan limit
    public function updateLoanLimit(Request $request,$id){
        try {
            if(Auth::user()->role == 'Admin'){
                $user = User::find($id);
                if($user){
                    $user->loan_limit = (int)$request->loan_limit;
                    $user->save();
                }
                return response('user does not exist',401);
            }
            return response('unathorized',401);
        } catch (\Throwable $th) {
            throw $th;
        }
    }


    public function requestLoan(Request $request){
        $validated = $request->validate([
            'category'=>'required'
        ]);

        $user = AuthenticationController::getUserById(session('user_id'));
        // $user = Auth::user();
        dd($user);
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
    }


    // public function requestLoan(Request $request){
    //     $validated = $request->validate([
    //         'category'=>'required'
    //     ]);

    //     $user = AuthenticationController::getUserById(session('user_id'));

    //     if ($user[0]['NIN']==null || $user[0]['card_no']==null || $user[0]['address']==null) {
    //         return redirect()->route('profile')->withErrors(['Errors'=>'Your Profile Is not Set Up fully ']);
    //     }

    //     $loans = AuthenticationController::getLoanHistory(session('user_id'));
    //     $loan_cat = AuthenticationController::getLoanByCatID($request['category']);
    //     $i = 0;

    //     foreach ($loans as $key) {
    //         # code...
    //         if ($key['status']==6 || $key['status']==4 ) {
    //             # code...
    //             $i++;
    //         }
    //     }

    //     if ($i>0) {
    //         return redirect()->back()->withErrors(['Errors'=>'You Have an Outstanding Loan ']);
    //     }

    //     $db = DB::table('alliases')
    //         ->where('refferer','=',session('user_id'))
    //         ->get();
        
    //     $dbxc = json_decode($db,true);

    //     $numcv = sizeof($dbxc);

    //     if ($numcv<2) {
    //         # code...
    //         return redirect()->back()->withErrors(['Errors'=>'You Dont Have enough Alliases to qualify For a loan,Add alliance and try again later']);

    //     }
    //     $uloan = 'LN-'.rand(11111,99999);
    //     $pay = 0;
    //     $status = 5;
    //     $db = DB::table('userloans')->insert([
    //         'ULoan_Id'=>$uloan,
    //         'user_id'=>session('user_id'),
    //         'loan_amount'=>$loan_cat[0]['loan_amount'],
    //         'amount_paid'=>$pay,
    //         'status'=>$status,
    //         'dueDate'=>time(),
    //         'approved_by'=>' ',
    //         'created_at'=>date('Y-m-d H:i:s',time())
    //     ]);
        
    //     if ($db) {
    //         # code...
    //         return redirect()->back()->with('Success','Loan Request Sent');
    //     }else {
    //         # code...
    //         return redirect()->back()->withErrors(['Errors'=>'Loan Request Not Sent']);
    //     }
    // }
}
