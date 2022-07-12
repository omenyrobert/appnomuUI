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
          </div>

      @include('business_loans.partials.scripts')
@endsection