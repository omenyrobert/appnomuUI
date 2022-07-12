@extends('layouts.master')
@section('content')
    @include('payments.airtime.partials.tables.airtime_rates_table')
    @include('payments.airtime.partials.modals.airtime_rate_modal')

@endsection