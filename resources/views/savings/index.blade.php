@extends('layouts.master')
@section('content')
    @include('savings.partials.wizard.cards')
    <div class="row  bg-light">
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
    </div>
    @include('savings.partials.tables.savings_table')
    @include('savings.partials.modals.save_modal')
@endsection