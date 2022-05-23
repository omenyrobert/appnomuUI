<?php


namespace App\Http\Traits;

use App\Models\Loan;

trait AccountsOperationsTrait{

    public function accountOperation($operation,$type,$id){
        //operation = credit or debit
        //type = loan,soma,business,saving,transaction
        try {
            //code...
            switch ($operation) {
                case 'credit':
                    switch ($type) {
                        case 'loan':
                            $loan = Loan::findOrFail($id);
                            $account = $loan->account;
                            $account->Loan_Balance = $account->Loan_Balance + $loan->principal;
                            $account->Outstanding_Balance = $account->Outstanding_Balance + $loan->principal;
                            break;
                        
                        default:
                            # code...
                            break;
                    }    
                    break;
                
                default:
                    # code...
                    break;
            }
        } catch (\Throwable $th) {
            //throw $th;
        }

    }

}