@extends('layouts.master')
@section('content')
    <div class="card col-sm-12 col-md-3 " style="display:inline-block; height:150px; width: 200px;">
            <h5>Electricity</h5>
            <button class="btn btn-warning" data-placement="top" data-bs-toggle="modal" data-bs-target="#pay_electricity">
                Pay Electricity Bill
            </button>
    </div>
    <div class="card col-sm-12 col-md-3"style="display:inline-block; height:150px; width: 200px;">
            <h5>Water</h5>
            <p>
                COMING SOON
            </p>
            <button class="btn btn-warning" data-placement="top" data-bs-toggle="modal" data-bs-target="#pay_electricity" hidden>
                Pay Electricity Bill
            </button>
            </div>
    <div class="card col-sm-12 col-md-3"style="display:inline-block; height:150px;">
            <h5>TV</h5>
            <P>
            COMING SOON
            </P>
            <button class="btn btn-warning" data-placement="top" data-bs-toggle="modal" data-bs-target="#pay_electricity"hidden>
                Pay Electricity Bill
            </button>
            </div>
    <div class="card col-sm-12 col-md-3"style="display:inline-block; height:150px;">
            <h5>Internet Data</h5>
            <P>
            COMING SOON
            </P>
            <button class="btn btn-warning" data-placement="top" data-bs-toggle="modal" data-bs-target="#pay_electricity"hidden>
                Pay Electricity Bill
            </button>
    </div>
    @include('payments.utilities.partials.modals.pay_electricity_modal')
@endsection