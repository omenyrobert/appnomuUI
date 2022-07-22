@extends('layouts.master')
@section('content')
<div class="row p-5">
    <div class="col-md-3 " >
            <h5 class="text-white">Electricity</h5>
            <button class="btn btn-primary" >
                Pay Electricity Bill
            </button>
    </div>
    <div class="col-md-3">
            <h5 class="text-white">Water</h5>
            <button class="btn btn-primary">
                Coming Soon
            </button>
    </div>
    <div class="col-md-3"style="display:inline-block; height:150px;">
            <h5 class="text-white">TV</h5>
            <button class="btn btn-primary">
                Coming Soon
            </button>
            <button class="btn btn-warning" data-placement="top" data-bs-toggle="modal" data-bs-target="#pay_electricity"hidden>
                Pay Electricity Bill
            </button>
    </div>
            <div class="col-md-3"style="display:inline-block; height:150px;">
            <h5 class="text-white">Internet Data</h5>
            <button class="btn btn-primary">
                Coming Soon
            </button>
            <button class="btn btn-warning" data-placement="top" data-bs-toggle="modal" data-bs-target="#pay_electricity"hidden>
                Pay Electricity Bill
            </button>
    </div>
</div>
<form action="{{route('electricity.rate.store')}}" method="post" class="p-5" style="margin-top: -150px;">
            @csrf
            <div class="row mt-5 ">
                <div class="col-md-4">
                    <label class="text-white">Lower Limit</label>
                    <input type="number" name="lower_limit"class="form-control mt-2" placeholder="Enter minimum amount for this offer" />
                </div>
                <div class="col-md-4">
                    <label class="text-white">Upper Limit</label>
                    <input type="number" name="upper_limit"class="form-control mt-2" placeholder="Enter maximum airtime amount for this offer" />
                </div>

                <div class="col-md-4">
                    <label class="text-white">Promotional Bonus</label>
                    <input type="number" name="bonus"class="form-control mt-2" placeholder="Enter bonus in percentage" />
                </div>
               
                <div class="col-md-4">
                    <br/>
                    <button type="submit" class="btn btn-primary form-control">Create</button>
                </div>
            </div>
        </form>
        <br/><br/><br/><br/><br/>
@endsection