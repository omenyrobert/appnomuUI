<style>
  #linkk{
    border-radius: 10px; margin: 5px; background-color: #113c56; color: #fff;
  }
</style>

@extends('layouts.master')
@section('content')
            @include('business_loans.partials.wizard.cards2')
            <div class="p-4 m-4" style="background-color: #113c56; border-radius: 15px;">
            <div class="row">
                <button class="border-primary col-md-3 p-2" id="linkk">Apply for Soma Loan</button>
                <button class="border-primary col-md-3 p-2" id="linkk">Request A Business Loan</button>
                <button class="border-primary col-md-2 p-2" id="linkk">Businesses</button>
                <button class="border-primary col-md-2 p-2" id="linkk">Pending Soma Loans</button>
                <button class="border-primary col-md-3 p-2" id="linkk">Approved Soma Loans</button>
                <button class="border-primary col-md-3 p-2" id="linkk">Declined Soma Loans</button>
                <button class="border-primary col-md-3 p-2" id="linkk">Soma Loans On Hold</button>
                <button class="border-primary col-md-2 p-2" id="linkk">Late Soma Loans</button>
              </div>
              @include('business_loans.partials.wizard.loan_table')
            </div>

      @include('business_loans.partials.scripts')
@endsection