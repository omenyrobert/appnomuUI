@extends('layouts.master')
@section('content')
    @include('layouts.partials.stats.cards')
    @include('layouts.partials.stats.cards2')
    
   
    <div class="container-fluid" style="background-color: #294658;">

          <div class="row">

            @include('layouts.partials.stats.loan_over_view')
            @include('layouts.partials.stats.monthly_loan_analysis')
            
          </div>
  
          <div class="row">
     
            @include('layouts.partials.stats.news_letter_chart')
        </div>         
    </div>
@endsection