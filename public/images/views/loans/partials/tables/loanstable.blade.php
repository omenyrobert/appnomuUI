<div class="card">
                <div class="card-header card-header-primary card-header-icon">
                  <div class="card-icon">
                    <i class="material-icons">assignment</i>
                  </div>
                  
                  <div class="float-left">
                    <h4 class="card-title">Loans</h4>
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
                          <th>Loan Amount</th>
                          <th>Amount Paid</th>
                          <th>Status</th>
                          <th>Action</th>
                        </tr>
                      </thead>
                      <tfoot>
                        <tr>
                            <th>Due Date</th>
                            <th>Loan. Id</th>
                            <th>Loan Amount</th>
                            <th>Amount Paid</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                      </tfoot>
                      <tbody>
                          @foreach ($loans as $loan ) 
                            <tr>
                              <td>{{$loan->due_date}}</td>
                              <td><a href="{{route('loan.show',['id'=>$loan->id])}}">{{$loan->ULoan_Id}}</a></td>
                              <td>{{$loan->principal}}</td>
                              <td>{{$loan->amount_paid}} </td>
                              @switch($loan->status )
                                @case('Paid')
                                  <td><span class="badge badge-success">{{ $loan->status }}</span></td>
                                  @break
                                @case('Over Due')
                                  <td><span class="badge badge-rose">{{ $loan->status }}</span></td>
                                  @break
                                @case('Requested')
                                  <td><span class="badge badge-warning">{{ $loan->status }}</span></td>
                                  @break
                              @endswitch
                              @if($loan->status == 'Requested')
                              <td>
                                  <select name="action" id="action">
                                      <option value="">Choose Action</option>
                                      @if($user->role == 'admin')
                                        <option value="Approve"><span><a href="">Approve</a></span></option>
                                        <option value="Deny"><span><a href="">Deny</a></span></option>
                                        <option value="On Hold"><span><a href="">Put On Hold</a></span></option>
                                        @else
                                        <option value="Cancel"><span><a href="">Cancel Request</a></span></option>
                                        @endif
                                    </select>
                              </td>
                              @endif
                              @if($loan->status == 'Cancelled')
                              <td><a href="">Cancel Request</a></span></td>
                              @endif
                            </tr>
                            @endforeach
                       
                        
                      </tbody>
                    </table>
                  </div>
                </div>
</div>
                 