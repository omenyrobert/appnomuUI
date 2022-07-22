<style>
  #linkk{
    border-radius: 10px; margin: 5px; background-color: #113c56; color: #fff;
  }
</style>
@extends('layouts.master')
@section('content')
            <div class="p-4 m-4" style="background-color: #113c56; border-radius: 15px;">
              <br/>
              <h5 class="text-white">Users Account</h5>
              <br/>
              <div class="m-2 p-3" style="background-color: #1a4661;">
              <table class="table text-white" style="width: 95%;">
                <thead>
                  <th>Creation Date</th>
                  <th>User ID</th>
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
                    <td>Suspend | blacklist</td>
                  </tr>
                  <tr>
                    <td>20-06-22</td>
                    <td>BLN02</td>
                    <td>200,000</td>
                    <td>Pending</td>
                    <td>Suspend | blacklist</td>
                  </tr>
                  <tr>
                    <td>20-06-22</td>
                    <td>BLN02</td>
                    <td>200,000</td>
                    <td>Pending</td>
                    <td>Suspend | blacklist</td>
                  </tr>
                  <tr>
                    <td>20-06-22</td>
                    <td>BLN02</td>
                    <td>200,000</td>
                    <td>Pending</td>
                    <td>Suspend | blacklist</td>
                  </tr>
                  <tr>
                    <td>20-06-22</td>
                    <td>BLN02</td>
                    <td>200,000</td>
                    <td>Pending</td>
                    <td>Suspend | blacklist</td>
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