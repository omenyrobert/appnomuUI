@extends('layouts.master')
@section('content')
    @include('layouts.partials.stats.cards')
    @include('layouts.partials.stats.cards2')
    
   
    <div class="container-fluid">
          <!-- Row -->
          <div class="row">
            <!-- Column -->
            @include('layouts.partials.stats.loan_over_view')
            @include('layouts.partials.stats.monthly_loan_analysis')
            
          </div>
          <!-- Row -->
          <!-- Row -->
          <div class="row">
            <!-- Column -->
            @include('layouts.partials.stats.news_letter_chart')
        </div>
        <div class="row">
        @include('layouts.partials.stats.top_paid_loans')
        </div>
         
    </div>
@endsection