<?php
namespace App\Http\Traits;
use Illuminate\Http\Request;
use App\Models\LoanCategory;
use App\Models\User;

trait LoanTrait{
    public function checkLoanLegibilityStatus(LoanCategory $category,User $user){
        if ($user->NIN ==null || $user->card_no == null || $user->address == null) {
            return redirect()->route('profile')->withErrors(['Errors'=>'Your Profile Is not Set Up fully ']);
        }    
        $outstanding_loan_count = $user->loans()->where('status','Approved')->orWhere('status','Over Due')->count();
        // $loan_cat = AuthenticationController::getLoanByCatID($request['category']);
        
        if ($outstanding_loan_count  > 0) {
            return redirect()->back()->withErrors(['Errors'=>'You Have an Outstanding Loan ']);
        }
        
        if ($user->alliances()->count() < 2) {
            return redirect()->back()->withErrors(['Errors'=>'You Dont Have enough Allianses to qualify For a loan,Add alliance and try again later']);
        }
        if($user->account->Loan_Limit < $category->loan_amount){
            return redirect()->back()->withErrors(['Errors'=>'Your loan limit is '.$user->account->Loan_Limit .'you cannot borrow above your loan limit']);
        }
        if($user->status == 'suspended'){
            return redirect()->back()->withErrors(['Errors'=>'You  have been suspended from Borrowing loans.','message'=>'contact the System admin or wait for your suspension to end']);
        }
        if($user->status == 'blacklisted'){
            return redirect()->route('user.logout')->withErrors(['Errors'=>'You  have been banned from accessing services Appnomu.','message'=>'contact the System Admin to resolve this']);
        }

        return;
    }
}