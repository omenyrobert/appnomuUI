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
                <button class="border-primary col-md-3 p-2" id="linkk">Deposit To Your Savings</button>
                <button class="border-primary col-md-3 p-2" id="linkk">Withdraw From Savings</button>
                <button class="border-primary col-md-3 p-2" id="linkk">Send Money</button>
                <button class="border-primary col-md-3 p-2" id="linkk">Airtime and Data</button>
                <button class="border-primary col-md-3 p-2" id="linkk">Pay for  Utilities</button>
              </div>
              <br/>
              <h5 class="text-white">Loan History</h5>
              <br/>
              <div class="m-2 p-3" style="background-color: #1a4661;">
              <table class="table text-white" style="width: 95%;">
                <thead>
                  <th>Due Date</th>
                  <th>Loan ID</th>
                  <th>Loan Amount</th>
                  <th>Status</th>
                  <th>Action</th>
                </thead>
                <tbody>
                  <tr>
                    <td>20-06-22</td>
                    <td>BLN02</td>
                    <td>200,000</td>
                    <td>Pending</td>
                    <td>View</td>
                  </tr>
                  <tr>
                    <td>20-06-22</td>
                    <td>BLN02</td>
                    <td>200,000</td>
                    <td>Pending</td>
                    <td>View</td>
                  </tr>
                  <tr>
                    <td>20-06-22</td>
                    <td>BLN02</td>
                    <td>200,000</td>
                    <td>Pending</td>
                    <td>View</td>
                  </tr>
                  <tr>
                    <td>20-06-22</td>
                    <td>BLN02</td>
                    <td>200,000</td>
                    <td>Pending</td>
                    <td>View</td>
                  </tr>
                  <tr>
                    <td>20-06-22</td>
                    <td>BLN02</td>
                    <td>200,000</td>
                    <td>Pending</td>
                    <td>View</td>
                  </tr>
                </tbody>
              </table>
            </div>
    @include('accounts.partials.modals.suspend_modal')
    @include('accounts.partials.modals.blacklist_modal')
    @include('accounts.partials.modals.unblacklist_modal')
    @include('accounts.partials.modals.loan_limit_modal')
      @include('business_loans.partials.scripts')
          </div>
   )
@endsection