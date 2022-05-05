<div class="material-datatables">
                    <table id="datatables" class="table table-striped table-no-bordered table-hover" cellspacing="0" width="100%" style="width:100%">
                      <thead>
                        <tr>
                          <th>Due Date</th>
                          <th>Loan. Id</th>
                          <th>Loan Amount</th>
                          <th>Amount Paid</th>
                          <th>Status</th>
                          @if($user->role == 'admin')
                            <th>Action</th>
                            @endif
                        </tr>
                      </thead>
                      <tfoot>
                        <tr>
                            <th>Due Date</th>
                            <th>Loan. Id</th>
                            <th>Loan Amount</th>
                            <th>Amount Paid</th>
                            <th>Status</th>
                            @if($user->role == 'admin')
                            <th>Action</th>
                            @endif
                        </tr>
                      </tfoot>
                      <tbody>
                        
                          @foreach ($loans as $loan ) 
                           
                            <tr>
                              <td>{{ $loan->due_date}}</td>
                              <td>{{$loan->SLN_Id}}</td>
                              <td>{{$loan->principal}}</td>
                              <td>{{$loan->amount_paid}} </td>
                              <td><span class="badge  ">{{$loan->status}}</span></td>
                              @if($user->role == 'admin')
                            <td>
                              <span><a href="">approve</a></span>
                              <span><a href="">mark as paid</a></span>

                            </td>
                            @endif
                            </tr>
                            @endforeach
                       
                        
                      </tbody>
                    </table>
                  </div>
                 