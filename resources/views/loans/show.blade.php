@extends('layouts.master')
@section('content')

          <div class="row">
            <div class="col-md-8">
                @if(count($repayments) > 0)
                    @include('loans.partials.tables.repaymentstable')
                @endif
                @include('loans.partials.tables.loantable')
            </div>
            <div class="col-md-4">
              <div class="card card-profile">
                <div class="card-avatar">
                  <a href="#pablo">
                    <img class="img" src="/assets/img/default-avatar.png" />
                  </a>
                </div>
                <div class="card-body">
                   
                  <h6 class="card-category text-gray">{{$user->user_id}}</h6>
                  <h4 class="card-title">{{$user->name}}</h4>
                  <h4 class="card-title">{{$user->NIN ? $user->NIN : ''}}</h4>
                  <h4 class="card-title">{{$user->card_no ?  $user->card_no : ' '}}</h4>
                  <h4 class="card-title">{{$user->address ? $user->address : ' '}}</h4>
                  <h4 class="card-title">{{$user->refferer ? $user->refferer : ' '}}</h4>
                  <h4 class="card-title">{{$user->telephone}} 
                      @if($user->sms_verified_at)
                      <span class="badge badge-success">{{ 'Verified' }}</span> </h4>
                      @else
                      <span class="badge badge-rose">{{ 'Not Verified' }}</span> </h4>
                      @endif
                  <h4 class="card-title">{{$user->email}}</h4>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      @include('loans.partials.modals.pay_modal')
@endsection