<?php

namespace App\Http\Controllers;

use App\Http\Traits\FlutterwaveTrait;
use App\Models\AirtimeRate;
use App\Models\Saving;
use App\Models\SavingSubCategory;
use App\Models\User;
use App\Models\Country;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SavingController extends Controller
{
    use FlutterwaveTrait;
    public function index(){
        $user = User::find(Auth::id());
        if($user){
            $iso_banks = $this->getBanks();
                $iso_banks = json_decode($iso_banks,true);
                if($iso_banks['status'] == 'success'){
                    $banks = $iso_banks['data'];
    
                }else{
                    $banks = [];
                }
            $rates = AirtimeRate::all();
            $savings = $user->role == 'admin' ? Saving::latest()->paginate(10)
                : $user->savings()->latest()->paginate(10);
            $categories = SavingSubCategory::with('savingCategory')->get();
            $countries = Country::all();
            return view('savings.index',
                ['savings'=>$savings,
                'user'=>$user,
                'categories'=>$categories,
                'countries'=>$countries,
                'banks'=>$banks,
                'rates'=>$rates
                ]
            )->with('page','All | Savings');
        }
        return redirect()->route('login');
    }

    public function create(){
        $user = User::find(Auth::id());
        if($user){
            $savings = $user->role == 'admin' ? Saving::latest()->paginate(10)
                : $user->savings()->latest()->paginate(10);
            return view('savings.index')->with('page','All | Savings');
        }
        return redirect()->route('login');
    }

    public function handleDeposit(Request $request){
        
    }
}
