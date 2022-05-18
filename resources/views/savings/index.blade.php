@extends('layouts.master')
@section('content')
    <div class="row card bg-light">
        <button data-bs-toggle="modal" data-placement="top" data-bs-target="#create_saving" class="btn btn-primary col-sm-12 col-md-4">
            Make A Saving
        </button>
    </div>
    @include('savings.partials.tables.savings_table')
    @include('savings.partials.modals.save_modal')
@endsection