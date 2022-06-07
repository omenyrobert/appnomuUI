<?php

namespace App\Http\Controllers;

use App\Http\Traits\FlutterwaveTrait;
use App\Models\Country;
use App\Models\User;
use App\Models\Withdraw;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Withdraws;

class WithdrawController extends Controller
{   
    use FlutterwaveTrait;
    public function index(){
        try {
            //code...
            $user = User::find(Auth::id());
            if($user){
                $iso_banks = $this->getBanks();
                $iso_banks = json_decode($iso_banks,true);
                if($iso_banks['status'] == 'success'){
                    $banks = $iso_banks['data'];
    
                }else{
                    $banks = [];
                }
                $countries = Country::all();
                // $default_currency = $user->country->currency;
                $withdraws = $user->role == 'admin' ? Withdraw::latest()->get() 
                    : $user->withdraws()->latest()->get();
                return view('withdraws.index',['countries'=>$countries,'withdraws'=>$withdraws,'banks'=>$banks,'user'=>$user])->with('page','Withdraw | index');
            }
            return redirect()->route('login');
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function create(){
        try {
            //code...
            $user = User::find(Auth::id());
            if($user){
                $iso_banks = $this->getBanks();
                $iso_banks = json_decode($iso_banks,true);
                if($iso_banks['status'] == 'success'){
                    $banks = $iso_banks['data'];
    
                }else{
                    $banks = [];
                }
                // dd($user->account);
                // dd(Country::where('ISO',$user->country_id)->first());
                $countries = Country::all();
                // $default_currency = $user->country->currency;
                return view('withdraws.create',['countries'=>$countries,'banks'=>$banks,'user'=>$user])->with('page','withdraw | create');
            }
            return redirect()->route('login');
        } catch (\Throwable $th) {
            throw $th;
        }
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
