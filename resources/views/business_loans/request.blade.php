@extends('layouts.master')
@section('content')
    @include('business_loans.partials.wizard.cards')
    <div class="p-5 bg-white card">
        <form  class="row g-3 " method="post" action="{{route('loan.business.loan.store')}}"> 
            @csrf
            <div class="row">
                  @include('business_loans.partials.wizard.business_details') 
            </div>

            <div class="row">
                  @include('business_loans.partials.wizard.loan_details') 
            </div>
             <div class="row">   
            <div>
                <button  type="submit"  class="btn btn-success" class="form-control">
                    Request Loan
                </button>
            </div>
                   <span>By Clicking button, u agree to the appnomu <a href="" title="read terms and conditions">terms and conditions</a></span>
            </div> 
        </form>
    </div>
@endsection