<div class="container bg-white">
            <table class="table table-striped table-hover">
              <thead>
                <th>Date</th>
                <th>Transaction ID</th>
                @if($user->role == 'admin')
                    <th>User</th>
                @endif
                <th>Saving Amount</th>
                <th>Interest</th>
               
              </thead>
              <tbody>
                @if(count($savings) == 0)
                  <tr>
                    <td colspan="4">No Savings In Your Account Yet. Make Some Savings And Earn Interest On Your Savings</td>
                  </tr>
                @endif
                  @foreach($savings as $saving)
                <tr>
                  <td>{{$saving->created_at}}</td>
                  <td>{{$saving->saving_id}}</td>
                  @if($user->role == 'admin')
                    <td>{{$saving->user->name}}</td>
                  @endif
                  <td>{{$saving->amount}}</td>
                  <td>{{$saving->Interest}}</td>
                  
                </tr>
                  @endforeach
              </tbody>
            </table>

</div>