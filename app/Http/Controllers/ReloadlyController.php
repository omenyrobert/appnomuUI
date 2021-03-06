<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Traits\AirtimeTrait;
use App\Http\Traits\UtilityTrait;
use App\Http\Traits\FlutterwaveTrait;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;

class ReloadlyController extends Controller
{
    use AirtimeTrait, UtilityTrait, FlutterwaveTrait;
    public function buyAirtime(){
        
    }
    public function playground(){
        $user = User::find(Auth::id());
        $response = $this->getUtilityBillers();
        $response = json_decode($response,true);
        // foreach($response['content'] as $key){
        //     dump($key['name']);
        // }
        dd($response);
        return view('payments.dashboards.client_dashboard',['response'=>$response,'user'=>$user]);

    }

    public function dashboard(){
        try {
            $user = User::find(Auth::id());
            if($user){
                if($user->role == 'admin'){
                    
                    return view('payments.dashboards.index')->with('page','Airtime|Utility|Dashboard');
                }
                return redirect()->back()->withErrors('Error','UnAuthorised!');
            }
            return redirect()->route('login');
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function clientDashboard(){
        try {
            $user = User::find(Auth::id());
            if($user){
                $airtime_providers = $this->getTopupOperators();
                $elctricity_providers = $this->getUtilityBillers();
                return view('payments.dashboards.client_dashboard')->with('page','Airtime | Utilities');
            }
            return redirect()->route('login');
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
