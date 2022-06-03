<div >
    @foreach($providers as $provider)
        <div class="card  col-sm-12 col-md-3  " style="display: inline-block;  ">
            <h6 class="my-2">{{$provider->name}}</h6>
            <img src="{{$provider->logoUrls[2]}}" class="col-12" style="height:150px;"/>
            <button class="btn btn-primary col-12 my-2 btn-airtime" data-placement="top" data-bs-target="#buy_operator_airtime"data-bs-toggle="modal" data-operator="{{$provider->id}}">
                Buy  {{$provider->name}} Airtime
            </button>
        </div>
    @endforeach

   
</div>