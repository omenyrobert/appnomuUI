<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Withdraw;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Withdraws;

class WithdrawController extends Controller
{
    public function index(){
        $user = User::find(Auth::id());
        if($user){
            $withdraws = $user->role == 'admin' ? Withdraw::latest()->get() 
                : $user->withdraws()->latest()->get();
            return view('withdraws.index',['withdraws'=>$withdraws])->with('page','Withdraw | index');
        }
    }

    public function create(){
        $user = User::find(Auth::id());
        if($user){

            return view('withdraws.create')->with('page','withdraw | create');
        }
        return redirect()->route('login');
    }



    // public static function getAllWithdraws(){
    //     $db = DB::table('withdraws')
    //         ->get();

    //     $dbx = json_decode($db,true);
    //     return $dbx;
    // }

    // public static function getMyWithdraws($user_id){
    //     $db = DB::table('withdraws')
    //         ->where('user_id','=',$user_id)
    //         ->get();

    //     $dbx = json_decode($db,true);
    //     return $dbx;
    // }

    // public static function getAllWithrawsByStatus($status){
    //     $amounts = 0;
    //     $withdraws_number = 0;
    //     $db_save = AuthenticationController::getAllWithdraws();
        
    //     foreach ($db_save as $key) {
    //         if (($key['status']==$status)) {
    //             $amounts = $amounts+ $key['amount'];
    //             $withdraws_number ++;
    //         }
    //     }

    //     $arr =  array('amounts' => $amounts,'number'=>$withdraws_number);
    //     return $arr;
    // }



}
