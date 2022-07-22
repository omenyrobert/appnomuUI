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
              <div class="col-md-4">
                <label class="text-white">Students Name</label>
                 <input type="text" class="form-control border-primary mt-1" style="background-color: #113c56;" placeholder="Enter Business name">
              </div>
              <div class="col-md-4">
                <label class="text-white">School's Name</label>
                 <input type="text" class="form-control border-primary mt-1" style="background-color: #113c56;" placeholder="Enter Business name">
              </div>
              <div class="col-md-4">
                <label class="text-white">Class</label>
                 <input type="text" class="form-control border-primary mt-1" style="background-color: #113c56;" placeholder="Enter Business name">
              </div>
              <div class="col-md-4">
                <label class="text-white">School Reports</label>
                 <input type="text" class="form-control border-primary mt-1" style="background-color: #113c56;" placeholder="Enter Business name">
              </div>
              <div class="col-md-4">
                <label class="text-white">First Parent</label>
                 <input type="text" class="form-control border-primary mt-1" style="background-color: #113c56;" placeholder="Enter Business name">
              </div>
              <div class="col-md-4">
                <label class="text-white">Second Parent</label>
                 <input type="text" class="form-control border-primary mt-1" style="background-color: #113c56;" placeholder="Enter Business name">
              </div>
              <div class="col-md-4">
                <label class="text-white">Term</label>
                 <input type="text" class="form-control border-primary mt-1" style="background-color: #113c56;" placeholder="Enter Business name">
              </div>
              <div class="col-md-4">
               <button class="btn btn-primary form-control mt-4">Submit</button>
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
  
      @include('business_loans.partials.scripts')
@endsection