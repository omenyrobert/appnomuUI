<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\AirtimeController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\LoanController;
use App\Http\Controllers\FlutterwaveController;
use App\Http\Controllers\SmsController;
use App\Http\Controllers\SomaLoanController;
use App\Http\Controllers\AllianceController;
use App\Http\Controllers\BusinessLoanController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LoanCategoryController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ReloadlyController;
use App\Http\Controllers\SavingCategoryController;
use App\Http\Controllers\SavingController;
use App\Http\Controllers\UtilityController;
use App\Http\Controllers\WithdrawController;
use App\Models\BusinessLoan;
use App\Models\Country;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });


Route::get('/dashboard',[HomeController::class,'dashboard'])->name('dashboard');
Route::get('/dashboard/admin',[HomeController::class,'adminDashboard'])->name('dashboard.admin');
Route::get('/dashboard/client',[HomeController::class,'clientDashboard'])->name('dashboard.client');
Route::get('/login', function () {
    view()->share('page','Login');
    $countries = Country::all();
    return view('auth.login',['countries'=>$countries]);
})->name('login');





Route::post('/forgot',[LoginController::class,'forgotPassword']);

Route::get('/reset-password', function () {
    view()->share('page','New Password');
    return view('view.new_pass');
})->name('reset_pass');



Route::get('/profile', function () {
    view()->share('page','User Profile');
    return view('view.profile');
})->name('profile');






// Route::get('/loans', function () {
//     view()->share('page','Loans');
//     return view('view.loans');
// });




//loan categories=======
Route::get('/loan-chart',[LoanCategoryController::class,'index'])->name('loan.categories.index');
Route::post('/loan/category/store',[LoanCategoryController::class,'store'])->name('loan.categories.store');






Route::post('/verify-email/{email}/{code}',[LoginController::class,'verifyUserEmail'])->name('verify.email');
Route::post('/verify-phone',[LoginController::class,'verifyUserPhone'])->name('verify.phone');
// Route::get('/verify-phone', function () {
//     view()->share('page','Verify phone');
//     return view('view.verify-phone');
// })->name('verify2');



Route::get('/send_sms',function(){
    $sms = SmsController::sms_test(env('AFRICASTALKING_USERNAME'),env('AFRICASTALKING_APIKEY'));
    echo $sms;
});





Route::get('/sendSms', function () {
    view()->share('page','Send SMS');
    return view('view.sms-temp');
});

Route::get('/allSms', function () {
    view()->share('page','All SMSes');
    return view('view.sentSms');
});






// loan routes
Route::post('/request_loan',[LoanController::class,'requestLoan'])->name('loan.request'); 
Route::get('/loans/index',[LoanController::class,'index'])->name('loan.index');
Route::get('/loans/pending',[LoanController::class,'pending'])->name('loan.pending');
Route::get('/loans/approved',[LoanController::class,'approved'])->name('loan.approved');
Route::get('/loans/late',[LoanController::class,'late'])->name('loan.late');
Route::get('/loans/declined',[LoanController::class,'declined'])->name('loan.declined');
Route::get('/loans/held',[LoanController::class,'held'])->name('loan.held');
Route::get('/loan/user/{id}',[LoanController::class,'userIndex'])->name('loan.user.index');
Route::get('/loan/show/{id}',[LoanController::class,'show'])->name('loan.show');
Route::get('/loan/change-status/{action}/{id}',[LoanController::class,'loanStatusChange'])->name('loan.status');

