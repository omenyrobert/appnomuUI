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
                              <option value="{{$key->id}} ">{{$key['loan_amount']}} - {{$key['loan_period']}} @ {{$key['interest_rate']}}% </option>
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
            <!-- </div> -->