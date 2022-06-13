@extends('layouts.master')
@section('content')
  <div class="row">
    <div class="col-md-3">
              
      @if($user->role != 'admin')
        @include('loans.partials.forms.loan_request_form')
      @endif
      <div class="card ">
                <div class="card-header card-header-rose card-header-text">
                  <div class="card-text">
                    <h4 class="card-title">Get Loan</h4>
                  </div>
                <!-- </div> -->
                <div class="card-body ">
                <ul class="nav flex-column  bg-light">
                  <li class="nav-item">
                    <a class="nav-link" href="{{route('soma.create')}}">Profile</a>
                  </li>
                    @if($user->role != 'admin')
                    
                    <li class="nav-item">
                      <a class="nav-link" href="{{route('alliance.index',['id'=>$user->id])}}">Alliances</a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link" href="{{route('alliance.index')}}">Guarantors</a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link" href="#">Refferals</a>
                    </li>
                    @endif
                    
                   
                    
                </ul>
                </div>
                <div class="card-body ">
                <ul class="nav flex-column  bg-light">
                   
                    
                    <li class="nav-item">
                      <a class="nav-link" href="{{route('soma.index')}}">Soma Loans</a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link" href="#">Business Loans</a>
                    </li>
                   
                    
                   
                    
                </ul>
                </div>
                <div class="card-body ">
                <ul class="nav flex-column  bg-light">
                    
                    <li class="nav-item">
                      <a class="nav-link" href="{{route('loan.pending')}}">Pending Loans</a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link" href="{{route('loan.approved')}}">Approved Loans</a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link" href="{{route('loan.declined')}}">Declined Loans</a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link" href="{{route('loan.held')}}">Loans On Hold</a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link" href="{{route('loan.late')}}">Over Due Loans</a>
                    </li>
                    
                </ul>
                </div>
              </div>
            </div>
          </div>
            <div class="col-md-9">
              <!-- repayments -->
              <div class="card">
                <div class="card-header card-header-primary card-header-icon">
                  <div class="card-icon">
                    <i class="material-icons">assignment</i>
                  </div>
                  
                  <div class="float-left">
                    <h4 class="card-title">UpComing Repayments</h4>
                  </div>
                </div>
                <div class="card-body">
                  <div class="toolbar">
                    <!--        Here you can write extra buttons/actions for the toolbar              -->
                  </div>
                  
                  @if($repayments)
                    @include('loans.partials.tables.repaymentstable')
                 @endif
                </div>
                <!-- end content-->
              </div>
              <!-- end repayments -->
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

                  @if(!empty($loans))
                    @include('loans.partials.tables.loanstable')
                 @endif
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
      @include('loans.partials.modals.pay_modal')
@endsection