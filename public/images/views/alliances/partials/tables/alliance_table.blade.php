<div class="card">
                <div class="card-header card-header-primary card-header-icon">
                  <div class="card-icon">
                    <i class="material-icons">assignment</i>
                  </div>
                  
                  <div class="float-left">
                    <h4 class="card-title">My Alliances</h4>
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
                            <th>Name</th>
                            <th>Telephone</th>
                            <th>NIN</th>
                            <th>Relationship</th>
                            <th>Status</th>
                        </tr>
                      </thead>
                      <tfoot>
                        <tr>
                            <th>Name</th>
                            <th>Telephone</th>
                            <th>NIN</th>
                            <th>Relationship</th>
                            <th>Status</th>
                        </tr>
                      </tfoot>
                      <tbody>
                        
                        @foreach($alliances as $alliance)
                        <tr>
                          <td>{{ $alliance->name }}</td>
                          <td>{{ $alliance->Phone_Number }}</td>
                          <td>{{ $alliance->NIN }}</td>
                          <td>{{ $alliance->relationship }}</td>
                          @if($alliance->sms_verified_at)
                          <td><span class="badge badge-success">{{'Verified'}}</span></td>
                          @else
                          <td><span class="badge badge-danger">{{'Not Verified'}}</span></td>
                          @endif
                        </tr>
                        @endforeach
                      </tbody>
                    </table>
                  </div>
                  </div>
                <!-- end content-->
              </div>
              <!--  end card  -->
           