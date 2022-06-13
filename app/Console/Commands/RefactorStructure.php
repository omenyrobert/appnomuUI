<?php

namespace App\Console\Commands;

use App\Models\Account;
use App\Models\Identification;
use App\Models\Alliance;
use App\Models\Loan;
use App\Models\LoanCategory;
use App\Models\LoanPayment;
use App\Models\Repayment;
use App\Models\Saving;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Withdraw;
use Carbon\Carbon;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Console\Command;

class RefactorStructure extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'refactor:structure';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'this command refactors the database to match the structure of the laravel logic';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('updating identifications table');
        $ident = $this->updateIdentifications();
        if($ident == 'success'){
            $this->info('finished updating identifications table');
        }else{
            $this->error($ident);
        }

        $this->info('updating alliances table');
        $res = $this->updateAlliances();
        if($res == 'success'){
            $this->info('finished updating alliances table');
        }else{
            $this->error($res);
        }

        $this->info('updating accounts table');
        $res = $this->updateAccounts();
        if($res == 'success'){
            $this->info('finished updating accounts table');
        }else{
            $this->error($res);
        }

        $this->info('updating loans table');
        $res = $this->updateLoans();
        if($res == 'success'){
            $this->info('finished updating loans table');
        }else{
            $this->error($res);
        }

        $this->info('updating savings table');
        $res = $this->updateSavings();
        if($res == 'success'){
            $this->info('finished updating savings table');
        }else{
            $this->error($res);
        }

        $this->info('updating repayments table');
        $res = $this->repayments();
        if($res == 'success'){
            $this->info('finished updating repayments table');
        }else{
            $this->error($res);
        }

        $this->reStructureWithdrawsTable();
        $this->reStructureTransactions();
        return 0;
    }

   
    private function reStructureWithdrawsTable(){
        $this->info('fetch all withdraws');
        $withdraws = Withdraw::all();
        $this->info('checking each withdraw');
        foreach($withdraws as $withdraw){
            $this->info("checking withdraw $withdraw->id");
            $user = User::where('user_id',$withdraw->Uuser_id)->first();
            if($user){
                $withdraw->user()->associate($user);
                switch($withdraw->status){
                    case '7':
                        $withdraw->status = 'Successful';
                        break;
                    case '6':
                        $withdraw->status = 'Initiated';
                        break;
                    case '5':
                        $withdraw->status = 'Failed';
                        break;
                }
                $withdraw->save(); 
                $transaction = Transaction::where('Trans_id',$withdraw->trans_id)->first();
                if($transaction){
                    $transaction->transactionable()->associate($withdraw);
                }
                continue;
            }
            $this->info("withdraw $withdraw->id has no registered user");
            $this->error("deleting withdraw $withdraw->id");
            // $withdraw->delete();
        }
        Schema::table('withdraws', function (Blueprint $table) {
            $this->info('trimming withdraws table');
            // $table->dropColumn('Uuser_id');
            // $table->dropColumn('trans_id');
        });
    }

    private function reStructureTransactions(){
        $this->info('re-structuring transactions table');
        $this->info('fetching all transactions');
        $transactions = Transaction::all();
        $this->info('checking each transaction');
        foreach($transactions as $transaction){
            $user = User::where('user_id',$transaction->Uuser_id)->first();
            if($user){
                $transaction->user()->associate($user);
                switch($transaction->status){
                    case "07":
                        $transaction->status = 'Successful';
                        break;
                    case "06":
                        $transaction->status = 'Pending';
                        break;
                    case "05":
                        $transaction->status = 'Failed';
                        break;
                    default:
                        $transaction->status = 'Undefined';
                        break;
                }
                $transaction->save(); 
                // break;

            }
        }
    }
    

    private function updateIdentifications()
    {
        try {
            $identifications = Identification::all();
            foreach($identifications as $identification){
                $user = User::where('user_id',$identification->Uuser_id)->first();
                if($user){
                    $identification->user()->associate($user);
                    $identification->save();
                }else{
                    $identification->delete();
                }
            }
            return 'success';
        } catch (\Throwable $th) {
            //throw $th;
            return $th->getMessage();
        }
        
    }

    private function updateAlliances(){
        try {
            $alliances = Alliance::all();
            foreach($alliances as $alliance){
                $user = User::where('user_id',$alliance->refferer)->first();
                if ($user) {
                    $alliance->user()->associate($user);
                    $alliance->save();
                }else{
                    $alliance->delete();
                }
            }
            return 'success';
        } catch (\Throwable $th) {
            //throw $th;
            return $th->getMessage();
        }
    }

    private function updateAccounts(){
        try {
            $accounts = Account::all();
            foreach($accounts as $account){
                $user = User::where('user_id',$account->Uuser_id)->first();
                if ($user) {
                    $account->user()->associate($user);
                    $account->save();
                }else{
                    $account->delete();
                }
            }
            return 'success';
        } catch (\Throwable $th) {
            //throw $th;
            return $th->getMessage();
        }
    }

    private function updateLoans(){
        try {
            $loans = Loan::all();
            foreach($loans as $loan){
                $user = User::where('user_id',$loan->Uuser_id)->first();
                $cat = LoanCategory::where('loan_amount',$loan->principal)->first();
                if ($user) {
                    $loan->user()->associate($user);
                    $loan->loanCategory()->associate($cat);
                    $loan->account()->associate($user->account);
                    $loan->approved_at = $loan->approved_at_time ? Carbon::createFromTimestamp($loan->approved_at_time) : null;
                    $loan->due_date = $loan->due_date_time ? Carbon::createFromTimestamp($loan->due_date_time) : null;
                    switch ($loan->status_num) {
                        case 7:
                            $loan->status = 'Paid';
                            break;
                        case 6:
                            $loan->status = 'Approved';
                            break;
                        case 5:
                            $loan->status = 'Requested';
                            break;
                        case 4:
                            $loan->status = 'Over Due';
                            break;
                        case 3:
                            $loan->status = 'Denied';
                            break;
                        case 2:
                            $loan->status = 'On Hold';
                            break;
                    }
                    $approver = User::where('user_id',(int)$loan->approver)->first();
                    if($approver){
                        $loan->approved_by = $approver->id;
                    }
                    $loan->save();
                }else{
                    $loan->delete();
                }
            }
            return 'success';
        } catch (\Throwable $th) {
            //throw $th;
            return $th->getMessage();
        }
    }

    private function updateSavings(){
        try {
            $savings = Saving::all();
            foreach($savings as $saving){
                $user = User::where('user_id',$saving->Uuser_id)->first();
                if ($user) {
                    $saving->user()->associate($user);
                    $saving->account()->associate($user->account);
                    switch($saving->status){
                        case '5':
                            $saving->status = 'Initiated';
                            break;
                        case '9':
                            $saving->status = 'Failed';
                            break;
                        case '7':
                            $saving->status = 'Successful';
                            break;
                        case '6':
                            $saving->status = 'Matured';
                            break;
                        case '8':
                            $saving->status = 'Paid';
                            break;
                        default:
                        $saving->status = 'Undefined';
                        break;
                    }
                    $saving->save();
                }else{
                    $saving->delete();
                }
            }
            return 'success';
        } catch (\Throwable $th) {
            //throw $th;
            return $th->getMessage();
        }
    }

    private function repayments(){
        try {
            $installments = LoanPayment::all();
            foreach($installments as $installment){
                $this->info('checking installments');
                $user = User::where('user_id',$installment->Uuser_id)->first();
                if ($user) {
                    $this->info('user found');
                    $this->info($installment->ULoan_Id);
                    $loan = Loan::where('ULoan_Id',$installment->ULoan_Id)->first();
                    if($loan){
                        $this->info('loan found');
                        $payment = new Repayment();
                        $payment->user()->associate($user);
                        $payment->amount = $installment->Amount_Paid;
                        $payment->amount_paid = $installment->Amount_Paid;
                        $payment->repaymentable()->associate($loan);
                        $payment->created_at = $installment->created_at;
                        $payment->updated_at = $installment->updated_at;
                        $payment->due_date = $installment->pay_day ? Carbon::createFromTimestamp($installment->pay_day ): Carbon::now();
                        switch ($installment->status) {
                            case 7:
                                $payment->status = 'Paid';
                                break;
                            case 6:
                                $payment->status = 'Pending';
                                break;
                            case 3:
                                $payment->status = 'Denied';
                                break;
                            case 4:
                                $payment->status = 'Over Due';
                                break;
                            case 2:
                                $payment->status = 'On Hold';
                                break;                              
                            
                           
                        }
                        $payment->loan_id = $installment->ULoan_Id;
                        $payment->save();
                        if($payment){
                            $this->info('payment saved');
                        }else{
                            $this->error('payment not saved');
                        }
                        
                    }else{
                        // return 'loan '.$installment->ULoan_id.' not found';
                        $this->error('loan not found');
                        continue;
                    }
                    
                }else{
                    // $installment->delete();
                    // return 'user '.$installment->Uuser_id.' not found';
                    $this->error('user not found');
                    continue;                    
                }
            }
            return 'success';
        } catch (\Throwable $th) {
            //throw $th;
            // return $th->getMessage();
            $this->error($th->getMessage());
        }
    }

    


}
