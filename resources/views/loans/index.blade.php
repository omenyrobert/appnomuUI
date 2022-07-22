@extends('layouts.master')
@section('content')
<div class="container-fluid" style="background-color: #1a4661; padding: 20px;">


  <div class="m-3 p-3" style="background-color: #04273d; border-radius: 15px;">
  <!-- <div class="row p-2">
     <button class="col-md-2 border-primary p-2 m-1 text-white" style="background-color: #202A44; border-radius: 7px;">
     Alliances
     </button>
     <button class="col-md-2 border-primary p-2 m-1 text-white" style="background-color: #202A44; border-radius: 7px;">
     Guarantors
</button>
<button class="col-md-2 border-primary p-2 m-1 text-white" style="background-color: #202A44; border-radius: 7px;">
Refferals
</button>
<button class="col-md-2 border-primary p-2 m-1 text-white" style="background-color: #202A44; border-radius: 7px;">
Soma Loans
</button>
<button class="col-md-2 border-primary p-2 m-1 text-white" style="background-color: #202A44; border-radius: 7px;">
Business Loans
</button>
<button class="col-md-2 border-primary p-2 m-1 text-white" style="background-color: #202A44; border-radius: 7px;">
Pending Loans
</button>
<button class="col-md-2 border-primary p-2 m-1 text-white" style="background-color: #202A44; border-radius: 7px;">
Approved Loans
</button>
<button class="col-md-2 border-primary p-2 m-1 text-white" style="background-color: #202A44; border-radius: 7px;">
Declined Loans
</button>
<button class="col-md-2 border-primary p-2 m-1 text-white" style="background-color: #202A44; border-radius: 7px;">
Loans On Hold
</button>
<button class="col-md-2 border-primary p-2 m-1 text-white" style="background-color: #202A44; border-radius: 7px;">
Over Due Loans
</button>
  </div> -->
  <div class="row mt-4">
    <h4>Apply for a loan</h4>
              <div class="col-md-4">
                <label class="text-white">Amount</label>
                 <input type="text" class="form-control border-primary mt-1" style="background-color: #113c56;" placeholder="Enter Amount">
              </div>
              <div class="col-md-4">
                <label class="text-white">Category</label>
                 <input type="text" class="form-control border-primary mt-1" style="background-color: #113c56;" placeholder="Choose the loan">
                 <br/>
                 <button class="btn btn-primary mt-3 form-control">Submit</button>
                </div>
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
            <!-- end col-md-12 -->
          </div>
          <!-- end row -->
        </div>
      </div>
</div>
      @include('loans.partials.modals.pay_modal')

      @endsection