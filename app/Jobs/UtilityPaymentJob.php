<?php

namespace App\Jobs;

use App\Http\Traits\AccountsOperationsTrait;
use Illuminate\Bus\Queueable;
use App\Http\Traits\UtilityTrait;
use App\Models\Electricity;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Support\Facades\Auth;

class UtilityPaymentJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels,UtilityTrait, AccountsOperationsTrait;

    public $id;
    public $account;
    public $model;
    public $source;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($id,$account,$model,$source)
    {
        $this->id = $id;
        $this->account = $account;
        $this->model = $model;
        $this->source = $source;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $details = $this->getUtilityTransaction($this->id);
        $details = json_decode($details,true);
        for($i=0;($details['status'] != 'SUCCESSFUL' || $details['status'] != 'FAILED') && $i<10;$i++ ){
            sleep(5);
            $details = $this->getUtilityTransaction($this->id);
            $details = json_decode($details,true);
        }
        
        $payment = new Payment();
        $payment->paymentable()->associate($this->model);
        $payment->amount = $this->model->amount;
        $payment->source = $this->source;

        if($details['status'] == 'SUCCESSFUL'){
            $payment->status = 'Successful';
            $payment->save();
            $account = $this->accountOperation('debit','payment',$payment->id);
            return;            
        }
        $payment->status = 'Successful';
        $payment->save();
        return;
        //todo:return failure notification
    }
}
