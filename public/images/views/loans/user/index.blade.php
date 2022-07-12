@extends('layouts.header')
@section('content')

          <div class="row">
          <div class="col-md-5">
              <div class="card ">
                <div class="card-header card-header-rose card-header-text">
                  <div class="card-text">
                    <h4 class="card-title">Get Loan</h4>
                  </div>
                </div>
                <div class="card-body ">
                  <form method="post" action="/request_loan" class="form-horizontal">
                  @csrf
                    <div class="row">
                      <label class="col-sm-4 col-form-label">Select Loan Type</label>
                      <div class="col-sm-8">
                        <div class="form-group">
                          <select name="category" id="" class="form-control">
                              <option value="">Select Loan Type</option>
                              <?php

                                foreach ($categories as $key) {
                                  # code...
                                
                              ?>
                              <option value="{{$key['loan_id']}} ">{{$key['loan_amount']}} - {{$key['loan_period']}} @ {{$key['interest_rate']}}% </option>
                              <?php
                                }
                              ?>
                          </select>
                        </div>
                      </div>
                    </div>
                    <button type="submit" class="btn btn-rose">Request A Loan</button>
                  </form>
                </div>
              </div>
            </div>
            <div class="col-md-7">
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
                 @include('loans.partials.tables.loanstable')
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