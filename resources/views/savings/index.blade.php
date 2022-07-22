@extends('layouts.master')
@section('content')
<div class="p-5"  style="height: 100vh;">
<div class="row">
        <button data-bs-toggle="modal" data-placement="top" data-bs-target="#create_saving" class="btn btn-primary col-sm-12 col-md-2 m-auto">
            Deposit To Your Savings
        </button>
        <button data-bs-toggle="modal" data-placement="top" data-bs-target="#withdraw_saving" class="btn btn-primary col-sm-12 col-md-2 m-auto">
            Withdraw From Savings
        </button>
        <button data-bs-toggle="modal" data-placement="top" data-bs-target="#send_saving" class="btn btn-primary col-sm-12 col-md-2 m-auto">
            Send Money
        </button>
        <button data-bs-toggle="modal" data-placement="top" data-bs-target="#airtime_saving" class="btn btn-primary col-sm-12 col-md-2 m-auto">
            Airtime and Data
        </button>
        <button data-bs-toggle="modal" data-placement="top" data-bs-target="#utility_saving" class="btn btn-primary col-sm-12 col-md-2 m-auto">
           Pay for  Utilities
        </button>
    </div>
    <br/><br/>
    @include('savings.partials.tables.savings_table')
    @include('savings.partials.modals.save_modal')
    @include('savings.partials.modals.withdraw_modal')
    @include('savings.partials.modals.sendmoney_modal')
    @include('savings.partials.modals.utilities_modal')
    @include('savings.partials.modals.airtime_data')
</div>


@endsection