<div class="container bg-white">
            <table class="table table-striped table-hover">
              <thead>
                <th>Date</th>
                <th>Transaction ID</th>
                <th>Beneficiary</th>
                <th>Withdraw Amount</th>
                <th>Fee</th>
               
              </thead>
              <tbody>
                  @foreach($withdraws as $withdraw)
                <tr>
                  <td>{{$withdraw->created_at}}</td>
                  <td>{{$withdraw->transaction->id}}</td>
                  <td>{{$withdraw->user}}</td>
                  <td>{{$withdraw->amount}}</td>
                  <td>{{$withdraw->fee}}</td>
                 
                </tr>
               @endforeach
              </tbody>
            </table>

          </div>