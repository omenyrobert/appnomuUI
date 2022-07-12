@extends('layouts.master')
@section('content')
<div class="p-5 bg-white card">
    <form  class="row g-3 " method="post" action="{{route('soma.store.student')}}"> 
        @csrf
        @include('soma.partials.wizard.student_details')
        <div class="row">
              @include('soma.partials.wizard.school_details') 
        </div>

        <div class="row">
              @include('soma.partials.wizard.applicant_details') 
        </div>

        <div class="row">
              @include('soma.partials.wizard.loan_details') 
        </div>
         <div class="row">   
        <div>
            <button  type="submit"  class="btn btn-success" class="form-control">
                Request Loan
            </button>
        </div>
               <span>By Clicking button, u agree to the appnomu terms and conditions</span>
        </div> 
    </form>
</div>
@endsection