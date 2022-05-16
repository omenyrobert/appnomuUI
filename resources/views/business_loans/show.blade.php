@extends('layouts.master')
@section('content')
            @include('business_loans.partials.wizard.cards')
          <div class="row">
          <div class="col-md-3">
              <div class="card bg-dark">
                
              </div>
              <div class="card ">
                <div class="card-header card-header-rose card-header-text">
                  <div class="card-text">
                    <!-- <h4 class="card-title">Get Loan</h4> -->
                  </div>
                </div>
                <div class="card-body ">
                <ul class="nav flex-column  bg-light">
                    @if($user->role != 'admin')
                    <li class="nav-item">
                      <a class="nav-link" href="{{route('soma.create')}}">Apply for Soma Loan</a>
                    </li>
                    @endif
                    
                    <li class="nav-item">
                      <a class="nav-link" href="{{route('loan.business.create')}}">Request A Business Loan</a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link" href="#">Businesses</a>
                    </li>
                    
                   
                    
                </ul>
                </div>
                <div class="card-body ">
                <ul class="nav flex-column  bg-light">
                    
                    <li class="nav-item">
                      <a class="nav-link" href="{{route('soma.pending')}}">Pending Soma Loans</a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link" href="{{route('soma.approved')}}">Approved Soma Loans</a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link" href="{{route('soma.declined')}}">Declined Soma Loans</a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link" href="{{route('soma.held')}}">Soma Loans On Hold</a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link" href="{{route('soma.late')}}">Late Soma Loans</a>
                    </li>
                    
                </ul>
                </div>
              </div>
            </div>
            <div class="col-md-9">
              <div class="card">
                <div class="card-header card-header-primary card-header-icon">
                  <div class="card-icon">
                    <i class="material-icons">assignment</i>
                  </div>
                  
                  <div class="float-left">
                    <h4 class="card-title">My Loan History</h4>
                  </div>
                </div>
                <div class="card-body">
                  <div class="toolbar">
                    <!--        Here you can write extra buttons/actions for the toolbar              -->
                  </div>
                 
                    @include('business_loans.partials.wizard.loan_table')
                
                </div>
                <!-- end content-->
              </div>
              <!--  end card  -->
            </div>
            <!-- end col-md-12 -->
          </div>
          <!-- end row -->
        </div>
      </div>
@endsection