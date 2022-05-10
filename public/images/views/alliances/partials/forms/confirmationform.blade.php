<div class="card">
                <div class="card-header card-header-icon card-header-rose">
                  <div class="card-icon">
                    <i class="material-icons">perm_identity</i>
                  </div>
                  <h4 class="card-title">Confirm Alliance
                  </h4>
                </div>
                <div class="card-body">
<form method="post" action="{{route('alliance.confirm')}}">
                    @csrf
                    <div class="row">
                      <div class="col-md-12">
                        <div class="form-group">
                          <label class="bmd-label-floating">Enter Confirmation Code</label>
                          <input type="text" name="token" class="form-control" >
                        </div>
                      </div>
                    </div>
                    <button type="submit" class="btn btn-warning pull-right">Confirm Alliance</button>
                    <!-- <div class="clearfix"></div> -->
                  </form>
                </div>
</div>