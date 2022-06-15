@extends('layouts.master')
@section('content')
    <!-- <div class="row  bg-light">
        <button data-bs-toggle="modal" data-placement="top" data-bs-target="#create_saving" class="btn btn-primary col-sm-12 col-md-2 m-auto">
            Deposit To Your Savings
        </button>
        <button data-bs-toggle="modal" data-placement="top" data-bs-target="#create_saving" class="btn btn-primary col-sm-12 col-md-2 m-auto">
            Withdraw From Savings
        </button>
        <button data-bs-toggle="modal" data-placement="top" data-bs-target="#create_saving" class="btn btn-primary col-sm-12 col-md-2 m-auto">
            Send Money
        </button>
        <button data-bs-toggle="modal" data-placement="top" data-bs-target="#create_saving" class="btn btn-primary col-sm-12 col-md-2 m-auto">
            Airtime and Data
        </button>
        <button data-bs-toggle="modal" data-placement="top" data-bs-target="#create_saving" class="btn btn-primary col-sm-12 col-md-2 m-auto">
           Pay for  Utilities
        </button>
    </div> -->
    <div class="row">
        
    </div>
    <div class="row card-group">
        <h4>Savings Account</h4>
        <div class="col-sm-12 col-md-3 card" style="display: inline-block;">
            <h5>Available Balance(matured savings)</h5>
            <p>{{$user->account->available_balance}}</p>
        </div>
        <div class="col-sm-12 col-md-3 card" style="display: inline-block;">
            <h5>Ledger Balance(unmatured savings)</h5>
            <p>{{$user->account->Ledger_Balance}}</p>
        </div>
        <div class="col-sm-12 col-md-3 card" style="display: inline-block ;">
            <h5>Total Savings</h5>
            <p>{{$user->account->Total_Saved}}</p>
        </div>
        <!-- <div class="col-sm-12 col-md-3 card">
            <h5>Available Balance(matured savings)</h5>
            <p>$user->account-></p>
        </div> -->
    </div>

    <div class="row card-group">
        <h4>Loans Account</h4>
        <div class="col-sm-12 col-md-3 card mx-auto">
            <h5>Withdrawable Balance</h5>
            <p>{{$user->account->Loan_Balance}}</p>
        </div>
        <div class="col-sm-12 col-md-3 card">
            <h5>Outstanding Balance</h5>
            <p>{{$user->account->Outstanding_Balance}}</p>
        </div>
        <div class="col-sm-12 col-md-3 card">
            <h5>Total Paid Loans</h5>
            <p>{{$user->account->Total_Paid}}</p>
        </div>
        <div class="col-sm-12 col-md-3 card">
            <h5>Loan Limit</h5>
            <p>{{$user->account->Loan_Limit}}</p>
        </div>
    </div>

    <div class="row card-group">
        <h4>transactions</h4>
        <div class="col-sm-12 col-md-3 card">
            <h5>Airtime</h5>
            <p>{{$user->airtimes()->where('status','successful')->count()}}</p>
        </div>
        <div class="col-sm-12 col-md-3 card">
            <h5>Electricity</h5>
            <p>{{$user->electricities()->where('status','successful')->count()}}</p>
        </div>
        <!-- <div class="col-sm-12 col-md-3 card">
            <h5>Total Paid Loans</h5>
            <p>{{$user->account->Total_Paid}}</p>
        </div>
        <div class="col-sm-12 col-md-3 card">
            <h5>Loan Limit</h5>
            <p>{{$user->account->Loan_Limit}}</p>
        </div> -->
    </div>

@endsection