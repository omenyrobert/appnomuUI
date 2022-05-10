@extends('layouts.header')
@section('content')

    <div class="row">
        <div class="col-md-8">             
                  
                @include('alliances.partials.forms.createform')                
                @include('alliances.partials.forms.confirmationform')
                @include('alliances.partials.tables.alliance_table')
        </div>
        <div class="col-md-4">
            <div class="card card-profile">
                <div class="card-avatar">
                    <a href="#pablo">
                      <img class="img" src="/assets/img/default-avatar.png" />
                    </a>
                </div>
                <div class="card-body">
                    <?php
                        if ($user->sms_verified_at == null) {
                            # code...
                            $status = 'Not Verified';
                            $badge = 'badge-rose';
                        }else {
                            # code...
                            $status = 'Verified';
                            $badge = 'badge-success';
                        }
                    
                    ?>
                  <h6 class="card-category text-gray">{{$user->user_id}}</h6>
                  <h4 class="card-title">{{$user->name}}</h4>
                  <h4 class="card-title">{{$user->telephone}} <span class="badge {{ $badge }}">{{ $status }}</span> </h4>
                  <h4 class="card-title">{{$user->email}}</h4>
                  
                  <a href="#pablo" class="btn btn- btn-round">Follow</a>
                </div>
            </div>
        </div>
    </div>
@endsection