@extends('layouts.master')
@section('content')
<div style="background-color: #113c56; border-radius: 20px;" class="row card-group m-3 p-5">
        <h4 class="text-white">Savings Account</h4>
        <br/><br/>
        <div class="row">
        <div class="col-md-4">
        <div class="border border-primary p-3 text-white" style="border-radius: 10px;">
        <h5>Available Balance(matured savings)</h5>
            <p>200,000</p>
        </div>
        </div>
        <div class="col-md-4">
        <div class="border border-primary p-3 text-white" style="border-radius: 10px;">
            <h5>Ledger Balance(unmatured savings)</h5>
            <p>55,000</p>
          </div>
        </div>
        <div class="col-md-4">
        <div class="border border-primary p-3 text-white" style="border-radius: 10px;">
            <h5>Total Savings</h5>
            <p>7,000</p>
        </div>
      </div>
    </div>

    <h4 class="text-white" style="margin-top: 20px;">Loans Account</h4>
        <br/><br/>
        <div class="row">
        <div class="col-md-4">
        <div class="border border-primary p-3 text-white" style="border-radius: 10px;">
        <h5>Withdrawable Balance</h5>
            <p>200,000</p>
        </div>
        </div>
        <div class="col-md-4">
        <div class="border border-primary p-3 text-white" style="border-radius: 10px;">
            <h5>Outstanding Balance</h5>
            <p>55,000</p>
          </div>
        </div>
        <div class="col-md-4">
          <div class="border border-primary p-3 text-white" style="border-radius: 10px;">
            <h5>Total Paid Loans</h5>
            <p>7,000</p>
          </div>
      </div>
      <div class="col-md-4 mt-3">
          <div class="border border-primary p-3 text-white" style="border-radius: 10px;">
            <h5>Loan Limit</h5>
            <p>7,000</p>
          </div>
      </div>
    </div>

    <h4 class="text-white" style="margin-top: 20px;">Payments</h4>
    <div class="row">
    <div class="col-md-4">
          <div class="border border-primary p-3 text-white" style="border-radius: 10px;">
            <h5>Airtime</h5>
            <p>7,000</p>
          </div>
      </div>
      <div class="col-md-4">
          <div class="border border-primary p-3 text-white" style="border-radius: 10px;">
            <h5>Electricity</h5>
            <p>7,000</p>
          </div>
      </div>
    </div>
</div>

@endsection