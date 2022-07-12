<div class="card">
                <div class="card-header card-header-primary card-header-icon">
                  <!-- <div class="card-icon">
                    <i class="material-icons">assignment</i>
                  </div> -->
                  
                  <div class="float-left">
                    <h4 class="card-title">Saving Categories</h4>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-placement="top"data-bs-target="#create_save_category">New Category</button>
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
                          <th>Category Id</th>
                          <th>Date Created</th>
                          <th>Lower Limit</th>
                          <th>Upper Limit</th>
                          <th>Status</th>
                          <th>Action</th>
                        </tr>
                      </thead>
                     
                      <tbody>
                        
                          @foreach ($categories as $category ) 
                         
                            <tr>
                              <td>{{$category->cate_id}}</td>
                              <td>{{ \Carbon\Carbon::parse($category->created_at)->toFormattedDateString()}}</a></td>
                              <td>{{$category->lowerlimit}}</td>
                              <td>{{$category->upperlimit}} </td>
                              @switch($category->status )
                                @case('Active')
                                  <td><span class="btn btn-success">{{ $category->status }}</span></td>
                                @break                                
                                @case('InActive')
                                  <td><span class="btn btn-danger">{{ $category->status }}</span></td>
                                @break
                              @endswitch
                              <td>
                              <div class="dropdown">
                                <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                                  Choose Action
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                               
                                  <li><button class="dropdown-item btn-edit" data-bs-toggle="modal"  data-placement="top" data-bs-target="#edit_save_category" data-category="{{$category}}">Edit</button></li>
                                  
                                </ul>
                              </div>
                                  
                              </td>
                             
                            </tr>
                            @endforeach
  
                        
                      </tbody>
                    </table>
                    {{$categories->links()}}
                  </div>
                </div>
</div>
                 