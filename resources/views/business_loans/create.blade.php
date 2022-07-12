@extends('layouts.master')
@section('content')
            @include('business_loans.partials.wizard.cards2')
            <div class="row ">
        <button style="margin-left: 50px; border-radius: 10px; background-color: #113c56;" class="border-primary p-2 text-white mt-5 col-md-4">Request With A new Business</button>
    </div>
            @include('business_loans.partials.tables.businesses_table')
            @include('business_loans.partials.scripts')
@endsection