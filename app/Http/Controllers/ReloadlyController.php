<?php

namespace App\Http\Controllers;

use App\Http\Traits\ReloadlyTrait;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReloadlyController extends Controller
{
    use ReloadlyTrait;
    public function buyAirtime(){
        
    }
    public function playground(){
        $user = User::find(Auth::id());
        $response = $this->getAccessToken('topup');
        dd($response);
        return view('payments.dashboards.index',['response'=>$response,'user'=>$user]);

    }
}