// =========================end loan routes
Route::post('/sendSms',[SmsController::class,'sendBulks']);
Route::post('/verification',[AuthenticationController::class,'verifyphone']);
Route::post('/save_saving_category',[AuthenticationController::class,'saveSavingCate']);
Route::post('/save_saving_sub_category',[AuthenticationController::class,'saveSavingSubCate']);
Route::post('/save_loan_category',[AuthenticationController::class,'saveLoanCategory']);
Route::post('/pay', [FlutterwaveController::class, 'initialize'])->name('pay');
Route::get('/rave/callback', [FlutterwaveController::class, 'callback'])->name('callback');
Route::post('/loginty',[LoginController::class,'authenticate'])->name('user.login');
Route::post('/register_user',[LoginController::class,'register'])->name('user.register');
Route::get('/logout',[LoginController::class,'logout'])->name('user.logout');
//====alliance routes
Route::post('/save-alliaces',[AllianceController::class,'store'])->name('alliance.store');
Route::post('/confirm-alliaces',[AllianceController::class,'confirmAlliance'])->name('alliance.confirm');
Route::get('alliances',[AllianceController::class,'index'])->name('alliance.index');
Route::post('/editProfile',[AuthenticationController::class,'editUserProfile']);
Route::post('/editPasswords',[AuthenticationController::class,'editPasswords']);
Route::post('/webhook/rave', [FlutterwaveController::class, 'webhook'])->name('webhook');
Route::get('/rave/loans_callback', [FlutterwaveController::class, 'loan_callback'])->name('callback_loans');
Route::post('/rave/withdraws', [FlutterwaveController::class, 'withdraw'])->name('withdraw');
Route::post('/reset_pass', [AuthenticationController::class, 'forgotPassword'])->name('reset');
Route::post('/new_pass', [AuthenticationController::class, 'resetPassword'])->name('new_pass');
Route::post('/frontface', [AuthenticationController::class, 'storeFrontFace']);
Route::post('/backface', [AuthenticationController::class, 'storebackFace']);
Route::post('/passport', [AuthenticationController::class, 'passport']);
Route::post('/article', [AuthenticationController::class, 'giveknowlegebase']);



//Website routes

Route::get('/', function () {
    return view('view.home');
});

Route::get('/about', function () {
    return view('view.about');
});

Route::get('/contact-us', function () {
    return view('view.contactus');
});




// Route::get('/foo', function () {
//     Artisan::call('storage:link');
// });

Route::get('/messages-contact', function () {
    view()->share('page','Contact Page');
    return view('view.messages_conts');
});

Route::get('/paycheck', function () {
  	if(isset($_GET['p']) && isset($_GET['c'])){
      $pay = AuthenticationController::setpaycheck($_GET['p'],$_GET['c']);
      $message = $pay;
    }else{
    	$message = '';
    }
  	view()->share('message',$message);
    return view('view.paycheck');
});



Route::post('/contact_pagexc',[AuthenticationController::class,'sendMessagex']);
// soma loan routes
// Route::get('loans/soma/dashboard', [SomaLoanController::class,'index'])->name('soma.dashboard');
Route::get('loans/soma/index', [SomaLoanController::class,'index'])->name('soma.index');
Route::get('loans/soma/show/{id}', [SomaLoanController::class,'show'])->name('soma.show');
Route::get('loans/soma/pending', [SomaLoanController::class,'pending'])->name('soma.pending');
Route::get('loans/soma/declined', [SomaLoanController::class,'declined'])->name('soma.declined');
Route::get('loans/soma/held', [SomaLoanController::class,'somaOnHold'])->name('soma.held');
Route::get('loans/soma/approved', [SomaLoanController::class,'approved'])->name('soma.approved');
Route::get('loans/soma/late', [SomaLoanController::class,'late'])->name('soma.late');
Route::get('loans/soma/client/index', [SomaLoanController::class,'borrowerIndex'])->name('soma.borrower.index');
Route::post('loans/soma/new', [SomaLoanController::class,'store'])->name('soma.store');
Route::match(['get', 'put'], '/loans/soma/approve/{id}', [SomaLoanController::class,'approveSomaLoan'])->name('soma.approve');
Route::match(['get', 'put'], '/loans/soma/status/{action}/{id}', [SomaLoanController::class,'loanStatusChange'])->name('soma.loan.status');

Route::get('loans/soma/create', [SomaLoanController::class,'create'])->name('soma.create');
Route::post('loans/soma/create/student', [SomaLoanController::class,'storeSomaStudent'])->name('soma.store.student');
Route::get('loans/soma/student/create', [SomaLoanController::class,'createStudent'])->name('soma.create.student');
Route::get('loans/soma/dashboard', [SomaLoanController::class,'somaDashboard'])->name('soma.dashboard');

