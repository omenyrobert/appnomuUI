@extends('layouts.master')
@section('content')
    <div style="height: 100vh;">
        <h2 class="text-white pt-5" style="margin-left: 50px;">Buy Airtime</h2>
    <form action="{{route('airtime.buy')}}" class="">
            @csrf
            <div class="row mt-5 p-5">
                <div class="col-md-4">
                    <label class="text-white">Enter Phone Number</label>
                    <input id="phone" type="number" name="phone"class="form-control mt-2" placeholder="Enter valid amount" />
                </div>
                <div class="col-md-4">
                    <label class="text-white">Enter Amount</label>
                    <input id="amount" type="number" name="amount" class="form-control mt-2" placeholder="Enter valid amount" />
                </div>
                <div class="col-md-4">
                      <label class="text-white">Select Purchasing Account</label>
                      <select class="form-control mt-2" name="account">
                      <option value="select">select purchasing Account</option>
                          <option value="savings">Savings Account</option>
                          <option value="loans">Loan Account</option>
                      </select>
                </div>
                <div class="col-md-4">
                    <br/>
                    <button class="btn btn-primary form-control">Buy Airtime Offer</button>
                </div>
            </div>
        </form>


    </div>
@endsection