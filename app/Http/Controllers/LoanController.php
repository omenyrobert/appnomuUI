<?php

namespace App\Http\Controllers;

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
}