//business loan routes
Route::get('loans/business/index',[BusinessLoanController::class,'index'])->name('loan.business.index');
Route::get('loans/business/client',[BusinessLoanController::class,'borrowerIndex'])->name('loan.business.client');
Route::get('loans/business/pending',[BusinessLoanController::class,'pending'])->name('loan.business.pending');
Route::get('loans/business/approved',[BusinessLoanController::class,'approved'])->name('loan.business.approved');
Route::get('loans/business/denied',[BusinessLoanController::class,'declined'])->name('loan.business.declined');
Route::get('loans/business/held',[BusinessLoanController::class,'onHold'])->name('loan.business.held');
Route::get('loans/business/over_due',[BusinessLoanController::class,'late'])->name('loan.business.late');
Route::get('loans/business/create',[BusinessLoanController::class,'create'])->name('loan.business.create');
Route::get('loans/business/request/{id?}',[BusinessLoanController::class,'requestLoan'])->name('loan.business.request');
Route::get('loans/business/show/{id}',[BusinessLoanController::class,'show'])->name('loan.business.show');
Route::post('loans/business/store/business',[BusinessLoanController::class,'storeBusiness'])->name('loan.business.loan.store');
Route::post('loans/business/store/loan/{id}',[BusinessLoanController::class,'store'])->name('loan.business.store');
Route::get('loans/business/status/{action}/{id}',[BusinessLoanController::class,'loanStatusChange'])->name('business.loan.status');

// Route::get('loans/business/store/{id}',[BusinessLoanController::class,'store'])->name('loan.business.store');

// withdraws routes
Route::get('withdraws/index',[WithdrawController::class,'index'])->name('withdraws.index');
Route::get('withdraws/create',[WithdrawController::class,'create'])->name('withdraws.create');

// savings routes
Route::get('savings/index',[SavingController::class,'index'])->name('savings.index'); 
Route::get('savings/handle',[SavingController::class,'handleDeposit'])->name('savings.handle'); 


// savings categories
Route::get('savings/categories/index',[SavingCategoryController::class,'index'])->name('savings.category.index');
Route::get('savings/categories/edit/{id}',[SavingCategoryController::class,'update'])->name('savings.category.update');
Route::post('savings/categories/store',[SavingCategoryController::class,'store'])->name('savings.category.store');

//reloadly routes
Route::get('payments/playground',[ReloadlyController::class,'playground'])->name('reloadly.playground');
Route::get('payments/dashboard',[ReloadlyController::class,'dashboard'])->name('reloadly.dashboard');


//airtime routes
Route::get('airtime/index',[AirtimeController::class,'index'])->name('airtime.index');
Route::post('airtime/buy/{id?}',[AirtimeController::class,'buyAirtime'])->name('airtime.buy');
Route::post('airtime/rate/store',[AirtimeController::class,'storeRate'])->name('airtime.rate.store');
Route::put('airtime/rate/update',[AirtimeController::class,'updateRate'])->name('airtime.rate.update');
Route::get('airtime/rate/index',[AirtimeController::class,'indexRates'])->name('airtime.rate.index');
Route::get('airtime/transactions',[AirtimeController::class,'airtimeTransactions'])->name('airtime.transactions');
Route::get('airtime/operators/{iso}',[AirtimeController::class,'getCountryOperators'])->name('airtime.iso.operators');
Route::get('airtime/get/rate/{id}',[AirtimeController::class,'getRate'])->name('airtime.get.rate');

//utility routes
Route::get('utilities/index',[UtilityController::class,'index'])->name('utilities.index');
Route::get('utilities/electricity/rates',[UtilityController::class,'getElectricityRates'])->name('electricity.rate.index');

Route::post('utilities/pay/electricity',[UtilityController::class,'payUtility'])->name('utility.pay.electricity');
Route::post('utilities/rate/electricity/store',[UtilityController::class,'storeElectricityRate'])->name('electricity.rate.store');



//flutterwave routes
Route::post('/make-withdraw',[FlutterwaveController::class,'makeWithdraw'])->name('make.withdraw');
Route::post('/make-deposit',[FlutterwaveController::class,'makeDeposit'])->name('make.deposit');
Route::post('/pay-installment',[FlutterwaveController::class,'payLoanInstallment'])->name('pay.installment');

Route::webhooks('/flutterwave-webhook','flutterwave');
Route::webhooks('/reloadly-webhook','reloadly');

//account mgt routes
Route::post('/account/change/limit',[AccountController::class,'changeLoanLimit'])->name('account.change.limit');
Route::post('/account/blacklist',[AccountController::class,'blacklistUser'])->name('account.blacklist');
Route::post('/account/unblacklist',[AccountController::class,'unBlacklistUser'])->name('account.unblacklist');
Route::post('/account/suspend',[AccountController::class,'suspendUser'])->name('account.suspend');
Route::post('/account/unsuspend',[AccountController::class,'unSuspendUser'])->name('account.unsuspend');
