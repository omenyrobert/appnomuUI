<div class="card">
                <div class="card-header card-header-primary card-header-icon">
                  <div class="card-icon">
                    <i class="material-icons">assignment</i>
                  </div>
                  
                  <div class="float-left">
                    <h4 class="card-title">Loan Repayments</h4>
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
                          <th>Due Date</th>
                          <th>Loan. Id</th>
                          <th>Installment Amount</th>
                          <th>Amount Paid</th>
                          <th>Status</th>
                          <th>Action</th>
                        </tr>
                      </thead>
                     
                      <tbody>
                        @if(count($repayments)== 0)
                          <tr><h5>You have no upcoming Repaymens</h5></tr>
                        @else
                          @foreach ($repayments as $repayment ) 
                           
                            <tr>
                              <td>{{ $repayment->due_date}}</td>
                              <td>{{$repayment->loan_id}}</td>
                              <td>{{$repayment->amount}}</td>
                              <td>{{$repayment->amount_paid}} </td>
                              @switch($repayment->status )
                                @case('Paid')
                                  <td><span class="btn btn-success">{{ $repayment->status }}</span></td>
                                  @break
                                @case('Over Due')
                                  <td><span class="btn btn-rose">{{ $repayment->status }}</span></td>
                                  @break
                                @case('Pending')
                                  <td><span class="btn btn-warning">{{ $repayment->status }}</span></td>
                                  @break
                              @endswitch
                              <td>
                                @if($repayment->status != 'Paid')
                                  @if($user->role == 'admin')
                                   <a class="btn btn-primary"><span>Mark as Paid</span></a>
                                  @else
                                  <a class="btn btn-success"><span>Pay</span></a>
                                  @endif
                                @endif
                              </td>
                            </tr>
                            @endforeach                  
                        @endif
                      </tbody>
                    </table>
                  </div>
                </div>
</div>


                
                  <!-- <td><span class="badge $badge  ">$loan->status }}</span></td> -->
                  <!-- #Statues 7 paid 6 approved 5 requested 4 overdue 3 Denied 2 Waiting
                            if ($key['status']==7) {
                              # code...
                              $status = 'Paid';
                              $badge = 'badge-success';
                              
                            }elseif ($key['status']==6) {
                              # code...
                              $status = 'Approved';
                              $badge = 'badge-primary';

                            }elseif ($key['status']==5) {
                              # code...
                              $status = 'Requested';
                              $badge = 'badge-secondary';

                            }elseif ($key['status']==4) {
                              # code...
                              $status = 'Overdued';
                              $badge = 'badge-rose';

                            }elseif ($key['status']==3) {
                              # code...
                              $status = 'Denied';
                              $badge = 'badge-danger';

                            }elseif ($key['status']==2) {
                              # code...
                              $status = 'Warning';
                              $badge = 'badge-warning';

                            } -->