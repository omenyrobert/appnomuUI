<div class="card">
                <div class="card-header card-header-primary card-header-icon">
                  <!-- <div class="card-icon">
                    <i class="material-icons">assignment</i>
                  </div> -->
                  
                  <div class="float-left">
                    <h4 class="card-title">Electricity Offers/Promotions</h4>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-placement="top"data-bs-target="#create_electricity_rate">Create New Offer</button>
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
                          <!-- <th>Offer Id</th> -->
                          <th>Date Created</th>
                          <th>Lower Limit</th>
                          <th>Upper Limit</th>
                          <th>Bonus</th>
                          <th>Status</th>
                          <th>Action</th>
                        </tr>
                      </thead>
                     
                      <tbody>
                        
                          @foreach ($rates as $rate ) 
                         
                            <tr>
                              <!-- <td>{{$rate->cate_id}}</td> -->
                              <td>{{ \Carbon\Carbon::parse($rate->created_at)->toFormattedDateString()}}</a></td>
                              <td>{{$rate->lower_limit}}</td>
                              <td>{{$rate->upper_limit}} </td>
                              <td>{{$rate->bonus}}% </td>
                              @switch($rate->status )
                                @case('Active')
                                  <td><span class="btn btn-success">{{ $rate->status }}</span></td>
                                @break                                
                                @case('InActive')
                                  <td><span class="btn btn-danger">{{ $rate->status }}</span></td>
                                @break
                              @endswitch
                              <td>
                                @if($user->role == 'admin')
                              <div class="dropdown">
                                <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                                  Choose Action
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                               
                                  <li><button class="dropdown-item btn-edit" data-bs-toggle="modal"  data-placement="top" data-bs-target="#edit_airtime_offer" data-category="{{$rate}}">Edit</button></li>
                                  
                                </ul>
                              </div>
                                  @else
                                    <button class="btn btn-primary buy-airtime" data-bs-toggle="modal" data-placement="top" data-rate="{{json_encode($rate)}}" data-bs-target="#buy_airtime_modal">Buy this offer</button>
                                  @endif
                              </td>
                             
                            </tr>
                            @endforeach
  
                        
                      </tbody>
                    </table>
                    {{$rates->links()}}
                  </div>
                </div>
</div>
                 