@extends('layouts.master')
@section('content')
    @include('payments.dashboards.partials.wizard.cards')
    <div class="row  bg-light">
        <a href="{{route('airtime.rate.index')}}" class="btn btn-primary col-sm-12 col-md-2 m-auto">
            Airtime Rates
        </a>
        <button data-bs-toggle="modal" data-placement="top" data-bs-target="#create_saving" class="btn btn-primary col-sm-12 col-md-2 m-auto">
            Data Bundles
        </button>
        <button data-bs-toggle="modal" data-placement="top" data-bs-target="#create_saving" class="btn btn-primary col-sm-12 col-md-2 m-auto">
            Water Rates
        </button>
        <a href="{{route('airtime.rate.index')}}" class="btn btn-primary col-sm-12 col-md-2 m-auto">
            Electricity Rates
        </a>
        <button data-bs-toggle="modal" data-placement="top" data-bs-target="#create_saving" class="btn btn-primary col-sm-12 col-md-2 m-auto">
           Pay for  Utilities
        </button>
    </div>

@endsection