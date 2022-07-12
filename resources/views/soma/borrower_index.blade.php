@extends('layouts.master')
@section('content')
<div class="row">
@include('soma.partials.wizard.cards')     
</div>
<div class="row">
    <a href="{{route('soma.create')}}" class="btn btn-primary col-md-4" >Request A Loan</a>
</div>
    <div class="row">
       <div class="col-md-6">
           <h5>Soma Loans</h5>
           @include('soma.partials.tables.somatable')  
       </div>
       <div class="col-md-6">
           <h5>UpComing repayments</h5>
           @include('soma.partials.tables.repaymentstable')  
       </div>
    </div>
    @include('soma.partials.scripts')  
@endsection