<div class="material-datatables">
                    <table id="datatables" class="table table-striped table-no-bordered table-hover" cellspacing="0" width="100%" style="width:100%">
                      <thead>
                        <tr>
                          <th>Due Date</th>
                          <th>Loan. Id</th>
                          <th>Loan Amount</th>
                          <th>Amount Paid</th>
                          <th>Status</th>
                        </tr>
                      </thead>
                      <tfoot>
                        <tr>
                            <th>Due Date</th>
                            <th>Loan. Id</th>
                            <th>Loan Amount</th>
                            <th>Amount Paid</th>
                            <th>Status</th>
                        </tr>
                      </tfoot>
                      <tbody>
                        
                          @foreach ($repayments as $repayment ) 
                           
                            <tr>
                              <td>{{ $repayment->due_date}}</td>
                              <td>{{$repayment->ULoan_Id}}</td>
                              <td>{{$repayment->principal}}</td>
                              <td>{{$repayment->amount_paid}} </td>
                              <td><span class="badge  ">{{ $repayment->status }}</span></td>
                            </tr>
                            @endforeach
                       
                        
                      </tbody>
                    </table>
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