<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Traits\AirtimeTrait;
use App\Http\Traits\UtilityTrait;
use Illuminate\Support\Facades\Auth;

class ReloadlyController extends Controller
{
    use AirtimeTrait, UtilityTrait;
    public function buyAirtime(){
        
    }
    public function playground(){
        $user = User::find(Auth::id());
        $response = $this->getCountryCodes();
        // $response = json_encode($response);
        
        dd($response['AN']);
        return view('payments.dashboards.client_dashboard',['response'=>$response,'user'=>$user]);

    }

    public function dashboard(){
        try {
            $user = User::find(Auth::id());
            if($user){
                if($user->role == 'admin'){
                    
                    return view('payments.dashboards.index');
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
