@extends('layouts.master')
@section('content')
    <div class="row">
    @include('soma.partials.wizard.cards')     
    </div>
    <div class="row">
    <a href="{{route('soma.create.student')}}" class="btn btn-primary col-md-4" >Create New Student</a>
    <a href="{{route('soma.create')}}" class="btn btn-primary col-md-4" >How to Applay</a>
</div>
<div class="row">
  @include('students.partials.tables.studenttable')
</div>
<div class="border border-success">
<hr>
 <!-- <form id="wizard-vertical" method="POST"
        action="{{ route('soma.store') }}"
                       enctype="multipart/form-data">
  @csrf -->
  
  <h4>Soma Loan Application</h4>
  @if(count($students) != 0)
      <h5>Choose Student for which to apply for</h5>
      <div class="dropdown">
      <a class="btn btn-secondary dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-expanded="false">
        Students
      </a>
      <ul class="dropdown-menu" aria-labelledby="dropdownMenuLink">
        @foreach($students as $student)
          <li><a class="dropdown-item" href="#">{{$student->fname}} {{$student->lname}}</a></li>
        @endforeach
      </ul>
      div>
  @endif

  <!-- @include('soma.partials.wizard.student_details')
  @include('soma.partials.wizard.school_details')
  @include('soma.partials.wizard.applicant_details')
  @include('soma.partials.wizard.loan_details')  -->
<!-- </form> -->
@include('soma.partials.scripts.create_scripts')

@endsection