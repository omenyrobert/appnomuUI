<?php

namespace App\Jobs;

use App\Models\Transaction;
use App\Http\Traits\FlutterwaveTrait;
use App\Http\Traits\AccountsOperationsTrait;
use App\Models\Repayment;
use Spatie\WebhookClient\Jobs\ProcessWebhookJob as SpatieProcessWebhookJob;

class FlutterwaveWebhookResponse extends SpatieProcessWebhookJob
{
    use FlutterwaveTrait, AccountsOperationsTrait;
    public function handle()
    {
        // $this->webhookCall // contains an instance of `WebhookCall`
        logger('webhook cameback');
        $response = json_decode($this->webhookCall,true);
        if($response['event'] == 'charge.completed'){
            $refference =$response['data']['tx_ref'];
            $flw_id = $response['data']['id'];
            $transaction = Transaction::where('Trans_id',$refference)->first();
            if($transaction){
                $verification = $this->verifyTransaction($flw_id);
                $verification = json_decode($verification,true);
                if($transaction->status == 'Successful'){
                    return;
                }
                if($verification['data']['status'] == 'success'
                     && $verification['data']['tx_ref'] == $transaction->Trans_id
                     && $verification['data']['currency'] == $transaction->currency){
                        if($transaction->status != 'Successful'){
                            $transaction->status = 'Successful';
                            $transaction->FLW_Id = $flw_id;
                            $transaction->mode = $verification['data']['auth_model'];
                            $transaction->flw_charge = $verification['data']['app_fee'];
                            $transaction->save();
                            switch ($verification['data']['meta']['model']) {
                                case 'saving':
                                    $saving = $transaction->transactionable;
                                    $saving->status = $transaction->status;
                                    $saving->save();
                                    $this->accountOperation('credit','savings',$saving->id);
                                    break;
                                
                                case 'repayment':
                                    $repayment = $transaction->transactionable;
                                    if($repayment->amount == $verification['data']['amount']){
                                        $repayment->status = 'Paid';
                                        $repayment->amount_paid = $verification['data']['amount'];
                                    }
                                    if($repayment->amount < $verification['data']['amount']){
                                        $repayment->status = 'Paid';
                                        $repayment->amount_paid = $repayment->amount;
                                        $account = $repayment->user->account;
                                        $account->available_balance += $verification['data']['amount'] - $repayment->amount;
                                        $account->save();
                                    }
                                    if($repayment->amount < $verification['data']['amount']){
                                        $repayment->amount_paid = $repayment->amount;
                                    }
                                    $repayment->amount_paid = $verification['data']['amount'];
                                    $repayment->save();
                                    $loan = $repayment->repaymentable;
                                    $loan->amount_paid = $loan->amount_paid + $repayment->amount_paid;
                                    if($loan->amount_paid >= $loan->payment_amount){
                                        $loan->status = 'Paid';
                                    }
                                    $loan->save();
                                    $this->accountOperation('credit','repayment',$repayment->id);
                                    break;
                            }
                        
                        } 

                }
            }

        }
    }
}