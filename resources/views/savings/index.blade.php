@extends('layouts.master')
@section('content')
    @include('savings.partials.wizard.cards')
    <div class="row  bg-light">
        <button data-bs-toggle="modal" data-placement="top" data-bs-target="#create_saving" class="btn btn-primary col-sm-12 col-md-2 m-auto">
            Deposit To Your Savings
        </button>
        <button data-bs-toggle="modal" data-placement="top" data-bs-target="#withdraw_saving" class="btn btn-primary col-sm-12 col-md-2 m-auto">
            Withdraw From Savings
        </button>
        <button data-bs-toggle="modal" data-placement="top" data-bs-target="#send_saving" class="btn btn-primary col-sm-12 col-md-2 m-auto">
            Send Money
        </button>
        <button data-bs-toggle="modal" data-placement="top" data-bs-target="#airtime_saving" class="btn btn-primary col-sm-12 col-md-2 m-auto">
            Airtime and Data
        </button>
        <button data-bs-toggle="modal" data-placement="top" data-bs-target="#utility_saving" class="btn btn-primary col-sm-12 col-md-2 m-auto">
           Pay for  Utilities
        </button>
    </div>
    @include('savings.partials.tables.savings_table')
    @include('savings.partials.modals.save_modal')
    @include('savings.partials.modals.withdraw_modal')
    @include('savings.partials.modals.sendmoney_modal')
    @include('savings.partials.modals.utilities_modal')
    @include('savings.partials.modals.airtime_data')
    <script>
    $('.buy-airtime').on('click',function(e){
        let rate = $(this).data('rate');
        $.ajax({
            type:"GET",
            url: "{{route('airtime.iso.operators',['iso'=>'UG'])}}"
        });
    });

    $('.btn-airtime').on('click',function(e){
        console.log("button clicked");
        let operator = $(this).data('operator');
        console.log('operator',operator);
        $('#operator_id').val(operator)
        // $.ajax({
        //     type:"GET",
        //     url: "{{route('airtime.iso.operators',['iso'=>'UG'])}}"
        // });
    });

    $('#select_rate_id').on('change',function(e){
        console.log('inside select rate')
        console.log('selected',$(this).children("option:selected").val())
        if( $(this).children("option:selected").val() != 'select'){
            $('.div-amount').prop('hidden',false);
        }else{
            $('.div-amount').prop('hidden',true);
        }
       
        // $.ajax({
        //     type:"GET",
        //     url: "{{route('airtime.iso.operators',['iso'=>'UG'])}}"
        // });
    });

    $('#amount_op').on('focusout',function(e){
        console.log('inside amount focus out');
        let lower =  $('#select_rate_id').children("option:selected").data('lower'); 
        let upper =  $('#select_rate_id').children("option:selected").data('upper'); 
        console.log('lower',lower);
        console.log('upper',upper);
        if($('#amount_op').val() < lower || $('#amount_op').val() > upper){
            $('#amount_op').addClass('is-invalid');
        }
    });
    $('#amount_op').on('click change',function(e){
            console.log('amount_op clicked or changed');
            $('#amount_op').removeClass('is-invalid');
    });
</script>
@endsection