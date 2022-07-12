<div class="card"  style="background-color: #113c56;">
<div class="row">
  <div class="col-md-8">
                <div class="card-header card-header-icon card-header-rose">
                  <h4 class="card-title text-white">Confirm Alliance
                  </h4>
                </div>
                <div class="card-body">
          <form method="post" action="{{route('alliance.confirm')}}">
                    @csrf
                    <div class="row">
                      <div class="col-md-12">
                        <div class="form-group">
                          <label class="bmd-label-floating text-white">Enter Confirmation Code</label>
                          <input type="text" name="token" class="form-control" >
                        </div>
                      </div>
                    </div>
                    <button type="submit" class="btn btn-primary mt-4" style="width: 300px;">Confirm Alliance</button>
                    <!-- <div class="clearfix"></div> -->
                  </form>
                </div>
</div>
<div class="col-md-4 p-5">
            <div class="card card-profile">
                <div class="card-avatar">
                    <a href="#pablo">
                      <img class="img" src="/assets/img/default-avatar.png" />
                    </a>
                </div>
                <div class="card-body">
                  <h6 class="card-category text-gray"></h6>
                  <h4 class="card-title"></h4>
                  <h4 class="card-title"> <span class="badge"></span> </h4>
                  <h4 class="card-title"></h4>
                  
                  <a href="#pablo" class="btn btn- btn-round">Follow</a>
                </div>
            </div>
        </div>
</div>