<div class="card">
                <div class="card-header card-header-primary card-header-icon">
                  <div class="card-icon">
                    <i class="material-icons">assignment</i>
                  </div>
                  
                  <div class="float-left">
                    <h4 class="card-title">Loan Details</h4>
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
                              <td>
                              <div class="dropdown">
                                <button class="btn btn-rose dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Select Action
                                </button>
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                  @if($loan->status == 'Requested')
                                  @if($user->role == 'admin')
                                    <a class="dropdown-item" href="{{route('loan.status',['action'=>'Approve','id'=>$loan->id])}}">Approve Loan</a>
                                    
                                    <a class="dropdown-item" href="{{route('loan.status',['action'=>'Deny','id'=>$loan->id])}}">Deny Loan</a>
                                  @endif
                                  @if($user->role != 'admin')
                                    <a class="dropdown-item" href="{{route('loan.status',['action'=>'Cancel','id'=>$loan->id])}}">Cancel Request</a>
                                  @endif
                                  @endif
                                  @if($loan->status == 'Cancelled')
                                  <a class="dropdown-item" href="{{route('loan.status',['action'=>'Re-Submit','id'=>$loan->id])}}">Cancel Request</a>
                                  @endif
                                    <a class="dropdown-item" href="{{route('loan.show',['id'=>$loan->id])}}">View Loan</a>
                                </div>
                            </div>
                              </td>
                            </tr>
                            
                       
                        
                      </tbody>
                    </table>
                  </div>
                </div>
</div>
                 