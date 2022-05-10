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
                     
                      <tbody>
                        @if(count($loans) == 0)
                        <tr><h5>You have no Loans</h5></tr>

                        @else
                          @foreach ($loans as $loan ) 
                         
                            <tr>
                              <td>{{$loan->due_date}}</td>
                              <td><a href="{{route('loan.show',['id'=>$loan->id])}}">{{$loan->ULoan_Id}}</a></td>
                              <td>{{$loan->principal}}</td>
                              <td>{{$loan->amount_paid}} </td>
                              @switch($loan->status )
                                @case('Paid')
                                  <td><span class="btn btn-success">{{ $loan->status }}</span></td>
                                  @break
                                @case('Over Due')
                                  <td><span class="btn btn-rose">{{ $loan->status }}</span></td>
                                  @break
                                @case('Requested')
                                  <td><span class="btn btn-warning">{{ $loan->status }}</span></td>
                                  @break
                              @endswitch
                              @if($loan->status == 'Requested')
                              <td>
                              <div class="dropdown">
                                <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                                  Choose Action
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                @if($user->role == 'admin')
                                  <li><a class="dropdown-item"href="{{route('loan.status',['action'=>'Approve','id'=>$loan->id])}}">Approve</a></li>
                                  <li><a class="dropdown-item" href="{{route('loan.status',['action'=>'Deny','id'=>$loan->id])}}">Deny</a></li>
                                  <li><a class="dropdown-item" href="{{route('loan.status',['action'=>'Hold','id'=>$loan->id])}}">Put On Hold</a></li>
                                @else
                                <li><a class="dropdown-item" href="{{route('loan.status',['action'=>'Cancel','id'=>$loan->id])}}">Cancel Request</a></li>
                                @endif
                                </ul>
                              </div>
                                  
                              </td>
                              @endif
                              @if($loan->status == 'Cancelled' && $user->role != 'admin')
                              <td><a href="{{route('loan.status',['action'=>'Re-submit','id'=>$loan->id])}}">Re-submit Request</a></span></td>
                              @endif
                            </tr>
                            @endforeach
                           @endif
  
                        
                      </tbody>
                    </table>
                  </div>
                </div>
</div>
                 