<div class="container bg-white">
            <h5>Businesses</h5>
            <table class="table table-striped table-hover">
              <thead>
                <th>Business_Id</th>
                <th>Business Name</th>
                <th>Business Owner</th>
                <th>District</th>
                <th>Location</th>
                <th>Business Loan Status</th>
                <th>Action</th>
              </thead>
              <tbody>
                @if(count($businesses) == 0)
                <tr><td>No Business Registered In Your Account Yet. Register A Business to Qualify to borrow an appnomu Business Loan</td></tr>
                @else
                @foreach($businesses as $business)
                <tr>
                  <td><a href="">{{$business->Biz_id}}</a></td>
                  <td><a href="">{{$business->name}}</a> </td>
                  <td><a href="">{{$business->user->name}}</a></td>
                  <td>{{$business->district->name}}</td>
                  <td>{{$business->location}}</td>
                  <td>{{$business->loans()->latest()->first()->status}}</td>
                  <td><span class="btn btn-success">Approved</span>
                  <td>
                    <!-- <a href="" title="delete">
                  <svg xmlns="http://www.w3.org/2000/svg"  width="16" height="16" fill="currentColor" class="bi bi-trash-fill text-danger" viewBox="0 0 16 16">
                    <path d="M2.5 1a1 1 0 0 0-1 1v1a1 1 0 0 0 1 1H3v9a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V4h.5a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H10a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1H2.5zm3 4a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 .5-.5zM8 5a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7A.5.5 0 0 1 8 5zm3 .5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 1 0z"/>
                  </svg>
                  </a> -->
                  @if($user->role != 'admin' && count($business->loans()->where('status','Approved')->orWhere('status','Over Due')->get() == 0))
                  <a href="" title="Edit Business details">
                  <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-fill text-warning" viewBox="0 0 16 16">
                    <path d="M12.854.146a.5.5 0 0 0-.707 0L10.5 1.793 14.207 5.5l1.647-1.646a.5.5 0 0 0 0-.708l-3-3zm.646 6.061L9.793 2.5 3.293 9H3.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.207l6.5-6.5zm-7.468 7.468A.5.5 0 0 1 6 13.5V13h-.5a.5.5 0 0 1-.5-.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.5-.5V10h-.5a.499.499 0 0 1-.175-.032l-.179.178a.5.5 0 0 0-.11.168l-2 5a.5.5 0 0 0 .65.65l5-2a.5.5 0 0 0 .168-.11l.178-.178z"/>
                  </svg>
                  </a>
                  <a href="{{route('loan.business.request',['id'=>$business->id])}}" class="btn btn-primary">Request Business Loan</a>
                  @endif
                </td>
                </tr>
                @endforeach
                 @endif
              </tbody>
            </table>

          </div>