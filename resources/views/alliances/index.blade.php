@extends('layouts.master')
@section('content')
<div class="m-5">
<div class="row">
        <div class="col-md-12">             
                @include('alliances.partials.forms.createform')                
                @include('alliances.partials.forms.confirmationform')
                @include('alliances.partials.tables.alliance_table')
        </div>
    </div>
</div>

@endsection