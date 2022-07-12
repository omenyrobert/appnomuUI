<div class="container bg-white">
    <table class="table table-striped table-hover">
    	<thead>
    		<th>Account Number</th>
			<th>Account Name</th>
			<th>Available Balance</th>
			<th>Ledger Balance</th>
			<th>Total Saved</th>
			<th>Total Withdrawn</th>
			<th>Loan Limit</th>
			<th>Status</th>
			<th>Action</th>
    	</thead>
    	<tbody>
			@foreach($accounts as $account)
    			<tr>
    				<td>{{'Acc-'.$account->user->user_id}}</td>
					<td>{{$account->user->name}}</td>
					<td>{{$account->available_balance}}</td>
					<td>{{$account->Ledger_Balance}}</td>
					<td>{{$account->Total_Saved}}</td>
					<td>{{$account->Total_Withdrawn}}</td>
					<td>{{$account->Total_Loan_Limit}}</td>
					<td>
						@if($account->status == 'active')
							<span class="btn btn-success">{{$account->status}}</span>
						@endif

						@if($account->status == 'inactive')
							<span class="btn btn-primary">{{$account->status}}</span>
						@endif

						@if($account->status == 'suspended')
							<span class="btn btn-warning">{{$account->status}}</span>
						@endif

						@if($account->status == 'blacklisted')
							<span class="btn btn-danger">{{$account->status}}</span>
						@endif
					</td>
					<td>
						<div class="dropdown">
                            <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                              Choose Action
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                            
                            	<li>
									<a type="button" href="" class="dropdown-item btn-acc-actions" data-bs-toggle="modal"  data-placement="top" data-bs-target="#edit_airtime_offer" data-category="{{$rate}}">
										Details
									</a>
									<button class="dropdown-item btn-acc-actions" data-bs-toggle="modal"  data-placement="top" data-bs-target="#loan_limit" data-account="{{$account->id}}">
										Loan Limit
									</button>
									@if($account->status == 'suspended')
									<button class="dropdown-item btn-acc-actions" data-bs-toggle="modal"  data-placement="top" data-bs-target="#unsuspend" data-account="{{$account->id}}">
										unSuspend
									</button>
									@else
									<button class="dropdown-item btn-acc-actions" data-bs-toggle="modal"  data-placement="top" data-bs-target="#suspend" data-account="{{$account->id}}">
										Suspend
									</button>
									@endif
									@if($account->status == 'blacklisted')
									<button class="dropdown-item btn-acc-actions" data-bs-toggle="modal"  data-placement="top" data-bs-target="#unblacklist" data-account="{{$account->id}}">
										unBlacklist
									</button>
									@else
									<button class="dropdown-item btn-acc-actions" data-bs-toggle="modal"  data-placement="top" data-bs-target="#blacklist" data-account="{{$account->id}}">
										Blacklist
									</button>
									@endif
								</li>
                              
                            </ul>
						</div>		
    				</td>
    			</tr>
			@endforeach
    	</tbody>
    </table>
	{{$accounts->links()}}
</div>