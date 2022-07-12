<div class="card">
    <div class="card-header card-header-icon card-header-rose">
        <div class="card-icon">
            <i class="material-icons">perm_identity</i>
        </div>
        <h4 class="card-title">Save Alliance</h4>
                  
    </div>
    <div class="card-body">
        <form method="post" action="{{route('alliance.store')}}">

            @csrf
            <div class="row">
              <div class="col-md-5">
                <div class="form-group">
                  <label class="bmd-label-floating">Name</label>
                  <input type="text" name="name" class="form-control" >
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  <label class="bmd-label-floating">Telephone (+2567xxxxxxxx)</label>
                  <input type="text" name="telephone" class="form-control">
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label class="bmd-label-floating">Relationship</label>
                  <input type="text" name="relationship" class="form-control">
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label class="bmd-label-floating">NIN Number</label>
                  <input type="text" name="nin" class="form-control">
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label class="bmd-label-floating">National ID Card Number</label>
                  <input type="text" name="card_no" class="form-control">
                </div>
              </div>
            </div>
            <button type="submit" class="btn btn-warning pull-right">Save Alliance</button>
            <!-- <div class="clearfix"></div> -->
          </form>
    </div>
</div>