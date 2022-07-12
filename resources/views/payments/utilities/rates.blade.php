@extends('layouts.master')
@section('content')
    @include('payments.utilities.partials.tables.electricity_rates_table')
    @include('payments.utilities.partials.modals.electricity_rate_modal')

@endsection