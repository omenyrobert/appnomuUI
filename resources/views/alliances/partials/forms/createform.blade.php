<div class="card" style="background-color: #113c56;">
    <div class="card-header card-header-icon card-header-rose">
        <div class="card-icon">
        </div>
        <h4 class="card-title text-white">Save Alliance</h4>
                  
    </div>
    <div class="card-body">
        <form method="post" action="{{route('alliance.store')}}">

            @csrf
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label class="bmd-label-floating text-white">Name</label>
                  <input type="text" style="background-color: #113c56;" name="name" class="form-control" >
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label class="bmd-label-floating text-white">Telephone (+2567xxxxxxxx)</label>
                  <input type="text" style="background-color: #113c56;" name="telephone" class="form-control">
                </div>
              </div>
            </div>
            <div class="row mt-4">
             <div class="col-md-6">
                <div class="form-group">
                  <label class="bmd-label-floating text-white">Relationship</label>
                  <input type="text" style="background-color: #113c56;" name="relationship" class="form-control">
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label class="bmd-label-floating text-white">NIN Number</label>
                  <input type="text" style="background-color: #113c56;" name="nin" class="form-control">
                </div>
              </div>
            </div>
            <div class="row mt-4">
              <div class="col-md-6">
              <button type="submit" class="btn btn-primary form-control mt-5">Save Alliance</button>
              </div>
              <div class="col-md-6">
                <div class="form-group mt-3">
                  <label class="bmd-label-floating text-white">National ID Card Number</label>
                  <input type="text" name="card_no" style="background-color: #113c56;" class="form-control">
                </div>
              </div>
            </div>
            <!-- <div class="clearfix"></div> -->
          </form>
    </div>
</div>