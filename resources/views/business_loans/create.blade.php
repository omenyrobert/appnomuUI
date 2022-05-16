@extends('layouts.master')
@section('content')
            @include('business_loans.partials.wizard.cards')
            <div class="row">
        <a href="{{route('loan.business.request')}}" class="btn btn-primary col-sm-6 col-md-4">Request With A new Business</a>
    </div>
            @include('business_loans.partials.tables.businesses_table')

@endsection