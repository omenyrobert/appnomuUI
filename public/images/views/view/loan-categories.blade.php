@extends('layouts.header')
@section('content')

          <div class="row">
          <div class="col-md-4">
              <div class="card ">
                <div class="card-header card-header-rose card-header-text">
                  <div class="card-text">
                    <h4 class="card-title">New Category</h4>
                  </div>
                </div>
                <div class="card-body ">
                  <form method="post" action="/save_loan_category" class="form-horizontal">
                    @csrf
                    <div class="row">
                      <label class="col-sm-4 col-form-label">Loan Amount</label>
                      <div class="col-sm-8">
                        <div class="form-group">
                          <input type="number" name="amount" class="form-control">
                          <span class="bmd-help">Enter the actual loan Amount</span>
                        </div>
                      </div>
                      
                    </div>
                    <div class="row">
                      <label class="col-sm-4 col-form-label">Loan Period</label>
                      <div class="col-sm-8">
                        <div class="form-group">
                          <input type="number" name="period" class="form-control">
                          <span class="bmd-help">All Periods are in Days (1 week = 7 Days)</span>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <label class="col-sm-4 col-form-label">Interest Rate</label>
                      <div class="col-sm-8">
                        <div class="form-group">
                          <input type="number" name="interest" class="form-control">
                          <span class="bmd-help">Enter the valid loan Interest Rate</span>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <label class="col-sm-4 col-form-label">Installments</label>
                      <div class="col-sm-8">
                        <div class="form-group">
                          <input type="number" name="installments" class="form-control">
                          <span class="bmd-help">Enter the valid Payment Installments</span>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <label class="col-sm-4 col-form-label">Processing Fee</label>
                      <div class="col-sm-8">
                        <div class="form-group">
                          <input type="number" name="processing" class="form-control">
                          <span class="bmd-help">Enter the valid loan Processing Fee</span>
                        </div>
                      </div>
                    </div>
                    <button type="submit" class="btn btn-rose">Save Category</button>
                  </form>
                </div>
              </div>
            </div>
            <div class="col-md-8">
              <div class="card">
                <div class="card-header card-header-primary card-header-icon">
                  <div class="card-icon">
                    <i class="material-icons">assignment</i>
                  </div>
                  
                  <div class="float-left">
                    <h4 class="card-title">Loan Categories</h4>
                  </div>
                </div>
                <div class="card-body">
                  <div class="toolbar">
                    <!--        Here you can write extra buttons/actions for the toolbar              -->
                  </div>
                  <div class="material-datatables">
                    <table id="datatables" class="table table-striped table-no-bordered table-hover" cellspacing="0" width="100%" style="width:100%">
                      <thead>
                        <tr>
                          <!-- <th>Category Id</th> -->
                          <th>Amount</th>
                          <th>Interest</th>
                          <th>Processing</th>
                          <th>Period</th>
                          <th>Status</th>
                        </tr>
                      </thead>
                      <tfoot>
                        <tr>
                            <!-- <th>Category Id</th> -->
                            <th>Amount</th>
                            <th>Interest</th>
                            <th>Processing</th>
                            <th>Period</th>
                            <th>Status</th>
                        </tr>
                      </tfoot>
                      <tbody>
                        <?php
                            
                            foreach ($categories as $category){
                              # code...

                              if ($category->loan_period<7) {
                                $mea = 'Days';
                                $value = $category->loan_period;
                              }elseif ($category->loan_period>6 && $category->loan_period<30) {
                                $mea = 'Weeks';
                                $mea2 = 'Days';
                                $value = intdiv($category->loan_period,7);
                                $remainder = $category->loan_period % 7;
                              }elseif ($category->loan_period>29 && $category->loan_period<365) {
                                $mea = 'Months';
                                $mea2 = 'days';
                                $value = intdiv($category->loan_period,30);
                                $remainder = $category->loan_period % 30;
                              }else {
                                $mea = 'Years';
                                $mea2 = 'days';
                                $value = intdiv($category->loan_period,365);
                                $remainder = $category->loan_period % 365;
                              }

                              if ($category->status==7) {
                                # code...
                                $status = 'Active';
                                $but = 'badge-success';
                                $route = 'deactivate';
                              }elseif ($category->status==5) {
                                # code...
                                $status = 'Not Active';
                                $but = 'badge-danger';
                                $route = 'activate';
                              }else {
                                # code...
                                $status = 'Deleted';
                                $but = 'badge-danger';
                                
                              }
                            
                        ?>
                        <tr>
                          <!-- <td>CAT001</td> -->
                          <td>{{$category->loan_amount}}</td>
                          <td>{{$category->interest_rate}}%</td>
                          <td>{{$category->processing_fees}}</td>
                          <td>{{ $value }} {{ $mea ?? ' '}} {{ $remainder ?? ' '}} {{ $mea2 ?? ' ' }}</td>
                          <td><span class="badge {{$but}}">{{$status}}</span></td>
                        </tr>
                        <?php
                            }
                        ?>
                      </tbody>
                    </table>
                  </div>
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